<?php

namespace App\Http\Controllers\UserPreference;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserPreferencesRequest;
use App\Http\Resources\UserPreferencesResource;

class UpdateController extends Controller
{
    /**
     * Update User Preferences.
     *
     * This endpoint allows an authenticated user to update their preferences.
     * @group User
     * @subgroup User Preferences
     *
     * @authenticated
     */
    public function __invoke(UserPreferencesRequest $request): UserPreferencesResource
    {
        $user = auth()->user();

        $validated = $request->all();

        // Clear existing preferences
        $user->preferences()->delete();

        $user->preferredNewsSources()->sync($validated['news_sources']);
        $user->preferredAuthors()->sync($validated['authors']);
        $user->preferredCategories()->sync($validated['categories']);

        return new UserPreferencesResource($user);
    }
}
