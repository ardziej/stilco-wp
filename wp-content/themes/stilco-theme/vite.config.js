import { defineConfig } from 'vite';

export default defineConfig({
  build: {
    outDir: 'dist',
    emptyOutDir: true,
    manifest: true,
    rollupOptions: {
      input: {
        main: './assets/js/app.js',
        style: './assets/css/app.css',
      },
    },
  },
});
