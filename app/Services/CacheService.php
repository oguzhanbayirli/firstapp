<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class CacheService
{
    /**
     * Cache key prefixes
     */
    const PREFIX_USER = 'user';
    const PREFIX_POST = 'post';
    const PREFIX_FOLLOW = 'follow';

    /**
     * Default cache duration in hours
     */
    const DEFAULT_DURATION = 2;

    /**
     * Remember a value in cache
     */
    public function remember(string $key, callable $callback, ?int $hours = null): mixed
    {
        $hours = $hours ?? self::DEFAULT_DURATION;
        
        return Cache::remember(
            $key,
            now()->addHours($hours),
            $callback
        );
    }

    /**
     * Get value from cache
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return Cache::get($key, $default);
    }

    /**
     * Put value in cache
     */
    public function put(string $key, mixed $value, ?int $hours = null): void
    {
        $hours = $hours ?? self::DEFAULT_DURATION;
        Cache::put($key, $value, now()->addHours($hours));
    }

    /**
     * Forget a cache key
     */
    public function forget(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Forget multiple cache keys
     */
    public function forgetMany(array $keys): void
    {
        foreach ($keys as $key) {
            $this->forget($key);
        }
    }

    /**
     * Clear user-related cache
     */
    public function clearUserCache(int $userId): void
    {
        $prefix = self::PREFIX_USER;
        $this->forgetMany([
            "{$prefix}.{$userId}.followers_count",
            "{$prefix}.{$userId}.following_count",
            "{$prefix}.{$userId}.posts_count",
            "profile_{$userId}_counts", // UserController profile cache
        ]);
    }

    /**
     * Clear follow-related cache for a user
     */
    public function clearFollowCache(int $userId): void
    {
        $prefix = self::PREFIX_USER;
        $this->forgetMany([
            "{$prefix}.{$userId}.followers_count",
            "{$prefix}.{$userId}.following_count",
        ]);
    }

    /**
     * Get followers count from cache
     */
    public function getFollowersCount(int $userId, callable $callback): int
    {
        return $this->remember($this->userCacheKey($userId, 'followers_count'), $callback);
    }

    /**
     * Get following count from cache
     */
    public function getFollowingCount(int $userId, callable $callback): int
    {
        return $this->remember($this->userCacheKey($userId, 'following_count'), $callback);
    }

    /**
     * Get posts count from cache
     */
    public function getPostsCount(int $userId, callable $callback): int
    {
        return $this->remember($this->userCacheKey($userId, 'posts_count'), $callback);
    }

    /**
     * Generate user cache key
     */
    protected function userCacheKey(int $userId, string $attribute): string
    {
        return self::PREFIX_USER . ".{$userId}.{$attribute}";
    }

    /**
     * Flush all cache
     */
    public function flushAll(): bool
    {
        return Cache::flush();
    }
}
