<?php

namespace App\Livewire;

use App\Events\ChatMessage;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;

class Chat extends Component
{
    public $message = '';
    public $messages = [];
    public $isVisible = false;
    public $unreadCount = 0;

    public function mount()
    {
        $this->loadMessages();
    }

    #[On('toggleChat')]
    public function toggleChat()
    {
        $this->isVisible = !$this->isVisible;
        
        if ($this->isVisible) {
            $this->unreadCount = 0;
            $this->dispatch('chat-opened');
        }
    }

    public function closeChat()
    {
        $this->isVisible = false;
    }

    public function sendMessage()
    {
        $this->validate(['message' => 'required|string|max:1000']);

        $sanitized = strip_tags(trim($this->message));
        if (empty($sanitized)) {
            $this->message = '';
            return;
        }

        $user = Auth::user();
        $this->addMessage(true, $user->username, $sanitized, $user->avatar);
        broadcast(new ChatMessage($user->username, $sanitized, $user->avatar))->toOthers();
        $this->message = '';
        $this->dispatch('message-sent');
    }

    #[On('chatMessageReceived')]
    public function receiveMessage($username, $message, $avatar)
    {
        $this->addMessage(false, $username, $message, $avatar);
        if (!$this->isVisible) $this->unreadCount++;
        $this->dispatch('message-received');
    }

    private function addMessage(bool $isOwn, string $username, string $message, string $avatar)
    {
        $this->messages[] = compact('isOwn', 'username', 'message', 'avatar');
        $this->saveMessages();
    }

    private function loadMessages()
    {
        $saved = session('chatMessages', []);
        if (!empty($saved)) {
            $this->messages = $saved;
        }
    }

    private function saveMessages()
    {
        session(['chatMessages' => $this->messages]);
    }

    public function render()
    {
        return view('livewire.chat');
    }
}


