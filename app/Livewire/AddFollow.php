<?php

namespace App\Livewire;

use App\Models\User;
use App\Services\FollowService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class AddFollow extends Component
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

    public function follow(FollowService $followService)
    {
        if (!Auth::check()) {
            $this->dispatch('auth_required');
            return;
        }

        $this->isLoading = true;

        $userToFollow = User::where('username', $this->username)->first();

        if (!$userToFollow) {
            $this->errorMessage = 'User not found.';
            $this->isLoading = false;
            return;
        }

        $result = $followService->follow(Auth::user(), $userToFollow);

        if ($result) {
            session()->flash('success', 'Successfully followed ' . $this->username);
            return $this->redirect('/profile/' . $this->username, navigate: true);
        } else {
            $this->errorMessage = 'Could not follow this user. You may already be following them.';
        }

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.add-follow');
    }
}
