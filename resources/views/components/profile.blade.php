<x-layout :doctitle="$doctitle ?? $username">
    <div class="container py-md-5 container--narrow">
        <h2>
            <img class="avatar-small" src="{{ $avatar }}" /> {{ $username }}
            @auth
                @if (auth()->user()->username !== $username)
                    @if ($currentlyFollowing)
                        <form class="ml-2 d-inline" action="/unfollow/{{ $username }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm" style="border-color: #6c757d;">
                                <i class="fas fa-user-times"></i> Unfollow
                            </button>
                        </form>
                    @else
                        <form class="ml-2 d-inline" action="/follow/{{ $username }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-sm">
                                <i class="fas fa-user-plus"></i> Follow
                            </button>
                        </form>
                    @endif
                @else
                    <a href="/manage-avatar" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-camera"></i> Manage Avatar
                    </a>
                @endif
            @endauth
        </h2>

        <div class="profile-nav nav nav-tabs pt-2 mb-4">
            <a href="/profile/{{ $username }}" class="profile-nav-link nav-item nav-link {{ request()->is('profile/' . $username) ? 'active' : '' }}">Posts: {{ $postCount }}</a>
            <a href="/profile/{{ $username }}/followers" class="profile-nav-link nav-item nav-link {{ request()->is('profile/' . $username . '/followers') ? 'active' : '' }}">Followers: {{ $followerCount }}</a>
            <a href="/profile/{{ $username }}/following" class="profile-nav-link nav-item nav-link {{ request()->is('profile/' . $username . '/following') ? 'active' : '' }}">Following: {{ $followingCount }}</a>
        </div>
        <div class="profile-slot-content">
            {{ $slot }}
        </div>
    </div>
</x-layout>