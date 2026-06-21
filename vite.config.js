import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { glob } from 'glob';
import path from 'path';
import iconsPlugin from './vite.icons.plugin.js';

/**
 * Get Files from a directory
 * @param {string} query
 * @returns array
 */
function GetFilesArray(query) {
  return glob.sync(query);
}

// Page JS Files
const pageJsFiles = GetFilesArray('resources/assets/js/*.js');

// Processing Vendor JS Files
const vendorJsFiles = GetFilesArray('resources/assets/vendor/js/*.js');

// Processing Libs JS Files
const LibsJsFiles = GetFilesArray('resources/assets/vendor/libs/**/*.js');

// Processing Libs Scss & Css Files
const LibsScssFiles = GetFilesArray('resources/assets/vendor/libs/**/!(_)*.scss');
const LibsCssFiles = GetFilesArray('resources/assets/vendor/libs/**/*.css');

// Processing Core, Themes & Pages Scss Files
const CoreScssFiles = GetFilesArray('resources/assets/vendor/scss/**/!(_)*.scss');

// Processing Fonts Scss & JS Files
const FontsScssFiles = GetFilesArray('resources/assets/vendor/fonts/!(_)*.scss');
const FontsJsFiles = GetFilesArray('resources/assets/vendor/fonts/**/!(_)*.js');
const FontsCssFiles = GetFilesArray('resources/assets/vendor/fonts/**/!(_)*.css');

// Processing Window Assignment for Libs like jKanban, pdfMake
function libsWindowAssignment() {
  return {
    name: 'libsWindowAssignment',

    transform(src, id) {
      if (id.includes('jkanban.js')) {
        return src.replace('this.jKanban', 'window.jKanban');
      } else if (id.includes('vfs_fonts')) {
        return src.replaceAll('this.pdfMake', 'window.pdfMake');
      }
    }
  };
}

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/assets/css/demo.css',
        'resources/js/app.js',
        ...pageJsFiles,
        ...vendorJsFiles,
        ...LibsJsFiles,
        'resources/js/laravel-user-management.js', // Processing Laravel User Management CRUD JS File
        ...CoreScssFiles,
        ...LibsScssFiles,
        ...LibsCssFiles,
        ...FontsScssFiles,
        ...FontsJsFiles,
        ...FontsCssFiles
      ],
      refresh: true
    }),
    libsWindowAssignment(),
    iconsPlugin()
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, 'resources')
    }
  },
  json: {
    stringify: true // Helps with JSON import compatibility
  },
  build: {
    commonjsOptions: {
      include: [/node_modules/] // Helps with importing CommonJS modules
    },
    rollupOptions: {
      output: {
        // Code splitting: vendor chunks terpisah agar lebih cacheable
        manualChunks(id) {
          // Pisahkan vendor/library besar ke chunk terpisah
          if (id.includes('node_modules')) {
            // Vendor utama yang sering berubah
            // Hanya Bootstrap core + Popper; wrapper CJS seperti datatables.net-bs5,
            // bootstrap-select, bootstrap-daterangeparker, dan plugin Bootstrap lainnya
            // harus masuk ke chunk lain untuk menghindari circular chunk.
            if (id.includes('/bootstrap/') || id.includes('@popperjs/core')) {
              return 'vendor-bootstrap';
            }
            if (id.includes('chart.js') || id.includes('apexcharts')) {
              return 'vendor-charts';
            }
            if (id.includes('flatpickr') || id.includes('daterangepicker')) {
              return 'vendor-datepicker';
            }
            if (id.includes('sweetalert2') || id.includes('notyf') || id.includes('toastr')) {
              return 'vendor-notifications';
            }
            if (id.includes('select2') || id.includes('tagify')) {
              return 'vendor-forms';
            }
            // Vendor sisanya digabung
            return 'vendor-other';
          }
        }
      }
    }
  }
});
