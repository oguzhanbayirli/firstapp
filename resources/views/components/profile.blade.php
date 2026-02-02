<x-layout :doctitle="$doctitle ?? $sharedData['username']">
    <div class="container py-md-5 container--narrow">
        <h2>
            <img class="avatar-small" src="{{ $sharedData['avatar'] }}" /> {{ $sharedData['username'] }}
            @auth
                @if (auth()->user()->username !== $sharedData['username'])
                    @if ($sharedData['currentlyFollowing'])
                        <form class="ml-2 d-inline" action="/remove-follow/{{ $sharedData['username'] }}" method="POST">
                            @csrf
                            <button class="btn btn-outline-secondary btn-sm" style="border-color: #6c757d;">
                                <i class="fas fa-user-times"></i> Unfollow
                            </button>
                        </form>
                    @else
                        <form class="ml-2 d-inline" action="/create-follow/{{ $sharedData['username'] }}" method="POST">
                            @csrf
                            <button class="btn btn-sm" style="background-color: #f9322c; border-color: #f9322c; color: #fff;">
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
            <a href="/profile/{{ $sharedData['username'] }}" class="profile-nav-link nav-item nav-link {{ request()->is('profile/' . $sharedData['username']) ? 'active' : '' }}">Posts: {{ $sharedData['postCount'] }}</a>
            <a href="/profile/{{ $sharedData['username'] }}/followers" class="profile-nav-link nav-item nav-link {{ request()->is('profile/' . $sharedData['username'] . '/followers') ? 'active' : '' }}">Followers: {{ $sharedData['followerCount'] }}</a>
            <a href="/profile/{{ $sharedData['username'] }}/following" class="profile-nav-link nav-item nav-link {{ request()->is('profile/' . $sharedData['username'] . '/following') ? 'active' : '' }}">Following: {{ $sharedData['followingCount'] }}</a>
        </div>
        <div class="profile-slot-content">
            {{ $slot }}
        </div>
    </div>
</x-layout>