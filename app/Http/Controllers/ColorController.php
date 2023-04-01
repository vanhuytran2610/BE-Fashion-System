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
        ],200);
    }

    // Get Detail Color By Id
    public function getColorById ($id) {
        $color = Color::where("id", $id)->first();

        if (!$color) {
            return response()->json([
                'status' => 'Error',
                'message' => 'This color does not exist'
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
        // $this->authorize('authorize');
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
        // $this->authorize('authorize');
        $color = Color::where("id", $id)->first();

        if (!$color) {
            return response()->json([
                "status" => "Error",
                "message" => "This color does not exist",
            ], 404);
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
        // $this->authorize('authorize');
        $color = Color::where('id', $id)->first();

        if (!$color) {
            return response()->json([
                "status" => "Error",
                "message" => "This color does not exist",
            ], 404);
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
