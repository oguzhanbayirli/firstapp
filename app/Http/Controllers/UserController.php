<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $incomingFields = $request->validate([
            'username' => 'required|unique:users,username|min:3|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|max:50|confirmed',
        ]);
        $incomingFields['password'] = bcrypt($incomingFields['password']);
        $incomingFields['avatar'] = 'default-avatar.svg';
        $user = User::create($incomingFields);
        Auth::login($user);
        return redirect('/')->with('success', 'Welcome to FirstApp! You have successfully registered.');
    }
    public function login(Request $request)
    {
        $incomingFields = $request->validate([
            'loginusername' => 'required',
            'loginpassword' => 'required',
        ]);
        if (Auth::attempt(['username' => $incomingFields['loginusername'], 'password' => $incomingFields['loginpassword']])) {
            $request->session()->regenerate();
            return redirect('/')->with('success', 'You have successfully logged in.');
        } else {
            return redirect('/')->with('failure', 'Incorrect login information.');
        }
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('success', 'You have successfully logged out.');
    }
    public function showCorrectHomePage(Request $request)
    {
        if (Auth::check()) {
            /** @var User $user */
            $user = Auth::user();
            $filter = $request->get('feed', 'all');
            
            if ($filter === 'following') {
                $posts = $user->feedPosts()->latest()->paginate(10)->withQueryString();
            } else {
                $posts = Post::latest()->paginate(10)->withQueryString();
            }
            
            return view('home-feed', ['posts' => $posts, 'filter' => $filter]);
        }
        return view('home');
    }
    public function profile(User $profile)
    {
        $this->getSharedData($profile);
        return view('profile-posts', ['posts' => $profile->posts()->latest()->get()]);
    }
    public function showAvatarForm()
    {
        return view('manage-avatar');
    }
    public function storeAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|max:5000',
        ]);

        /** @var User $user */
        $user = Auth::user();
        if (!$user) {
            return redirect('/')->with('failure', 'User not authenticated.');
        }

        // Get uploaded file
        $file = $request->file('avatar');
        $filename = Auth::id() . '.jpg';
        $path = storage_path('app/public/avatars/' . $filename);

        // Ensure directory exists
        if (!file_exists(storage_path('app/public/avatars'))) {
            mkdir(storage_path('app/public/avatars'), 0755, true);
        }

        // Move uploaded file
        $file->move(storage_path('app/public/avatars'), $filename);

        // Delete old avatar if exists
        $oldAvatar = $user->getRawOriginal('avatar');
        if ($oldAvatar && $oldAvatar !== $filename) {
            $oldPath = storage_path('app/public/avatars/' . $oldAvatar);
            if (file_exists($oldPath)) {
                unlink($oldPath);
            }
        }

        // Update user avatar
        $user->avatar = $filename;
        $user->save();

        return back()->with('success', 'Avatar successfully updated.');
    }
    private function getSharedData(User $profile)
    {
        $currentlyFollowing = false;
        if (Auth::check()) {
            $currentlyFollowing = Follow::where('user_id', Auth::id())->where('followed_user_id', $profile->id)->exists();
        }
        view()->share('sharedData', [
            'avatar' => $profile->avatar,
            'username' => $profile->username,
            'postCount' => $profile->posts()->count(),
            'currentlyFollowing' => $currentlyFollowing,
            'followerCount' => $profile->followers()->count(),
            'followingCount' => $profile->following()->count(),
        ]);
    }
    public function profileFollowers(User $profile)
    {
        $this->getSharedData($profile);
        return view('profile-followers', ['followers' => $profile->followers()->get()]);
    }
    public function profileFollowing(User $profile)
    {
        $this->getSharedData($profile);
        return view('profile-following', ['following' => $profile->following()->get()]);
    }
}