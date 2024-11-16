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
     * Handle an incoming article index request.
     */
    public function __invoke(ArticleFilter $filter) : AnonymousResourceCollection
    {
        return ArticleResource::collection(Article::filter($filter)->simplePaginate());
    }
}
