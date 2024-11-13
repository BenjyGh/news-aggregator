<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;

test('registration validation', function () {
    $response = $this->postJson(route('auth:register'), [
        'name' => '',
        'email' => '',
        'password' => '',
    ]);

    $response
        ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "errors" => [
                ['field' => 'name'],
                ['field' => 'email'],
                ['field' => 'password'],
            ]
        ]);
});

test('no duplicate email is allowed', function () {
    User::factory()->create(['email' => 'test@example.com']);

    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ];

    $response = $this->postJson(route('auth:register'), $data);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "errors" => [
                ['field' => 'email'],
            ]
        ]);
});

test('user is saved in database', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ];

    $this->postJson(route('auth:register'), $data);

    $this->assertDatabaseHas('users', [
        'email' => $data['email']
    ]);
});

test('password is hashed', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ];

    $this->postJson(route('auth:register'), $data);

    $user = User::where('email', $data['email'])->first();
    expect($user)
        ->and(Hash::check($data['password'], $user->password))
        ->toBeTrue();
});

test('registration response is the expected format', function () {
    $data = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
    ];

    $response = $this->postJson(route('auth:register'), $data);

    $response
        ->assertStatus(Response::HTTP_CREATED)
        ->assertExactJson([
            'message' => __('auth.register'),
            'status' => Response::HTTP_CREATED,
        ]);
});
