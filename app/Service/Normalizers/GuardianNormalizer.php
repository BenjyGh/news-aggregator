<?php

namespace App\Service\Normalizers;

class GuardianNormalizer implements NewsNormalizerInterface
{
    /**
     * Normalize a raw Guardian article.
     *
     * @param array $article The raw article data from Guardian.
     * @return array The normalized article data
     */
    public function normalize(array $article): array
    {
        return [
            'title' => $article['webTitle'] ?? '',
            'content' => $article['fields']['body'] ?? '',
            'url' => $article['webUrl'] ?? '',
            'image_url' => $article['elements'][0]['assets'][0]['file'] ?? '',
            'published_at' => $article['webPublicationDate'] ?? null,

            'author' => strtolower($article['fields']['byline'] ?? '') ?: null,
            'source' => [
                'name' => 'the guardian',
                'url' => 'https://www.theguardian.com/'
            ],
            'category' => strtolower($article['sectionName'])
        ];
    }
}
