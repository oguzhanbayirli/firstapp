<x-layout doctitle="Feed">
    <div class="container py-md-4 container--narrow">
      <div class="mb-4 pb-3 border-bottom">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" href="/?feed=all">
              <i class="fas fa-globe mr-2"></i> All Posts
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ $filter === 'following' ? 'active' : '' }}" href="/?feed=following">
              <i class="fas fa-heart mr-2"></i> Following
            </a>
          </li>
        </ul>
      </div>

      @forelse ($posts as $post)
          <div class="list-group">
              <x-post :post="$post" />
          </div>
      @empty
          <div class="empty-state-container py-5">
            <div class="empty-state-icon">
              @if ($filter === 'following')
                <i class="fas fa-heart-broken"></i>
              @else
                <i class="fas fa-inbox"></i>
              @endif
            </div>
            @if ($filter === 'following')
              <h2 class="empty-state-title">Your Feed Is Empty</h2>
              <p class="empty-state-text">Start following users to see their posts here!</p>
              <a href="/?feed=all" class="btn btn-primary">
                <i class="fas fa-globe mr-2"></i> Discover Posts
              </a>
            @else
              <h2 class="empty-state-title">No Posts Yet</h2>
              <p class="empty-state-text">Be the first to share your thoughts with the community!</p>
              <a href="/create-post" class="btn btn-primary">
                <i class="fas fa-pencil-alt mr-2"></i> Create Post
              </a>
            @endif
        </div>
      @endforelse

      @if ($posts->isNotEmpty())
          <div class="mt-4">
            {{ $posts->links() }}
          </div>
      @endif
    </div>
</x-layout>