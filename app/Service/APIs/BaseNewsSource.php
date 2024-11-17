<?php

namespace App\Service\APIs;

use App\Service\Normalizers\NewsNormalizerInterface;

/**
 * Abstract class representing the base implementation for a news source.
 */
abstract class BaseNewsSource
{
    /**
     *  The name of news source
     *
     * @var string
     */
    public string $name;

    /**
     * The API endpoint for the news source.
     *
     * @var string
     */
    protected string $endpoint;

    /**
     * The normalizer instance for transforming raw API responses into a standard format.
     *
     * @var NewsNormalizerInterface
     */
    protected NewsNormalizerInterface $normalizer;

    /**
     * Fetch the news articles from the news source.
     *
     * @return array The normalized list of articles fetched from news source API.
     */
    abstract public function fetchNews(): array;
}
