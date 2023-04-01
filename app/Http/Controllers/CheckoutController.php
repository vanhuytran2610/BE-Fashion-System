<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrderRequest;
use App\Models\Cart;
use App\Models\Order;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function placeOrder (PlaceOrderRequest $request) {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;

            $order = new Order;
            $order->user_id = $user_id;
            $order->firstname = $request->firstname;
            $order->lastname = $request->lastname;
            $order->phone = $request->phone;
            $order->address = $request->address;
            $order->district = $request->district;
            $order->city = $request->city;

            $order->payment_mode = "COD";
            $order->payment_id = $request->payment_id;
            $order->tracking_no = 'harmony'.rand(111,999);
            $order->save();

            $cart = Cart::where('user_id', $user_id)->get();

            $order_items = [];
            foreach ($cart as $item) {
                $order_items[] = [
                    'product_id' => $item->product_id,
                    'quantity' => $item->product_quantity,
                    'price' => $item->product->price
                ];

                $item->product->update([
                    'quantity' => $item->product->quantity - $item->quantity
                ]);
            }

            $order->orderItems()->createMany($order_items);
            Cart::destroy($cart);

            return response()->json([
                'status' => 'OK',
                'message' => 'Order placed successfully'
            ]);
        }
        else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Access Denied',
            ],401); 
        }
    }
}
