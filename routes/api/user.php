<?php

use App\Http\Controllers\User;
use Illuminate\Support\Facades\Route;

Route::get('newsfeed', User\NewsfeedController::class)->name('newsfeed');
