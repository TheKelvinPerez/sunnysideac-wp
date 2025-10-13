# SunnySide AC WordPress Theme

A modern WordPress theme built with Vite and Tailwind CSS v4.

## Development Setup

### Prerequisites
- Node.js (v18 or higher)
- npm

### Installation

```bash
npm install
```

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
