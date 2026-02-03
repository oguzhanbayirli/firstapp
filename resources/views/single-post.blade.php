<x-layout :doctitle="$post->title">
    <div class="container py-md-4 container--narrow">
      <article class="post-single">
        <header class="post-header mb-4 pb-3 border-bottom">
          <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
              <h1 class="h2 mb-2">{{ $post->title }}</h1>
              <div class="post-meta text-muted small">
                <img class="avatar-tiny" src="{{ $post->user->avatar }}" />
                <a href="/profile/{{ $post->user->username }}" class="text-muted font-weight-bold">{{ $post->user->username }}</a>
                <span class="mx-1">•</span>
                <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->format('M d, Y') }}</time>
              </div>
            </div>
            @can('update', $post)
            <div class="post-actions">
              <a href="/post/{{ $post->id }}/edit" class="btn btn-sm btn-outline-primary mr-2" data-toggle="tooltip" data-placement="bottom" title="Edit post">
                <i class="fas fa-edit"></i>
              </a>
              <form class="delete-post-form d-inline" action="/post/{{ $post->id }}" method="POST">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger" data-toggle="tooltip" data-placement="bottom" title="Delete post">
                  <i class="fas fa-trash"></i>
                </button>
              </form>
            </div>
            @endcan
          </div>
        </header>

        <div class="post-content body-content">
          {!! $post->body !!}
        </div>

        <footer class="post-footer mt-4 pt-3 border-top">
          <div class="d-flex gap-2">
            <a href="/" class="btn btn-sm btn-outline-secondary">
              <i class="fas fa-arrow-left mr-1"></i> Back to Feed
            </a>
            <a href="/profile/{{ $post->user->username }}" class="btn btn-sm btn-outline-secondary">
              <i class="fas fa-user mr-1"></i> View Profile
            </a>
          </div>
        </footer>
      </article>
    </div>
</x-layout>