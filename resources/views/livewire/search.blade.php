<div>
    <div class="search-overlay {{ $isOpen ? 'search-overlay--visible' : '' }}">
        <div class="search-overlay-top shadow-sm">
            <div class="container container--narrow">
                <label for="live-search-field" class="search-overlay-icon"><i class="fas fa-search"></i></label>
                <input 
                    wire:model.live.debounce.750ms="query"
                    type="text" 
                    id="live-search-field" 
                    class="live-search-field" 
                    placeholder="What are you interested in?"
                    autocomplete="off"
                    @keydown.escape="@this.call('closeSearch')"
                />
                <span wire:click="closeSearch" class="close-live-search"><i class="fas fa-times-circle"></i></span>
            </div>
        </div>
        <div class="search-overlay-bottom">
            <div class="container container--narrow py-3">
                <div wire:loading.delay class="circle-loader circle-loader--visible"></div>

                <div class="live-search-results {{ count($results) ? 'live-search-results--visible' : '' }}">
                    @if (count($results))
                    <div class="list-group shadow-sm">
                        <div class="list-group-item active">
                            <strong>Search Results</strong> ({{ count($results) }} found)
                        </div>
                        @foreach ($results as $post)
                        <a href="/post/{{ $post['id'] }}" class="list-group-item list-group-item-action">
                            <img class="avatar-tiny" src="{{ $post['user']['avatar'] }}" alt="{{ $post['user']['username'] }}" loading="lazy">
                            <strong>{{ $post['title'] }}</strong>
                            <span class="text-muted small">
                                by {{ $post['user']['username'] }} on {{ \Carbon\Carbon::parse($post['created_at'])->format('m/d/Y') }}
                            </span>
                        </a>
                        @endforeach
                    </div>
                    @elseif (!empty($query))
                    <div class="alert alert-danger text-center shadow-sm">
                        <i class="fas fa-search mr-2"></i>No results found for your search.
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    @script
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Livewire.on('openSearch', () => {
                $wire.openSearch();
            });

            document.addEventListener('keydown', (e) => {
                const searchOverlay = document.querySelector('.search-overlay');
                const isOverlayHidden = !searchOverlay?.classList.contains('search-overlay--visible');
                const isNotTyping = !['INPUT', 'TEXTAREA'].includes(document.activeElement?.tagName);

                if (e.key.toUpperCase() === 'S' && isOverlayHidden && isNotTyping) {
                    $wire.openSearch();
                    setTimeout(() => document.getElementById('live-search-field')?.focus(), 50);
                }

                if (e.key === 'Escape' && !isOverlayHidden) {
                    $wire.closeSearch();
                }
            });
        });
    </script>
    @endscript
</div>
