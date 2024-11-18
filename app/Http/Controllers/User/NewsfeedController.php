<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\ArticleFilter;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class NewsfeedController extends Controller
{
    /**
     * Handle an incoming newsfeed index request.
     */
    public function __invoke(ArticleFilter $filter): AnonymousResourceCollection
    {
        $user = auth()->user();

        $preferredSources = $user->preferredNewsSources()->pluck('news_sources.id');
        $preferredCategories = $user->preferredCategories()->pluck('categories.id');
        $preferredAuthors = $user->preferredAuthors()->pluck('authors.id');

        $articles = Article::query()
            ->when($preferredSources->isNotEmpty(),
                fn($query) => $query->orWhereIn('news_source_id', $preferredSources))
            ->when($preferredCategories->isNotEmpty(),
                fn($query) => $query->orWhereIn('category_id', $preferredCategories))
            ->when($preferredAuthors->isNotEmpty(),
                fn($query) => $query->orWhereIn('author_id', $preferredAuthors))
            ->orderBy('published_at', 'desc')
            ->filter($filter);

        return ArticleResource::collection($articles->simplePaginate());
    }
}
