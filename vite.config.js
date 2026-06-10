import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [
    laravel({
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
        'resources/js/transport-qr-seller.js',
        'resources/js/transport-qr-scanner.js',
      ],
      refresh: true,
    }),
  ],
});
