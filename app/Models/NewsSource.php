<?php

namespace App\Models;

use App\Http\Filters\Trait\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer id
 * @property string name
 * @property string url
 * @property string category_name
 */
class NewsSource extends Model
{
    /** @use HasFactory<\Database\Factories\NewsSourceFactory> */
    use HasFactory, Filterable;

    protected $fillable = [
        'name',
        'url',
        'category_name'
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
