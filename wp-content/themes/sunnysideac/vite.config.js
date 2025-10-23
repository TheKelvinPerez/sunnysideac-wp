import { defineConfig, loadEnv } from 'vite';
import path from 'path';

export default defineConfig(({ mode }) => {
  // Load env file based on `mode` in the current working directory.
  const env = loadEnv(mode, process.cwd(), '');

  // Get server configuration from environment variables with defaults
  const protocol = env.VITE_DEV_SERVER_PROTOCOL || 'http';
  const host = env.VITE_DEV_SERVER_HOST || '0.0.0.0';
  const port = parseInt(env.VITE_DEV_SERVER_PORT || '3000');
  const hmrProtocol = env.VITE_HMR_PROTOCOL || protocol;
  const hmrHost = env.VITE_HMR_HOST || 'sunnyside-ac.ddev.site';
  const hmrPort = env.VITE_HMR_PORT || '3000';

  return {
    plugins: [],
    build: {
      manifest: true,
      rollupOptions: {
        input: {
          main: path.resolve(__dirname, 'src/main.js'),
          // Add more entry points here as needed
        },
      },
      outDir: 'dist',
      assetsDir: 'assets',
      emptyOutDir: true,
    },
    server: {
      host: host, // Bind to 0.0.0.0 to accept connections from outside container
      port: port,
      strictPort: true, // Important for DDEV port mapping
      cors: true,
      hmr: {
        // Configure what the CLIENT (browser) should use to connect
        protocol: hmrProtocol,
        host: hmrHost, // Hostname the browser uses to connect
        clientPort: parseInt(hmrPort), // Port the browser connects to (via DDEV router)
      },
      watch: {
        usePolling: true, // Required for file watching to work in Docker
        interval: 1000,
        // Watch PHP files for Tailwind CSS class scanning
        included: ['**/*.php', '**/*.js', '**/*.css', '**/*.html'],
        // Exclude certain directories
        excluded: ['**/node_modules/**', '**/dist/**'],
      },
    },
  };
});
