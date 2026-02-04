<?php

namespace App\Http\Controllers;

use App\Events\FirstExampleEvent;
use App\Models\User;
use App\Models\Post;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Handle user registration
     */
    public function register(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|unique:users,username|min:3|max:20|alpha_dash',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:50|confirmed',
        ]);

        $user = User::create([
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'avatar' => 'default-avatar.svg',
        ]);

        Auth::login($user);

        return redirect('/')->with('success', 'Welcome to FirstApp! You have successfully registered.');
    }

    /**
     * Handle user login
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required',
        ]);

        if (Auth::attempt(
            ['username' => $credentials['loginusername'], 'password' => $credentials['loginpassword']],
            false
        )) {
            event(new FirstExampleEvent($credentials['loginusername'], 'logged in'));
            $request->session()->regenerate();
            return redirect('/')->with('success', 'You have successfully logged in.');
        }

        return redirect('/')->with('failure', 'Incorrect login information.');
    }

    /**
     * Handle user logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'You have successfully logged out.');
    }
    /**
     * Show home page based on auth status
     */
    public function showCorrectHomePage(Request $request)
    {
        if (!Auth::check()) {
            return view('home');
        }

        /** @var User $user */
        $user = Auth::user();
        $filter = $request->get('feed', 'all');

        $posts = $filter === 'following'
            ? $user->feedPosts()
                ->select('id', 'title', 'body', 'user_id', 'created_at')
                ->with('user:id,username,avatar')
                ->latest()
                ->paginate(10)
                ->withQueryString()
            : Post::select('id', 'title', 'body', 'user_id', 'created_at')
                ->with('user:id,username,avatar')
                ->latest()
                ->paginate(10)
                ->withQueryString();

        return view('home-feed', compact('posts', 'filter'));
    }

    /**
     * Display user profile with posts
     */
    public function profile(User $profile)
    {
        $data = $this->getSharedData($profile);
        $data['posts'] = $profile->posts()
            ->select('id', 'title', 'body', 'user_id', 'created_at')
            ->with('user:id,username,avatar')
            ->latest()
            ->get();

        return view('profile-posts', $data);
    }

    /**
     * Show avatar management form
     */
    public function showAvatarForm()
    {
        return view('manage-avatar');
    }

    /**
     * Store avatar file and update user
     */
    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,gif|max:5000',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return redirect('/')->with('failure', 'User not authenticated.');
        }

        $file = $request->file('avatar');
        $filename = Auth::id() . '.jpg';
        $directory = storage_path('app/public/avatars');

        // Ensure directory exists
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Store the file
        $file->move($directory, $filename);

        // Delete old avatar if different
        $oldAvatar = $user->getRawOriginal('avatar');
        if ($oldAvatar && $oldAvatar !== 'default-avatar.svg' && $oldAvatar !== $filename) {
            $oldPath = $directory . '/' . $oldAvatar;
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        $user->update(['avatar' => $filename]);

        return back()->with('success', 'Avatar successfully updated.');
    }

    private function getSharedData(User $profile): array
    {
        $currentlyFollowing = false;
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $currentlyFollowing = $user->isFollowing($profile);
        }

        return [
            'avatar' => $profile->avatar,
            'username' => $profile->username,
            'postCount' => $profile->posts()->count(),
            'currentlyFollowing' => $currentlyFollowing,
            'followerCount' => $profile->followerCount(),
            'followingCount' => $profile->followingCount(),
        ];
    }

    /**
     * Display user followers with pagination
     */
    public function profileFollowers(User $profile)
    {
        $data = $this->getSharedData($profile);
        $data['followers'] = $profile->followers()
            ->select('users.id', 'users.username', 'users.avatar')
            ->latest('follow.created_at')
            ->paginate(15);

        return view('profile-followers', $data);
    }

    /**
     * Display users that user is following with pagination
     */
    public function profileFollowing(User $profile)
    {
        $data = $this->getSharedData($profile);
        $data['following'] = $profile->following()
            ->select('users.id', 'users.username', 'users.avatar')
            ->latest('follow.created_at')
            ->paginate(15);

        return view('profile-following', $data);
    }
}