<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Filters\ArticleFilter;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Cache;

class NewsfeedController extends Controller
{
    /**
     * Newsfeed
     *
     * This endpoint retrieves personalized newsfeed of the authenticated user.
     *
     * @group User
     * @authenticated
     */
    public function __invoke(ArticleFilter $filter): AnonymousResourceCollection
    {
        $user = auth()->user();

        $preferredSources = $user->preferredNewsSources()->pluck('news_sources.id');
        $preferredCategories = $user->preferredCategories()->pluck('categories.id');
        $preferredAuthors = $user->preferredAuthors()->pluck('authors.id');


        $cacheKey = "user_{$user->id}_articles_"
            . md5(
                json_encode(request()->all())
                . $preferredSources->implode(",")
                . $preferredCategories->implode(",")
                . $preferredAuthors->implode(",")
            );

        $articles = Cache::remember($cacheKey, now()->addMinutes(30),
            function () use ($preferredSources, $preferredCategories, $preferredAuthors, $filter) {
                return Article::query()
                    ->when($preferredSources->isNotEmpty(),
                        fn($query) => $query->orWhereIn('news_source_id', $preferredSources))
                    ->when($preferredCategories->isNotEmpty(),
                        fn($query) => $query->orWhereIn('category_id', $preferredCategories))
                    ->when($preferredAuthors->isNotEmpty(),
                        fn($query) => $query->orWhereIn('author_id', $preferredAuthors))
                    ->orderBy('published_at', 'desc')
                    ->filter($filter)
                    ->simplePaginate();
            });

        return ArticleResource::collection($articles);
    }
}
