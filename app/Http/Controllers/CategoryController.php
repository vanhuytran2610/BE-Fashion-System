<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Support\Facades\Request;

class CategoryController extends Controller
{
    // Get All Categories
    public function getCategories (Request $request) 
    {
        $categories = Category::all();

        return response()->json([
            "status" => "OK",
            "data" => $categories
        ]);
    }

    // Get Detail Category By Id
    public function getCategoryById ($id) {
        $category = Category::where("id", $id)->first();

        if (!$category) {
            return response()->json([
                'status' => 'Error',
                'message' => 'No categories found'
            ], 404);
        }
        else {
            return response()->json([
                'status' => 'OK',
                'data' => $category
            ], 200);
        }
    }

    // Create new category
    public function createCategory (CategoryCreateRequest $request) {
        $category = Category::create([
            'name' => $request->name
        ]);

        if (!$category) {
            return response()->json([
                "status" => "Error",
                "message" => "Category could not be saved",
            ], 401);
        }
        else {
            return response()->json([
                "status" => "OK",
                "message" => "Category was created successfully",
                "data" => $category
            ], 200);
        }
    }

    // Update category
    public function updateCategory (CategoryUpdateRequest $request, $id) {
        $category = Category::where("id", $id)->first();

        if (!$category) {
            return response()->json([
                "status" => "Error",
                "message" => "Category could not found",
            ], 401);
        }
        else {
            $category->update([
                "name" => $request->name
            ]);

            return response()->json([
                'status' => 'OK',
                'message' => 'Category was updated successfully',
                'data' => $category
            ], 200);
        }
    }

    // Delete category
    public function deleteCategory ($id) {
        $category = Category::where('id', $id)->first();

        if (!$category) {
            return response()->json([
                "status" => "Error",
                "message" => "Category could not found",
            ], 401);
        }
        else {
            $category->delete();

            return response()->json([
                'status' => 'OK',
                'message' => 'Category was deleted successfully',
                'data' => $category
            ],200);
        }
    }
}
