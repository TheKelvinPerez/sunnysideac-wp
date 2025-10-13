import { defineConfig, loadEnv } from 'vite';
import path from 'path';

export default defineConfig(({ mode }) => {
  // Load env file based on `mode` in the current working directory.
  const env = loadEnv(mode, process.cwd(), '');

  // Get server configuration from environment variables with defaults
  const protocol = env.VITE_DEV_SERVER_PROTOCOL || 'http';
  const host = env.VITE_DEV_SERVER_HOST || 'localhost';
  const port = parseInt(env.VITE_DEV_SERVER_PORT || '3000');

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
      host: host,
      port: port,
      strictPort: false,
      cors: true,
      hmr: {
        protocol: protocol,
        host: host,
        port: port,
      },
    },
  };
});
