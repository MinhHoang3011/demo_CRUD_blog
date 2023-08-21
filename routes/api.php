<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PostController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


// Route for user authentication
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Protected routes for creating, updating, and managing posts
Route::middleware('auth:sanctum')->group(function () {
    Route::post('posts', [PostController::class, 'store']); // Create a new post
    Route::put('posts/{id}', [PostController::class, 'update']); // Update a post
    Route::delete('posts/{id}', [PostController::class, 'destroy']); // Delete a post
    Route::delete('posts', [PostController::class, 'multipleDestroy']); // Delete multiple post
});

// Public routes for fetching posts
Route::get('posts', [PostController::class, 'index']); // Get all posts
Route::get('posts/{id}', [PostController::class, 'show']); // Get a specific post

Route::post('register', [AuthController::class,'register']);
Route::post('login', [AuthController::class,'login']);

//Route::post('posts', [PostController::class,'store']);
//Route::post('posts/{id}', [PostController::class,'update']);
//Route::get('posts', [PostController::class,'index']);
//Route::get('posts/{id}', [PostController::class,'show']);
//Route::delete('posts/{id}', [PostController::class,'destroy']);
