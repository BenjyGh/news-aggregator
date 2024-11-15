<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => ['Laravel' => app()->version()]);

Route::prefix('auth')->as('auth:')->group(base_path('routes/api/auth.php'));
Route::prefix('category')->as('category:')->group(base_path('routes/api/category.php'));
