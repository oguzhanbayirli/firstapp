<x-layout doctitle="Feed">
    <div class="container py-md-5 container--narrow">
      <div class="mb-4">
        <ul class="nav nav-tabs" role="tablist">
          <li class="nav-item">
            <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" href="/?feed=all">
              <i class="fas fa-globe mr-1"></i> All Posts
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link {{ $filter === 'following' ? 'active' : '' }}" href="/?feed=following">
              <i class="fas fa-users mr-1"></i> Following
            </a>
          </li>
        </ul>
      </div>

      @unless ($posts->isEmpty())
          <div class="list-group">
              @foreach ($posts as $post)
              <x-post :post="$post" />
              @endforeach
          </div>
          <div class="mt-3">
            {{ $posts->links() }}
          </div>
        @else
          <div class="empty-state">
            @if ($filter === 'following')
              <i class="fas fa-user-friends"></i>
              <h4>Your feed is empty</h4>
              <p class="text-muted">Hello <strong>{{ auth()->user()->username }}</strong>! Follow some users to see their posts here.</p>
              <a href="/?feed=all" class="btn btn-sm" style="background-color: #f9322c; color: #fff;">
                <i class="fas fa-search mr-1"></i> Discover Users
              </a>
            @else
              <i class="fas fa-pen-fancy"></i>
              <h4>No posts yet</h4>
              <p class="text-muted">Hello <strong>{{ auth()->user()->username }}</strong>! Be the first to share your thoughts.</p>
              <a href="/create-post" class="btn btn-sm" style="background-color: #f9322c; color: #fff;">
                <i class="fas fa-plus mr-1"></i> Create Your First Post
              </a>
            @endif
          </div>
      @endunless
    </div>
</x-layout>