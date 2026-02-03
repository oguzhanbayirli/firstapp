<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    /**
     * Create follow relationship between users
     */
    public function createFollow(User $user)
    {
        /** @var User $loggedInUser */
        $loggedInUser = Auth::user();

        if ($loggedInUser->id === $user->id) {
            return redirect("/profile/{$user->username}")
                ->with('failure', 'You cannot follow yourself.');
        }

        if ($loggedInUser->isFollowing($user)) {
            return redirect("/profile/{$user->username}")
                ->with('failure', 'You are already following this user.');
        }

        $loggedInUser->following()->attach($user->id);
        
        // Clear cache for both users
        $loggedInUser->clearFollowCache();
        $user->clearFollowCache();

        return redirect("/profile/{$user->username}")
            ->with('success', "You are now following {$user->username}.");
    }

    /**
     * Remove follow relationship between users
     */
    public function removeFollow(User $user)
    {
        /** @var User $loggedInUser */
        $loggedInUser = Auth::user();

        if (!$loggedInUser->isFollowing($user)) {
            return redirect("/profile/{$user->username}")
                ->with('failure', 'You are not following this user.');
        }

        $loggedInUser->following()->detach($user->id);
        
        // Clear cache for both users
        $loggedInUser->clearFollowCache();
        $user->clearFollowCache();

        return redirect("/profile/{$user->username}")
            ->with('success', "You have unfollowed {$user->username}.");
    }

}
