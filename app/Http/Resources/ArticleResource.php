<?php

namespace App\Http\Resources;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property Article $resource
 */
class ArticleResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'content' => $this->resource->content,
            'url' => $this->resource->url,
            'image_url' => $this->resource->image_url,
            'published_at' => $this->resource->published_at,

            'source' => new NewsSourceResource($this->whenLoaded('newsSource')),
            'author' => new AuthorResource($this->whenLoaded('author')),
            'category' => new CategoryResource($this->whenLoaded('category'))
        ];
    }
}
