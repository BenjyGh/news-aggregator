<?php

namespace App\Service\APIs;


use App\Service\Normalizers\GuardianNormalizer;
use Illuminate\Support\Facades\Http;

/**
 * News source implementation for Guardian.
 *
 * This class fetches and normalizes news data from the Guardian API.
 */
class GuardianSource extends BaseNewsSource
{
    /**
     *  The name of news source
     *
     * @var string
     */
    public string $name = 'Guardian';

    /**
     * The API endpoint for articles from Guardian.
     *
     * @var string
     */
    protected string $endpoint = 'https://content.guardianapis.com/search';

    /**
     * Initializes the normalizer to be used for transforming raw data into a standard format.
     */
    public function __construct()
    {
        $this->normalizer = new GuardianNormalizer();
    }

    /**
     * Fetch news articles from Guardian.
     *
     * This method fetch articles from Guardian and filters out incomplete articles
     * and maps them into a normalized format.
     *
     * @return array The normalized list of articles.
     */
    public function fetchNews(): array
    {
        $response = Http::get($this->endpoint, [
            'api-key' => config('news_source.guardian_token'),
            'show-fields' => 'body,byline',
            'show-elements' => 'image',
        ]);

        $articles = collect($response->json()['response']['results'] ?? []);

        $articles = $articles->filter(
            fn($article) => isset($article['fields']['byline']) && $article['fields']['byline']
        );

        return $articles
            ->map(fn($item) => $this->normalizer->normalize($item))
            ->toArray();
    }
}
