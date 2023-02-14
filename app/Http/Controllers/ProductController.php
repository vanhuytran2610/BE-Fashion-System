<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function createProduct (ProductCreateRequest $request) {
        $category_id = Category::find($request->category_id);
        $color_id = Color::find($request->color_id);

        if(!$category_id or !$color_id) {
            return response()->json([
                "status" => "Error",
                "message" => "Product could not be saved",
            ], 401);
        }

        //$size = json_decode($request->size, true);
        
        $product = Product::create([
            "name" => $request->name,
            "description" => $request->description,
            "category_id" => $request->category_id,
            "color_id" => $request->color_id,
            "size" => $request->size,
            "price" => preg_replace('/[^0-9]/', '', $request->price),
            "quantity" => preg_replace('/[^0-9]/', '', $request->quantity)
        ]);

        $product->load('category:id,name', 'color:id,color');

        return response()->json([
            "status" => "OK",
            "message" => "Product was created successfully",
            "data" => $product
        ], 200);
    }
    
    public function getProducts(Request $request) {
        $product_query = Product::with(['category', 'color']);
        $products = $product_query->get();
        // $lower_price = Product::whereNotNull('price')->min('price');
        // dd($lower_price);

        // Filter by category
        if ($request->category) {
            $products = $product_query->whereHas('category', function ($query) use ($request) {
                $query->where('name', $request->category);
            });
        }

        // Filter by color
        if ($request->color) {
            $products = $product_query->whereHas('color', function ($query) use ($request) {
                $query->where('color', $request->color);
            });
        }

        // Filter by price

        // Sort
        if ($request->sortBy && in_array($request->sortBy, ['id', 'created_at', 'price'])) {
            $sortBy = $request->sortBy;
        } else {
            $sortBy = 'id';
        }

        if ($request->sortOrder && in_array($request->sortOrder, ['asc', 'desc'])) {
            $sortOrder = $request->sortOrder;
        } else {
            $sortOrder = 'desc';
        }

        // Pagination
        if ($request->perPage) {
            $perPage = $request->perPage;
        } else {
            $perPage = 5;
        }

        if ($request->paginate) {
            $products = $product_query->orderBY($sortBy, $sortOrder)->paginate($perPage);
        } else {
            $products = $product_query->orderBY($sortBy, $sortOrder)->get();
        }

        $products->load('category:id,name', 'color:id,color');

        return response()->json([
            "status" => "OK",
            "data" => $products
        ], 200);
    }

}
