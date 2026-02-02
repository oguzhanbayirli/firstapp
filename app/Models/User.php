<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'avatar',
    ];
    protected function avatar(): Attribute
    {
        return Attribute::make(get: function ($value) {
            if (!$value || $value === 'default-avatar.svg') {
                return '/storage/avatars/default-avatar.svg';
            }
            return '/storage/avatars/' . $value;
        });
    }
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follow', 'followed_user_id', 'user_id');
    }

    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follow', 'user_id', 'followed_user_id');
    }
    /**
     * Get posts from users that this user follows.
     */
    public function feedPosts()
    {
        return Post::whereIn('user_id', $this->following()->pluck('users.id'));
    }
    public function recentPosts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id')->orderBy('created_at', 'desc');
    }
    
}
