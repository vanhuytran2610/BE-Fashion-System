<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function createProduct (ProductCreateRequest $request) {
        $category_id = Category::find($request->category_id);
        $color_id = Color::find($request->color_id);
        $size_id = Size::find($request->size_id);

        $error_message = [];

        if (!$category_id) {
            return response()->json([
                "status" => "Error",
                "message" => "Category does not exist"
            ], 404);
        }

        if (!$color_id) {
            return response()->json([
                "status" => "Error",
                "message" => "Color does not exist"
            ], 404);
        }

        if (!$size_id) {
            return response()->json([
                "status" => "Error",
                "message" => "Size does not exist"
            ], 404);
        }
        
        $product = Product::create($request->all());

        $product->load('category:id,name', 'color:id,color', 'size:id,size');

        if(!$product) {
            return response()->json([
                "status" => "Error",
                "message" => "Product could not be saved",
            ], 401);
        }
        else {
            return response()->json([
                "status" => "OK",
                "message" => "Product was created successfully",
                "data" => $product
            ], 200);
        }
    }
    
    public function getProductsByCategory(Request $request) {
        $product_query = Product::with(['category', 'color', 'size']);

        if ($request->category) {
            $products = $product_query->whereHas('category', function ($query) use ($request) {
                $query->where('name', $request->category);
            });
        }

        if ($request->color) {
            $products = $product_query->whereHas('color', function ($query) use ($request) {
                $query->where('color', $request->color);
            });
        }

        if ($request->size) {
            $products = $product_query->whereHas('size', function ($query) use ($request) {
                $query->where('size', $request->size);
            });
        }

        $products = $product_query->get();
        $products->load('category:id,name', 'color:id,color', 'size:id,size');

        return response()->json([
            "status" => "OK",
            "data" => $products
        ], 200);
    }
}
