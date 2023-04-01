<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function addToCart(AddToCartRequest $request) {
        if (auth('sanctum')->user()) {
            $user_id = auth('sanctum')->user()->id;
            $product_id = $request->product_id;
            $product_quantity = $request->product_quantity;

            $product_check = Product::where('id', $product_id)->first();
            if ($product_check) {
                if (Cart::where('product_id', $product_id)->where('user_id', $user_id)->exists()) {
                    return response()->json([
                        'status' => 'Error',
                        'message' => $product_check->name.' already added to Cart'
                    ], 409);
                }
                else {
                    $cart_item = Cart::create([
                        'user_id' => $user_id,
                        'product_id' => $product_id,
                        'product_quantity' => $product_quantity
                    ]);

                    $cart_item->load('user:id,email', 'product:id,name,image_avatar,color_id,size_id');

                    return response()->json([
                        'status' => 'OK',
                        'message' => 'Add product to cart successfully',
                        'data' => $cart_item
                    ], 200);
                }
            }

            else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'This Product does not exist'
                ],404);
            }            
        }
        else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Please login to add product to cart'
            ], 401);
        }
    }

    public function viewCart () {
        // if ($this->authorize('authorize')) {
            if (auth('sanctum')->user()) {
                $cart_items = Cart::all();
                $cart_items->load('user:id,email', 'product:id,name,image_avatar,color_id,size_id');
    
                return response()->json([
                    'status' => 'OK',
                    'data' => $cart_items
                ],200);
            }
            else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'Access Denied',
                ],401);
            }
        // }
        // else {
        //     return response()->json([
        //         'status' => 'Error',
        //         'message' => 'Only Admin has access'
        //     ],401);
        // }
    }

    public function updateQuantity ($cart_id, $scope) {
        if (auth('sanctum')->check()) {
            $user_id = auth('sanctum')->user()->id;
            $cart_item = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            if ($scope == "inc") {
                $cart_item->product_quantity += 1;
            }
            else if ($scope == "dec") {
                $cart_item->product_quantity -= 1;
            }
            else {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'The scope has only inc and dec',
                ],401); 
            }

            $cart_item->update();

            return response()->json([
                "status" => "Success",
                "message" => "Cart Item Quantity is updated"
            ],200);
        }
        else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Access Denied',
            ],401); 
        }
    }

    public function deleteCartItem($cart_id) {
        if (auth('sanctum')->user()) {
            $user_id = auth('sanctum')->user()->id;
            $cart_item = Cart::where('id', $cart_id)->where('user_id', $user_id)->first();

            if ($cart_item) {
                $cart_item->delete();

                return response()->json([
                    "status" => "Success",
                    "message" => "Cart Item successfully deleted",
                    "data" => $cart_item
                ],200);
            }
            else {
                return response()->json([
                    "status" => "Error",
                    "message" => "This Cart Item does not exist"
                ],404);
            }
        }
        else {
            return response()->json([
                'status' => 'Error',
                'message' => 'Access Denied',
            ],401);
        }
    }
}
