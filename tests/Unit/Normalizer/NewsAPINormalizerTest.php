<?php

use App\Service\Normalizers\NewsAPINormalizer;

test('transforms NewsAPI article into standard format', function () {
    $normalizer = new NewsAPINormalizer();

    $inputArticle = [
        'title' => 'Test Title',
        'content' => 'Test Content',
        'url' => 'https://example.com/article',
        'urlToImage' => 'https://example.com/image.jpg',
        'publishedAt' => '2024-11-16T22:18:51Z',
        'author' => 'John Doe',
        'source' => [
            'name' => 'Example Source'
        ]
    ];

    $expectedOutput = [
        'title' => 'Test Title',
        'content' => 'Test Content',
        'url' => 'https://example.com/article',
        'image_url' => 'https://example.com/image.jpg',
        'published_at' => '2024-11-16T22:18:51Z',
        'author' => 'john doe',
        'source' => [
            'name' => 'example source',
            'url' => null,
        ],
        'category' => null,
    ];

    expect($normalizer->normalize($inputArticle))
        ->toBe($expectedOutput);
});

test('handles missing fields gracefully', function () {
    $normalizer = new NewsAPINormalizer();

    $inputArticle = [];

    $expectedOutput = [
        'title' => '',
        'content' => '',
        'url' => '',
        'image_url' => '',
        'published_at' => null,
        'author' => null,
        'source' => [
            'name' => null,
            'url' => null,
        ],
        'category' => null,
    ];

    expect($normalizer->normalize($inputArticle))
        ->toBe($expectedOutput);
});
