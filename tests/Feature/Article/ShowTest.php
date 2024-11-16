<?php

use App\Models\Article;
use \Symfony\Component\HttpFoundation\Response;

test('get specific article by id and response is in expected format', function () {
    $article = Article::factory(1)->create()->first();

    $response = $this->getJson(route('article:show', $article->id));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'id',
            'title',
            'content',
            'url',
            'image_url',
            'published_at',
        ]);
});

test('source is included with the response', function () {
    $article = Article::factory(1)->create()->first();

    $response = $this->getJson(route('article:show', $article->id));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'source' => [
                'id',
                'name',
                'url',
            ]
        ]);
});

test('category is included with the response', function () {
    $article = Article::factory(1)->create()->first();

    $response = $this->getJson(route('article:show', $article->id));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'category' => [
                'id',
                'name',
            ]
        ]);
});

test('author is included with the response', function () {
    $article = Article::factory(1)->create()->first();

    $response = $this->getJson(route('article:show', $article->id));

    $response
        ->assertOk()
        ->assertJsonStructure([
            'author' => [
                'id',
                'name',
            ]
        ]);
});

test('wrong id returns 404 not found', function () {
    $response = $this->getJson(route('article:show', 100));

    $response
        ->assertStatus(Response::HTTP_NOT_FOUND);
});
