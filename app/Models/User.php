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

    /**
     * Format avatar path
     */
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

    /**
     * User's posts relationship
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    /**
     * User's followers relationship
     */
    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follow', 'followed_user_id', 'user_id');
    }

    /**
     * Users that this user follows
     */
    public function following(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'follow', 'user_id', 'followed_user_id');
    }

    /**
     * Get posts from users that this user follows
     */
    public function feedPosts()
    {
        return Post::whereIn('user_id', $this->following()->pluck('users.id'));
    }

    /**
     * Check if user is following another user
     */
    public function isFollowing(User $user): bool
    {
        return $this->following()->where('users.id', $user->id)->exists();
    }

    /**
     * Check if user is followed by another user
     */
    public function isFollowedBy(User $user): bool
    {
        return $this->followers()->where('users.id', $user->id)->exists();
    }

    /**
     * Get count of followers - cached
     */
    public function followerCount(): int
    {
        return cache()->remember(
            "user.{$this->id}.follower_count",
            now()->addHours(1),
            fn() => $this->followers()->count()
        );
    }

    /**
     * Get count of following - cached
     */
    public function followingCount(): int
    {
        return cache()->remember(
            "user.{$this->id}.following_count",
            now()->addHours(1),
            fn() => $this->following()->count()
        );
    }

    /**
     * Clear user cache when follow/unfollow happens
     */
    public function clearFollowCache(): void
    {
        cache()->forget("user.{$this->id}.follower_count");
        cache()->forget("user.{$this->id}.following_count");
    }
}