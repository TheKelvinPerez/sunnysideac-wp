# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Development Commands

### Frontend Development
```bash
npm run dev          # Start Vite dev server with Hot Module Replacement
npm run build        # Build optimized assets for production
npm run preview      # Preview production build locally
```

### PHP Dependencies
```bash
composer install     # Install PHP dependencies (runs automatically on npm install)
```

## Architecture Overview

This is a modern WordPress theme built with Vite and Tailwind CSS v4, featuring Hot Module Replacement and smart asset loading.

### Key Technology Stack
- **Build Tool**: Vite 6.3.6 with HMR support
- **CSS Framework**: Tailwind CSS v4 (CSS-native configuration)
- **JavaScript**: ES modules with `type="module"` support
- **PHP**: Modern PHP with PSR-4 autoloading via Composer
- **Environment**: PHPDotenv for environment variable management

### Development vs Production Asset Loading
The theme automatically detects whether the Vite dev server is running:
- **Development**: Assets loaded from Vite dev server at `http://localhost:3000` with HMR
- **Production**: Built assets loaded from `dist/` directory using Vite manifest

### File Structure
```
src/
├── main.js          # Main JavaScript entry point
├── navigation.js    # Interactive navigation functionality
└── css/
    └── main.css     # Tailwind CSS v4 entry point

inc/                 # PHP includes with helper functions
└── constants.php   # Global constants and utility functions

template-parts/     # Self-contained template components
├── hero-section.php     # Hero section with data at top
├── why-choose-us.php    # Why choose us section with data at top
├── logo-marquee.php     # Logo marquee component
├── social-icons.php     # Social media icons
├── work-process.php     # Work process section
└── ...

dist/               # Production build output (auto-generated)
```

### Component Architecture (React-style)
Components follow a self-contained pattern similar to React:
- **Data at the top**: All component data is defined at the top of the template file
- **No separate config files**: Avoid creating separate `-config.php` files for simple components
- **Self-contained**: Each template part includes all its data and functionality
- **Simple usage**: Components are included with `get_template_part('template-parts/component-name')`

## Configuration

### Environment Variables
Copy `.env.example` to `.env` and configure:
```env
VITE_DEV_SERVER_PROTOCOL=http
VITE_DEV_SERVER_HOST=localhost
VITE_DEV_SERVER_PORT=3000
APP_ENV=development
```

### Tailwind CSS v4 Configuration
Configuration is done directly in `src/css/main.css` using CSS-native syntax:
```css
@import "tailwindcss";

@theme {
  --color-primary: #0070f3;
  --font-sans: 'Inter', system-ui, sans-serif;
}

@layer components {
  .btn-primary {
    @apply bg-primary text-white px-4 py-2 rounded-lg;
  }
}
```

**Important**: Tailwind CSS v4 does NOT use `tailwind.config.js` - configuration is CSS-based.

## PHP Architecture

### PSR-4 Autoloading
PHP classes in `inc/` are autoloaded under the `SunnysideAC\` namespace.

### Debug Helper
Use `dd($variable)` for development debugging with Whoops error handler.

### Asset Loading System
The theme uses intelligent asset detection:
- Checks if Vite dev server is running via HTTP request
- Automatically switches between dev server and built assets
- Properly handles ES module script loading

## JavaScript Development

### ES Module Support
All scripts are loaded as ES modules (`type="module"`). Import additional modules in `src/main.js`:
```javascript
import './navigation.js';
import './components/custom-component.js';
```

### Hot Module Replacement
Changes to CSS/JS files are reflected instantly without page refresh when `npm run dev` is running.

## Business Context

This theme serves an HVAC business (Sunnyside AC) with:
- Service areas throughout South Florida
- Contact information and service details
- Interactive navigation with location selection
- Hero sections showcasing business statistics

## Component Development Guidelines

### Creating New Components
When creating new template parts, follow this React-style pattern:

```php
<?php
/**
 * Component Template Part
 * Self-contained component with all data at the top
 */

// Component data (like props in React)
$items = [
    ['title' => 'Item 1', 'description' => 'Description 1'],
    ['title' => 'Item 2', 'description' => 'Description 2']
];

$images = [
    'background' => sunnysideac_asset_url('assets/images/bg.jpg')
];
?>

<!-- Component HTML -->
<section class="component">
    <!-- Use $items and $images directly -->
</section>
```

### Usage
Include components in templates with:
```php
<?php get_template_part('template-parts/component-name'); ?>
```

## Important Notes

- Always run `npm run dev` during development for HMR
- Run `npm run build` before deploying to production
- The theme uses modern PHP array syntax (`[]` notation)
- Tailwind v4 requires CSS-native configuration (no JS config file)
- All PHP files are automatically scanned for Tailwind classes
- **Keep components self-contained** - don't separate data from templates
- **Define data at the top** of template files (React-style pattern)