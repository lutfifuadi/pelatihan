import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

// Only initialize Echo if broadcast is enabled and app key is configured
if (window.broadcastEnabled === '1') {
  const appKey = import.meta.env.VITE_REVERB_APP_KEY;

  if (!appKey) {
    console.warn('Echo/Reverb not initialized: VITE_REVERB_APP_KEY is not set in .env');
  } else {
    (async () => {
      const Echo = (await import('laravel-echo')).default;
      const Pusher = (await import('pusher-js')).default;

      window.Pusher = Pusher;

      window.Echo = new Echo({
        broadcaster: 'reverb',
        key: appKey,
        wsHost: import.meta.env.VITE_REVERB_HOST ?? window.location.hostname,
        wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
        wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
        forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
        enabledTransports: ['ws', 'wss'],
      });

      // Dispatch event that Echo is loaded and ready
      document.dispatchEvent(new CustomEvent('echo:ready'));
    })();
  }
}

