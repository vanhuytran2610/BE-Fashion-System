<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductColorSize;
use App\Models\Size;
use Illuminate\Http\Request;

class ProductColorSizeController extends Controller
{
    public function getAll(Request $request)
    {
        $this->authorize('authorize');
        $prod_size_quans = ProductColorSize::all();
        $prod_size_quans->load('product:id,name', 'size:id,size');
        return response()->json([
            "status" => "OK",
            "data" => $prod_size_quans
        ]);
    }

    public function getDetailById($id)
    {
        $this->authorize('authorize');
        $prod_size_quan = ProductColorSize::where("id", $id)->first();

        if (!$prod_size_quan) {
            return response()->json([
                'status' => 'Error',
                'message' => 'Not found'
            ], 404);
        } else {
            $prod_size_quan->load('product:id,name', 'size:id,size');
            return response()->json([
                'status' => 'OK',
                'data' => $prod_size_quan
            ], 200);
        }
    }

    public function create(Request $request)
    {
        $this->authorize('authorize');
        $product_id = Product::find($request->product_id);
        $size_id = Size::find($request->size_id);

        if (!$product_id or !$size_id) {
            return response()->json([
                "status" => "Error",
                "message" => "Data could not be saved",
            ], 401);
        }

        $prod_size_quan = ProductColorSize::create([
            'product_id' => $request->product_id,
            'size_id' => $request->size_id,
            'quantity' => $request->quantity
        ]);

        if (!$prod_size_quan) {
            return response()->json([
                "status" => "Error",
                "message" => "Data could not be saved",
            ], 401);
        } else {
            $prod_size_quan->load('product:id,name', 'size:id,size');
            return response()->json([
                "status" => "OK",
                "message" => "Data was created successfully",
                "data" => $prod_size_quan
            ], 200);
        }
    }

    public function update (Request $request, $id)
    {
        $this->authorize('authorize');
        $prod_size_quan = ProductColorSize::where("id", $id)->first();

        if (!$prod_size_quan) {
            return response()->json([
                "status" => "Error",
                "message" => "Data could not found",
            ], 401);
        } else {
            $product_id = Product::find($request->product_id);
            $size_id = Size::find($request->size_id);
    
            if (!$product_id or !$size_id) {
                return response()->json([
                    "status" => "Error",
                    "message" => "Data could not be saved",
                ], 401);
            }    

            $prod_size_quan->update([
                'product_id' => $request->product_id,
                'size_id' => $request->size_id,
                'quantity' => $request->quantity
            ]);
            $prod_size_quan->load('product:id,name', 'size:id,size');
            return response()->json([
                'status' => 'OK',
                'message' => 'Data was updated successfully',
                'data' => $prod_size_quan
            ], 200);
        }
    }

    public function delete ($id)
    {
        $this->authorize('authorize');
        $prod_size_quan = ProductColorSize::where('id', $id)->first();

        if (!$prod_size_quan) {
            return response()->json([
                "status" => "Error",
                "message" => "Data could not found",
            ], 401);
        } else {
            $prod_size_quan->delete();
            $prod_size_quan->load('product:id,name', 'size:id,size');
            return response()->json([
                'status' => 'OK',
                'message' => 'Data was deleted successfully',
                'data' => $prod_size_quan
            ], 200);
        }
    }
}
