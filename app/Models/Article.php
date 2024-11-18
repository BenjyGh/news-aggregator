<?php

namespace App\Models;

use App\Http\Filters\Trait\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property integer id
 * @property string title
 * @property string content
 * @property string url
 * @property string image_url
 * @property integer news_source_id
 * @property integer author_id
 * @property integer category_id
 * @property Carbon published_at
 */
class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory, Filterable;

    protected $fillable = [
      'title',
      'content',
      'url',
      'image_url',
      'news_source_id',
      'author_id',
      'category_id',
      'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    // Relations
    public function newsSource(): BelongsTo
    {
        return $this->belongsTo(NewsSource::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
