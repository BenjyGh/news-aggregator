<?php

use App\Http\Controllers\UserPreference;
use Illuminate\Support\Facades\Route;

Route::get('/', UserPreference\ShowController::class)->name('show');
Route::put('/', UserPreference\UpdateController::class)->name('update');
