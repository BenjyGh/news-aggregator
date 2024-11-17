<?php

namespace App\Service\APIs;


use App\Service\Normalizers\NewsAPINormalizer;
use Illuminate\Support\Facades\Http;

/**
 * News source implementation for NewsAPI.
 *
 * This class fetches and normalizes news data and sources data from the NewsAPI.
 */
class NewsAPISource extends BaseNewsSource
{
    /**
     *  The name of news source
     *
     * @var string
     */
    public string $name = 'NewAPI';

    /**
     * The API endpoint for fetching articles from NewsAPI.
     *
     * @var string
     */
    protected string $endpoint = 'https://newsapi.org/v2/top-headlines';

    /**
     * Initializes the normalizer to be used for transforming raw data into a standard format.
     */
    public function __construct()
    {
        $this->normalizer = new NewsAPINormalizer();
    }

    /**
     * Fetch news articles from NewsAPI.
     *
     * This method fetch articles from NewsAPI and filters out incomplete articles
     * and maps them into a normalized format.
     *
     * @return array The normalized list of articles.
     */
    public function fetchNews(): array
    {
        $response = Http::get($this->endpoint, [
            'apiKey' => config('news_source.news_api_token'),
            'country' => 'us',
        ]);

        $articles = collect($response->json()['articles'] ?? []);

        // Filter out incomplete articles with missing author or content
        $articles = $articles->filter(
            fn($article) => $article['author'] !== null && $article['content'] !== null
        );

        return $articles
            ->map(fn($item) => $this->normalizer->normalize($item))
            ->toArray();
    }

    /**
     * Fetch news sources from NewsAPI.
     *
     * This method retrieves a list of news sources from the NewsAPI and maps them into a normalized format.
     *
     * @return array The normalized list of news sources.
     */
    public function fetchSources(): array
    {
        $response = Http::get($this->endpoint . '/sources', [
            'apiKey' => config('news_source.news_api_token'),
            'country' => 'us',
        ]);

        $sources = $response->json()['sources'] ?? [];

        return array_map(fn($source) => [
            'name' => strtolower($source['name']),
            'url' => $source['url'],
            'category_name' => $source['category'],
        ], $sources);
    }
}
