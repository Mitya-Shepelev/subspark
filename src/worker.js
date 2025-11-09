if ('serviceWorker' in navigator) {
  try {
    const root = (window.siteurl || '/');
    const url = root.replace(/\/?$/, '/') + 'sw.js';
    navigator.serviceWorker.register(url).then((registration) => {
      console.log('SW Registered!', registration);
    }).catch((error) => {
      console.log('SW Registration Failed!', error);
    });
  } catch (e) {
    console.log('SW Registration Error', e);
  }
}
