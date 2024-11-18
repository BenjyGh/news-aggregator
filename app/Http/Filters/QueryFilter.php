<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

/**
 * @template TModel of \Illuminate\Database\Eloquent\Model
 */
abstract class QueryFilter
{
    /**
     * The query builder instance.
     *
     * @var Builder<TModel>
     */
    protected Builder $builder;

    /**
     * allowed relations that can be requested by the client
     *
     * key: query param name => value: relation name
     *
     * @var array<string,string>
     */
    protected array $allowedIncludes = [];

    /**
     * allowed sort field that can be requested by the client
     *
     * key: query param name => value: column name
     *
     * @var array<string,string>
     */
    protected array $allowedSorts = [];

    public function __construct(public Request $request)
    {
    }

    /**
     * Apply the filters to the given query builder.
     *
     *  This method processes query parameters in this format:
     *  http://example.com?filter[category]=1,2&filter[source]=1,3&include=category,author.
     *
     * Iterates over the query params, checking if a corresponding method exists
     * for each key. If a method exists, it invokes the method with the given value(s).
     *
     * @param Builder<TModel> $builder
     * @return Builder
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->request->all() as $key => $value) {
            if (!$value)
                continue;

            if (method_exists($this, $key))
                $this->$key($value);
        }

        return $this->builder;
    }

    /**
     * Include requested relationships in the query builder if allowed.
     *
     * @param string $relations comma separated relationships
     * @return void
     */
    public function include(string $relations): void
    {
        $queryList = collect(explode(',', $relations));

        // filter out the invalid params
        $validQueryNames = $queryList->filter(fn($item) => Arr::exists($this->allowedIncludes, $item));

        // map query param name to relation name
        $validRelations = $validQueryNames->map(fn($relation) => $this->allowedIncludes[$relation]);

        // load the relation with limit amount of 10
        foreach ($validRelations as $relation) {
            $this->builder->with([
                $relation => fn($query) => $query->limit(10)
            ]);
        }
    }

    /**
     * get "filter[]" array from apply method and iterate through them
     * calling corresponding method if exist
     *
     * @param array $arr
     * @return void
     */
    protected function filter(array $arr) : void
    {
        foreach ($arr as $key => $value) {
            if (!$value)
                continue;

            if (method_exists($this, $key))
                $this->$key($value);
        }
    }

    /**
     * Apply sorting to the query based on specified attributes.
     *
     * @param string $values A comma-separated list of sorting attributes,
     * optionally prefixed with a minus sign (`-`) for descending order.
     *
     * @return void
     */
    public function sort(string $values): void
    {
        $sortAttributes = explode(',', $values);

        foreach ($sortAttributes as $sortAttribute) {
            $direction = 'asc';

            if (str_starts_with($sortAttribute, '-')) {
                $direction = 'desc';
                $sortAttribute = substr($sortAttribute, 1);
            }

            if (!array_key_exists($sortAttribute, $this->allowedSorts)) {
                continue;
            }

            $this->builder->orderBy($this->allowedSorts[$sortAttribute], $direction);
        }
    }
}
