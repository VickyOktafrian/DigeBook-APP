<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Books\BooksController;
use App\Http\Controllers\Users\UsersController;

Route::get('/books', [BooksController::class, 'getBooksJson']);
Route::get('/books/{id}', [BooksController::class, 'getBookById']);


Route::post('/register', [RegisterController::class, 'register']); // API register
Route::post('/login', [LoginController::class, 'login']); // API login

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logout']);

    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
});
use App\Http\Controllers\Books\CartController;
use App\Http\Controllers\Books\OrderController;

// Route untuk Cart
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'index']); // Menampilkan semua item di keranjang
    Route::post('/cart/add', [CartController::class, 'add']); // Menambah item ke keranjang
    Route::delete('/cart/{id}/remove', [CartController::class, 'remove']); // Menghapus item dari keranjang
});

// Route untuk Order
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [OrderController::class, 'checkout']); // Checkout order
    Route::get('/checkout/success', [OrderController::class, 'success']); // Status sukses checkout
});