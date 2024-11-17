<?php

use App\Service\APIs\GuardianSource;
use \Illuminate\Support\Facades\Http;

// we have to test this in feature test instead of unit test,
// because in unit test we don't have access to facades
test('fetches and normalizes articles from Guardian API', function () {
    // Mock API response
    Http::fake(['*' => Http::response([
        'response' => [
            'results' => [
                [
                    'webTitle' => 'Breaking News: Example Title',
                    'fields' => [
                        'body' => '<p>This is the article content.</p>',
                        'byline' => 'Jane Doe',
                    ],
                    'webUrl' => 'https://www.theguardian.com/example-article',
                    'elements' => [
                        [
                            'assets' => [
                                ['file' => 'https://example.com/image.jpg'],
                            ]
                        ]
                    ],
                    'webPublicationDate' => '2024-11-16T22:18:51Z',
                    'sectionName' => 'World News',
                ],
            ],
        ],
    ]),
    ]);

    $guardianSource = new GuardianSource();

    $articles = $guardianSource->fetchNews();

    expect($articles)
        ->toHaveCount(1)
        ->and($articles[0])
        ->toBe([
            'title' => 'Breaking News: Example Title',
            'content' => '<p>This is the article content.</p>',
            'url' => 'https://www.theguardian.com/example-article',
            'image_url' => 'https://example.com/image.jpg',
            'published_at' => '2024-11-16T22:18:51Z',
            'author' => 'jane doe',
            'source' => [
                'name' => 'the guardian',
                'url' => 'https://www.theguardian.com/'
            ],
            'category' => 'world news',
        ]);
});

test('filters out incomplete articles', function () {
    // Mock API response with incomplete data
    Http::fake(['*' => Http::response([
        'response' => [
            'results' => [
                [
                    'webTitle' => 'Valid Article',
                    'fields' => [
                        'body' => '<p>This is valid content.</p>',
                        'byline' => 'John Doe',
                    ],
                    'webUrl' => 'https://www.theguardian.com/valid-article',
                    'elements' => [
                        [
                            'assets' => [
                                ['file' => 'https://example.com/valid-image.jpg'],
                            ]
                        ]
                    ],
                    'webPublicationDate' => '2024-11-16T22:18:51Z',
                    'sectionName' => 'Politics',
                ],
                [
                    'webTitle' => 'Invalid Article',
                    'fields' => [
                        'body' => '<p>Content without byline.</p>',
                    ],
                    'webUrl' => 'https://www.theguardian.com/invalid-article',
                ],
            ],
        ],
    ]),
    ]);

    $guardianSource = new GuardianSource();

    $articles = $guardianSource->fetchNews();

    expect($articles)
        ->toHaveCount(1)
        ->and($articles[0]['title'])
        ->toBe('Valid Article');
});

test('handles empty results gracefully', function () {
    // Mock API response with no results
    Http::fake(['*' => Http::response([
        'response' => [
            'results' => [],
        ]]),
    ]);

    $nyTimesSource = new GuardianSource();

    $articles = $nyTimesSource->fetchNews();

    expect($articles)
        ->toBeEmpty();
});
