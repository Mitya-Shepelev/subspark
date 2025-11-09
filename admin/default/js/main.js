window.onload = () => {
  'use strict';

  if ('serviceWorker' in navigator) {
    try {
      const root = (window.siteRoot || '/');
      const url = root.replace(/\/?$/, '/') + 'sw.js';
      navigator.serviceWorker.register(url);
    } catch (e) {}
  }
}
