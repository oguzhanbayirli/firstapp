export default class Chat {
    constructor() {
        this.chatWrapper = document.querySelector('#chat-wrapper');
        this.openIcon = document.querySelector('.header-chat-icon');
        this.injectHTML();
        this.chatLog = document.querySelector('#chat');
        this.chatField = document.querySelector('#chatField');
        this.chatForm = document.querySelector('#chatForm');
        this.closeIcon = document.querySelector('.chat-title-bar-close');
        this.unreadBadge = document.querySelector('.chat-count-badge');
        this.isVisible = false;
        this.unreadCount = 0;
        this.events();
        this.loadMessages(); // Load saved messages
        this.openConnection();
    }

    events() {
        this.openIcon.addEventListener('click', () => this.toggleChat());
        this.closeIcon.addEventListener('click', () => this.hideChat());
        this.chatForm.addEventListener('submit', (e) => {
            e.preventDefault();
            this.sendMessage();
        });
        // Enter tuşu ile de gönder
        this.chatField.addEventListener('keypress', (e) => {
            if (e.key === 'Enter' && !e.shiftKey) {
                e.preventDefault();
                this.sendMessage();
            }
        });
    }

    toggleChat() {
        if (this.isVisible) {
            this.hideChat();
            return;
        }
        this.showChat();
    }

    showChat() {
        this.isVisible = true;
        this.chatWrapper.classList.add('chat--visible');
        this.chatField.focus();
        // Okunmamış mesaj sayısını sıfırla
        this.unreadCount = 0;
        this.unreadBadge.classList.remove('chat-count-badge--is-visible');
        this.unreadBadge.textContent = '';
    }

    hideChat() {
        this.isVisible = false;
        this.chatWrapper.classList.remove('chat--visible');
    }

    openConnection() {
        window.Echo.private('chat')
            .listen('.message', (data) => {
                this.displayMessageFromOther(data);
                // Chat kapalıysa bildirim göster
                if (!this.isVisible) {
                    this.unreadCount++;
                    this.unreadBadge.textContent = this.unreadCount;
                    this.unreadBadge.classList.add('chat-count-badge--is-visible');
                }
            });
    }

    sendMessage() {
        const message = this.chatField.value.trim();
        if (!message) return;

        // Kendi mesajını hemen göster
        this.chatLog.insertAdjacentHTML('beforeend', `
            <div class="chat-self">
                <div class="chat-message">
                    <div class="chat-message-inner">${this.sanitizeHTML(message)}</div>
                </div>
                <img class="chat-avatar avatar-tiny" src="${this.avatar}">
            </div>
        `);
        this.chatLog.scrollTop = this.chatLog.scrollHeight;
        this.chatField.value = '';
        this.chatField.focus();

        // Sunucuya gönder
        fetch('/chat', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'X-Socket-ID': window.Echo.socketId(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: message })
        })
        .then(() => this.saveMessages())
        .catch(error => console.error('Chat error:', error));
    }

    displayMessageFromOther(data) {
        this.chatLog.insertAdjacentHTML('beforeend', `
            <div class="chat-other">
                <a href="/profile/${this.sanitizeHTML(data.username)}"><img class="avatar-tiny" src="${this.sanitizeHTML(data.avatar)}"></a>
                <div class="chat-message">
                    <div class="chat-message-inner">
                        <a href="/profile/${this.sanitizeHTML(data.username)}"><strong>${this.sanitizeHTML(data.username)}:</strong></a>
                        ${this.sanitizeHTML(data.message)}
                    </div>
                </div>
            </div>
        `);
        this.chatLog.scrollTop = this.chatLog.scrollHeight;
        this.saveMessages();
    }

    saveMessages() {
        const messages = [];
        document.querySelectorAll('.chat-self, .chat-other').forEach(el => {
            const isOwn = el.classList.contains('chat-self');
            const messageInner = el.querySelector('.chat-message-inner');
            
            let messageText, username, avatar;
            
            if (isOwn) {
                // Kendi mesajı - sadece içeriği al
                messageText = messageInner.textContent;
                username = 'Siz';
                avatar = el.querySelector('img').src;
            } else {
                // Diğer mesajı - username ve mesajı ayır
                const usernameEl = messageInner.querySelector('a strong');
                username = usernameEl ? usernameEl.textContent.replace(':', '') : 'Bilinmiyor';
                
                // Sadece mesaj kısmını al (username hariç)
                const allText = messageInner.textContent;
                messageText = allText.replace(username + ':', '').trim();
                
                avatar = el.querySelector('img').src;
            }
            
            messages.push({
                isOwn,
                username,
                message: messageText,
                avatar
            });
        });
        localStorage.setItem('chatMessages', JSON.stringify(messages));
    }

    loadMessages() {
        const saved = localStorage.getItem('chatMessages');
        if (saved) {
            try {
                const messages = JSON.parse(saved);
                messages.forEach(msg => {
                    if (msg.isOwn) {
                        this.chatLog.insertAdjacentHTML('beforeend', `
                            <div class="chat-self">
                                <div class="chat-message">
                                    <div class="chat-message-inner">${this.sanitizeHTML(msg.message)}</div>
                                </div>
                                <img class="chat-avatar avatar-tiny" src="${this.sanitizeHTML(msg.avatar)}">
                            </div>
                        `);
                    } else {
                        this.chatLog.insertAdjacentHTML('beforeend', `
                            <div class="chat-other">
                                <a href="/profile/${this.sanitizeHTML(msg.username)}"><img class="avatar-tiny" src="${this.sanitizeHTML(msg.avatar)}"></a>
                                <div class="chat-message">
                                    <div class="chat-message-inner">
                                        <a href="/profile/${this.sanitizeHTML(msg.username)}"><strong>${this.sanitizeHTML(msg.username)}:</strong></a>
                                        ${this.sanitizeHTML(msg.message)}
                                    </div>
                                </div>
                            </div>
                        `);
                    }
                });
                this.chatLog.scrollTop = this.chatLog.scrollHeight;
            } catch (e) {
                console.error('Mesajlar yüklenirken hata:', e);
            }
        }
    }

    sanitizeHTML(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }

    get avatar() {
        return document.querySelector('.header-bar img')?.src || '/fallback-avatar.jpg';
    }

    injectHTML() {
        this.chatWrapper.innerHTML = `
            <div class="chat-title-bar">Chat <span class="chat-title-bar-close"><i class="fas fa-times-circle"></i></span></div>
            <div id="chat" class="chat-log"></div>
            <form id="chatForm" class="chat-form border-top">
                <input type="text" class="chat-field" id="chatField" placeholder="Type a message..." autocomplete="off">
            </form>
        `;
    }
}
