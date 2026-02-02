<x-profile :sharedData="$sharedData" :doctitle="$sharedData['username'] . ' - Followers'">
    <div class="list-group">
        @forelse ($followers as $follower)
            <a href="/profile/{{ $follower->username }}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $follower->avatar }}" />
                <strong>{{ $follower->username }}</strong>
            </a>
        @empty
            <p class="text-muted m-0">No followers yet.</p>
        @endforelse
    </div>
</x-profile>
