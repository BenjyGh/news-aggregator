<?php

namespace App\Models;

use App\Http\Filters\Trait\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer id
 * @property string name
*/
class Category extends Model
{
    /** @use HasFactory<\Database\Factories\CategoryFactory> */
    use HasFactory, Filterable;

    protected $fillable = [
        'name'
    ];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }
}
