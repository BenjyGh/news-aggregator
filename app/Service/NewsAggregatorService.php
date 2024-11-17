<?php

namespace App\Service;

use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Models\NewsSource;
use App\Service\APIs\BaseNewsSource;
use App\Service\APIs\NewsAPISource;
use Symfony\Component\Console\Helper\ProgressBar;

class NewsAggregatorService
{
    /**
     * The array of news source objects.
     *
     * @var array<BaseNewsSource>
     */
    protected array $sources;

    /**
     * Initialize the service with the provided news sources.
     *
     * @param array<BaseNewsSource> $sources Instances of news source classes.
     */
    public function __construct(array $sources)
    {
        $this->sources = $sources;
    }

    /**
     * Fetch articles and sources from all provided news sources
     * and store it in the database.
     *
     * @param ProgressBar $progressBar
     * @return void
     */
    public function fetchAndStoreNews(ProgressBar $progressBar): void
    {
        $progressBar->setMaxSteps(count($this->sources));

        foreach ($this->sources as $source) {
            $progressBar->setMessage("Fetching news from: " . $source->name . " ...");

            if ($source instanceof NewsAPISource) {
                $this->storeSources($source->fetchSources());
            }

            $articles = $source->fetchNews();

            $this->storeArticles($articles);

            $progressBar->advance();
        }
    }

    /**
     * Store newAPI.org news sources in the database.
     *
     * If a source already exists, it won't be created again.
     *
     * @param array $sources Array of source data.
     *
     * @return void
     */
    private function storeSources(array $sources): void
    {
        foreach ($sources as $source) {
            NewsSource::firstOrCreate(
                ['name' => $source['name']],
                ['url' => $source['url'], 'category_name' => $source['category_name']]
            );
        }
    }

    /**
     * Store articles in the database, ensuring proper relationships are established.
     *
     * This method handles creating authors, categories and sources
     * if they don't exist and associates them with the articles.
     *
     * @param array $articles Array of normalized article data.
     *
     * @return void
     */
    private function storeArticles(array $articles): void
    {
        foreach ($articles as $article) {
            // Handle news source creation or retrieval.
            $source = NewsSource::firstOrCreate(
                ['name' => $article['source']['name']],
                ['url' => $article['source']['url']]
            );

            // Handle author creation or retrieval.
            $author = Author::firstOrCreate(['name' => $article['author']]);

            // Handle category creation or retrieval.

            // in case of newsAPI.org, it doesn't return category, instead it returns "source"
            // and in different endpoint for "sources" it returns list of sources with category.
            // so we have to query the news_source table to get the category of newsAPI.org article
            $category = null;

            if ($article['category']) {
                $category = Category::firstOrCreate(['name' => $article['category']]);
            } elseif ($article['category'] === null && $source->category_name) {
                $category = Category::firstOrCreate(['name' => $source->category_name]);
            }

            // Create or update the article.
            Article::updateOrCreate(
                ['url' => $article['url']],
                [
                    'title' => $article['title'],
                    'content' => $article['content'],
                    'image_url' => $article['image_url'],
                    'published_at' => $article['published_at'],
                    'news_source_id' => $source?->id,
                    'author_id' => $author?->id,
                    'category_id' => $category?->id,
                ]
            );
        }
    }
}
