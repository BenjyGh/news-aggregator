<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, hasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send a password reset notification to the user.
     * Here we will just Log the token instead of sending actual email
     * @param  string  $token
     */
    public function sendPasswordResetNotification($token): void
    {
        Log::info("Password Reset Token: " . $token);
    }

    public function preferences(): HasMany
    {
        return $this->hasMany(UserPreference::class);
    }

    public function preferredCategories(): MorphToMany
    {
        return $this->morphedByMany(Category::class, 'preferable', 'user_preferences');
    }

    public function preferredAuthors(): MorphToMany
    {
        return $this->morphedByMany(Author::class, 'preferable', 'user_preferences');
    }

    public function preferredNewsSources(): MorphToMany
    {
        return $this
            ->morphedByMany(NewsSource::class, 'preferable', 'user_preferences');
    }
}
