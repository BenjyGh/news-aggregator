<?php

namespace App\Service\Normalizers;

class NYTimesNormalizer implements NewsNormalizerInterface
{
    protected string $url = "https://www.nytimes.com/";

    /**
     * Normalize a raw NYTimes article.
     *
     * @param array $article The raw article data from NYTimes.
     * @return array The normalized article data
     */
    public function normalize(array $article): array
    {
        return [
            'title' => $article['headline']['main'] ?? '',
            'content' => $article['lead_paragraph'] ?? '',
            'url' => $article['web_url'] ?? '',
            'image_url' => isset($article['multimedia'][0]['url'])
                ? $this->url . $article['multimedia'][0]['url']
                : '',
            'published_at' => $article['pub_date'] ?? null,

            'author' => strtolower($article['byline']['original'] ?? '') ?: null,
            'source' => [
                'name' => 'new york times',
                'url' => $this->url
            ],
            'category' => strtolower($article['news_desk'] ?? '') ?: null
        ];
    }
}
