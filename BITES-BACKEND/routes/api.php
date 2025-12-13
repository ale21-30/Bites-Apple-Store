<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Controllers
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\CommentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// =======================
// AUTH
// =======================
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);


// =======================
// PRODUCTS
// =======================

// Público: listar productos
Route::get('/products', [ProductController::class, 'index']);

// Solo admin: crear, actualizar y eliminar productos
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});


// =======================
// FAVORITES
// =======================

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::delete('/favorites/{id}', [FavoriteController::class, 'destroy']);
});


// =======================
// COMMENTS
// =======================

// Público: ver comentarios de un producto
Route::get('/products/{product}/comments', [CommentController::class, 'index']);

// Usuario autenticado: crear comentario
Route::middleware('auth:sanctum')->post('/comments', [CommentController::class, 'store']);

// Solo admin: eliminar comentario
Route::middleware(['auth:sanctum', 'admin'])
    ->delete('/comments/{id}', [CommentController::class, 'destroy']);


// =======================
// USER (opcional)
// =======================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});