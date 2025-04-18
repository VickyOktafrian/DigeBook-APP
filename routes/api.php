<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Books\BooksController;

Route::get(uri: '/books', [BooksController::class, 'getBooks']);
