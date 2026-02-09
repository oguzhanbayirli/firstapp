<div>
    @auth
    <span class="chat-count-badge {{ $unreadCount > 0 ? 'chat-count-badge--is-visible' : '' }}">
        {{ $unreadCount > 0 ? $unreadCount : '' }}
    </span>

    <div id="chat-wrapper" class="chat-wrapper shadow border-top border-left border-right {{ $isVisible ? 'chat--visible' : '' }}">
        <div class="chat-title-bar">
            Chat 
            <span class="chat-title-bar-close" wire:click="closeChat">
                <i class="fas fa-times-circle"></i>
            </span>
        </div>
        
        <div id="chat" class="chat-log" wire:ignore>
            @foreach($messages as $msg)
                @if($msg['isOwn'])
                    <div class="chat-self">
                        <div class="chat-message">
                            <div class="chat-message-inner">{{ $msg['message'] }}</div>
                        </div>
                        <img class="chat-avatar avatar-tiny" src="{{ $msg['avatar'] }}" alt="You" loading="lazy">
                    </div>
                @else
                    <div class="chat-other">
                        <a href="/profile/{{ $msg['username'] }}">
                            <img class="avatar-tiny" src="{{ $msg['avatar'] }}" alt="{{ $msg['username'] }}" loading="lazy">
                        </a>
                        <div class="chat-message">
                            <div class="chat-message-inner">
                                <a href="/profile/{{ $msg['username'] }}">
                                    <strong>{{ $msg['username'] }}:</strong>
                                </a>
                                {{ $msg['message'] }}
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        
        <form wire:submit.prevent="sendMessage" class="chat-form border-top">
            <input 
                type="text" 
                class="chat-field" 
                wire:model="message"
                placeholder="Type a message..." 
                autocomplete="off"
            >
        </form>
    </div>
    @endauth

    @script
    <script>
        const chatLog = document.getElementById('chat');
        
        function scrollToBottom() {
            if (chatLog) {
                chatLog.scrollTop = chatLog.scrollHeight;
            }
        }
        
        $wire.on('message-sent', () => {
            scrollToBottom();
        });
        
        $wire.on('message-received', () => {
            scrollToBottom();
        });
        
        $wire.on('chat-opened', () => {
            scrollToBottom();
            const chatField = document.querySelector('.chat-field');
            if (chatField) {
                chatField.focus();
            }
        });
        
        setTimeout(scrollToBottom, 100);
    </script>
    @endscript
</div>
