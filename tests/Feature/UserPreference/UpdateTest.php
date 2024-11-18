<?php

use App\Models\Author;
use App\Models\Category;
use App\Models\NewsSource;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use \Symfony\Component\HttpFoundation\Response;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('allows a user to update their preferences', function () {
    Sanctum::actingAs($this->user);

    $newsSources = NewsSource::factory()->count(3)->create()->pluck('id');
    $categories = Category::factory()->count(3)->create()->pluck('id');
    $authors = Author::factory()->count(3)->create()->pluck('id');

    $payload = [
        'news_sources' => [$newsSources[0], $newsSources[1]],
        'categories' => [$categories[0]],
        'authors' => [$authors[0], $authors[1]],
    ];

    $response = $this->putJson(route('user-preference:update'), $payload);

    $response
        ->assertOk()
        ->assertJsonStructure([
            'news_sources' => [
                "*" => ['id', 'name', 'url']
            ],
            'authors' => [
                "*" => ['id', 'name']
            ],
            'categories' => [
                "*" => ['id', 'name']
            ],
        ]);

    expect($this->user->preferences()->count())
        ->toBe(5);

    $this->assertDatabaseHas('user_preferences', [
        'user_id' => $this->user->id,
        'preferable_id' => $newsSources[0],
        'preferable_type' => NewsSource::class,
    ]);

    $this->assertDatabaseHas('user_preferences', [
        'user_id' => $this->user->id,
        'preferable_id' => $categories[0],
        'preferable_type' => Category::class,
    ]);

    $this->assertDatabaseHas('user_preferences', [
        'user_id' => $this->user->id,
        'preferable_id' => $authors[0],
        'preferable_type' => Author::class,
    ]);
});

test('validates the payload correctly', function () {
    Sanctum::actingAs($this->user);

    $response = $this->putJson(route('user-preference:update'), [
        'news_sources' => [9999],
        'categories' => ['invalid_id'],
        'authors' => null,
    ]);

    $response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
        ->assertJson([
            "errors" => [
                [
                    "message" => "The authors field must be an array.",
                    "field" => "authors"
                ],
                [
                    "message" => "One or more selected news sources are invalid.",
                    "field" => "news_sources.0"
                ],
                [
                    "message" => "The categories.0 field must be an integer.",
                    "field" => "categories.0"
                ],
            ]
        ]);
});

test('ensures only authenticated users can update preferences', function () {
    $response = $this->putJson(route('user-preference:update'), []);

    $response
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'errors' => [
                'message' => 'Unauthenticated.'
            ]
        ]);
});
