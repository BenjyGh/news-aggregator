<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LogoutController extends Controller
{
    /**
     * Handle an incoming logout request.
     */
    public function __invoke(): Response
    {
        \auth()->user()->currentAccessToken()->delete();

        return response()->noContent();
    }
}
