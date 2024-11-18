<?php

namespace App\Http\Filters;

use App\Models\Article;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends QueryFilter<Article>
 */
class ArticleFilter extends QueryFilter
{
    /**
     * The query builder instance for the Article model.
     *
     * @var Builder<Article>
     */
    protected Builder $builder;

    protected array $allowedIncludes = [
        'author' => 'author',
        'source' => 'newsSource',
        'category' => 'category',
    ];

    protected array $allowedSorts = [
        'title' => 'title',
        'publishedAt' => 'published_at'
    ];

    /**
     * Filter the query by the starting date for the "published_at" field.
     *
     * @param string $date yyyy-mm-dd
     * @return void
     */
    public function publishedAtStart(string $date): void
    {
        $this->builder->whereDate('published_at', '>=', $date);
    }

    /**
     * Filter the query by the ending date for the "published_at" field.
     *
     * @param string $date yyyy-mm-dd
     * @return void
     */
    public function publishedAtEnd(string $date): void
    {
        $this->builder->whereDate('published_at', '<=', $date);
    }

    /**
     * Filter the query by categories.
     *
     * @param string $value comma separated categories
     * @return void
     */
    public function category(string $value): void
    {
        $categoryIds = explode(',', $value);

        $this->builder->whereIn('category_id', $categoryIds);
    }

    /**
     * Filter the query by sources.
     *
     * @param string $value comma separated categories
     * @return void
     */
    public function source(string $value): void
    {
        $categoryIds = explode(',', $value);

        $this->builder->whereIn('news_source_id', $categoryIds);
    }

    /**
     * Filter the query by keyword for "title" and "content" field.
     *
     * @param string $keywords
     * @return void
     */
    public function keyword(string $keywords): void
    {
        $this->builder->whereRaw(
            "MATCH(title, content) AGAINST (? IN NATURAL LANGUAGE MODE)", [$keywords]
        );
    }
}
