<?php

namespace App\Services;

use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class FollowService
{
    /**
     * Create a follow relationship between two users
     */
    public function follow(User $follower, User $userToFollow): bool
    {
        // Prevent self-following
        if ($follower->id === $userToFollow->id) {
            return false;
        }

        // Check if already following
        if ($this->isFollowing($follower, $userToFollow)) {
            return false;
        }

        // Create follow relationship
        Follow::create([
            'user_id' => $follower->id,
            'followeduser' => $userToFollow->id,
        ]);

        // Clear cache
        $this->clearFollowCache($follower);
        $this->clearFollowCache($userToFollow);

        return true;
    }

    /**
     * Remove a follow relationship between two users
     */
    public function unfollow(User $follower, User $userToUnfollow): bool
    {
        $deleted = Follow::where('user_id', $follower->id)
            ->where('followeduser', $userToUnfollow->id)
            ->delete();

        if ($deleted) {
            // Clear cache
            $this->clearFollowCache($follower);
            $this->clearFollowCache($userToUnfollow);
            return true;
        }

        return false;
    }

    /**
     * Check if one user is following another
     */
    public function isFollowing(User $follower, User $user): bool
    {
        return Follow::where('user_id', $follower->id)
            ->where('followeduser', $user->id)
            ->exists();
    }

    /**
     * Get followers count for a user
     */
    public function getFollowersCount(User $user): int
    {
        return Cache::remember(
            "user.{$user->id}.followers_count",
            now()->addHours(2),
            fn() => Follow::where('followeduser', $user->id)->count()
        );
    }

    /**
     * Get following count for a user
     */
    public function getFollowingCount(User $user): int
    {
        return Cache::remember(
            "user.{$user->id}.following_count",
            now()->addHours(2),
            fn() => Follow::where('user_id', $user->id)->count()
        );
    }

    /**
     * Get paginated followers for a user
     *
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFollowers(User $user, int $perPage = 10)
    {
        return Follow::where('followeduser', $user->id)
            ->with('userDoingTheFollowing')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get paginated following for a user
     *
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getFollowing(User $user, int $perPage = 10)
    {
        return Follow::where('user_id', $user->id)
            ->with('userBeingFollowed')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Clear follow-related cache for a user
     */
    public function clearFollowCache(User $user): void
    {
        Cache::forget("user.{$user->id}.followers_count");
        Cache::forget("user.{$user->id}.following_count");
    }
}
