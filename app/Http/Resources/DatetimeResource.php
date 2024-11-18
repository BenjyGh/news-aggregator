<?php

namespace App\Http\Resources;

use App\Models\Article;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property CarbonInterface $resource
 */
class DatetimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        return [
            'human' => $this->resource->diffForHumans(),
            'datetime' => $this->resource->toDateTimeString(),
            'string' => $this->resource->toIso8601String(),
            'timestamp' => $this->resource->timestamp,
         ];
    }
}
