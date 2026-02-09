import axios from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

// Setup
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.Pusher = Pusher;
window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
});

// Listen for chat messages
Echo.private('chat').listen('message', (data) => {
    // Dispatch event to Livewire component
    Livewire.dispatch('chatMessageReceived', [data.username, data.message, data.avatar]);
});


// Init
document.addEventListener('DOMContentLoaded', () => {
    // Tooltips
    if (window.$?.fn?.tooltip) $('[data-toggle="tooltip"]').tooltip();
    
    // Avatar preview
    const avatarInput = document.getElementById('avatar');
    const preview = document.getElementById('preview');
    const fileLabel = document.getElementById('file-label');
    
    if (avatarInput && preview && fileLabel) {
        avatarInput.addEventListener('change', (e) => {
            const file = e.target.files?.[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => preview.src = ev.target.result;
            reader.readAsDataURL(file);
            fileLabel.innerHTML = `<i class="fas fa-check-circle mr-1 text-success"></i>Selected: ${file.name}`;
        });
    }
    

});
