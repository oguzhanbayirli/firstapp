import './bootstrap';
import Search from './live-search';
import Chat from './chat';
import './manage-avatar';
import './ui';

if (document.querySelector('.header-chat-icon')) {
    new Chat();
}

if (document.querySelector('.header-search-icon')) {
    new Search();
}
