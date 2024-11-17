<?php

namespace App\Service\Normalizers;

class NewsAPINormalizer implements NewsNormalizerInterface
{
    /**
     * Normalize a raw NewsAPI article.
     *
     * @param array $article The raw article data from NewsAPI.
     * @return array The normalized article data
     */
    public function normalize(array $article): array
    {
        return [
            'title' => $article['title'] ?? '',
            'content' => $article['content'] ?? '',
            'url' => $article['url'] ?? '',
            'image_url' => $article['urlToImage'] ?? '',
            'published_at' => $article['publishedAt'] ?? null,

            'author' => strtolower($article['author'] ?? '') ?: null,
            'source' => [
                'name' => strtolower($article['source']['name'] ?? '') ?: null,
                'url' => null
            ],
            'category' => null // newsAPI doesn't return category,
            // instead it has source which is connected to a category
        ];
    }
}
