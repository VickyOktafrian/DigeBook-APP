<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Books\BooksController;
use App\Http\Controllers\Users\UsersController;

Route::get('/books', [BooksController::class, 'getBooksJson']);

Route::post('/register', [UsersController::class, 'register']);
Route::post('/login', [UsersController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UsersController::class, 'logout']);

    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
});
