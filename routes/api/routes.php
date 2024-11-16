<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn() => ['Laravel' => app()->version()]);

// Auth Routes
Route::prefix('auth')->as('auth:')->group(base_path('routes/api/auth.php'));

// Category Routes
Route::prefix('category')->as('category:')->group(base_path('routes/api/category.php'));

// Author Routes
Route::prefix('author')->as('author:')->group(base_path('routes/api/author.php'));

// NewsSource Routes
Route::prefix('news-source')->as('news-source:')->group(base_path('routes/api/news_source.php'));

// Article Routes
Route::prefix('article')->as('article:')->group(base_path('routes/api/article.php'));
