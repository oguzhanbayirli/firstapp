<?php

namespace App\Services;

use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Gate;

class PostService
{
    /**
     * Create a new post
     */
    public function createPost(User $user, array $data): Post
    {
        $data['title'] = strip_tags($data['title']);
        $data['body'] = strip_tags($data['body']);
        $data['user_id'] = $user->id;

        return Post::create($data);
    }

    /**
     * Update an existing post
     */
    public function updatePost(Post $post, array $data): bool
    {
        // Authorization check should be done in controller
        $post->title = strip_tags($data['title']);
        $post->body = strip_tags($data['body']);

        return $post->save();
    }

    /**
     * Delete a post
     */
    public function deletePost(Post $post): bool
    {
        // Authorization check should be done in controller
        return $post->delete();
    }

    /**
     * Search posts by query
     *
     * @param string $query
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchPosts(string $query)
    {
        $query = strip_tags($query);
        
        if (empty(trim($query))) {
            return collect([]);
        }
        
        return Post::search($query)
            ->with('user:id,username,avatar')
            ->get();
    }

    /**
     * Get posts for home feed
     *
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getHomeFeed(User $user, int $perPage = 10)
    {
        // Get IDs of users that the current user follows
        $followingIds = $user->followingTheseUsers()
            ->pluck('followeduser')
            ->toArray();

        // Include current user's ID to show their own posts
        $userIds = array_merge($followingIds, [$user->id]);

        return Post::whereIn('user_id', $userIds)
            ->with('user:id,username,avatar')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Get posts by a specific user
     *
     * @param User $user
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUserPosts(User $user, int $perPage = 10)
    {
        return Post::where('user_id', $user->id)
            ->with('user:id,username,avatar')
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Check if user can modify post
     */
    public function canModify(User $user, Post $post): bool
    {
        return Gate::forUser($user)->allows('update', $post);
    }
}
