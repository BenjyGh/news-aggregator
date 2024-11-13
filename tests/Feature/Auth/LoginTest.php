<?php

use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;

test('registration validation', function () {
    $response = $this->postJson(route('auth:login'), [
        'email' => '',
        'password' => '',
    ]);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "errors" => [
                ['field' => 'email'],
                ['field' => 'password'],
            ]
        ]);
});

test('user can login', function () {
    $user = User::factory()->create();

    $response = $this->post(route('auth:login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertOk()
        ->assertJson(fn(AssertableJson $json) => $json->has('data.token')
            ->etc());
});

test('user cannot login with invalid credential', function () {
    $user = User::factory()->create();

    $response = $this->post(route('auth:login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "errors" => [
                [
                    'field' => 'email',
                    'message' => __('auth.failed')
                ],
            ]
        ]);
});

test('rate limiting', function () {
    $user = User::factory()->create();

    // Simulate multiple failed login attempts
    for ($i = 0; $i < 5; $i++) {
        $response = $this->postJson(route('auth:login'), [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    // On the 6th attempt, expect a 429 Too Many Requests
    $response = $this->postJson(route('auth:login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);
});
