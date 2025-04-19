<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OpenRouterController;
use App\Http\Controllers\Books\BooksController;
use App\Http\Controllers\Users\UsersController;

Route::get('/ask', [OpenRouterController::class, 'form']);
Route::post('/ask', [OpenRouterController::class, 'ask']);
Route::get('/books/fetch', [BooksController::class, 'getBooks'])->name('books.fetch');
Route::get('/', [BooksController::class, 'showBooksView'])->name('books.view');




Auth::routes();

// Route::post('/logout', [UsersController::class, 'logout'])->name('logout');

