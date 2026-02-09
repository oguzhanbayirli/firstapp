<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use App\Helpers\QueryCache;

class CacheManage extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'cache:manage {action=info} {--flush}';

    /**
     * The console command description.
     */
    protected $description = 'Manage application cache. Actions: info, clear, stats';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $action = $this->argument('action');
        $flush = $this->option('flush');

        match($action) {
            'info' => $this->showInfo(),
            'clear' => $this->clearCache(),
            'stats' => $this->showStats(),
            default => $this->showInfo(),
        };

        if ($flush) {
            $this->line('');
            if ($this->confirm('Flush ALL cache?', false)) {
                Cache::flush();
                $this->info('✅ All cache cleared!');
            }
        }

        return 0;
    }

    /**
     * Show cache information
     */
    private function showInfo(): void
    {
        $this->info('Cache Configuration:');
        $this->newLine();

        $driver = config('cache.default');
        $store = config("cache.stores.{$driver}");

        $this->line("<fg=cyan>Driver:</> {$driver}");
        $this->line("<fg=cyan>Prefix:</> " . (config('cache.prefix') ?: 'none'));

        if ($driver === 'file') {
            $path = config('cache.stores.file.path');
            $this->line("<fg=cyan>Path:</> {$path}");
        }

        $this->newLine();
        $stats = QueryCache::stats();
        $this->info("Cache Statistics:");
        $this->line("  Files: {$stats['files']}");
        $this->line("  Size: {$stats['formatted_size']}");
    }

    /**
     * Clear cache
     */
    private function showStats(): void
    {
        $stats = QueryCache::stats();
        
        $this->info('📊 Cache Statistics:');
        $this->newLine();
        $this->line("<fg=green>Total Files:</> {$stats['files']}");
        $this->line("<fg=green>Total Size:</> {$stats['formatted_size']}");
        
        if ($stats['files'] > 0) {
            $this->newLine();
            $this->info('To clear cache, run: php artisan cache:clear');
        }
    }

    /**
     * Clear cache
     */
    private function clearCache(): void
    {
        Cache::flush();
        $this->info('✅ Cache cleared successfully!');
    }
}
