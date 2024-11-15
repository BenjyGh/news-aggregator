<?php

use App\Http\Controllers\NewsSource;
use Illuminate\Support\Facades\Route;

Route::get('/', NewsSource\IndexController::class)->name('index');
