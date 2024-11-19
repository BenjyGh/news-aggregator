<?php

namespace App\Service\APIs;


use App\Service\Normalizers\NewsAPINormalizer;
use App\Service\Normalizers\NYTimesNormalizer;
use Illuminate\Support\Facades\Http;

/**
 * News source implementation for NYTimes.
 *
 * This class fetches and normalizes news data from the NYTimes API.
 */
class NYTimesSource extends BaseNewsSource
{
    /**
     *  The name of news source
     *
     * @var string
     */
    public string $name = 'NYTimes';

    /**
     * The API endpoint for articles from NYTimes.
     *
     * @var string
     */
    protected string $endpoint = 'https://api.nytimes.com/svc/search/v2/articlesearch.json';

    /**
     * Initializes the normalizer to be used for transforming raw data into a standard format.
     */
    public function __construct()
    {
        $this->normalizer = new NYTimesNormalizer();
    }

    /**
     * Fetch news articles from NYTimes.
     *
     * This method fetch articles from NYTimes and maps them into a normalized format.
     *
     * @return array The normalized list of articles.
     */
    public function fetchNews(): array
    {
        $response = Http::get($this->endpoint, [
            'api-key' => config('news_source.ny_times_token'),
            'fl' => 'lead_paragraph,headline,web_url,pub_date,byline,news_desk,multimedia'
        ]);

        $articles = collect($response->json()['response']['docs'] ?? []);

        $articles = $articles->filter(fn($article) => isset($article['byline']['original']));

        return $articles
            ->map(fn($item) => $this->normalizer->normalize($item))
            ->toArray();
    }
}
