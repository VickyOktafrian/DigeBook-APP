<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Books\BooksController;
use App\Http\Controllers\Books\CartController;
use App\Http\Controllers\Books\OrderController;
use App\Http\Controllers\OpenRouterController;

// Chatbot Routes
Route::get('/ask', [OpenRouterController::class, 'form']);
Route::post('/ask', [OpenRouterController::class, 'ask']);

// Public Book Routes
Route::get('/', [BooksController::class, 'showBooksView'])->name('books.view');
Route::get('/books/fetch', [BooksController::class, 'getBooks'])->name('books.fetch');

// Authentication Routes
Auth::routes();

// Protected Routes
Route::middleware('auth')->group(function () {
    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::delete('/cart/{id}/remove', [CartController::class, 'remove'])->name('cart.remove');

    // Checkout & Orders
    Route::get('/checkout', [OrderController::class, 'showCheckoutPage'])->name('checkout.view');
    Route::post('/checkout', [OrderController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
});

// Book Detail Route - Keep at bottom to prevent conflicts with other routes
Route::get('/{slug}', [BooksController::class, 'BookDetail'])->name('book.detail');