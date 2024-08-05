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
    Route::get('/', [CategoriController::class, 'index']);   // Menampilkan semua kategori
    Route::post('/', [CategoriController::class, 'store']);  // Menambahkan kategori baru
    Route::get('/{id}', [CategoriController::class, 'show']); // Menampilkan kategori berdasarkan ID
    Route::put('/{id}', [CategoriController::class, 'update']); // Memperbarui kategori berdasarkan ID
    Route::delete('/{id}', [CategoriController::class, 'destroy']); // Menghapus kategori berdasarkan ID
});

Route::prefix('posts')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [PostController::class, 'index']);   
    Route::post('/', [PostController::class, 'store']); 
    Route::get('/{id}', [PostController::class, 'show']); 
    Route::put('/{id}', [PostController::class, 'update']); 
    Route::delete('/{id}', [PostController::class, 'destroy']);
});