<?php

use App\Service\APIs\GuardianSource;
use App\Service\APIs\NewsAPISource;
use \Illuminate\Support\Facades\Http;

// we have to test this in feature test instead of unit test,
// because in unit test we don't have access to facades
test('fetches and normalizes articles from NewsAPI', function () {
    // Mock API response for articles
    Http::fake(['*' => Http::response([
        'articles' => [
            [
                'author' => 'John Doe',
                'content' => 'This is a NewsAPI article content.',
                'title' => 'Breaking News: NewsAPI Example Title',
                'url' => 'https://newsapi.org/example-article',
                'urlToImage' => 'https://newsapi.org/images/example.jpg',
                'publishedAt' => '2024-11-16T22:18:51Z',
                'source' => ['name' => 'Example Source'],
            ],
        ],
    ]),
    ]);

    $newsAPISource = new NewsAPISource();

    $articles = $newsAPISource->fetchNews();

    expect($articles)
        ->toHaveCount(1)
        ->and($articles[0])
        ->toBe([
            'title' => 'Breaking News: NewsAPI Example Title',
            'content' => 'This is a NewsAPI article content.',
            'url' => 'https://newsapi.org/example-article',
            'image_url' => 'https://newsapi.org/images/example.jpg',
            'published_at' => '2024-11-16T22:18:51Z',
            'author' => 'john doe',
            'source' => [
                'name' => 'example source',
                'url' => null,
            ],
            'category' => null,
        ]);
});

test('fetches and normalizes sources from NewsAPI', function () {
    // Mock API response for sources
    Http::fake(['*' => Http::response([
        'sources' => [
            [
                'name' => 'Example Source',
                'url' => 'https://newsapi.org/example-source',
                'category' => 'General',
            ],
        ],
    ]),
    ]);

    $newsAPISource = new NewsAPISource();

    $sources = $newsAPISource->fetchSources();

    expect($sources)
        ->toHaveCount(1)
        ->and($sources[0])
        ->toBe([
            'name' => 'example source',
            'url' => 'https://newsapi.org/example-source',
            'category_name' => 'General',
        ]);
});

test('handles empty articles gracefully', function () {
    // Mock API response with no articles
    Http::fake(['*' => Http::response([
        'articles' => []]),
    ]);

    $newsAPISource = new NewsAPISource();

    $articles = $newsAPISource->fetchNews();

    expect($articles)
        ->toBeEmpty();
});

test('handles empty sources gracefully', function () {
    // Mock API response with no sources
    Http::fake(['*' => Http::response([
        'sources' => []]),
    ]);

    $newsAPISource = new NewsAPISource();

    $sources = $newsAPISource->fetchSources();

    expect($sources)
        ->toBeEmpty();
});
