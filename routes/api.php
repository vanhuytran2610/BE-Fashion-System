<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ColorController;
use App\Http\Controllers\ProfileAuthController;

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
        Route::post('/auth/register', [AuthController::class, 'register']);
        Route::post('/auth/login', [AuthController::class, 'login']);
        
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

