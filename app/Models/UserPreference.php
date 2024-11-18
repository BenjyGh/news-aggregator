<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property integer id
 * @property integer user_id
 * @property string preferable_type
 * @property integer preferable_id
 */
class UserPreference extends Model
{
    protected $fillable = [
        'user_id',
        'preferable_id',
        'preferable_type',
    ];

    public function preferable()
    {
        return $this->morphTo();
    }
}
