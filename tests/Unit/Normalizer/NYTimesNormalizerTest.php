<?php

use App\Service\Normalizers\NYTimesNormalizer;

test('transforms NYTimes article into normalized format', function () {
    $normalizer = new NYTimesNormalizer();

    $inputArticle = [
        'headline' => [
            'main' => 'NYTimes Headline'
        ],
        'lead_paragraph' => 'This is the lead paragraph of the NYTimes article.',
        'web_url' => 'https://www.nytimes.com/example-article',
        'multimedia' => [
            [
                'url' => 'images/2024/11/16/example.jpg'
            ]
        ],
        'pub_date' => '2024-11-16T22:18:51Z',
        'byline' => ['original' => 'John Doe'],
        'news_desk' => 'Politics',
    ];

    $expectedOutput = [
        'title' => 'NYTimes Headline',
        'content' => 'This is the lead paragraph of the NYTimes article.',
        'url' => 'https://www.nytimes.com/example-article',
        'image_url' => 'https://www.nytimes.com/images/2024/11/16/example.jpg',
        'published_at' => '2024-11-16T22:18:51Z',

        'author' => 'john doe',
        'source' => [
            'name' => 'new york times',
            'url' => 'https://www.nytimes.com/'
        ],
        'category' => 'politics',
    ];

    expect($normalizer->normalize($inputArticle))
        ->toBe($expectedOutput);
});

test('handles missing fields gracefully', function () {
    $normalizer = new NYTimesNormalizer();

    $inputArticle = [];

    $expectedOutput = [
        'title' => '',
        'content' => '',
        'url' => '',
        'image_url' => '',
        'published_at' => null,

        'author' => null,
        'source' => [
            'name' => 'new york times',
            'url' => 'https://www.nytimes.com/'
        ],
        'category' => null,
    ];

    expect($normalizer->normalize($inputArticle))
        ->toBe($expectedOutput);
});
