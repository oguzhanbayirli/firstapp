<?php

namespace App\Livewire;

use App\Models\Post;
use App\Services\PostService;
use Livewire\Component;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DeletePost extends Component
{
    use AuthorizesRequests;

    public $post;
    public $isLoading = false;
    public $errorMessage = '';
    public $showConfirmation = false;

    public function mount(Post $post)
    {
        $this->authorize('delete', $post);
        $this->post = $post;
    }

    public function toggleConfirmation()
    {
        $this->showConfirmation = !$this->showConfirmation;
    }

    public function delete(PostService $postService)
    {
        $this->authorize('delete', $this->post);

        $this->isLoading = true;
        $this->errorMessage = '';

        try {
            $postService->deletePost($this->post);
            session()->flash('success', 'Post deleted successfully!');
            return $this->redirect('/', navigate: true);
            
        } catch (\Exception $e) {
            $this->errorMessage = 'Failed to delete post: ' . $e->getMessage();
        }

        $this->isLoading = false;
    }

    public function render()
    {
        return view('livewire.delete-post');
    }
}
