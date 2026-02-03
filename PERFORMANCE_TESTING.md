# Performance Testing & Verification Guide

## Database Query Optimization Verification

### 1. Check Query Counts
In any controller method, add this to verify queries are optimized:

```php
if (config('app.debug')) {
    \Illuminate\Support\Facades\DB::enableQueryLog();
}

// ... your code ...

if (config('app.debug')) {
    $queries = \Illuminate\Support\Facades\DB::getQueryLog();
    \Log::info('Query count: ' . count($queries));
    foreach ($queries as $query) {
        \Log::info($query['query']);
    }
}
```

### 2. N+1 Query Prevention
✅ **Optimized Examples:**

```php
// ✅ Optimized: Load user relationships eagerly
$posts = Post::with('user:id,username,avatar')
    ->select('id', 'title', 'body', 'user_id', 'created_at')
    ->latest()
    ->paginate(10);

// ✅ In views, access without extra queries
@foreach($posts as $post)
    {{ $post->user->username }}
@endforeach
```

❌ **Anti-patterns to avoid:**

```php
// ❌ Bad: Will cause N+1 queries
$posts = Post::all();
@foreach($posts as $post)
    {{ $post->user->username }}  // N queries!
@endforeach
```

## Performance Metrics

### Expected Performance After Optimization

| Metric | Before | After | Improvement |
|--------|--------|-------|-------------|
| Database Queries (per page) | 20-30 | 4-6 | 75-85% reduction |
| Page Load Time | ~1500ms | ~400ms | 73% faster |
| Database Query Time | ~800ms | ~150ms | 81% faster |
| Memory Usage (per request) | 12MB | 3MB | 75% reduction |
| Cache Hit Rate | 0% | 85%+ | N/A |

### Testing Commands

#### 1. Database Query Analysis
```bash
# Enable query logging in tinker
php artisan tinker

DB::enableQueryLog();
$posts = App\Models\Post::with('user')->paginate(10);
dd(DB::getQueryLog());  // Count and review queries
```

#### 2. Measure Page Load Time
```bash
# Test a route with timing
php artisan tinker

$start = microtime(true);
$posts = App\Models\Post::with('user:id,username,avatar')
    ->select('id', 'title', 'body', 'user_id', 'created_at')
    ->latest()
    ->paginate(10);
$time = microtime(true) - $start;
echo "Time: {$time}ms";
```

#### 3. Check Cache Effectiveness
```bash
php artisan tinker

// Check if cache is working
cache()->put('test_key', 'test_value', now()->addHour());
echo cache()->get('test_key');  // Should return 'test_value'
```

## Real-World Performance Testing

### 1. Load Testing with Apache Bench
```bash
# Test home feed endpoint (10 requests, 5 concurrent)
ab -n 10 -c 5 http://localhost:8000/

# Test search endpoint (50 requests, 10 concurrent)
ab -n 50 -c 10 "http://localhost:8000/search/laravel"
```

### 2. Profile with Laravel Telescope
Laravel Telescope helps monitor requests, queries, and performance:

```bash
composer require laravel/telescope
php artisan telescope:install
```

Then visit: `http://localhost:8000/telescope`

### 3. Database Query Profiling
```bash
# In php artisan tinker
DB::enableQueryLog();
$user = App\Models\User::with('posts.user', 'followers', 'following')->find(1);
$queries = DB::getQueryLog();

// Display query count and execution time
dd([
    'count' => count($queries),
    'time' => array_sum(array_column($queries, 'time'))
]);
```

## Monitoring in Production

### 1. Slow Query Log (MySQL/MariaDB)
```sql
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 1;  -- Log queries taking > 1 second
```

### 2. Application Monitoring
Configure your `.env` for production monitoring:

```env
LOG_LEVEL=warning
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

### 3. Key Performance Indicators (KPIs)

Track these metrics regularly:

- **Database Query Time:** Target < 100ms per request
- **Page Load Time:** Target < 500ms total
- **Cache Hit Rate:** Target > 80%
- **Memory Usage:** Target < 5MB per request
- **Error Rate:** Target < 0.1%
- **API Response Time:** Target < 200ms

## Optimization Checklist

Database Layer:
- [ ] Eager loading with `.with()` implemented
- [ ] Column selection with `.select()` in queries
- [ ] Database indexes created on foreign keys
- [ ] Database indexes created on sort columns (created_at)
- [ ] Pagination implemented for large datasets
- [ ] Query result caching enabled (followers/following counts)

Application Layer:
- [ ] Rate limiting on API endpoints (search)
- [ ] Response compression enabled
- [ ] Cache headers configured
- [ ] Deferred script loading in views
- [ ] Preload critical resources
- [ ] No N+1 queries detected

Frontend Layer:
- [ ] CSS minified by Vite
- [ ] JavaScript minified and code-split
- [ ] Images lazy-loaded
- [ ] Font preloading configured
- [ ] Bootstrap utility classes used efficiently

## Debugging Performance Issues

### 1. Identify Slow Queries
```php
// In AppServiceProvider
public function boot()
{
    DB::listen(function ($query) {
        if ($query->time > 100) {  // Log queries > 100ms
            \Log::warning('Slow query: ' . $query->sql, [
                'time' => $query->time,
                'bindings' => $query->bindings
            ]);
        }
    });
}
```

### 2. Check Cache Functionality
```php
// Verify cache is working
if (!cache()->has('user.1.follower_count')) {
    \Log::warning('Cache miss for user followers');
}
```

### 3. Monitor Memory Usage
```php
// Log memory usage in requests
\Log::info('Memory: ' . memory_get_usage(true) / 1024 / 1024 . 'MB');
```

## Continuation: Further Optimizations

### 1. Full-Text Search
Implement MySQL full-text search for better search performance:

```php
$posts = Post::whereFullText(['title', 'body'], $query)
    ->latest()
    ->limit(10)
    ->get();
```

### 2. Redis Caching
Implement Redis for faster cache operations:

```env
CACHE_DRIVER=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

### 3. Query Optimization
Use `chunk()` for bulk operations instead of loading all records:

```php
Post::chunk(100, function ($posts) {
    foreach ($posts as $post) {
        // Process post
    }
});
```

### 4. Image Optimization
Add image processing for avatars:

```php
Image::make($file)
    ->fit(200, 200)
    ->optimize()
    ->save($path);
```

## References

- Laravel Query Performance: https://laravel.com/docs/11.x/database
- Eager Loading: https://laravel.com/docs/11.x/eloquent-relationships#eager-loading
- Database Indexes: https://laravel.com/docs/11.x/migrations#indexes
- Caching: https://laravel.com/docs/11.x/cache
- Rate Limiting: https://laravel.com/docs/11.x/rate-limiting
