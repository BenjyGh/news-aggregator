<?php

use App\Models\User;
use Symfony\Component\HttpFoundation\Response;

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->postJson(route('auth:login'), [
        'email' => $user->email,
        'password' => 'password'
    ]);

    $token = $response['data']['token'];

    $response = $this->withHeaders([
        'Authorization' => "Bearer $token",
    ])->postJson(route('auth:logout'));

    // Token should be removed from database
    $this->assertDatabaseCount('personal_access_tokens', 0);

    $response->assertNoContent();
});

test('logout without token returns error', function () {
    $response = $this->postJson(route('auth:logout'));

    $response
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJsonFragment(['message' => 'Unauthenticated.']);
});

test('logout with invalid token returns error', function () {
    $response = $this->withHeaders([
        'Authorization' => 'Bearer invalid_token',
    ])->postJson(route('auth:logout'));

    $response
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJsonFragment(['message' => 'Unauthenticated.']);
});
