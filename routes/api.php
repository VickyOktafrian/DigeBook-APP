<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Books\BooksController;
use App\Http\Controllers\Books\CartController;
use App\Http\Controllers\Books\OrderController;
use App\Http\Controllers\OpenRouterController;

// Books Routes
Route::get('/books', [BooksController::class, 'getBooksJson']);
Route::get('/books/{id}', [BooksController::class, 'getBookById']);

// Auth Routes
Route::post('/register', [RegisterController::class, 'registerAPI']);
Route::post('/login', [LoginController::class, 'loginAPI']);

// Chatbot API
Route::post('/ask-api', [OpenRouterController::class, 'askApi']);

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth
    Route::post('/logout', [LoginController::class, 'logoutAPI']);
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
    
    // Cart
    Route::get('/cart', [CartController::class, 'indexAPI']);
    Route::post('/cart/add', [CartController::class, 'addAPI']);
    Route::delete('/cart/{id}/remove', [CartController::class, 'removeAPI']);
    
    // Orders
    Route::post('/checkout', [OrderController::class, 'checkoutAPI']);
    Route::get('/checkout/success', [OrderController::class, 'successAPI']);
    Route::post('/orders', [OrderController::class, 'indexAPI']);
});