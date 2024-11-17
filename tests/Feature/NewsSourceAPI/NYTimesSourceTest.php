<?php

use App\Service\APIs\GuardianSource;
use App\Service\APIs\NYTimesSource;
use \Illuminate\Support\Facades\Http;

// we have to test this in feature test instead of unit test,
// because in unit test we don't have access to facades
test('fetches and normalizes articles from NYTimes API', function () {
    // Mock API response
    Http::fake(['*' => Http::response([
        'response' => [
            'docs' => [
                [
                    'headline' => ['main' => 'Breaking News: NYTimes Example Title'],
                    'lead_paragraph' => 'This is the NYTimes article content.',
                    'web_url' => 'https://www.nytimes.com/example-article',
                    'pub_date' => '2024-11-16T22:18:51Z',
                    'byline' => ['original' => 'Jane Doe'],
                    'news_desk' => 'World',
                    'multimedia' => [
                        ['url' => 'images/2024/11/example.jpg'],
                    ],
                ],
            ],
        ],
    ])
    ]);

    $nyTimesSource = new NYTimesSource();

    $articles = $nyTimesSource->fetchNews();

    expect($articles)
        ->toHaveCount(1)
        ->and($articles[0])
        ->toBe([
            'title' => 'Breaking News: NYTimes Example Title',
            'content' => 'This is the NYTimes article content.',
            'url' => 'https://www.nytimes.com/example-article',
            'image_url' => 'https://www.nytimes.com/images/2024/11/example.jpg',
            'published_at' => '2024-11-16T22:18:51Z',
            'author' => 'jane doe',
            'source' => [
                'name' => 'new york times',
                'url' => 'https://www.nytimes.com/'
            ],
            'category' => 'world',
        ]);
});

test('handles empty results gracefully', function () {
    // Mock API response with no results
    Http::fake(['*' => Http::response([
        'response' => [
            'docs' => [],
        ]]),
    ]);

    $nyTimesSource = new NYTimesSource();

    $articles = $nyTimesSource->fetchNews();

    expect($articles)
        ->toBeEmpty();
});
