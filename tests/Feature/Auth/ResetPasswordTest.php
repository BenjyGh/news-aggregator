<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Password;
use Symfony\Component\HttpFoundation\Response;

test('reset password validation', function () {
    $response = $this->postJson(route('auth:password.reset'), [
        'token' => '',
        'email' => '',
        'password' => '',
    ]);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "errors" => [
                ['field' => 'token'],
                ['field' => 'email'],
                ['field' => 'password'],
            ]
        ]);
});

test('password can be reset with valid token', function () {
    $user = User::factory()->create();
    $token = Password::createToken($user);

    $response = $this->postJson(route('auth:password.reset'), [
        'token' => $token,
        'email' => $user->email,
        'password' => 'new-password'
    ]);

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJson(['message' => __('passwords.reset')]);

    // Verify the password has been updated
    $this->expect(Hash::check('new-password', $user->fresh()->password))
        ->toBeTrue();
});

test('password cannot be reset with invalid token', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('auth:password.reset'), [
        'token' => 'invalid-token',
        'email' => $user->email,
        'password' => 'new-password'
    ]);

    // Assert failure response
    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "errors" => [
                ['message' => __('passwords.token')],
            ]
        ]);;
});
