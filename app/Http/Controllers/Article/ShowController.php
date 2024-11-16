<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Filters\ArticleFilter;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ShowController extends Controller
{
    /**
     * Handle an incoming article index request.
     */
    public function __invoke(Article $article, ArticleFilter $filter): ArticleResource
    {
        return new ArticleResource($article->load(['category', 'newsSource', 'author']));
    }
}
