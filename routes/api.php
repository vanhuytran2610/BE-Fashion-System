<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProductColorSizeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileAuthController;
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

// Auth - Register, Login
Route::group(
    [
        'middleware' => 'api'
    ],
    function ($router) {
        Route::post('/auth/login', [AuthController::class, 'login']);
        Route::post('/auth/register', [AuthController::class, 'register']);
    }
);

// Auth - Logout, Profile
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    function($router) {  
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::post('/auth/change-password', [ProfileAuthController::class, 'changePassword']);
        Route::post('/auth/update-profile', [ProfileAuthController::class, 'updateProfile']);
        Route::get('/auth/profile', [ProfileAuthController::class, 'getAuthProfile']);
        Route::get('auth/get-users', [ProfileAuthController::class, 'getAllUsers']);
    }
);

// Auth - Category
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    function($router) {
        Route::get('/auth/get-categories', [CategoryController::class, 'getCategories']);
        Route::get('/auth/get-category/{id}', [CategoryController::class, 'getCategoryById']);
        Route::post('/auth/create-category', [CategoryController::class, 'createCategory']);
        Route::put('/auth/update-category/{id}', [CategoryController::class, 'updateCategory']);
        Route::delete('/auth/delete-category/{id}', [CategoryController::class, 'deleteCategory']);
    }
);

// Auth - Color
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    function($router) {
        Route::get('/auth/get-colors', [ColorController::class, 'getColors']);
        Route::get('/auth/get-color/{id}', [ColorController::class, 'getColorById']);
        Route::post('/auth/create-color', [ColorController::class, 'createColor']);
        Route::put('/auth/update-color/{id}', [ColorController::class, 'updateColor']);
        Route::delete('/auth/delete-color/{id}', [ColorController::class, 'deleteColor']);
    }
);

// Auth - Size
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    function($router) {
        Route::get('/auth/get-sizes', [SizeController::class, 'getSizes']);
        Route::get('/auth/get-size/{id}', [SizeController::class, 'getSizeById']);
        Route::post('/auth/create-size', [SizeController::class, 'createSize']);
        Route::put('/auth/update-size/{id}', [SizeController::class, 'updateSize']);
        Route::delete('/auth/delete-size/{id}', [SizeController::class, 'deleteSize']);
    }
);

// Product
Route::group(
    [
        'middleware' => ['auth:sanctum']
    ],
    function ($router) {
        Route::post('/auth/create-product', [ProductController::class, 'createProduct']);
        Route::get('/auth/get-products', [ProductController::class, 'getProducts']);
        Route::get('/auth/get-product/{id}', [ProductController::class, 'getProductById']);
        Route::put('/auth/update-product/{id}', [ProductController::class, 'updateProduct']);
        Route::delete('/auth/delete-product/{id}', [ProductController::class, 'deleteProduct']);
    }
);

// Cart
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    function ($router) {
        Route::post('/auth/add-to-cart', [CartController::class, 'addToCart']);
        Route::get('/auth/carts', [CartController::class, 'viewCart']);
        Route::put('/auth/update-quantity/{cart_id}/{scope}', [CartController::class, 'updateQuantity']);
        Route::delete('auth/delete-cartitem/{cart_id}',[CartController::class, 'deleteCartItem']);
    }
);

// Order and Checkout
Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    function ($router) {
        Route::post('/auth/place-order', [CheckoutController::class, 'placeOrder']);
    }
);
