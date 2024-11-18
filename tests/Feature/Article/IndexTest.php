<?php

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;


test('user can get articles list', function () {
    Article::factory(10)->create();

    $response = $this->getJson(route('article:index'));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'content',
                    'url',
                    'image_url',
                    'published_at',
                ],
            ]
        ]);
});

test('user can include relationships to the response', function () {
    Article::factory(10)->create();

    $response = $this->getJson(route('article:index') . "?include=author,source,category");

    $response
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'source' => [
                        'id',
                        'name',
                        'url',
                    ],
                    'author' => [
                        'id',
                        'name',
                    ],
                    'category' => [
                        'id',
                        'name',
                    ],
                ],
            ]
        ]);
});

test('user can filter articles based on published_at date', function () {
    // create 5 article whiting filter range
    Article::factory(5)->create([
        'published_at' => fake()->dateTimeBetween('2023-01-01', '2024-01-01')
    ]);

    // create 5 article outside filter range
    Article::factory(5)->create([
        'published_at' => fake()->dateTimeBetween('2022-01-01', '2023-01-01')
    ]);

    $response = $this->getJson(route('article:index')
        . "?filter[publishedAtStart]=2023-01-01&filter[publishedAtEnd]=2024-01-01");

    $response->assertOk();

    $articles = collect($response->json('data'));

    expect($articles)
        ->toHaveCount(5)
        ->and($articles->pluck('published_at.datetime'))
        ->each
        ->toBeBetween(Carbon::parse('2023-01-01'), Carbon::parse('2024-01-01'));
});

test('user can filter articles based on category', function () {
    $categories = Category::factory(2)->create();
    $filteredCategoryIds = $categories->pluck('id');

    // Create articles with the created categories
    Article::factory(2)->for($categories[0])->create();
    Article::factory(2)->for($categories[1])->create();

    // Create articles that do not belong to the specified categories
    Article::factory(2)->create();

    $response = $this->getJson(route('article:index')
        . "?filter[category]=" . $filteredCategoryIds->implode(',')
        . '&include=category');

    $response->assertOk();

    $articles = collect($response->json('data'));

    expect($articles)
        ->toHaveCount(4)
        ->and($articles->pluck('category.id'))
        ->each
        ->toBeIn($filteredCategoryIds);
});

test('user can filter articles based on keyword', function () {
    // Create articles containing keywords in the title or content
    Article::factory()->create([
        'title' => 'foo is a great keyword for testing',
        'content' => 'This is some content containing foo',
    ]);

    Article::factory()->create([
        'title' => 'Another article with bar keyword',
        'content' => 'This article mentions bar several times',
    ]);

    // Create articles that do not match the keywords
    Article::factory()->create([
        'title' => 'Irrelevant title',
        'content' => 'This content has no matching keywords',
    ]);

    $response = $this->getJson(route('article:index') . "?filter[keyword]=foo bar");

    $response->assertOk();

    $articles = collect($response->json('data'));

    expect($articles)->toHaveCount(2);

    $articles
        ->each(function ($article) {
            expect(
                stripos($article['title'], 'foo') !== false ||
                stripos($article['title'], 'bar') !== false ||
                stripos($article['content'], 'foo') !== false ||
                stripos($article['content'], 'bar') !== false
            )->toBeTrue();
        });
});

test('user can sort articles based on published_at field in ascending order', function () {
    $article1 = Article::factory()->create(['published_at' => '2023-01-01']);
    $article2 = Article::factory()->create(['published_at' => '2023-06-01']);
    $article3 = Article::factory()->create(['published_at' => '2023-12-01']);

    $response = $this->getJson(route('article:index') . "?sort=publishedAt");

    $response->assertOk();

    $articles = collect($response->json('data'));

    expect($articles)
        ->toHaveCount(3)
        ->and($articles->pluck('id')->toArray())
        ->toEqual([$article1->id, $article2->id, $article3->id]);
});

test('user can sort articles based on published_at field in descending order', function () {
    $article1 = Article::factory()->create(['published_at' => '2023-01-01']);
    $article2 = Article::factory()->create(['published_at' => '2023-06-01']);
    $article3 = Article::factory()->create(['published_at' => '2023-12-01']);

    $response = $this->getJson(route('article:index') . "?sort=-publishedAt");

    $response->assertOk();

    $articles = collect($response->json('data'));

    expect($articles)
        ->toHaveCount(3)
        ->and($articles->pluck('id')->toArray())
        ->toEqual([$article3->id, $article2->id, $article1->id]);
});

test('response is paginated', function () {
    Article::factory(10)->create();

    $response = $this->getJson(route('article:index'));

    assertResponseIsPaginated($response);
});
