<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColorSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            "price" => preg_replace('/[^0-9]/', '', $request->price),
        ]);

        $product->load('category:id,name', 'color:id,color');

        return response()->json([
            "status" => "OK",
            "message" => "Product was created successfully",
            "data" => $product
        ], 200);
    }
    
    public function getProducts(Request $request) {
        //$product_query = Product::query()->with(['category', 'color']); // for pagination
        $product_query = Product::with(['category', 'color']);
        $products = $product_query->get();
        // $lower_price = Product::whereNotNull('price')->min('price');
        // dd($lower_price);

        // Search product by name
        if ($request->keyword) {
            $products = $product_query->where('name', 'LIKE', '%'.$request->keyword.'%');
        }

        // Get by category
        if ($request->category_id) {
            $products = $product_query->whereHas('category', function ($query) use ($request) {
                $query->where('id', $request->category_id);
            });
        }

        // Filter by color
        if ($request->color_id) {
            $products = $product_query->whereHas('color', function ($query) use ($request) {
                $query->where('id', $request->color_id);
            });
        }

        // Filter by price
        if ($request->low_price) {
            $products = $product_query->where('price', '>=', $request->low_price);
        }

        if ($request->high_price) {
            $products = $product_query->where('price', '<=', $request->high_price);
        }

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

        $products = $product_query->orderBY($sortBy, $sortOrder)->get();

        // Pagination
        // if ($request->perPage) {
        //     $perPage = $request->perPage;
        // } else {
        //     $perPage = 5;
        // }

        // if ($request->paginate) {
        //     $products = $product_query->orderBY($sortBy, $sortOrder)->paginate($perPage);
        // } else {
        //     $products = $product_query->orderBY($sortBy, $sortOrder)->get();
        // }

        $products->load('category:id,name', 'color:id,color');

        return response()->json([
            "status" => "OK",
            "data" => $products
        ], 200);
    }

    public function getProductById($id) {
        $product = Product::with(['category', 'color'])->where('id', $id)->first();

        if (!$product) {
            return response()->json([
                'status' => 'Error',
                'message' => 'No product found'
            ], 401);
        }
        else {
            $product->load('category:id,name', 'color:id,color');
            return response()->json([
                'status' => 'OK',
                'data' => $product
            ]);
        }
    }

    public function updateProduct(ProductUpdateRequest $request, $id) {
        $category_id = Category::find($request->category_id);
        $color_id = Color::find($request->color_id);

        if(!$category_id or !$color_id) {
            return response()->json([
                "status" => "Error",
                "message" => "Product could not be saved",
            ], 401);
        }

        $product = Product::with(['category', 'color'])->where('id', $id)->first();
        
        if(!$product) {
            return response()->json([
                'status' => 'Error',
                'message' => 'No product found'
            ]);
        }
        else {
            $product->update([
                "name" => $request->name,
                "description" => $request->description,
                "category_id" => $request->category_id,
                "color_id" => $request->color_id,
                "price" => preg_replace('/[^0-9]/', '', $request->price),
            ]);

            $product->load('category:id,name', 'color:id,color');

            return response()->json([
                "status" => "OK",
                "message" => "Product was updated successfully",
                "data" => $product
            ], 200);
        }
    }

    public function deleteProduct($id) {
        $product = Product::with(['category', 'color'])->where('id', $id)->first();

        if (!$product) {
            return response()->json([
                'status' => 'Error',
                'message' => 'No product found'
            ]);
        }

        else {
            $product->load('category:id,name', 'color:id,color');
            $product->delete();

            return response()->json([
                "status" => "OK",
                "message" => "Product was deleted successfully",
                "data" => $product
            ], 200);
        }
    }
}
