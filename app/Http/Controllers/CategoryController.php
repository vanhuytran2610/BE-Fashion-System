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
        ],200);
    }

    // Get Detail Category By Id
    public function getCategoryById ($id) {
        // if ($this->authorize('authorize')) {
            $category = Category::where("id", $id)->first();

            if (!$category) {
                return response()->json([
                    'status' => 'Error',
                    'message' => 'This Category does not exist'
                ], 404);
            }
            else {
                return response()->json([
                    'status' => 'OK',
                    'data' => $category
                ], 200);
            }
        // }
        // else {
        //     return response()->json([
        //         'status' => 'Error',
        //         'message' => 'Only Admin has access'
        //     ],401);
        // }
    }

    // Create new category
    public function createCategory (CategoryCreateRequest $request) {
        // if($this->authorize('authorize')) {
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
        // }
        // else {
        //     return response()->json([
        //         'status' => 'Error',
        //         'message' => 'Only Admin has access'
        //     ],401);
        // }
    }

    // Update category
    public function updateCategory (CategoryUpdateRequest $request, $id) {
        // if($this->authorize('authorize')) {
            $category = Category::where("id", $id)->first();

            if (!$category) {
                return response()->json([
                    "status" => "Error",
                    "message" => "This Category does not exist",
                ], 404);
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
        // }
        // else {
        //     return response()->json([
        //         'status' => 'Error',
        //         'message' => 'Only Admin has access'
        //     ],401);
        // }
    }

    // Delete category
    public function deleteCategory ($id) {
        // if($this->authorize('authorize')) {
            $category = Category::where('id', $id)->first();

            if (!$category) {
                return response()->json([
                    "status" => "Error",
                    "message" => "This Category does not exist",
                ], 404);
            }
            else {
                $category->delete();
    
                return response()->json([
                    'status' => 'OK',
                    'message' => 'Category was deleted successfully',
                    'data' => $category
                ],200);
            }
        // }
        // else {
        //     return response()->json([
        //         'status' => 'Error',
        //         'message' => 'Only Admin has access'
        //     ],401);
        // }
    }
}
