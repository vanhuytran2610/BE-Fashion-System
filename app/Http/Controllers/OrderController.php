<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getOrders()
    {
        if (auth('sanctum')->user()) {
            $order = Order::all();
            return response()->json([
                'status' => 200,
                'data' => $order
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please login first',
            ]);
        }
    }

    public function getDetailOrder($id)
    {
        if (auth('sanctum')->user()) {
            $order = Order::with('user', 'orderItems', 'province', 'district', 'ward')->findOrFail($id);

            if (!$order) {
                return response()->json([
                    'status' => 404,
                    'message' => 'No order item found'
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'data' => $order
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please login first',
            ]);
        }
    }

    public function getOrdersByUser()
    {
        $user = auth('sanctum')->user();
        $orders = Order::where('user_id', $user->id)->with('orderItems.product', 'orderItems.product.color')->get();

        $orders->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        //$orders->updated_at = $currentDateTime->format('Y-m-d H:i:s');
        return response()->json([
            'status' => 200,
            'data' => $orders
        ]);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'status' => 200,
            'message' => 'Order deleted successfully'
        ]);
    }

    

    public function destroyMultiple(Request $request)
    {
        $orderIds = $request->input('orderIds');
        if (!is_array($orderIds)) {
            return response()->json(['status' => 400, 'message' => 'Invalid order IDs']);
        }

        try {
            // Delete categories using the $categoryIds array
            Order::whereIn('id', $orderIds)->delete();

            return response()->json(['status' => 200, 'message' => 'Orders deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Failed to delete orders']);
        }
    }
}
