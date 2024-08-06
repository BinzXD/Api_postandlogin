<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\LogOutControlller;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\TagController;

Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => "Akses Ditolak"
    ], 401);
})->name('login');

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/login', [LoginController::class, 'index']);
    Route::get('/logout', [LogOutControlller::class, 'logout']);

    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoriController::class, 'index']);
        Route::post('/', [CategoriController::class, 'store']);
        Route::patch('/{id}', [CategoriController::class, 'update']);
    });

    Route::prefix('posts')->group(function () {
        Route::get('/', [PostController::class, 'index']);
        Route::post('/', [PostController::class, 'store']);
        Route::get('/{id}', [PostController::class, 'show']);
        Route::patch('/{id}', [PostController::class, 'update']);
        Route::delete('/{id}', [PostController::class, 'destroy']);
    });

    Route::prefix('tags')->group(function () {
        Route::get('/', [TagController::class, 'index']);
        Route::post('/', [TagController::class, 'store']);
        Route::patch('/{id}', [TagController::class, 'update']);
    });
});