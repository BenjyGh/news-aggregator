<?php

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\NewsSource;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

beforeEach(function () {
    $this->user = User::factory()->create();
});

test('returns articles based on preferred news sources', function () {
    Sanctum::actingAs($this->user);

    $newsSource = NewsSource::factory()->create();

    $article1 = Article::factory()->create(['news_source_id' => $newsSource->id]);
    $article2 = Article::factory()->create();

    $this->user->preferredNewsSources()->attach($newsSource);

    $response = $this->getJson(route('user:newsfeed'));

    $response
        ->assertOk()
        ->assertJsonFragment(['id' => $article1->id])
        ->assertJsonMissing(['id' => $article2->id]);
});

test('returns articles based on preferred categories', function () {
    Sanctum::actingAs($this->user);

    $category = Category::factory()->create();

    $article1 = Article::factory()->create(['category_id' => $category->id]);
    $article2 = Article::factory()->create();

    $this->user->preferredCategories()->attach($category);

    $response = $this->getJson(route('user:newsfeed'));

    $response
        ->assertOk()
        ->assertJsonFragment(['id' => $article1->id])
        ->assertJsonMissing(['id' => $article2->id]);
});

test('returns articles based on preferred authors', function () {
    Sanctum::actingAs($this->user);

    $author = Author::factory()->create();

    $article1 = Article::factory()->create(['author_id' => $author->id]);
    $article2 = Article::factory()->create();

    $this->user->preferredAuthors()->attach($author);

    $response = $this->getJson(route('user:newsfeed'));

    $response
        ->assertOk()
        ->assertJsonFragment(['id' => $article1->id])
        ->assertJsonMissing(['id' => $article2->id]);
});

test('returns empty list if no articles match preferences', function () {
    Sanctum::actingAs($this->user);

    $newsSource = NewsSource::factory()->create();
    $author = Author::factory()->create();
    $category = Category::factory()->create();

    Article::factory()->create();
     Article::factory()->create();

    $this->user->preferredNewsSources()->attach($newsSource->id);
    $this->user->preferredCategories()->attach($author->id);
    $this->user->preferredAuthors()->attach($category->id);

    $response = $this->getJson(route('user:newsfeed'));

    // Assert
    $response
        ->assertOk()
        ->assertJsonFragment([
        'data' => []
    ]);
});

test('response is paginated', function () {
    Sanctum::actingAs($this->user);
    Article::factory()->count(10)->create();

    $response = $this->getJson(route('user:newsfeed'));

    assertResponseIsPaginated($response);
});
