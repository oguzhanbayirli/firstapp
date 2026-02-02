<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Follow;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    // User cant follow themselves
    public function createFollow(Request $request, User $user)
    {
        /** @var User $loggedInUser */
        $loggedInUser = Auth::user();
        if (!$loggedInUser) {
            return redirect('/')->with('failure', 'User not authenticated.');
        }
        if ($loggedInUser->username == $user->username) {
            return redirect('/profile/' . $user->username)->with('failure', 'You cannot follow yourself.');
        }
    // User cant follow same user more than once
        $alreadyFollowing = $loggedInUser->following()->where('followed_user_id', $user->id)->exists();
        if ($alreadyFollowing) {
            return redirect('/profile/' . $user->username)->with('failure', 'You are already following this user.');
        }

        Follow::create([
            'user_id' => $loggedInUser->id,
            'followed_user_id' => $user->id,
        ]);

        return redirect('/profile/' . $user->username)->with('success', 'You are now following ' . $user->username . '.');
    }
    public function removeFollow(Request $request, User $user)
    {
        /** @var User $loggedInUser */
        $loggedInUser = Auth::user();
        if (!$loggedInUser) {
            return redirect('/')->with('failure', 'User not authenticated.');
        }
        $existingFollow = $loggedInUser->following()->where('followed_user_id', $user->id)->first();
        if (!$existingFollow) {
            return redirect('/profile/' . $user->username)->with('failure', 'You are not following this user.');
        }
        $existingFollow->delete();
        return redirect('/profile/' . $user->username)->with('success', 'You have unfollowed ' . $user->username . '.');
    }

}
