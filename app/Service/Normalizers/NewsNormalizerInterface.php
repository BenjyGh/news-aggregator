<?php

namespace App\Service\Normalizers;

interface NewsNormalizerInterface
{
    /**
     * Normalize the given API response into a standard format.
     *
     * @param array $article The raw article data from an API.
     * @return array The normalized article data.
     */
    public function normalize(array $article): array;
}
