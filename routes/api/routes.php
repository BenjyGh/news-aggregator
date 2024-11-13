<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => ['Laravel' => app()->version()]);

Route::prefix('auth')->as('auth:')->group(base_path('routes/api/auth.php'));
