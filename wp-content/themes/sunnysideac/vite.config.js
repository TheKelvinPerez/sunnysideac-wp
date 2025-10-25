import { defineConfig } from 'vite';
import path from 'path';

export default defineConfig({
  build: {
    manifest: true,
    rollupOptions: {
      input: {
        main: path.resolve(__dirname, 'src/main.js'),
      },
    },
    outDir: 'dist',
    assetsDir: 'assets',
    emptyOutDir: true,
    // Simple asset naming
    assetFileNames: (assetInfo) => {
      const info = assetInfo.name.split('.');
      const ext = info[info.length - 1];
      return `assets/${ext}/[name].[hash].[ext]`;
    }
  },
  server: {
    host: '0.0.0.0',
    port: 3000,
    strictPort: true,
    cors: true,
    hmr: {
      protocol: 'https',
      host: 'sunnyside-ac.ddev.site',
      clientPort: 3001,
    },
    watch: {
      usePolling: true,
      interval: 1000,
      included: ['**/*.php', '**/*.js', '**/*.css', '**/*.html'],
      excluded: ['**/node_modules/**', '**/dist/**'],
    },
  },
});