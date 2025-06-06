import { defineConfig } from 'vite';
import vue from '@vitejs/plugin-vue';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
  plugins: [
    vue(),
    laravel({
        input: 'resources/js/app.js',
        refresh: true,
    }),
  ],
  resolve: {
    extensions: ['.js', '.vue', '.json'],
  },
  build: {
    outDir: 'public/build',
  },
});
