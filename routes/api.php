<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductColorSizeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileAuthController;
use App\Http\Controllers\ProvinceDistrictWard;
use App\Http\Controllers\ProvinceDistrictWardController;
use App\Http\Controllers\SizeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/register', [AuthController::class, 'register']);

// Province-District-Ward
Route::get('/getProvince', [ProvinceDistrictWardController::class, 'getProvince']);
Route::get('/getDistrict/{province_code}', [ProvinceDistrictWardController::class, 'getDistrict']);
Route::get('/getWard/{district_code}', [ProvinceDistrictWardController::class, 'getWard']);

// Category
Route::get('/get-categories', [CategoryController::class, 'getCategories']);

// Color
Route::get('/get-colors', [ColorController::class, 'getColors']);

// Size
Route::get('/get-sizes', [SizeController::class, 'getSizes']);

// Product

Route::get('/get-products/{category_id}', [ProductController::class, 'getProductByCategory']);
Route::get('/get-product/{category_id}/{product_id}', [ProductController::class, 'getDetailProduct']);
Route::get('/get-product/{id}', [ProductController::class, 'getProductById']);
Route::get('/products/search', [ProductController::class, 'searchProduct']);

Route::post('/add-to-cart', [CartController::class, 'addToCart']);
Route::get('/cart', [CartController::class, 'viewCartByUser']);
Route::put('/update-quantity/{cart_id}/{scope}', [CartController::class, 'updateQuantity']);
Route::delete('/delete-cartItem/{cart_id}', [CartController::class, 'deleteCartItem']);

Route::post('/place-order', [CheckoutController::class, 'placeOrder']);
Route::get('/orders', [OrderController::class, 'getOrdersByUser']);

Route::middleware(['auth:sanctum', 'isAPIAdmin'])->group(function () {
    Route::get('/checkingAuthenticated', function () {
        return response()->json(['status' => 200, 'message' => 'You are in'], 200);
    });
});

Route::post('/login', [AuthController::class, 'login']);

// Admin Methods
Route::group(
    [
        'middleware' => ['auth:sanctum', 'isAPIAdmin'],
    ],
    function ($router) {
        Route::get('/checkingAuthenticated', function () {
            return response()->json(['status' => 'OK', 'message' => 'You are in'], 200);
        });

        // Auth
        Route::get('auth/get-users', [ProfileAuthController::class, 'getAllUsers']);

        // Category
        Route::get('/auth/get-category/{id}', [CategoryController::class, 'getCategoryById']);
        Route::post('/auth/create-category', [CategoryController::class, 'createCategory']);
        Route::put('/auth/update-category/{id}', [CategoryController::class, 'updateCategory']);
        Route::delete('/auth/delete-category/{id}', [CategoryController::class, 'deleteCategory']);
        Route::post('/auth/delete-categories', [CategoryController::class, 'deleteCategories']);
        Route::get('/auth/categories/search', [CategoryController::class, 'search']);
        Route::get('/auth/categories/sort', [CategoryController::class, 'sort']);

        // Color
        Route::get('/auth/get-color/{id}', [ColorController::class, 'getColorById']);
        Route::post('/auth/create-color', [ColorController::class, 'createColor']);
        Route::put('/auth/update-color/{id}', [ColorController::class, 'updateColor']);
        Route::delete('/auth/delete-color/{id}', [ColorController::class, 'deleteColor']);

        // Size
        Route::get('/auth/get-size/{id}', [SizeController::class, 'getSizeById']);
        Route::post('/auth/create-size', [SizeController::class, 'createSize']);
        Route::put('/auth/update-size/{id}', [SizeController::class, 'updateSize']);
        Route::delete('/auth/delete-size/{id}', [SizeController::class, 'deleteSize']);

        // Product
        Route::post('/auth/create-product', [ProductController::class, 'createProduct']);
        Route::post('/auth/update-product/{id}', [ProductController::class, 'updateProduct']);
        Route::delete('/auth/delete-product/{id}', [ProductController::class, 'deleteProduct']);
        Route::post('/auth/delete-products', [ProductController::class, 'deleteProducts']);
        Route::get('/auth/get-all-products', [ProductController::class, 'getAllProducts']);
        Route::get('/auth/products', [ProductController::class, 'index']);

        

        // Cart
        Route::get('/auth/orders', [OrderController::class, 'getOrders']);
        Route::get('/auth/order/{id}', [OrderController::class, 'getDetailOrder']);
        Route::delete('/auth/orders/{id}', [OrderController::class, 'destroy']);
        Route::post('/auth/orders', [OrderController::class, 'destroyMultiple']);
    }
);

// User Methods
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    function ($router) {
        // Auth
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/change-password', [ProfileAuthController::class, 'changePassword']);
        Route::post('/update-profile', [ProfileAuthController::class, 'updateProfile']);
        Route::get('/profile', [ProfileAuthController::class, 'getAuthProfile']);
    }
);
