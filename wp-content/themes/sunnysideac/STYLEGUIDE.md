# Claude Code Styleguide: Sunnyside AC WordPress Theme

This styleguide establishes coding standards and patterns for working with the Sunnyside AC WordPress theme. Based on analysis of the existing codebase, these guidelines ensure consistency and maintainability.

## Table of Contents
1. [PHP Standards](#php-standards)
2. [JavaScript Standards](#javascript-standards)
3. [CSS/Tailwind Standards](#csstailwind-standards)
4. [File Organization](#file-organization)
5. [WordPress Integration](#wordpress-integration)
6. [Naming Conventions](#naming-conventions)
7. [Error Handling](#error-handling)
8. [Documentation Standards](#documentation-standards)
9. [Development Workflow](#development-workflow)

---

## PHP Standards

### Array Syntax
**Use modern `[]` syntax instead of `array()`:**
```php
// ✅ Modern syntax
$service_areas = ['Miramar', 'Pembroke Pines', 'South West Ranches'];
$hero_stats = [
    ['number' => '1.5K+', 'description' => "Project Completed\nWith Excellence"],
    ['number' => '8+', 'description' => "Years\nExperience"]
];

// ❌ Legacy syntax
$service_areas = array('Miramar', 'Pembroke Pines');
```

### Security & Escaping
**Always use WordPress escape functions:**
```php
// ✅ Proper escaping
echo esc_attr(SUNNYSIDE_TEL_HREF);
echo esc_url($image_url);
echo esc_html($stat['number']);
echo wp_kses_post($content);

// ❌ Unescaped output
echo $user_input;
echo $url;
```

### Helper Functions
**Create reusable helper functions for common operations:**
```php
// ✅ Asset URL helper
function sunnysideac_asset_url($path) {
    return get_template_directory_uri() . '/' . ltrim($path, '/');
}

// ✅ Usage
<img src="<?php echo esc_url(sunnysideac_asset_url('assets/images/logo.svg')); ?>">
```

### Constants
**Use constants for configuration values:**
```php
// ✅ Define constants in inc/constants.php
define('SUNNYSIDE_TEL', '(954) 999-9999');
define('SUNNYSIDE_TEL_HREF', 'tel:9549999999');
define('SUNNYSIDE_EMAIL', 'info@sunnysideac.com');
```

---

## JavaScript Standards

### ES Modules
**Use modern ES module syntax:**
```javascript
// ✅ Import statements
import './css/main.css';
import './navigation.js';
import './components/slider.js';

// ❌ Avoid global variables
window.myFunction = function() { ... };
```

### Event Handling
**Use modern event patterns with proper cleanup:**
```javascript
// ✅ Event delegation and cleanup
document.addEventListener('DOMContentLoaded', function() {
    const mobileMenuToggle = document.getElementById('mobile-menu-toggle');

    // Event listeners
    mobileMenuToggle?.addEventListener('click', toggleMobileMenu);

    // Cleanup on page unload
    window.addEventListener('beforeunload', function() {
        document.body.style.overflow = '';
    });
});
```

### State Management
**Use clean state management patterns:**
```javascript
// ✅ Centralized state
let activeMenuItem = 'Home';
let isServicesDropdownOpen = false;
let isMobileMenuOpen = false;

// ✅ Update functions
function updateActiveMenuItem(itemName) {
    activeMenuItem = itemName;
    // Update UI logic here
}
```

### DOM Caching
**Cache DOM elements for performance:**
```javascript
// ✅ Cache frequently used elements
const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
const servicesDropdown = document.querySelector('.services-dropdown');
const navigationItems = document.querySelectorAll('.nav-item');
```

---

## CSS/Tailwind Standards

### Tailwind CSS v4 Configuration
**Use CSS-native configuration with `@theme` directive:**
```css
@import "tailwindcss";

@theme {
  --color-primary: #0070f3;
  --color-secondary: #7928ca;
  --font-display: 'Playfair Display', serif;
  --spacing-huge: 5rem;
}

@layer components {
  .btn-primary {
    @apply bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors;
  }
}
```

### Utility-First Approach
**Prefer utility classes over custom CSS:**
```php
// ✅ Utility-first
<div class="flex flex-col gap-3 pt-4 sm:flex-row sm:gap-4 lg:justify-start">
    <span class="text-base font-medium whitespace-nowrap text-white lg:text-lg">

// ❌ Custom CSS when utilities suffice
<div class="custom-header-component">
```

### Responsive Design
**Use mobile-first responsive design:**
```php
// ✅ Mobile-first approach
<div class="lg:hidden">Mobile content</div>
<div class="hidden lg:block">Desktop content</div>

// ✅ Responsive utilities
class="text-sm md:text-base lg:text-lg"
```

---

## File Organization

### Directory Structure
```
sunnysideac/
├── src/                    # Development source files
│   ├── main.js            # JavaScript entry point
│   └── css/
│       └── main.css       # Tailwind CSS entry point
├── inc/                   # PHP includes with functions
│   └── constants.php      # Global constants and helper functions
├── template-parts/       # Self-contained template components
├── assets/               # Static assets
├── dist/                 # Production build output
└── docs/                 # Documentation
```

### Template Parts
**Use self-contained template parts with data at the top:**
```php
// ✅ Component template part - self-contained
<?php get_template_part('template-parts/hero-section'); ?>
<?php get_template_part('template-parts/why-choose-us'); ?>

// ✅ Template structure with data at top (like React components)
<?php
/**
 * Component Template Part
 * Self-contained component with all data defined at the top
 */

// Component data
$features = [
    [
        'title' => 'Feature 1',
        'description' => 'Description for feature 1',
        'icon' => sunnysideac_asset_url('assets/icons/feature1.svg')
    ]
];

$images = [
    'main' => sunnysideac_asset_url('assets/images/main-image.png')
];
?>

<!-- Component HTML -->
<section class="component">
    <!-- Use $features and $images directly -->
</section>
```

**Avoid separating data from components:**
```php
// ❌ Avoid separate config files and data passing
// Don't create separate -config.php files for simple components
// Don't pass data arrays to template parts
// Keep everything self-contained like React components
```

---

## WordPress Integration

### Theme Setup
**Proper WordPress theme initialization:**
```php
function sunnysideac_setup() {
    // Theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
    ]);

    // Navigation menus
    register_nav_menus([
        'primary' => __('Primary Menu', 'sunnysideac'),
        'footer'  => __('Footer Menu', 'sunnysideac'),
    ]);
}
add_action('after_setup_theme', 'sunnysideac_setup');
```

### Asset Loading
**Use the smart Vite asset loading system:**
```php
function sunnysideac_enqueue_assets() {
    $is_dev = sunnysideac_is_vite_dev_server_running();

    if ($is_dev) {
        // Development: Load from Vite dev server
        $vite_server_url = sunnysideac_get_vite_dev_server_url();
        wp_enqueue_script('sunnysideac-main', $vite_server_url . '/src/main.js', [], null, false);
        wp_script_add_data('sunnysideac-main', 'type', 'module');
    } else {
        // Production: Load built assets from manifest
        // Implementation in functions.php
    }
}
add_action('wp_enqueue_scripts', 'sunnysideac_enqueue_assets');
```

### ES Module Support
**Proper script type handling for ES modules:**
```php
function sunnysideac_add_type_attribute($tag, $handle) {
    if ('sunnysideac-vite-client' === $handle || 'sunnysideac-main' === $handle) {
        $tag = str_replace("type='text/javascript'", "type='module'", $tag);
        $tag = str_replace('type="text/javascript"', "type='module'", $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'sunnysideac_add_type_attribute', 10, 3);
```

---

## Naming Conventions

### Files
- **PHP files:** `kebab-case.php` (e.g., `hero-section.php`)
- **JavaScript files:** `kebab-case.js` (e.g., `navigation.js`)
- **CSS files:** `kebab-case.css` (e.g., `main.css`)

### Constants
- **Constants:** `UPPER_SNAKE_CASE` (e.g., `SUNNYSIDE_TEL_HREF`)
- **Prefix:** Use theme prefix `SUNNYSIDE_` for constants

### Functions
- **Functions:** `theme-prefix_function_name` (e.g., `sunnysideac_get_hero_images`)

### CSS Classes
- **Tailwind:** Use utility classes directly
- **Custom:** `kebab-case` for custom component classes

### JavaScript Variables
- **Variables:** `camelCase` (e.g., `isMobileMenuOpen`)
- **Constants:** `UPPER_SNAKE_CASE` (e.g., `API_ENDPOINT`)

---

## Error Handling

### Development Debugging
**Use the enhanced `dd()` function for development:**
```php
// ✅ Debug helper
dd($variable, $another_variable);

// ✅ Available only in development
if ($_ENV['APP_ENV'] === 'development') {
    dd($debug_data);
}
```

### Error Handling Patterns
**Robust error checking:**
```php
// ✅ File existence checks
if (file_exists($manifest_path)) {
    $manifest = json_decode(file_get_contents($manifest_path), true);
}

// ✅ Null coalescing for optional values
$protocol = $_ENV['VITE_DEV_SERVER_PROTOCOL'] ?? 'http';
$host = $_ENV['VITE_DEV_SERVER_HOST'] ?? 'localhost';

// ✅ Safe array access
$stat_number = $stat['number'] ?? 'N/A';
```

### JavaScript Error Handling
**Comprehensive event cleanup:**
```javascript
// ✅ Cleanup on page unload
window.addEventListener('beforeunload', () => {
    document.body.style.overflow = '';
    // Remove event listeners if needed
});

// ✅ Safe DOM queries
const element = document.getElementById('mobile-menu-toggle');
element?.addEventListener('click', toggleMobileMenu);
```

---

## Documentation Standards

### PHPDoc Blocks
**Comprehensive function documentation:**
```php
/**
 * Get hero image URLs
 *
 * @return array Array of image URLs with keys for mobile_hero, desktop_hero_left, desktop_hero_right
 */
function sunnysideac_get_hero_images() {
    // Implementation
}

/**
 * Get asset URL helper function
 *
 * @param string $path Path relative to theme directory
 * @return string Full URL to asset
 */
function sunnysideac_asset_url($path) {
    return get_template_directory_uri() . '/' . ltrim($path, '/');
}
```

### Inline Comments
**Clear, purposeful comments:**
```php
// Check if Vite dev server is running
$context = stream_context_create([
    'http' => [
        'timeout'       => 1,
        'ignore_errors' => true,
    ],
]);

// Mark body as ready once CSS is injected
setTimeout(function() {
    document.body.classList.add('vite-ready');
}, 50);
```

### Component Documentation
**Template part headers:**
```php
<?php
/**
 * Component Template Part
 * Self-contained component with all data defined at the top
 * Similar to React component structure
 */
```

---

## Development Workflow

### Environment Setup
**Use environment variables for configuration:**
```bash
# .env file
APP_ENV=development
VITE_DEV_SERVER_PROTOCOL=http
VITE_DEV_SERVER_HOST=localhost
VITE_DEV_SERVER_PORT=3000
```

### Development Commands
```bash
# Start development server with HMR
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview

# Install PHP dependencies
composer install
```

### Code Quality Checklist
**Before committing changes:**
1. ✅ Run `npm run build` to ensure production build works
2. ✅ Test in both development and production modes
3. ✅ Verify responsive design on mobile and desktop
4. ✅ Check console for JavaScript errors
5. ✅ Validate HTML markup
6. ✅ Ensure proper escaping of all dynamic output
7. ✅ Test accessibility (keyboard navigation, ARIA attributes)

### Git Commit Standards
**Use descriptive commit messages:**
```
✅ Add responsive navigation component
✅ Update hero section statistics display
✅ Fix mobile menu toggle accessibility
✅ Refactor asset loading for production optimization
```

---

## Best Practices Summary

### Performance
- ✅ Use Vite HMR for fast development
- ✅ Implement smart asset loading (dev vs production)
- ✅ Cache DOM elements in JavaScript
- ✅ Use CSS utilities for optimal tree-shaking

### Accessibility
- ✅ Include proper ARIA attributes
- ✅ Ensure keyboard navigation works
- ✅ Use semantic HTML elements
- ✅ Test with screen readers

### Security
- ✅ Always escape output with WordPress functions
- ✅ Use nonce verification for forms
- ✅ Sanitize user inputs
- ✅ Follow WordPress security best practices

### Maintainability
- ✅ Use modern PHP array syntax
- ✅ Implement reusable helper functions
- ✅ Follow consistent naming conventions
- ✅ Document functions and components thoroughly

---

This styleguide should be followed for all development work on the Sunnyside AC WordPress theme to ensure code quality, consistency, and maintainability.