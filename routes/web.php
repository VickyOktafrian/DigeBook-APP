<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\OpenRouterController;

Route::get('/ask', [OpenRouterController::class, 'form']);
Route::post('/ask', [OpenRouterController::class, 'ask']);

