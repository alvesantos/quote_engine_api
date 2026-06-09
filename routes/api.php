<?php

use App\Http\Controllers\HelloController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('hello', [HelloController::class, 'hello']);