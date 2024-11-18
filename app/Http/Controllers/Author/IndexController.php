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
     * Handle an incoming author index request.
     */
    public function __invoke(AuthorFilter $filter) : AnonymousResourceCollection
    {
        return AuthorResource::collection(Author::filter($filter)->simplePaginate());
    }
}
