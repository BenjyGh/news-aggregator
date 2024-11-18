<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    /**
     * Login
     *
     * This endpoint authenticates a user and returns an access token.
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @group Authentication
     */
    public function __invoke(LoginRequest $request): JsonResponse
    {
        $user = $request->authenticate();

        $token = $user->createToken('api-token')->plainTextToken;

        return $this->respondSuccess('', [
            'token' => $token,
        ]);
    }
}
