<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Knuckles\Scribe\Attributes\Authenticated;
use Knuckles\Scribe\Attributes\Endpoint;
use Knuckles\Scribe\Attributes\Group;

class RegisterController extends Controller
{
    /**
     * Register
     *
     * This endpoint allows a new user to create an account.
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @group Authentication
     */
    public function __invoke(RegisterRequest $request): JsonResponse
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return $this->respondSuccess(
            message: __('auth.register'),
            statusCode: 201
        );
    }
}
