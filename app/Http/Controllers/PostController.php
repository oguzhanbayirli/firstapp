<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class PostController extends Controller
{
    /**
     * Rate limit - Allow 30 searches per minute
     *
     * @return array|null Error array if limit exceeded, null otherwise
     */
    private function checkSearchRateLimit(): ?array
    {
        $key = 'search:' . Auth::id();
        
        $limit = RateLimiter::attempt(
            $key,
            $perMinute = 30,
            fn() => null
        );

        if (!$limit) {
            return [
                'error' => 'Too many search requests. Please try again later.',
                'retry_after' => RateLimiter::availableIn($key)
            ];
        }

        return null;
    }
    /**
     * Show create post form
     *
     * @return \Illuminate\View\View
     */
    public function showCreateForm()
    {
        return view('create-post');
    }

    /**
     * Display single post with formatted content
     *
     * @param Post $post
     * @return \Illuminate\View\View
     */
    public function showSinglePost(Post $post)
    {
        $post->load('user');
        $post->body = strip_tags(
            Str::markdown($post->body),
            '<p><a><strong><em><ul><ol><li><br>'
        );
        return view('single-post', ['post' => $post]);
    }

    /**
     * Store new post in database
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function storeNewPost(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:100',
            'body' => 'required|string|min:10|max:1000',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => trim(strip_tags($validated['title'])),
            'body' => trim(strip_tags($validated['body'])),
        ]);

        return redirect("/post/{$post->id}")
            ->with('success', 'Your post has been created.');
    }

    /**
     * Show edit form for post
     *
     * @param Post $post
     * @return \Illuminate\View\View
     */
    public function showEditForm(Post $post)
    {
        return view('edit-post', ['post' => $post]);
    }

    /**
     * Update post in database
     *
     * @param Post $post
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Post $post, Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|min:3|max:100',
            'body' => 'required|string|min:10|max:1000',
        ]);

        $post->update([
            'title' => trim(strip_tags($validated['title'])),
            'body' => trim(strip_tags($validated['body'])),
        ]);

        return back()->with('success', 'Post successfully updated.');
    }

    /**
     * Delete post from database
     *
     * @param Post $post
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Post $post)
    {
        /** @var User $user */
        $user = Auth::user();
        $username = $user->username;
        
        $post->delete();

        return redirect("/profile/{$username}")
            ->with('success', 'Post successfully deleted.');
    }

    /**
     * Search posts by title and body with rate limiting
     *
     * @param string $query
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(string $query)
    {
        // Check rate limit for authenticated users
        if (Auth::check()) {
            $rateLimitError = $this->checkSearchRateLimit();
            if ($rateLimitError) {
                return response()->json($rateLimitError, 429);
            }
        }

        $query = trim(urldecode($query));

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        // Sanitize query to prevent SQL injection
        $sanitizedQuery = strip_tags($query);

        $posts = Post::select('id', 'title', 'body', 'user_id', 'created_at')
            ->with('user:id,username,avatar')
            ->where('title', 'LIKE', "%{$sanitizedQuery}%")
            ->orWhere('body', 'LIKE', "%{$sanitizedQuery}%")
            ->latest()
            ->limit(10)
            ->get();

        return response()->json($posts);
    }
}
