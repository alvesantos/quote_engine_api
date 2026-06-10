<?php

use App\Http\Controllers\HelloController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('hello', [HelloController::class, 'hello']);

Route::post('quotes', [QuoteController::class, 'store']);
Route::get('/quotes', [QuoteController::class, 'index']);