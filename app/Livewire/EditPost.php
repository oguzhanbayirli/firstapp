<?php

namespace App\Livewire;

use App\Models\Post;
use App\Services\PostService;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class EditPost extends Component
{
    use AuthorizesRequests;

    public $post;
    public $title = '';
    public $body = '';
    public $isLoading = false;
    public $errorMessage = '';

    public function mount(Post $post)
    {
        $this->authorize('update', $post);

        $this->post = $post;
        $this->title = $post->title;
        $this->body = $post->body;
    }

    public function rules()
    {
        return [
            'title' => 'required|string|max:255|min:3',
            'body' => 'required|string|max:10000|min:10',
        ];
    }

    public function save(PostService $postService)
    {
        $this->authorize('update', $this->post);
        $this->validate();

        $this->isLoading = true;
        $this->errorMessage = '';

        try {
            $postService->updatePost($this->post, [
                'title' => $this->title,
                'body' => $this->body,
            ]);

            session()->flash('success', 'Post updated successfully!');
            return $this->redirect('/post/' . $this->post->id, navigate: true);
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to update post: ' . $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.edit-post');
    }
}
