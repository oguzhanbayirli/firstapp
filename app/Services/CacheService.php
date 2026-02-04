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
        $prefix = self::PREFIX_USER;
        return $this->remember(
            "{$prefix}.{$userId}.followers_count",
            $callback
        );
    }

    /**
     * Get following count from cache
     */
    public function getFollowingCount(int $userId, callable $callback): int
    {
        $prefix = self::PREFIX_USER;
        return $this->remember(
            "{$prefix}.{$userId}.following_count",
            $callback
        );
    }

    /**
     * Get posts count from cache
     */
    public function getPostsCount(int $userId, callable $callback): int
    {
        $prefix = self::PREFIX_USER;
        return $this->remember(
            "{$prefix}.{$userId}.posts_count",
            $callback
        );
    }

    /**
     * Flush all cache
     */
    public function flushAll(): bool
    {
        return Cache::flush();
    }
}
