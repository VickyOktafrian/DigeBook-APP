<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OpenRouterController;
use App\Http\Controllers\Books\BooksController;

Route::get('/ask', [OpenRouterController::class, 'form']);
Route::post('/ask', [OpenRouterController::class, 'ask']);
Route::get('/books', [BooksController::class, 'getBooks']);


