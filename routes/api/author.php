<?php

use App\Http\Controllers\Author;
use Illuminate\Support\Facades\Route;

Route::get('/', Author\IndexController::class)->name('index');
