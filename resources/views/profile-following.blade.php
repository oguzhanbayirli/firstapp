<x-profile :sharedData="$sharedData" :doctitle="$sharedData['username'] . ' - Following'">
    <div class="list-group">
        @forelse ($following as $user)
            <a href="/profile/{{ $user->username }}" class="list-group-item list-group-item-action">
                <img class="avatar-tiny" src="{{ $user->avatar }}" />
                <strong>{{ $user->username }}</strong>
            </a>
        @empty
            <p class="text-muted m-0">Not following anyone yet.</p>
        @endforelse
    </div>
</x-profile>