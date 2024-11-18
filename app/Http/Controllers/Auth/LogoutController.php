<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogoutController extends Controller
{
    /**
     * Logout
     *
     * This endpoint revokes the user's authentication token, effectively logging them out from the application.
     *
     * @authenticated
     * @group Authentication
     */
    public function __invoke(): Response
    {
        \auth()->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
