<?php

namespace App\Livewire;

use App\Models\Post;
use Livewire\Component;
use Livewire\Attributes\On;

class Search extends Component
{
    public $query = '';
    public $results = [];
    public $isOpen = false;

    /**
     * Updated on query change - debounced
     */
    public function updatedQuery()
    {
        if (strlen(trim($this->query)) < 2) {
            $this->results = [];
            return;
        }

        $sanitized = strip_tags($this->query);

        $this->results = Post::select('id', 'title', 'body', 'user_id', 'created_at')
            ->with('user:id,username,avatar')
            ->where('title', 'LIKE', "%{$sanitized}%")
            ->orWhere('body', 'LIKE', "%{$sanitized}%")
            ->latest()
            ->limit(10)
            ->get();
    }

    #[On('openSearch')]
    public function openSearch()
    {
        $this->isOpen = true;
        $this->dispatch('focus-search-input');
    }

    public function closeSearch()
    {
        $this->isOpen = false;
        $this->query = '';
        $this->results = [];
    }

    public function render()
    {
        return view('livewire.search');
    }
}
