<?php

namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Http\Filters\AuthorFilter;
use App\Http\Resources\AuthorResource;
use App\Models\Author;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class IndexController extends Controller
{
    /**
     * All Authors
     *
     * This endpoint retrieves a list of all authors.
     *
     * @group Article
     * @subgroup General
     *
     * @queryParam include string Comma-separated list of relations to include. Example: articles
     * @queryParam filter[name] string Filter categories by their name. No-example
     * @queryParam sort string Comma-separated list of fields to sort by.
     * Use a minus (`-`) for descending order. Example: -name
     * @queryParam page integer The page number for paginated results. No-example
     */
    public function __invoke(AuthorFilter $filter) : AnonymousResourceCollection
    {
        return AuthorResource::collection(Author::filter($filter)->simplePaginate());
    }
}
