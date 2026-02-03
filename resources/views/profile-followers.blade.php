<x-profile :avatar="$avatar" :username="$username" :postCount="$postCount" :currentlyFollowing="$currentlyFollowing" :followerCount="$followerCount" :followingCount="$followingCount" :doctitle="$username . ' - Followers'">
    <div class="list-group">
        @forelse ($followers as $follower)
            <a href="/profile/{{ $follower->username }}" class="list-group-item list-group-item-action d-flex align-items-center">
                <img class="avatar-tiny" src="{{ $follower->avatar }}" />
                <div class="ml-2">
                    <strong>{{ $follower->username }}</strong>
                </div>
            </a>
        @empty
            <div class="empty-state-container">
                <div class="empty-state-icon">
                    <i class="fas fa-users"></i>
                </div>
                <h3 class="empty-state-title">No Followers</h3>
                <p class="empty-state-text">{{ $username }} doesn't have any followers yet.</p>
            </div>
        @endforelse
    </div>
</x-profile>
