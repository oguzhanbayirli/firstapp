<x-layout :doctitle="$username . ' - Posts'">
    <div class="container py-md-5 container--narrow">
        <div class="profile-header">
            <div class="profile-header-info">
                <img class="avatar-small" src="{{ $avatar }}" alt="{{ $username }} avatar" loading="eager" fetchpriority="high">
                <span class="profile-header-name">{{ $username }}</span>
            </div>
            @auth
                <div class="profile-header-actions">
                    @if (auth()->user()->username !== $username)
                        @if ($currentlyFollowing)
                            <livewire:remove-follow :username="$username" />
                        @else
                            <livewire:add-follow :username="$username" />
                        @endif
                    @else
                        <a href="/manage-avatar" class="btn btn-outline-secondary btn-sm"><i class="fas fa-camera"></i> Manage Avatar</a>
                    @endif
                </div>
            @endauth
        </div>

        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a href="/profile/{{ $username }}" class="profile-nav-link nav-item nav-link {{ request()->is('profile/' . $username) ? 'active' : '' }}">Posts: {{ $postCount }}</a>
            <a href="/profile/{{ $username }}/followers" class="profile-nav-link nav-item nav-link {{ request()->is('profile/' . $username . '/followers') ? 'active' : '' }}">Followers: {{ $followerCount }}</a>
            <a href="/profile/{{ $username }}/following" class="profile-nav-link nav-item nav-link {{ request()->is('profile/' . $username . '/following') ? 'active' : '' }}">Following: {{ $followingCount }}</a>
        </div>

        @if($posts->count())
            <div class="list-group">
                @foreach($posts as $post)
                    <x-post :post="$post" :hideAuthor="true" />
                @endforeach
            </div>
        @else
            <div class="empty-state-container">
                <div class="empty-state-icon"><i class="fas fa-pencil-alt"></i></div>
                <h3 class="empty-state-title">No Posts Yet</h3>
                <p class="empty-state-text">{{ $username }} hasn't shared any posts yet.</p>
            </div>
        @endif
    </div>
</x-layout>