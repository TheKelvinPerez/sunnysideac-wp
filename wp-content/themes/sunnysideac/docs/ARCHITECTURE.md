# Architecture Guide: Modern WordPress with Vite + Tailwind CSS v4

## Overview

This WordPress theme uses a modern development stack with Vite and Tailwind CSS v4, providing fast development with Hot Module Replacement and optimized production builds.

## File Structure

```
sunnysideac/
├── src/                          # Source files (development)
│   ├── main.js                   # JavaScript entry point
│   └── css/
│       └── main.css              # Tailwind CSS entry point
│
├── dist/                         # Built files (production)
│   ├── .vite/
│   │   └── manifest.json         # Maps source files to built files
│   └── assets/
│       ├── main-[hash].js        # Minified JavaScript
│       └── main-[hash].css       # Minified CSS
│
├── index.php                     # WordPress homepage template
├── header.php                    # WordPress header template
├── footer.php                    # WordPress footer template
├── functions.php                 # Theme functionality and asset loading
├── style.css                     # WordPress theme metadata
│
├── package.json                  # npm dependencies and scripts
├── vite.config.js                # Vite configuration
├── postcss.config.cjs            # PostCSS configuration (Tailwind + Autoprefixer)
└── .env                          # Environment variables (not committed)
```

---

## How Vite Works

### Development Mode

When running `npm run dev`, Vite starts a development server at `http://localhost:3000` that:

- Serves files on-demand with no build step
- Provides Hot Module Replacement (HMR) for instant updates
- Transforms modern JavaScript and CSS on-the-fly
- Enables source maps for debugging

**Request Flow:**
```
Browser → Vite Dev Server (localhost:3000) → Processes files → Returns to browser
```

### Production Mode

When running `npm run build`, Vite:

- Bundles all JavaScript and CSS files
- Minifies and optimizes code
- Adds content-based hashes to filenames for cache busting
- Creates a manifest.json mapping source files to built files

**Result:**
```
dist/assets/main-[hash].js      # Minified JavaScript
dist/assets/main-[hash].css     # Minified CSS
dist/.vite/manifest.json        # File mapping
```

---

## Tailwind CSS v4

### What Changed from v3

**Tailwind v3:**
- Used `tailwind.config.js` for configuration
- Required `@tailwind base`, `@tailwind components`, `@tailwind utilities` directives

**Tailwind v4:**
- Configuration in CSS using `@import "tailwindcss"`
- Uses `@theme` directive for customization
- CSS-native approach for better performance

### Configuration

**File: `src/css/main.css`**
```css
@import "tailwindcss";

@theme {
  /* Custom colors */
  --color-brand: #3b82f6;

  /* Custom fonts */
  --font-display: 'Your Font', serif;
}

@layer components {
  .btn-primary {
    @apply bg-blue-600 text-white px-4 py-2 rounded;
  }
}
```

### How It Works

1. **Scanning:** Tailwind scans your PHP template files for class names
2. **Generation:** Creates CSS only for classes you actually use (tree-shaking)
3. **Processing:** PostCSS transforms the CSS with autoprefixer
4. **Output:** Vite bundles the final CSS

---

## WordPress Integration

### Smart Asset Loading

The theme automatically detects whether the Vite dev server is running and loads assets accordingly.

**File: `functions.php`**

```php
function sunnysideac_enqueue_assets() {
    $is_dev = sunnysideac_is_vite_dev_server_running();

    if ($is_dev) {
        // Development: Load from Vite dev server
    } else {
        // Production: Load from dist/ folder
    }
}
```

### Development Mode Assets

When dev server is detected:
```php
wp_enqueue_script('sunnysideac-vite-client', 'http://localhost:3000/@vite/client');
wp_enqueue_script('sunnysideac-main', 'http://localhost:3000/src/main.js');
```

**What gets loaded:**
1. **Vite Client** - Enables HMR and WebSocket communication
2. **Main Entry** - Your JavaScript that imports CSS

**CSS Loading:**
- CSS is injected via JavaScript as `<style>` tags
- This enables instant HMR updates for styles

### Production Mode Assets

When dev server is NOT running:
```php
$manifest = json_decode(file_get_contents($manifest_path), true);
$main = $manifest['src/main.js'];

// Load minified CSS
wp_enqueue_style('main', '/dist/' . $main['css'][0]);

// Load minified JS
wp_enqueue_script('main', '/dist/' . $main['file']);
```

**What gets loaded:**
- Pre-built, minified CSS file from `dist/assets/`
- Pre-built, minified JS file from `dist/assets/`
- Files have content-based hashes for cache busting

---

## ES Modules

### The Challenge

Modern JavaScript uses ES Modules with `import`/`export` syntax:

```javascript
import './css/main.css';
export default myFunction;
```

Browsers require `type="module"` attribute:
```html
<script type="module" src="script.js"></script>
```

### The Solution

WordPress's `wp_enqueue_script()` adds `type="text/javascript"` by default. We use the `script_loader_tag` filter to replace it:

```php
function sunnysideac_add_type_attribute($tag, $handle, $src) {
    if ('sunnysideac-main' === $handle) {
        $tag = str_replace('type="text/javascript"', 'type="module"', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'sunnysideac_add_type_attribute', 10, 3);
```

---

## Hot Module Replacement (HMR)

### How It Works

1. **File Change:** You save a CSS or JS file
2. **Detection:** Vite's file watcher detects the change
3. **Processing:** Vite reprocesses only the changed file
4. **WebSocket:** Vite sends update to browser via WebSocket
5. **Update:** Vite client updates the page without reload

**Connection:**
```
Browser ←→ WebSocket ←→ Vite Dev Server
```

### Benefits

- Instant feedback during development
- No page reload needed
- Preserves application state
- Much faster than traditional reload workflow

---

## Complete Request Flow

### Development Mode

```
1. Browser requests WordPress page
2. WordPress calls wp_head()
3. functions.php detects Vite dev server running
4. Enqueues scripts from localhost:3000
5. Browser loads Vite client and main.js
6. main.js imports CSS
7. Vite processes CSS through PostCSS/Tailwind
8. Vite injects CSS via JavaScript
9. WebSocket connection established for HMR
```

### Production Mode

```
1. Developer runs: npm run build
2. Vite bundles and minifies all files
3. Creates manifest.json with file mappings
4. Files saved to dist/ with hashed names
---
5. Browser requests WordPress page
6. functions.php detects dev server NOT running
7. Reads manifest.json
8. Enqueues built files from dist/
9. Browser loads minified CSS and JS
```

---

## Configuration

### Environment Variables

Create a `.env` file to customize the dev server:

```env
VITE_DEV_SERVER_PROTOCOL=http
VITE_DEV_SERVER_HOST=localhost
VITE_DEV_SERVER_PORT=3000
```

### WordPress Filters

You can override settings programmatically:

```php
add_filter('sunnysideac_vite_protocol', function($protocol) {
    return 'https';
});

add_filter('sunnysideac_vite_host', function($host) {
    return '192.168.1.100';
});

add_filter('sunnysideac_vite_port', function($port) {
    return '5173';
});
```

---

## Commands

```bash
# Development
npm run dev      # Start Vite dev server with HMR

# Production
npm run build    # Build optimized assets for production

# Preview
npm run preview  # Preview production build locally
```

---

## Troubleshooting

### Issue: Styles not loading in development

**Symptoms:** No styling, plain HTML
**Check:**
1. Is `npm run dev` running?
2. Can you access `http://localhost:3000`?
3. Check browser console for errors

### Issue: Styles not loading in production

**Symptoms:** No styling after deploying
**Solution:**
1. Run `npm run build` before deploying
2. Verify `dist/` folder exists and is uploaded
3. Check `dist/.vite/manifest.json` exists

### Issue: "Cannot use import statement outside a module"

**Symptoms:** JavaScript error in console
**Solution:**
- Verify `script_loader_tag` filter is working
- Check scripts have `type="module"` in page source

### Issue: HMR not working

**Symptoms:** Changes require manual refresh
**Check:**
1. Vite dev server is running
2. WebSocket connection not blocked by firewall
3. Browser console shows no WebSocket errors

---

## Best Practices

### Development Workflow

1. Start dev server: `npm run dev`
2. Open WordPress site in browser
3. Edit files and see instant updates
4. Build before committing: `npm run build`

### Production Deployment

1. Run `npm run build` locally
2. Commit the `dist/` folder
3. Deploy entire theme to server
4. WordPress will automatically use built files

### Code Organization

- Use Tailwind utility classes directly in PHP templates
- Only extract components if repeated 5+ times
- Define brand colors in `@theme` directive
- Keep JavaScript modular with ES imports

---

## Technical Benefits

### Performance

- **Smaller bundles:** Only used CSS is included
- **Cache busting:** Hashed filenames ensure fresh downloads
- **Minification:** Reduced file sizes
- **Modern syntax:** Optimized for modern browsers

### Developer Experience

- **Instant feedback:** HMR updates without reload
- **Fast builds:** Vite is significantly faster than Webpack
- **Modern JavaScript:** Use latest ES features
- **Type safety:** Easy to add TypeScript later

### Maintainability

- **Utility-first:** Less custom CSS to maintain
- **No config file:** Tailwind configuration in CSS
- **Standard tools:** Familiar npm/Vite workflow
- **Clear separation:** Source vs built files

---

## Further Reading

- [Vite Documentation](https://vitejs.dev/)
- [Tailwind CSS v4 Documentation](https://tailwindcss.com/)
- [WordPress Theme Development](https://developer.wordpress.org/themes/)
- [ES Modules on MDN](https://developer.mozilla.org/en-US/docs/Web/JavaScript/Guide/Modules)

---

**Version:** 1.0
**Last Updated:** October 2025
