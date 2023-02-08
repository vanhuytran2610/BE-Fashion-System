<?php

namespace App\Http\Controllers;

use App\Http\Requests\SizeCreateRequest;
use App\Http\Requests\SizeUpdateRequest;
use App\Models\Size;
use Illuminate\Http\Request;

class SizeController extends Controller
{
    // Get All Sizes
    public function getSizes(Request $request)
    {
        $colors = Size::all();

        return response()->json([
            "status" => "OK",
            "data" => $colors
        ]);
    }

    // Get Detail Size By Id
    public function getSizeById($id)
    {
        $size = Size::where("id", $id)->first();

        if (!$size) {
            return response()->json([
                'status' => 'Error',
                'message' => 'No sizes found'
            ], 404);
        } else {
            return response()->json([
                'status' => 'OK',
                'data' => $size
            ], 200);
        }
    }

    // Create new Size
    public function createSize(SizeCreateRequest $request)
    {
        $size = Size::create([
            'size' => $request->size
        ]);

        if (!$size) {
            return response()->json([
                "status" => "Error",
                "message" => "Size could not be saved",
            ], 401);
        } else {
            return response()->json([
                "status" => "OK",
                "message" => "Size was created successfully",
                "data" => $size
            ], 200);
        }
    }

    // Update Size
    public function updateSize(SizeUpdateRequest $request, $id)
    {
        $size = Size::where("id", $id)->first();

        if (!$size) {
            return response()->json([
                "status" => "Error",
                "message" => "Size could not found",
            ], 401);
        } else {
            $size->update([
                "size" => $request->size
            ]);

            return response()->json([
                'status' => 'OK',
                'message' => 'Size was updated successfully',
                'data' => $size
            ], 200);
        }
    }

    // Delete Size
    public function deleteSize($id)
    {
        $size = Size::where('id', $id)->first();

        if (!$size) {
            return response()->json([
                "status" => "Error",
                "message" => "Size could not found",
            ], 401);
        } else {
            $size->delete();

            return response()->json([
                'status' => 'OK',
                'message' => 'Size was deleted successfully',
                'data' => $size
            ], 200);
        }
    }
}
