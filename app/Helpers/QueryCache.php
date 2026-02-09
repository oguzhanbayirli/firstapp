<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

class QueryCache
{
    /**
     * Cache a database query result
     * 
     * Usage: QueryCache::query('users.10', fn() => User::find(10), 60)
     */
    public static function query(string $key, callable $callback, int $minutes = 120): mixed
    {
        return Cache::remember(
            $key,
            now()->addMinutes($minutes),
            $callback
        );
    }

    /**
     * Cache a collection query
     */
    public static function collection(string $key, callable $callback, int $minutes = 120): mixed
    {
        return Cache::remember(
            "collection.{$key}",
            now()->addMinutes($minutes),
            $callback
        );
    }

    /**
     * Cache search results
     */
    public static function search(string $query, callable $callback, int $minutes = 30): mixed
    {
        $key = 'search.' . md5(strtolower($query));
        return Cache::remember(
            $key,
            now()->addMinutes($minutes),
            $callback
        );
    }

    /**
     * Cache user-specific data
     */
    public static function userSpecific(int $userId, string $key, callable $callback, int $minutes = 120): mixed
    {
        $cacheKey = "user.{$userId}.{$key}";
        return Cache::remember(
            $cacheKey,
            now()->addMinutes($minutes),
            $callback
        );
    }

    /**
     * Invalidate a cache key
     */
    public static function invalidate(string $key): bool
    {
        return Cache::forget($key);
    }

    /**
     * Invalidate multiple cache keys
     */
    public static function invalidateMany(array $keys): void
    {
        foreach ($keys as $key) {
            static::invalidate($key);
        }
    }

    /**
     * Invalidate all user cache
     */
    public static function invalidateUser(int $userId): void
    {
        Cache::flush();
    }

    /**
     * Get cache size (bytes)
     */
    public static function size(): int
    {
        $path = storage_path('framework/cache/data');
        if (!is_dir($path)) {
            return 0;
        }

        $size = 0;
        foreach (scandir($path) as $file) {
            if (is_file("{$path}/{$file}")) {
                $size += filesize("{$path}/{$file}");
            }
        }
        return $size;
    }

    /**
     * Get cache statistics
     */
    public static function stats(): array
    {
        $path = storage_path('framework/cache/data');
        if (!is_dir($path)) {
            return ['files' => 0, 'size' => 0, 'formatted_size' => '0 bytes'];
        }

        $files = 0;
        $size = 0;
        foreach (scandir($path) as $file) {
            if (is_file("{$path}/{$file}")) {
                $files++;
                $size += filesize("{$path}/{$file}");
            }
        }

        return [
            'files' => $files,
            'size' => $size,
            'formatted_size' => static::formatBytes($size),
        ];
    }

    /**
     * Format bytes to human readable format
     */
    private static function formatBytes(int $bytes): string
    {
        $units = ['bytes', 'KB', 'MB', 'GB'];
        $size = $bytes;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }
}
