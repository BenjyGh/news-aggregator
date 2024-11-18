<?php

namespace App\Http\Controllers\UserPreference;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserPreferencesResource;

class ShowController extends Controller
{
    /**
     * Get User Preferences
     *
     *  This endpoint retrieves the current preferences of the authenticated user.
     *
     * @group User
     * @subgroup User Preferences
     *
     * @authenticated
     */
    public function __invoke(): UserPreferencesResource
    {
        $user = auth()->user();

        return new UserPreferencesResource($user);
    }
}
