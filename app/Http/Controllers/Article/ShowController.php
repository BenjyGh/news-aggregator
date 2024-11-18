<?php

namespace App\Http\Controllers\Article;

use App\Http\Controllers\Controller;
use App\Http\Filters\ArticleFilter;
use App\Http\Resources\ArticleResource;
use App\Models\Article;

class ShowController extends Controller
{
    /**
     * Show Article
     *
     * This endpoint retrieves a detailed view of a specific article.
     *
     * @group Article
     * @subgroup Article
     */
    public function __invoke(Article $article): ArticleResource
    {
        return new ArticleResource($article->load(['category', 'newsSource', 'author']));
    }
}
