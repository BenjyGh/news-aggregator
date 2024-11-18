<?php

use App\Models\Author;
use App\Models\Category;
use App\Models\NewsSource;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
use \Symfony\Component\HttpFoundation\Response;


test('returns the user preferences', function () {
    $user = User::factory()->create();

    $newsSources = NewsSource::factory()->count(3)->create();
    $categories = Category::factory()->count(3)->create();
    $authors = Author::factory()->count(3)->create();

    $user->preferences()->createMany([
        ['preferable_id' => $newsSources[0]->id, 'preferable_type' => NewsSource::class],
        ['preferable_id' => $categories[1]->id, 'preferable_type' => Category::class],
        ['preferable_id' => $authors[2]->id, 'preferable_type' => Author::class],
    ]);

    Sanctum::actingAs($user);

    $response = $this->getJson(route('user-preference:show'));

    $response
        ->assertOk()
        ->assertJson([
            'news_sources' => [
                ['id' => $newsSources[0]->id, 'name' => $newsSources[0]->name],
            ],
            'categories' => [
                ['id' => $categories[1]->id, 'name' => $categories[1]->name],
            ],
            'authors' => [
                ['id' => $authors[2]->id, 'name' => $authors[2]->name],
            ],
        ]);
});

it('ensures only authenticated users can see preferences', function () {
    $response = $this->getJson(route('user-preference:show'));

    $response
        ->assertStatus(Response::HTTP_UNAUTHORIZED)
        ->assertJson([
            'errors' => [
                'message' => 'Unauthenticated.'
            ]
        ]);
});
