# Tailwind CSS v4 Quick Reference Guide

## Key Changes from v3 to v4

Tailwind CSS v4 represents a major shift in how you configure and use Tailwind. The biggest change is moving from JavaScript configuration to CSS-native configuration.

### What's Different?

| Tailwind v3 | Tailwind v4 |
|-------------|-------------|
| `tailwind.config.js` | ❌ No config file |
| `@tailwind base` | `@import "tailwindcss"` |
| `@tailwind components` | Built into `@import` |
| `@tailwind utilities` | Built into `@import` |
| JS-based theming | `@theme` directive in CSS |
| JS plugins | `@plugin` directive in CSS |

## Configuration Examples

### Basic Setup

```css
/* src/css/main.css */
@import "tailwindcss";

/* Your custom CSS */
.custom-class {
  /* styles */
}
```

### Adding Custom Colors

```css
@import "tailwindcss";

@theme {
  /* Single color */
  --color-brand: #3b82f6;

  /* Color scale */
  --color-primary-50: #eff6ff;
  --color-primary-100: #dbeafe;
  --color-primary-500: #3b82f6;
  --color-primary-900: #1e3a8a;

  /* Default variant (used when no number) */
  --color-primary-DEFAULT: #3b82f6;
}
```

**Usage:**
- `bg-brand`
- `text-primary` (uses DEFAULT)
- `bg-primary-500`
- `border-primary-100`

### Custom Fonts

```css
@theme {
  /* Font families */
  --font-sans: 'Inter', system-ui, sans-serif;
  --font-display: 'Playfair Display', Georgia, serif;
  --font-mono: 'Fira Code', monospace;

  /* Font sizes */
  --font-size-tiny: 0.625rem;
  --font-size-huge: 4rem;
}
```

**Usage:**
- `font-sans`
- `font-display`
- `text-huge`

### Custom Spacing

```css
@theme {
  --spacing-xs: 0.25rem;
  --spacing-huge: 5rem;
  --spacing-section: 8rem;
}
```

**Usage:**
- `p-huge`
- `m-section`
- `gap-xs`

### Custom Breakpoints

```css
@theme {
  --breakpoint-xs: 480px;
  --breakpoint-tablet: 768px;
  --breakpoint-desktop: 1280px;
  --breakpoint-xl: 1536px;
}
```

**Usage:**
- `tablet:flex`
- `desktop:grid-cols-3`

### Custom Shadows

```css
@theme {
  --shadow-soft: 0 2px 15px -3px rgba(0, 0, 0, 0.07);
  --shadow-brutal: 8px 8px 0 rgba(0, 0, 0, 1);
}
```

**Usage:**
- `shadow-soft`
- `shadow-brutal`

### Custom Animations

```css
@theme {
  --animate-slide-in: slide-in 0.5s ease-out;
  --animate-bounce-slow: bounce 3s infinite;
}

@keyframes slide-in {
  from {
    transform: translateX(-100%);
  }
  to {
    transform: translateX(0);
  }
}
```

**Usage:**
- `animate-slide-in`
- `animate-bounce-slow`

## Component Styles with @layer

Use `@layer components` for reusable component classes that should be included in tree-shaking:

```css
@import "tailwindcss";

@layer components {
  .btn {
    @apply px-4 py-2 rounded font-medium transition-colors;
  }

  .btn-primary {
    @apply bg-blue-600 text-white hover:bg-blue-700;
  }

  .btn-secondary {
    @apply bg-gray-200 text-gray-800 hover:bg-gray-300;
  }

  .card {
    @apply bg-white rounded-lg shadow-lg p-6;
  }
}
```

## Custom Utilities with @layer

Use `@layer utilities` for utility classes:

```css
@layer utilities {
  .text-balance {
    text-wrap: balance;
  }

  .scrollbar-hide {
    -ms-overflow-style: none;
    scrollbar-width: none;
  }

  .scrollbar-hide::-webkit-scrollbar {
    display: none;
  }
}
```

## CSS Plugins with @plugin

For more complex customizations:

```css
@import "tailwindcss";

@plugin {
  /* Custom utility */
  @utility tab-* {
    tab-size: *;
  }

  /* Custom variant */
  @variant hocus {
    &:hover, &:focus {
      @apply @*;
    }
  }

  /* Custom variant with media query */
  @variant print {
    @media print {
      @apply @*;
    }
  }
}
```

**Usage:**
- `tab-4` (custom utility)
- `hocus:bg-blue-500` (applies on both hover and focus)
- `print:hidden` (hide when printing)

## Using CSS Variables

You can also use standard CSS custom properties:

```css
@import "tailwindcss";

:root {
  --brand-color: #3b82f6;
  --header-height: 4rem;
}

.header {
  height: var(--header-height);
  background: var(--brand-color);
}
```

Use with arbitrary values:
```html
<div class="h-[var(--header-height)] bg-[var(--brand-color)]">
  Header
</div>
```

## Responsive Design

Default breakpoints still work:
- `sm:` - 640px
- `md:` - 768px
- `lg:` - 1024px
- `xl:` - 1280px
- `2xl:` - 1536px

```html
<div class="text-sm md:text-base lg:text-lg xl:text-xl">
  Responsive text
</div>
```

## Dark Mode

Dark mode works the same way:

```html
<div class="bg-white dark:bg-gray-900 text-black dark:text-white">
  Content
</div>
```

Configure dark mode in your CSS:

```css
@theme {
  --color-background: #ffffff;
  --color-background-dark: #1a1a1a;
}
```

## Complete Example

```css
@import "tailwindcss";

/* Theme Configuration */
@theme {
  /* Colors */
  --color-primary: #0070f3;
  --color-secondary: #7928ca;
  --color-success: #10b981;
  --color-danger: #ef4444;

  /* Typography */
  --font-sans: 'Inter', system-ui, sans-serif;
  --font-display: 'Playfair Display', serif;

  /* Spacing */
  --spacing-section: 8rem;

  /* Shadows */
  --shadow-smooth: 0 4px 6px -1px rgba(0, 0, 0, 0.1);

  /* Animations */
  --animate-fade-in: fade-in 0.3s ease-in;
}

/* Components */
@layer components {
  .btn {
    @apply px-6 py-3 rounded-lg font-medium transition-all duration-200;
  }

  .btn-primary {
    @apply bg-primary text-white hover:opacity-90 active:scale-95;
  }

  .container-custom {
    @apply max-w-7xl mx-auto px-4 sm:px-6 lg:px-8;
  }
}

/* Utilities */
@layer utilities {
  .text-balance {
    text-wrap: balance;
  }
}

/* Plugins */
@plugin {
  @variant hocus {
    &:hover, &:focus {
      @apply @*;
    }
  }
}

/* Animations */
@keyframes fade-in {
  from { opacity: 0; }
  to { opacity: 1; }
}

/* Custom CSS */
.special-gradient {
  background: linear-gradient(135deg, var(--color-primary), var(--color-secondary));
}
```

## Tips & Best Practices

1. **Use @theme for design tokens** - Colors, fonts, spacing that define your brand
2. **Use @layer components** - For reusable component classes
3. **Use @layer utilities** - For single-purpose utility classes
4. **Use @plugin** - For complex variants and custom utilities
5. **Regular CSS** - For one-off styles that don't fit the above

## Resources

- [Tailwind CSS v4 Documentation](https://tailwindcss.com/docs)
- [Tailwind CSS v4 Alpha Docs](https://tailwindcss.com/blog/tailwindcss-v4-alpha)
- [Migration Guide](https://tailwindcss.com/docs/upgrade-guide)
