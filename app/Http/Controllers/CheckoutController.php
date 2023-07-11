<?php

namespace App\Http\Controllers;

use App\Mail\AdminOrderConfirmation;
use App\Models\Cart;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function placeOrder(Request $request)
    {
        if (auth('sanctum')->check()) {
            $validator = Validator::make($request->all(), [
                'firstname' => 'required|max:100',
                'lastname' => 'required|max:100',
                'phone' => 'required|max:100',
                'address' => 'required|max: 1000',
                'district_code' => 'required|max: 100',
                'province_code' => 'required|max: 100',
                'ward_code' => 'required|max: 100',
                'email' => 'required|max: 100',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 422,
                    'errors' => $validator->errors(),
                ]);
            }

            $user_id = auth('sanctum')->user()->id;

            $cart = Cart::with('product')->where('user_id', $user_id)->get();

            // Start a database transaction
            DB::beginTransaction();

            try {
                $order_items = [];
                foreach ($cart as $item) {
                    $selectedSize = $item->product->sizes()->find($item->size_id);

                    //$product = Product::find($item->product_id);

                    if ($selectedSize->pivot->quantity < $item->product_quantity) {
                        // Roll back the transaction in case of insufficient product quantity
                        DB::rollback();

                        return response()->json([
                            'status' => 400,
                            'message' => 'Product is out of stock',
                        ]);
                    }

                    $order_items[] = [
                        'product_id' => $item->product_id,
                        'quantity' => $item->product_quantity,
                        'price' => $item->product->price,
                        'size_id' => $selectedSize->id
                    ];

                    if ($selectedSize) {
                        $pivotData = [
                            'quantity' => $selectedSize->pivot->quantity - $item->product_quantity
                        ];
                        $item->product->sizes()->updateExistingPivot($selectedSize->id, $pivotData);
                    }

                    // Reduce the product quantity by the ordered quantity
                }

                // Create the order
                $order = new Order;
                $order->user_id = $user_id;
                $order->firstname = $request->firstname;
                $order->lastname = $request->lastname;
                $order->email = $request->email;
                $order->phone = $request->phone;
                $order->address = $request->address;
                $order->district_code = $request->district_code;
                $order->province_code = $request->province_code;
                $order->ward_code = $request->ward_code;
                $order->status = $request->status;
                $order->payment_mode = $request->payment_mode;
                $order->tracking_no = 'harmony' . rand(111, 999);
                $order->created_at = Carbon::now('Asia/Ho_Chi_Minh');
                $order->save();

                $order->orderItems()->createMany($order_items);

                // Destroy the cart
                Cart::destroy($cart);

                // Commit the transaction
                DB::commit();

                if ($order->payment_mode == "cash") {  //unpaid
                    Mail::mailer('smtp')->to($order->email)
                        ->send(new OrderConfirmation($order));

                    return response()->json([
                        'status' => 200,
                        'message' => 'Order placed successfully'
                    ]);
                }

            } catch (\Exception $e) {
                // Roll back the transaction in case of any error
                DB::rollback();

                return response()->json([
                    'status' => 500,
                    'message' => 'Error placing order'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please login to continue',
            ]);
        }
    }
}
