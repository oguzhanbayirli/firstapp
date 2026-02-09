<?php

namespace App\Services;

use App\Mail\NewFollowerEmail;
use App\Models\Follow;
use App\Models\User;
use Illuminate\Support\Facades\Mail;

class FollowService
{
    public function __construct(protected CacheService $cache) {}

    /**
     * Create a follow relationship between two users
     */
    public function follow(User $follower, User $userToFollow): bool
    {
        if ($follower->id === $userToFollow->id || $this->isFollowing($follower, $userToFollow)) {
            return false;
        }

        Follow::create(['user_id' => $follower->id, 'followed_user_id' => $userToFollow->id]);
        $this->clearCaches($follower->id, $userToFollow->id);
        Mail::to($userToFollow->email)->send(new NewFollowerEmail($follower, $userToFollow));
        return true;
    }

    /**
     * Remove a follow relationship between two users
     */
    public function unfollow(User $follower, User $userToUnfollow): bool
    {
        $deleted = Follow::where('user_id', $follower->id)
            ->where('followed_user_id', $userToUnfollow->id)
            ->delete();

        if ($deleted) {
            $this->clearCaches($follower->id, $userToUnfollow->id);
        }

        return (bool) $deleted;
    }

    /**
     * Clear cache for follow relationships
     */
    protected function clearCaches(int $followerId, int $followedId): void
    {
        $this->cache->clearFollowCache($followerId);
        $this->cache->clearFollowCache($followedId);
    }

    /**
     * Check if one user is following another
     */
    public function isFollowing(User $follower, User $user): bool
    {
        return Follow::where('user_id', $follower->id)
            ->where('followed_user_id', $user->id)
            ->exists();
    }

    /**
     * Get followers count for a user (cached)
     */
    public function getFollowersCount(User $user): int
    {
        return $this->cache->getFollowersCount(
            $user->id, 
            fn() => Follow::where('followed_user_id', $user->id)->count()
        );
    }

    /**
     * Get following count for a user (cached)
     */
    public function getFollowingCount(User $user): int
    {
        return $this->cache->getFollowingCount(
            $user->id,
            fn() => Follow::where('user_id', $user->id)->count()
        );
    }

    /**
     * Get paginated followers for a user
     */
    public function getFollowers(User $user, int $perPage = 10)
    {
        return Follow::where('followed_user_id', $user->id)
            ->with('userDoingTheFollowing')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get paginated following for a user
     */
    public function getFollowing(User $user, int $perPage = 10)
    {
        return Follow::where('user_id', $user->id)
            ->with('userBeingFollowed')
            ->latest()
            ->paginate($perPage);
    }
}
