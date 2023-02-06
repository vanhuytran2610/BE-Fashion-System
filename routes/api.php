<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//use App\Http\Controllers\CategoryController;
use App\Http\Controllers\AuthController;

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

// Auth
Route::group(
    [
        'middleware' => 'api'
    ],
    function ($router) {
        Route::post('/auth/register', [AuthController::class, 'register']);
        Route::post('/auth/login', [AuthController::class, 'login']);
        
    }
);

Route::group(
    [
        'middleware' => ['auth:sanctum'],
    ],
    function($router) {
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        // Route::post('/change-password', [ProfileController::class, 'changePassword']);
        // Route::post('/update-profile', [ProfileController::class, 'updateProfile']);
    }
);

