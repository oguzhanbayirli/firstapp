<x-layout :doctitle="$post->title">
    <div class="container py-md-5 container--narrow">
      <div class="d-flex justify-content-between align-items-start mb-3">
        <h2 class="mb-0">{{ $post->title }}</h2>
        @can('update', $post)
        <div class="d-flex align-items-center">
          <a href="/post/{{ $post->id }}/edit" class="btn btn-sm mr-2" style="color: #f9322c; border: 1px solid #f9322c; padding: 0.25rem 0.5rem; font-size: 0.82rem;" data-toggle="tooltip" data-placement="top" title="Edit">
            <i class="fas fa-edit"></i> Edit
          </a>
          <form class="delete-post-form d-inline" action="/post/{{ $post->id }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-sm" style="background-color: #f9322c; border: 1px solid #f9322c; color: #fff; padding: 0.25rem 0.5rem; font-size: 0.82rem;" data-toggle="tooltip" data-placement="top" title="Delete">
              <i class="fas fa-trash"></i> Delete
            </button>
          </form>
        </div>
        @endcan
      </div>

      <p class="text-muted small mb-4">
        <a href="/profile/{{ $post->user->username }}"><img class="avatar-tiny" src="{{ $post->user->avatar }}" /></a>
        Posted by <a href="/profile/{{ $post->user->username }}">{{ $post->user->username }}</a> on {{ $post->created_at->format('M jS Y') }}
      </p>

      <div class="body-content">
        {!! $post->body !!}
      </div>
    </div>
</x-layout>