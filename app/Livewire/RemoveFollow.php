<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\FollowService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class RemoveFollow extends Component
{
    public $username;
    public $isLoading = false;
    public $errorMessage = '';

    public function mount($username = null)
    {
        if (empty($username)) {
            abort(404);
        }
        $this->username = $username;
    }

    public function unfollow(FollowService $followService)
    {
        if (!Auth::check()) {
            $this->dispatch('auth_required');
            return;
        }

        $this->isLoading = true;

        $userToUnfollow = User::where('username', $this->username)->first();

        if (!$userToUnfollow) {
            $this->errorMessage = 'User not found.';
            $this->isLoading = false;
            return;
        }

        $result = $followService->unfollow(Auth::user(), $userToUnfollow);

        if ($result) {
            session()->flash('success', 'Successfully unfollowed ' . $this->username);
            return $this->redirect('/profile/' . $this->username, navigate: true);
        } else {
            $this->errorMessage = 'Could not unfollow this user.';
        }

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.remove-follow');
    }
}
