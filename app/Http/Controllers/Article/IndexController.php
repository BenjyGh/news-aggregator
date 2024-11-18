<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Filters\ArticleFilter;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    /**
     * All Articles
     *
     * This endpoint retrieves a list of all articles.
     *
     * @group Article
     * @subgroup Article
     *
     * @queryParam include string Comma-separated list of relations to include. Example: source,author,category
     * @queryParam filter[category] string Comma-separated list of category ids. No-example
     * @queryParam filter[keyword] string Filter results based on a keyword search. No-example
     * @queryParam filter[publishedAtStart] Filter results by the start of the publication date range (yyyy-mm-dd format). No-example
     * @queryParam filter[publishedAtEnd] Filter results by the end of the publication date range (yyyy-mm-dd format). No-example
     * @queryParam sort string Comma-separated list of fields to sort by. Use a minus (`-`) for descending order. Example: title,-publishedAt
     * @queryParam page integer The page number for paginated results. No-example
     */
    public function __invoke(ArticleFilter $filter) : AnonymousResourceCollection
    {
        return ArticleResource::collection(Article::filter($filter)->simplePaginate());
    }
}
