<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\HttpFoundation\Response;

test('forgot password validation', function () {
    $response = $this->postJson(route('auth:password.forgot'), [
        'email' => '',
    ]);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "errors" => [
                ['field' => 'email'],
            ]
        ]);
});

test('forgot password save token in database', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('auth:password.forgot'), [
        'email' => $user->email
    ]);

    $this->assertDatabaseHas('password_reset_tokens', [
        'email' => $user->email
    ]);

    $response
        ->assertStatus(Response::HTTP_OK)
        ->assertJson(['message' => __('passwords.sent')]);
});
