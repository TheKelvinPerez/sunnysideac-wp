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
        output: {
          // Enable code splitting for better caching
          manualChunks: (id) => {
            // Create chunks based on file paths
            if (id.includes('node_modules')) {
              return 'vendor';
            }
            if (id.includes('navigation.js')) {
              return 'navigation';
            }
            if (id.includes('forms/')) {
              return 'forms';
            }
            if (id.includes('components/')) {
              return 'components';
            }
          },
          // Optimize chunk naming for better caching
          chunkFileNames: (chunkInfo) => {
            // Add hash to chunks for cache busting
            if (chunkInfo.name === 'vendor') {
              return 'assets/vendor-[hash].js';
            }
            return 'assets/[name]-[hash].js';
          },
          assetFileNames: (assetInfo) => {
            const info = assetInfo.name.split('.');
            const ext = info[info.length - 1];
            if (/\.(mp4|webm|ogg|mp3|wav|flac|aac)(\?.*)?$/i.test(assetInfo.name)) {
              return 'assets/media/[name]-[hash][ext]';
            }
            if (/\.(png|jpe?g|gif|svg|webp|avif)(\?.*)?$/i.test(assetInfo.name)) {
              return 'assets/images/[name]-[hash][ext]';
            }
            if (/\.(woff2?|eot|ttf|otf)(\?.*)?$/i.test(assetInfo.name)) {
              return 'assets/fonts/[name]-[hash][ext]';
            }
            // Ensure CSS files have proper extension
            if (ext === 'css' || assetInfo.name.endsWith('.css')) {
              return 'assets/css/[name]-[hash].css';
            }
            return `assets/${ext}/[name]-[hash][ext]`;
          }
        }
      },
      outDir: 'dist',
      assetsDir: 'assets',
      emptyOutDir: true,
      // Enable source maps for production debugging
      sourcemap: mode === 'development',
      // Optimize chunks for better caching
      chunkSizeWarningLimit: 1000,
      // Use default minifier (esbuild) which is built-in
      minify: 'esbuild',
      // Optimize CSS
      cssCodeSplit: true,
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
    // Optimize dependencies
    optimizeDeps: {
      include: [
        // Pre-bundle dependencies for faster dev startup
      ],
      exclude: [
        // Exclude certain dependencies from pre-bundling if needed
      ]
    },
    // Define global constants
    define: {
      __APP_ENV__: JSON.stringify(mode),
    },
  };
});
