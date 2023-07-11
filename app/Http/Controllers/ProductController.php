<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductSize;
use App\Models\Size;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function createProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric|max:10000000',
            'sizes' => 'required|array',
            'sizes.*.id' => 'required|exists:sizes,id',
            'sizes.*.quantity' => 'required|integer|max:1000',
            'image_avatar' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'images' => 'nullable|array',
            'images.*' => 'image',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ]);
        }

        $product = new Product;
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->category_id = $request->input('category_id');
        $product->color_id = $request->input('color_id');
        $product->price = $request->input('price');
        $product->save();

        $sizes = $request->input('sizes');
        foreach ($sizes as $size) {
            $productSize = new ProductSize;
            $productSize->product_id = $product->id;
            $productSize->size_id = $size['id'];
            $productSize->quantity = $size['quantity'];
            $productSize->save();
        }
        $productSize = $product->sizes;

        if ($request->hasFile('image_avatar')) {
            $file = $request->file('image_avatar');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move('uploads/product/', $fileName);
            $product->image_avatar = 'uploads/product/' . $fileName;
        }

        if ($request->hasFile('images')) {
            $images = $request->file('images');
            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                $image->move('uploads/product/', $fileName);

                $productImage = new ProductImage;
                $productImage->product_id = $product->id;
                $productImage->image_path = 'uploads/product/' . $fileName;
                $productImage->save();
            }
        }
        $product->save();
        $productImages = $product->images;

        return response()->json([
            'status' => 201,
            'message' => 'Products created successfully',
            'data' => $product
        ]);
    }

    public function searchProduct(Request $request)
    {
        $searchTerm = $request->input('search');

        $products = Product::with('color', 'category', 'sizes')->search($searchTerm)->get();

        return response()->json($products);
    }

    public function index(Request $request)
    {
        $query = Product::query();

        // Search by name
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Sort by created_at or price
        if ($request->has('sort')) {
            $sortField = $request->sort;
            $sortDirection = $request->has('direction') ? $request->direction : 'asc';

            $query->orderBy($sortField, $sortDirection);
        }

        // Filter by category
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by color
        if ($request->has('color_id')) {
            $query->where('color_id', $request->color_id);
        }

        $products = $query->get();

        return response()->json($products);
    }
    
    public function getProducts(Request $request)
    {
        //$product_query = Product::query()->with(['category', 'color']); // for pagination
        $product_query = Product::with(['category', 'color', 'images', 'sizes']);
        $products = $product_query->get();
        // $lower_price = Product::whereNotNull('price')->min('price');
        // dd($lower_price);

        // Search product by name
        // if ($request->keyword) {
        //     $products = $product_query->where('name', 'LIKE', '%' . $request->keyword . '%');
        // }

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

        return response()->json([
            "status" => 200,
            "data" => $products
        ]);
    }

    public function getAllProducts()
    {
        $products = Product::with('category', 'sizes', 'images', 'color')->get();

        return response()->json([
            'status' => 200,
            'data' => $products
        ]);
    }

    public function getProductByCategory($categoryId)
    {
        $category = Category::where('id', $categoryId)->first();

        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'No category found'
            ]);
        } else {
            $product = Product::where('category_id', $categoryId)->get();
            if (!$product) {
                return response()->json([
                    'status' => 400,
                    'message' => 'No product found'
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'data' => [
                        'product' => $product,
                        'category' => $category
                    ]
                ]);
            }
        }
    }

    public function getProductById($id)
    {
        $product = Product::with(['category', 'color', 'sizes', 'images'])->where('id', $id)->first();

        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'No product found'
            ]);
        } else {
            return response()->json([
                'status' => 200,
                'data' => $product
            ]);
        }
    }

    public function getDetailProduct($categoryId, $productId)
    {
        $category = Category::where('id', $categoryId)->first();

        if (!$category) {
            return response()->json([
                'status' => 404,
                'message' => 'No category found'
            ]);
        } else {
            $product = Product::with(['category', 'sizes', 'images', 'color'])->where('category_id', $categoryId)->where('id', $productId)->get();
            if ($product->isEmpty()) {
                return response()->json([
                    'status' => 400,
                    'message' => 'No product found'
                ]);
            } else {
                return response()->json([
                    'status' => 200,
                    'data' => $product
                ]);
            }
        }
    }

    public function updateProduct(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'color_id' => 'required|exists:colors,id',
            'price' => 'required|numeric|max:10000000',
            'sizes' => 'required|array',
            'sizes.*.id' => 'required|exists:sizes,id',
            'sizes.*.quantity' => 'required|integer|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors(),
            ]);
        }

        $product = Product::with(['category', 'color', 'sizes', 'images'])->findOrFail($id);

        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'Product not found'
            ]);
        }

        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->category_id = $request->input('category_id');
        $product->color_id = $request->input('color_id');
        $product->price = $request->input('price');

        // Update product avatar image
        if ($request->hasFile('image_avatar')) {
            $path = $product->image_avatar;
            if (File::exists($path)) {
                File::delete($path);
            }
            $file = $request->file('image_avatar');
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '.' . $extension;
            $file->move('uploads/product/', $fileName);
            $product->image_avatar = 'uploads/product/' . $fileName;
        }

        // Update product images
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            // Delete existing images
            foreach ($product->images as $existingImage) {
                $path = $existingImage->image_path;
                if (File::exists($path)) {
                    File::delete($path);
                }
                $existingImage->delete();
            }
            // Upload and save new images
            foreach ($images as $image) {
                $extension = $image->getClientOriginalExtension();
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                $image->move('uploads/product/', $fileName);

                $productImage = new ProductImage;
                $productImage->product_id = $product->id;
                $productImage->image_path = 'uploads/product/' . $fileName;
                $productImage->save();
            }
        }

        $product->save(); // Save the product after updating avatar image and images

        // Update product sizes
        $sizes = $request->input('sizes');

        // Remove existing sizes
        $product->sizes()->detach();

        foreach ($sizes as $size) {
            $product->sizes()->attach($size['id'], ['quantity' => $size['quantity']]);
        }

        $productImages = $product->images;

        return response()->json([
            'status' => 200,
            'message' => 'Product updated successfully',
            'data' => $product
        ]);
    }



    public function deleteProduct($id)
    {
        $product = Product::with(['category', 'color', 'images', 'sizes'])->where('id', $id)->first();

        if (!$product) {
            return response()->json([
                'status' => 404,
                'message' => 'No product found'
            ]);
        } else {
            $product->delete();

            return response()->json([
                "status" => 200,
                "message" => "Product was deleted successfully",
                "data" => $product
            ]);
        }
    }

    public function deleteProducts(Request $request)
    {
        $productIds = $request->input('productIds');
        if (!is_array($productIds)) {
            return response()->json(['status' => 400, 'message' => 'Invalid product IDs']);
        }

        try {
            // Delete categories using the $categoryIds array
            Product::whereIn('id', $productIds)->delete();

            return response()->json(['status' => 200, 'message' => 'Products deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => 'Failed to delete products']);
        }
    }
}
