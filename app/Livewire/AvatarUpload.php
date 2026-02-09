<?php

namespace App\Livewire;

use App\Services\UserService;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class AvatarUpload extends Component
{
    use WithFileUploads;

    public $avatar;
    public $isLoading = false;
    public $successMessage = '';
    public $errorMessage = '';

    public function rules()
    {
        return ['avatar' => 'required|image|mimes:jpeg,png,gif|max:10240'];
    }

    public function upload(UserService $userService)
    {
        $this->validate();
        $this->isLoading = true;

        try {
            $userService->uploadAvatar(Auth::user(), $this->avatar);
            session()->flash('success', 'Avatar uploaded successfully!');
            return $this->redirect('/profile/' . Auth::user()->username, navigate: true);
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to upload avatar: ' . $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.avatar-upload');
    }
}
