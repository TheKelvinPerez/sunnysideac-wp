# WordPress Vite Starter

A modern WordPress starter theme built with Vite and Tailwind CSS v4. Features Hot Module Replacement (HMR), ES modules, and smart asset detection for seamless development and production workflows.

![WordPress](https://img.shields.io/badge/WordPress-6.0+-blue.svg)
![Vite](https://img.shields.io/badge/Vite-6.3-purple.svg)
![Tailwind CSS](https://img.shields.io/badge/Tailwind%20CSS-v4-38bdf8.svg)
![License](https://img.shields.io/badge/License-GPL%20v2-green.svg)

## ✨ Features

- ⚡ **Vite Development** - Lightning-fast Hot Module Replacement (HMR) for instant feedback
- 🎨 **Tailwind CSS v4** - Modern utility-first CSS framework with CSS-native configuration
- 🔄 **Smart Detection** - Automatically switches between dev server and production builds
- 📦 **ES Modules** - Modern JavaScript with native module support
- 🚀 **Optimized Production** - Minified assets with content-based hashing for cache busting
- 🛠️ **Zero Config** - Works out of the box with sensible defaults
- 🔧 **Customizable** - Easy configuration via `.env` file or WordPress filters

## Development Setup

### Prerequisites
- WordPress 6.0 or higher
- PHP 7.4 or higher
- Node.js 18 or higher
- npm or yarn

### 1. Clone the Repository

```bash
git clone https://github.com/yourusername/wordpress-vite-starter.git
cd wordpress-vite-starter
```

### 2. Install Dependencies

```bash
npm install
```

### 3. Start Development Server

```bash
npm run dev
```

The Vite dev server will start at `http://localhost:3000`.

### 4. Activate Theme

1. Copy the theme folder to your WordPress `wp-content/themes/` directory
2. Go to WordPress Admin → Appearance → Themes
3. Activate the "WordPress Vite Starter" theme
4. Open your WordPress site in a browser

You should see instant updates as you edit your files!

### Development

Run the Vite dev server with hot module replacement:

```bash
npm run dev
```

This starts the Vite dev server at `http://localhost:3000`. The theme will automatically detect the dev server and load assets from it.

### Production Build

Build optimized assets for production:

```bash
npm run build
```

This generates minified and optimized CSS/JS in the `dist/` directory.

## File Structure

```
.
├── src/
│   ├── main.js          # Main JS entry point
│   └── css/
│       └── main.css     # Tailwind CSS entry point
├── dist/                # Built assets (production)
├── functions.php        # WordPress theme functions
├── style.css            # WordPress theme header
├── vite.config.js       # Vite configuration
└── postcss.config.cjs   # PostCSS configuration
```

## How It Works

### Development Mode
When `npm run dev` is running, WordPress detects the Vite dev server and loads assets from `http://localhost:3000`. This enables hot module replacement and instant updates.

### Production Mode
When the dev server is not running, WordPress loads the built assets from the `dist/` directory using the manifest file.

## Configuration

### Environment Variables

You can customize the Vite dev server settings by creating a `.env` file in the theme directory:

```bash
cp .env.example .env
```

Available options:
```env
VITE_DEV_SERVER_PROTOCOL=http
VITE_DEV_SERVER_HOST=localhost
VITE_DEV_SERVER_PORT=3000
```

You can also use WordPress filters to override these settings programmatically:

```php
// In your functions.php or plugin
add_filter('vite_protocol', function($protocol) {
    return 'https';
});

add_filter('vite_host', function($host) {
    return '192.168.1.100';
});

add_filter('vite_port', function($port) {
    return '5173';
});
```

## Using Tailwind CSS

Tailwind CSS v4 is configured to scan all PHP files in your theme. Simply use Tailwind classes in your templates:

```php
<div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold text-blue-600">Hello World</h1>
</div>
```

## Configuring Tailwind CSS v4

**Important:** Tailwind CSS v4 does NOT use `tailwind.config.js` anymore! Configuration is now done directly in your CSS file using CSS variables and `@theme` directive.

### Method 1: Using @theme directive in CSS (Recommended)

Add your theme customizations directly in `src/css/main.css`:

```css
@import "tailwindcss";

/* Configure your theme */
@theme {
  /* Custom colors */
  --color-primary: #0070f3;
  --color-secondary: #7928ca;

  /* Custom fonts */
  --font-sans: 'Inter', system-ui, sans-serif;
  --font-mono: 'Fira Code', monospace;

  /* Custom spacing */
  --spacing-huge: 5rem;

  /* Custom breakpoints */
  --breakpoint-tablet: 768px;
  --breakpoint-laptop: 1024px;

  /* Custom animations */
  --animate-bounce-slow: bounce 3s infinite;
}

/* Your custom component styles */
@layer components {
  .btn-primary {
    @apply bg-primary text-white px-4 py-2 rounded-lg hover:opacity-90;
  }
}

/* Your custom utility styles */
@layer utilities {
  .text-balance {
    text-wrap: balance;
  }
}

/* Regular custom CSS */
.my-custom-class {
  /* Your styles */
}
```

### Method 2: Extending with CSS Custom Properties

You can also use CSS variables anywhere in your CSS:

```css
@import "tailwindcss";

:root {
  --brand-blue: #0070f3;
  --brand-purple: #7928ca;
}

.hero {
  background: var(--brand-blue);
}
```

Then use them with arbitrary values:
```html
<div class="bg-[var(--brand-blue)]">Content</div>
```

### Method 3: Using @plugin for Complex Logic

For advanced configurations, create CSS plugins:

```css
@import "tailwindcss";

@plugin {
  /* Add custom utilities */
  @utility rotate-y-180 {
    transform: rotateY(180deg);
  }

  /* Add custom variants */
  @variant hocus {
    &:hover, &:focus {
      @apply @*;
    }
  }
}

/* Now you can use: */
/* class="hocus:rotate-y-180" */
```

### Common Tailwind v4 Configurations

#### Custom Colors
```css
@theme {
  --color-brand-light: #e0f2fe;
  --color-brand-DEFAULT: #0284c7;
  --color-brand-dark: #0c4a6e;
}
```
Usage: `bg-brand`, `text-brand-light`, `border-brand-dark`

#### Custom Fonts
```css
@theme {
  --font-display: 'Playfair Display', serif;
}
```
Usage: `font-display`

#### Custom Shadows
```css
@theme {
  --shadow-soft: 0 2px 15px -3px rgba(0, 0, 0, 0.07);
}
```
Usage: `shadow-soft`

### Scanning Additional Files

Tailwind v4 automatically scans files based on your imports. The CSS is scanned from `src/main.js` which imports `src/css/main.css`. All PHP files in your theme are automatically detected.

To scan additional directories, they need to contain classes that are actually referenced. Tailwind v4 uses intelligent content detection.

### Migration from Tailwind v3

Key differences:
- ❌ No more `tailwind.config.js`
- ✅ Use `@theme` directive in CSS
- ✅ Use `@import "tailwindcss"` instead of `@tailwind` directives
- ✅ CSS-first configuration
- ✅ Faster build times
- ✅ Better IntelliSense support

## Adding Custom JavaScript

Add your custom JavaScript in `src/main.js`:

```js
// Custom code here
console.log('My custom code');

// You can also import other JS modules
import './components/slider.js';
import './utils/helpers.js';
```

## Adding Custom CSS

You have two options for adding custom CSS:

### Option 1: In main.css (Recommended)
```css
@import "tailwindcss";

@theme {
  /* Your theme config */
}

@layer components {
  /* Component styles that should be tree-shaken */
  .card {
    @apply rounded-lg shadow-lg p-6;
  }
}

/* Regular custom CSS */
.custom-class {
  /* Your styles */
}
```

### Option 2: Separate CSS files
Create new CSS files and import them in `src/main.js`:
```js
import './css/main.css';
import './css/custom.css';
import './css/components/header.css';
```

## 🔥 Hot Module Replacement (HMR)

HMR allows you to see changes instantly without refreshing the page:

1. Start the dev server: `npm run dev`
2. Open your WordPress site
3. Edit CSS or JS files
4. See instant updates in the browser!

**How it works:**
- Vite watches your files for changes
- When you save, Vite reprocesses only the changed file
- Updates are pushed to the browser via WebSocket
- Page updates without a full reload

## 📚 Documentation

For a detailed explanation of the architecture, see [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md).

Topics covered:
- How Vite works in WordPress
- Tailwind CSS v4 configuration
- ES modules and WordPress integration
- Hot Module Replacement explained
- Complete request flow
- Troubleshooting guide

## 🛠️ Available Commands

```bash
# Start Vite dev server with HMR
npm run dev

# Build optimized assets for production
npm run build

# Preview production build locally
npm run preview
```

## 🐛 Troubleshooting

### Styles not loading in development

**Check:**
1. Is `npm run dev` running?
2. Can you access `http://localhost:3000`?
3. Check browser console for errors

### Styles not loading in production

**Solution:**
1. Run `npm run build` before deploying
2. Verify `dist/` folder exists
3. Check `dist/.vite/manifest.json` exists

### "Cannot use import statement outside a module"

**Check:**
- Scripts should have `type="module"` in page source
- The `script_loader_tag` filter is working correctly

### HMR not working

**Check:**
1. Vite dev server is running
2. WebSocket connection not blocked by firewall
3. Browser console shows no WebSocket errors

## 🤝 Contributing

Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License

This project is licensed under the GNU General Public License v2 or later.

## 🙏 Credits

Built with:
- [Vite](https://vitejs.dev/) - Next Generation Frontend Tooling
- [Tailwind CSS v4](https://tailwindcss.com/) - Utility-first CSS Framework
- [WordPress](https://wordpress.org/) - Content Management System

## 📞 Support

- **Documentation**: [docs/ARCHITECTURE.md](docs/ARCHITECTURE.md)
- **Issues**: [GitHub Issues](https://github.com/yourusername/wordpress-vite-starter/issues)

---

**Made with ❤️ for the WordPress community**
