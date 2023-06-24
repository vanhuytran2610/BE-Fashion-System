<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    // Get All Categories
    public function getCategories(Request $request)
    {
        $categories = Category::all();

        return response()->json([
            "status" => 200,
            "data" => $categories
        ], 200);
    }

    // Get Detail Category By Id
    public function getCategoryById($id)
    {
        $category = Category::where("id", $id)->first();

        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'This Category does not exist'
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'data' => $category
            ]);
        }
    }

    public function search(Request $request)
    {
        $searchQuery = $request->query('q');

        $categories = Category::where('category', 'like', "%$searchQuery%")->get();

        return response()->json([
            'status' => 200,
            'data' => $categories
        ]);
    }

    public function sort(Request $request)
    {
        $sortOrder = $request->query('order', 'asc');

        $categories = Category::orderBy('created_at', $sortOrder)->get();

        return response()->json([
            'status' => 200,
            'data' => $categories
        ]);
    }

    // Create new category
    public function createCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'categories' => 'required|array',
            'categories.*.category' => 'required|string'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->errors()
            ]);
        }

        $categoriesData = $request->input('categories');
        $categories = [];

        foreach ($categoriesData as $categoryData) {
            $category = new Category();
            $category->category = $categoryData['category'];
            $category->save();

            $categories[] = $category;
        }

        return response()->json([
            "status" => 201,
            "message" => "Category was created successfully",
            "data" => $categories
        ], 201);
    }

    // Update category
    public function updateCategory(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'error' => $validator->errors()
            ]);
        }

        $category = Category::where("id", $id)->first();

        if (!$category) {
            return response()->json([
                "status" => 404,
                "message" => "This Category does not exist",
            ]);
        } else {
            $category->category = $request->input('category');
            $category->save();

            return response()->json([
                'status' => 200,
                'message' => 'Category was updated successfully',
                'data' => $category
            ]);
        }
    }

    // Delete category
    public function deleteCategory($id)
    {
        // if($this->authorize('authorize')) {
        $category = Category::where('id', $id)->first();

        if (!$category) {
            return response()->json([
                "status" => 404,
                "message" => "This Category does not exist",
            ]);
        } else {
            $category->delete();

            return response()->json([
                'status' => 200,
                'message' => 'Category was deleted successfully',
                'data' => $category
            ]);
        }
    }

    public function deleteCategories(Request $request)
    {
        $categoryIds = $request->input('categoryIds');
        if (!is_array($categoryIds)) {
            return response()->json(['status' => 400, 'message' => 'Invalid category IDs']);
        }

        try {
            // Delete categories using the $categoryIds array
            Category::whereIn('id', $categoryIds)->delete();

            return response()->json(['status' => 200, 'message' => 'Categories deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Failed to delete categories']);
        }
    }
}
