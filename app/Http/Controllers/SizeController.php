<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColorCreateRequest;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // Get All Colors
    public function getSizes(Request $request)
    {
        $sizes = Size::all();

        return response()->json([
            "status" => 200,
            "data" => $sizes
        ]);
    }

    // Get Detail Color By Id
    public function getSizeById($id)
    {
        $size = Size::where("id", $id)->first();

        if (!$size) {
            return response()->json([
                'status' => 404,
                'message' => 'This size does not exist'
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'data' => $size
            ]);
        }
    }

    // Create new color
    // public function createSize(Request $request)
    // {

    //     $size = Size::create([
    //         'size' => $request->size
    //     ]);

    //     return response()->json([
    //         "status" => "OK",
    //         "message" => "Size was created successfully",
    //         "data" => $size
    //     ], 200);
    // }
}
