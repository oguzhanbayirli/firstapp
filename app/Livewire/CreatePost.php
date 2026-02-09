<?php

namespace App\Livewire;

use App\Services\PostService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CreatePost extends Component
{
    public $title = '';
    public $body = '';
    public $isLoading = false;
    public $errorMessage = '';

    public function rules()
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'body' => 'required|string|max:10000|min:10',
        ];
    }

    public function save(PostService $postService)
    {
        $this->validate();

        $this->isLoading = true;
        $this->errorMessage = '';

        try {
            $post = $postService->createPost(Auth::user(), [
                'title' => $this->title,
                'body' => $this->body,
            ]);

            session()->flash('success', 'Post created successfully!');
            return $this->redirect('/post/' . $post->id, navigate: true);
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to create post: ' . $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.create-post');
    }
}
