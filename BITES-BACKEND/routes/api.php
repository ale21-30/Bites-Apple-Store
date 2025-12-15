<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| Auth
|--------------------------------------------------------------------------
*/
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);
Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/products/{product}/comments', [CommentController::class, 'index']);

/*
|--------------------------------------------------------------------------
| Protected (auth:sanctum)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    // 🔹 Usuario autenticado (ESTE ES EL QUE PREGUNTAS)
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // 🔹 Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // 🔹 Favoritos
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{product}', [FavoriteController::class, 'destroy']);
    Route::get('/favorites', [FavoriteController::class, 'index']);

    // 🔹 Comentarios
    Route::post('/products/{product}/comments', [CommentController::class, 'store']);
});
