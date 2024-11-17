<?php

use App\Service\Normalizers\GuardianNormalizer;
use App\Service\Normalizers\NewsAPINormalizer;

test('transforms Guardian article into normalized format', function () {
    $normalizer = new GuardianNormalizer();

    $inputArticle = [
        'webTitle' => 'Test Guardian Title',
        'fields' => [
            'body' => 'Test Guardian Content',
            'byline' => 'Jane Doe',
        ],
        'webUrl' => 'https://www.theguardian.com/article',
        'elements' => [
            [
                'assets' =>
                    [
                        ['file' => 'https://example.com/image.jpg']
                    ]
            ]
        ],
        'webPublicationDate' => '2024-11-16T22:18:51Z',
        'sectionName' => 'World News',
    ];

    $expectedOutput = [
        'title' => 'Test Guardian Title',
        'content' => 'Test Guardian Content',
        'url' => 'https://www.theguardian.com/article',
        'image_url' => 'https://example.com/image.jpg',
        'published_at' => '2024-11-16T22:18:51Z',

        'author' => 'jane doe',
        'source' => [
            'name' => 'the guardian',
            'url' => 'https://www.theguardian.com/'
        ],
        'category' => 'world news',
    ];

    expect($normalizer->normalize($inputArticle))
        ->toBe($expectedOutput);
});

it('handles missing fields gracefully', function () {
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
