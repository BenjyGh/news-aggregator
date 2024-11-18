<?php

namespace App\Http\Filters;

use App\Models\Article;
use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;

/**
 * @extends QueryFilter<Article>
 */
class NewSourceFilter extends QueryFilter
{
    /**
     * The query builder instance for the Article model.
     *
     * @var Builder<Author>
     */
    protected Builder $builder;

    protected array $allowedIncludes = [
        'articles' => 'articles',
    ];

    protected array $allowedSorts = [
        'name' => 'name',
    ];

    /**
     * Filter the query by keyword for "name" field.
     *
     * @param string $keyword
     * @return void
     */
    public function name(string $keyword): void
    {
        $this->builder->where('name', 'LIKE', "%$keyword%");
    }
}
