<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\CategoriController;
use App\Http\Controllers\LogOutControlller;
use App\Http\Controllers\PostController;
use App\Http\Controllers\RegisterController;


Route::get('/', function () {
    return response()->json([
        'status' => true,
        'message' => "Akses Ditolak"
    ], 401);
})->name('login');

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);
Route::get('/login', [LoginController::class, 'index'])->middleware('auth:sanctum');
Route::get('/logout', [LogOutControlller::class, 'logout'])->middleware('auth:sanctum');

Route::prefix('categories')->group(function () {
    Route::get('/', [CategoriController::class, 'index']);   
    Route::post('/', [CategoriController::class, 'store']);  
    Route::get('/{id}', [CategoriController::class, 'show']);
    Route::put('/{id}', [CategoriController::class, 'update']); 
    Route::delete('/{id}', [CategoriController::class, 'destroy']); 
});

Route::prefix('posts')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PostController::class, 'index']);   
    Route::post('/', [PostController::class, 'store']); 
    Route::get('/{id}', [PostController::class, 'show']); 
    Route::patch('/{id}', [PostController::class, 'update']); 
    Route::delete('/{id}', [PostController::class, 'destroy']);
});