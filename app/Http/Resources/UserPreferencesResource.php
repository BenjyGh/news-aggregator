<?php

namespace App\Http\Resources;

use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property User $resource
 */
class UserPreferencesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        return [
            'news_sources' => NewsSourceResource::collection($this->resource->preferredNewsSources),
            'authors' => AuthorResource::collection($this->resource->preferredAuthors),
            'categories' => CategoryResource::collection($this->resource->preferredCategories)
        ];
    }
}
