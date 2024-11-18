<?php

namespace App\Http\Controllers\UserPreference;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserPreferencesResource;

class ShowController extends Controller
{
    /**
     * Handle an incoming user preferences show request.
     */
    public function __invoke(): UserPreferencesResource
    {
        $user = auth()->user();

        return new UserPreferencesResource($user);
    }
}
