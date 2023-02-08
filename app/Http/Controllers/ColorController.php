<?php

namespace App\Http\Controllers;

use App\Http\Requests\ColorCreateRequest;
use App\Http\Requests\ColorUpdateRequest;
use App\Models\Color;
use Illuminate\Http\Request;

class ColorController extends Controller
{
    // Get All Colors
    public function getColors (Request $request) 
    {
        $colors = Color::all();

        return response()->json([
            "status" => "OK",
            "data" => $colors
        ]);
    }

    // Get Detail Color By Id
    public function getColorById ($id) {
        $color = Color::where("id", $id)->first();

        if (!$color) {
            return response()->json([
                'status' => 'Error',
                'message' => 'No colors found'
            ], 404);
        }
        else {
            return response()->json([
                'status' => 'OK',
                'data' => $color
            ], 200);
        }
    }

    // Create new color
    public function createColor (ColorCreateRequest $request) {
        $color = Color::create([
            'color' => $request->color
        ]);

        if (!$color) {
            return response()->json([
                "status" => "Error",
                "message" => "Color could not be saved",
            ], 401);
        }
        else {
            return response()->json([
                "status" => "OK",
                "message" => "Color was created successfully",
                "data" => $color
            ], 200);
        }
    }

    // Update color
    public function updateColor(ColorUpdateRequest $request, $id) {
        $color = Color::where("id", $id)->first();

        if (!$color) {
            return response()->json([
                "status" => "Error",
                "message" => "Color could not found",
            ], 401);
        }
        else {
            $color->update([
                "color" => $request->color
            ]);

            return response()->json([
                'status' => 'OK',
                'message' => 'Color was updated successfully',
                'data' => $color
            ], 200);
        }
    }

    // Delete color
    public function deleteColor ($id) {
        $color = Color::where('id', $id)->first();

        if (!$color) {
            return response()->json([
                "status" => "Error",
                "message" => "Color could not found",
            ], 401);
        }
        else {
            $color->delete();

            return response()->json([
                'status' => 'OK',
                'message' => 'Color was deleted successfully',
                'data' => $color
            ],200);
        }
    }
}
