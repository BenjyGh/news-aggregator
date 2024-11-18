<?php

namespace App\Http\Resources;

use App\Models\NewsSource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property NewsSource $resource
*/
class NewsSourceResource extends JsonResource
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
            'name' => $this->resource->name,
            'url' => $this->resource->url,

            'articles' => ArticleResource::collection($this->whenLoaded('articles'))
        ];
    }
}
