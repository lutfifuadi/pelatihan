import './bootstrap';
/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

// Alpine.js initialization
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

import './notification-bell';
import './foto-capture';
