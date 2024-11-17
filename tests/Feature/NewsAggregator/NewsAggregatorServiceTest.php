<?php

use App\Models\Article;
use App\Models\Category;
use App\Models\NewsSource;
use App\Models\Author;
use App\Service\APIs\GuardianSource;
use App\Service\APIs\NewsAPISource;
use App\Service\APIs\NYTimesSource;
use App\Service\NewsAggregatorService;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\NullOutput;

test('stores news articles from Guardian', function () {
    // Mock the Guardian API response
    Http::fake([
        'https://content.guardianapis.com/search*' => Http::response([
            'response' => [
                'results' => [
                    [
                        'webTitle' => 'Guardian News Title',
                        'fields' => ['body' => 'Guardian article content.', 'byline' => 'Guardian Author'],
                        'webUrl' => 'https://guardian.com/example',
                        'webPublicationDate' => '2024-11-16T20:00:00Z',
                        'elements' => [
                            [
                                'assets' => [
                                    ['file' => 'https://guardian.com/example-image.jpg']
                                ]
                            ]
                        ],
                        'sectionName' => 'World',
                    ]
                ]
            ]
        ]),
    ]);

    // Prepare sources
    $sources = [
        new GuardianSource(),
    ];

    $progressBar = new ProgressBar(new NullOutput());
    $newsAggregatorService = new NewsAggregatorService($sources);
    $newsAggregatorService->fetchAndStoreNews($progressBar);

    // Assertions: Ensure the data is stored correctly in the database
    expect(Article::count())->toBe(1)
        ->and(NewsSource::count())->toBe(1)
        ->and(Category::count())->toBe(1)
        ->and(Author::count())->toBe(1);

    $guardianArticle = Article::where('title', 'Guardian News Title')->first();
    expect($guardianArticle->content)
        ->toBe('Guardian article content.')
        ->and($guardianArticle->author->name)
        ->toBe('guardian author')
        ->and($guardianArticle->category->name)
        ->toBe('world');
});

test('store articles from NYTimes', function () {
    // Mock the NYTimes API response
    Http::fake([
        'https://api.nytimes.com/svc/search/v2/articlesearch.json*' => Http::response([
            'response' => [
                'docs' => [
                    [
                        'headline' => ['main' => 'NYTimes News Title'],
                        'lead_paragraph' => 'NYTimes article content.',
                        'web_url' => 'https://nytimes.com/example',
                        'pub_date' => '2024-11-16T21:00:00Z',
                        'byline' => ['original' => 'NYTimes Author'],
                        'news_desk' => 'Politics',
                        'multimedia' => [
                            ['url' => '/images/example.jpg']
                        ],
                    ]
                ]
            ]
        ]),
    ]);

    // Prepare sources
    $sources = [
        new NYTimesSource(),
    ];

    $progressBar = new ProgressBar(new NullOutput());
    $newsAggregatorService = new NewsAggregatorService($sources);
    $newsAggregatorService->fetchAndStoreNews($progressBar);

    expect(Article::count())->toBe(1)
        ->and(NewsSource::count())->toBe(1)
        ->and(Category::count())->toBe(1)
        ->and(Author::count())->toBe(1);

    $nyTimesArticle = Article::where('title', 'NYTimes News Title')->first();
    expect($nyTimesArticle->content)
        ->toBe('NYTimes article content.')
        ->and($nyTimesArticle->author->name)
        ->toBe('nytimes author')
        ->and($nyTimesArticle->category->name)
        ->toBe('politics');

});

test('store articles and news source from newsAPI', function () {
    // Mock the NewsAPI.org API response for sources
    Http::fake([
        'https://newsapi.org/v2/top-headlines/sources*' => Http::response([
            'sources' => [
                [
                    'name' => 'NewsAPI Source',
                    'url' => 'https://newsapi.org/example-source',
                    'category' => 'technology',
                ]
            ]
        ]),
    ]);

    // Mock the NewsAPI.org API response for articles
    Http::fake([
        'https://newsapi.org/v2/top-headlines*' => Http::response([
            'articles' => [
                [
                    'author' => 'NewsAPI Author',
                    'content' => 'NewsAPI article content.',
                    'title' => 'NewsAPI News Title',
                    'url' => 'https://newsapi.org/example',
                    'urlToImage' => 'https://newsapi.org/images/example.jpg',
                    'publishedAt' => '2024-11-16T22:00:00Z',
                    'source' => ['name' => 'NewsAPI Source'],
                ]
            ]
        ]),
    ]);

    // Prepare sources
    $sources = [
        new NewsAPISource(),
    ];

    $progressBar = new ProgressBar(new NullOutput());
    $newsAggregatorService = new NewsAggregatorService($sources);
    $newsAggregatorService->fetchAndStoreNews($progressBar);

    expect(Article::count())->toBe(1)
        ->and(NewsSource::count())->toBe(1)
        ->and(Category::count())->toBe(1)
        ->and(Author::count())->toBe(1);

    $newsApiArticle = Article::where('title', 'NewsAPI News Title')->first();
    expect($newsApiArticle->content)
        ->toBe('NewsAPI article content.')
        ->and($newsApiArticle->author->name)
        ->toBe('newsapi author')
        ->and($newsApiArticle->category->name)
        ->toBe('technology');
});
