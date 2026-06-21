import './bootstrap';
/*
  Add custom scripts here
*/
import.meta.glob([
  '../assets/img/**',
  // '../assets/json/**',
  '../assets/vendor/fonts/**'
]);

import './notification-bell';

// Alpine.js
import Alpine from 'alpinejs';
window.Alpine = Alpine;

// Dispatch alpine:init manually since Alpine ESM doesn't do it automatically
document.dispatchEvent(new CustomEvent('alpine:init'));

Alpine.start();
