<a href="/post/{{ $post->id }}" class="list-group-item list-group-item-action d-flex align-items-center py-3">
    <img class="avatar-tiny" src="{{ $post->user->avatar }}" />
    <div class="ml-2">
        <strong>{{ $post->title }}</strong>
        <div class="text-muted small">
            @if (!isset($hideAuthor))
            by {{ $post->user->username }} &bull;
            @endif
            {{ $post->created_at->format('M j, Y') }}
        </div>
    </div>
</a>