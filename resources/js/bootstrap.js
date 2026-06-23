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
  const config = window.reverbConfig;

  if (!config || !config.key) {
    console.warn('Echo/Reverb not initialized: reverbConfig or reverbConfig.key is missing or empty.');
  } else {
    (async () => {
      const Echo = (await import('laravel-echo')).default;
      const Pusher = (await import('pusher-js')).default;

      window.Pusher = Pusher;

      const host = config.host || window.location.hostname;
      const port = config.port || 80;
      const scheme = config.scheme || 'https';
      const isSecure = scheme === 'https';

      window.Echo = new Echo({
        broadcaster: 'reverb',
        key: config.key,
        wsHost: host,
        wsPort: isSecure ? 80 : port,
        wssPort: isSecure ? port : 443,
        forceTLS: isSecure,
        enabledTransports: ['ws', 'wss'],
      });

      // Dispatch event that Echo is loaded and ready
      document.dispatchEvent(new CustomEvent('echo:ready'));
    })();
  }
}

