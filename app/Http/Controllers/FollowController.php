<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\FollowService;
use Illuminate\Support\Facades\Auth;

class FollowController extends Controller
{
    public function __construct(
        protected FollowService $followService
    ) {}

    /**
     * Create follow relationship between users
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function createFollow(User $user)
    {
        /** @var User $loggedInUser */
        $loggedInUser = Auth::user();

        $success = $this->followService->follow($loggedInUser, $user);

        if (!$success) {
            return back()->with('failure', 'Unable to follow this user.');
        }

        return back()->with('success', "You are now following {$user->username}.");
    }

    /**
     * Remove follow relationship between users
     *
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function removeFollow(User $user)
    {
        /** @var User $loggedInUser */
        $loggedInUser = Auth::user();

        $success = $this->followService->unfollow($loggedInUser, $user);

        if (!$success) {
            return back()->with('failure', 'Unable to unfollow this user.');
        }

        return back()->with('success', "You have unfollowed {$user->username}.");
    }
}
