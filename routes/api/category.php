<?php

use App\Http\Controllers\Category;
use Illuminate\Support\Facades\Route;

Route::get('/', Category\IndexController::class)->name('index');
