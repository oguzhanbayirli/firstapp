<x-profile :avatar="$avatar" :username="$username" :postCount="$postCount" :currentlyFollowing="$currentlyFollowing" :followerCount="$followerCount" :followingCount="$followingCount" :doctitle="$username . ' - Following'">
    <div class="list-group">
        @forelse ($following as $user)
            <a href="/profile/{{ $user->username }}" class="list-group-item list-group-item-action d-flex align-items-center">
                <img class="avatar-tiny" src="{{ $user->avatar }}" />
                <div class="ml-2">
                    <strong>{{ $user->username }}</strong>
                </div>
            </a>
        @empty
            <div class="empty-state-container">
                <div class="empty-state-icon">
                    <i class="fas fa-heart"></i>
                </div>
                <h3 class="empty-state-title">Not Following Anyone</h3>
                <p class="empty-state-text">{{ $username }} isn't following anyone yet.</p>
            </div>
        @endforelse
    </div>
</x-profile>