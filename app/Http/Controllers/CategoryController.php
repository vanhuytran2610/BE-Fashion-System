<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function list (Request $request) 
    {
        $category = Category::all();

        return response()->json([
            "status" => "Success",
            "message" => "Category successfully fetched",
            "data" => $category
        ]);
    }
}
