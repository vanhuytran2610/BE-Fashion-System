<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(Request $request)
    {
        if (auth('sanctum')->user()) {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_quantity = $request->product_quantity;
            $size_id = $request->size_id; // Assuming the size ID is passed in the request

            $product_check = Product::where('id', $product_id)->first();
            if ($product_check) {
                if ($product_check->sizes()->where('sizes.id', $size_id)->exists()) {
                    if (Cart::where('product_id', $product_id)->where('user_id', $user_id)->where('size_id', $size_id)->exists()) {
                        return response()->json([
                            'status' => 409,
                            'message' => $product_check->name . ' already added to Cart'
                        ]);
                    } else {
                        $cart_item = Cart::create([
                            'user_id' => $user_id,
                            'product_id' => $product_id,
                            'product_quantity' => $product_quantity,
                            'size_id' => $size_id // Set the selected size ID in the cart item
                        ]);

                        return response()->json([
                            'status' => 201,
                            'message' => 'Add product to cart successfully',
                            'data' => $cart_item
                        ]);
                    }
                } else {
                    return response()->json([
                        'status' => 400,
                        'message' => 'Invalid size selection for this product'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => 404,
                    'message' => 'This Product does not exist'
                ]);
            }
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please login to add product to cart'
            ]);
        }
    }


    public function viewCartByUser()
    {

        if (auth('sanctum')->user()) {
            $user_id = auth('sanctum')->user()->id;
            $cart_items = Cart::with(['product','user', 'product.color'])->where('user_id', $user_id)->get();
            // Remove cart items with deleted products
            $cart_items = $cart_items->filter(function ($cartItem) {
                return $cartItem->product !== null;
            })->values();

            return response()->json([
                'status' => 200,
                'data' => $cart_items->toArray()
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please login first',
            ]);
        }
    }

    public function updateQuantity($cart_id, $scope)
    {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $cart_item = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            if ($scope == "inc") {
                $cart_item->product_quantity += 1;
            } else if ($scope == "dec") {
                $cart_item->product_quantity -= 1;
            } 

            $cart_item->update();

            return response()->json([
                "status" => 200,
                "message" => "Cart Item Quantity is updated"
            ]);
        } else {
            return response()->json([
                'status' => 401,
                'message' => 'Please login first',
            ]);
        }
    }

    public function deleteCartItem($cart_id)
    {
        if (auth('sanctum')->user()) {
            $user_id = auth('sanctum')->user()->id;
            $cart_item = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            if ($cart_item) {
                $cart_item->delete();

                return response()->json([
                    "status" => "Success",
                    "message" => "Cart Item successfully deleted",
                    "data" => $cart_item
                ], 200);
            } else {
                return response()->json([
                    "status" => "Error",
                    "message" => "This Cart Item does not exist"
                ], 404);
            }
        } else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Access Denied',
            ], 401);
        }
    }
}
