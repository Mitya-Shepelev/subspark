window.onload = () => {
  'use strict';

  // Register Service Worker (root scope)
  if ('serviceWorker' in navigator) {
    try {
      const root = (window.siteurl || '/');
      const url = root.replace(/\/?$/, '/') + 'sw.js';
      navigator.serviceWorker.register(url);
    } catch (e) {}
  }

  // Add to Home Screen (beforeinstallprompt) hook
  window.deferredPWAInstall = null;
  window.addEventListener('beforeinstallprompt', (e) => {
    e.preventDefault();
    window.deferredPWAInstall = e;
    // Dispatch an event so UI can show an install button if desired
    window.dispatchEvent(new Event('pwa-install-available'));
  });

  // Expose a helper to trigger the prompt from UI
  window.promptPWAInstall = async () => {
    try {
      if (!window.deferredPWAInstall) return false;
      const e = window.deferredPWAInstall;
      window.deferredPWAInstall = null;
      const { outcome } = await e.prompt();
      // Optional: notify UI of the outcome
      window.dispatchEvent(new CustomEvent('pwa-install-outcome', { detail: outcome }));
      return outcome === 'accepted';
    } catch (err) {
      return false;
    }
  };
}
