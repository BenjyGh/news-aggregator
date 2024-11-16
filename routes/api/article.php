<?php

use App\Http\Controllers\Article;
use Illuminate\Support\Facades\Route;

Route::get('/', Article\IndexController::class)->name('index');
Route::get('{article}', Article\ShowController::class)->name('show');
