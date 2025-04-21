<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Books\BooksController;
use App\Http\Controllers\Users\UsersController;

Route::get('/books', [BooksController::class, 'getBooksJson']);
Route::get('/books/{id}', [BooksController::class, 'getBookById']);


Route::post('/register', [RegisterController::class, 'registerAPI']); // API register
Route::post('/login', [LoginController::class, 'loginAPI']); // API login

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [LoginController::class, 'logoutAPI']);

    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
});
use App\Http\Controllers\Books\CartController;
use App\Http\Controllers\Books\OrderController;

// Route untuk Cart
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/cart', [CartController::class, 'indexAPI']); // Menampilkan semua item di keranjang
    Route::post('/cart/add', [CartController::class, 'addAPI']); // Menambah item ke keranjang
    Route::delete('/cart/{id}/remove', [CartController::class, 'removeAPI']); // Menghapus item dari keranjang
});

// Route untuk Order
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/checkout', [OrderController::class, 'checkoutAPI']); // Checkout order
    Route::get('/checkout/success', [OrderController::class, 'successAPI']); // Status sukses checkout
    Route::post('/orders', [OrderController::class, 'indexAPI']);

});
Route::post('/ask-api', [App\Http\Controllers\OpenRouterController::class, 'askApi']);