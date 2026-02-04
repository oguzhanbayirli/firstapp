document.addEventListener('DOMContentLoaded', () => {
  if (typeof window.$ !== 'undefined' && typeof window.$.fn?.tooltip === 'function') {
    window.$('[data-toggle="tooltip"]').tooltip();
  }
});
