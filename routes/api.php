<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Books\BooksController;

Route::get('/books', [BooksController::class, 'getBooksJson']);
