# Sunnyside AC - Modern WordPress Theme

A professional, high-performance WordPress theme built for HVAC service businesses. This theme showcases modern web development practices with Vite, Tailwind CSS v4, and a component-based architecture.

## ✨ Features

- **🎨 Modern Development Stack**: Built with Vite 6.3.6, Tailwind CSS v4, and PHP 8.3
- **🔥 Hot Module Replacement**: Live frontend development with instant updates
- **📱 Fully Responsive**: Mobile-first design that works on all devices
- **⚡ Performance Optimized**: Intelligent asset loading, CDN support, and caching
- **🧩 Component-Based**: Reusable template parts for maintainable development
- **🎯 Business-Focused**: Specifically designed for HVAC service companies
- **🔧 DDEV Integrated**: Full Docker development environment included
- **📊 Analytics Ready**: PostHog integration with feature flags
- **🌐 SEO Optimized**: Structured data, semantic HTML, and sitemap generation

## 🚀 Quick Start

### Prerequisites
- [DDEV](https://ddev.com/) (recommended) or local WordPress environment
- Node.js 18+ and npm
- PHP 8.3+

### Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/sunnysideac-wp-theme.git
   cd sunnysideac-wp-theme
   ```

2. **Start DDEV environment**
   ```bash
   ddev start
   ddev launch
   ```

3. **Install dependencies and start development**
   ```bash
   # Install theme dependencies (PHP + Node.js)
   ddev theme-install

   # Start Vite dev server with HMR
   ddev vite
   ```

4. **Visit your site**
   - Frontend: http://sunnyside-ac.ddev.site
   - WordPress Admin: http://sunnyside-ac.ddev.site/wp-admin

## 🛠 Development

### Frontend Development

The theme intelligently switches between development and production modes:

- **Development Mode**: Assets load from Vite dev server with HMR
- **Production Mode**: Built assets load from `dist/` directory

```bash
# Start development server (from any directory)
ddev vite

# Build for production
ddev build

# Alternative: Run from theme directory
cd app/public/wp-content/themes/sunnysideac
ddev exec npm run dev
ddev exec npm run build
```

### WordPress Development

```bash
# WordPress CLI commands
ddev wp plugin list
ddev wp cache flush
ddev wp rewrite flush  # IMPORTANT: Run after CPT/URL changes

# Database management
ddev mysql
ddev export-db
ddev snapshot --name=backup
```

### Debugging

```bash
# Enable Xdebug (disable when done!)
ddev xdebug on
ddev xdebug off

# View logs
ddev logs -f
```

## 📁 Architecture

### Directory Structure
```
sunnysideac/
├── src/                     # Frontend source files
│   ├── main.js             # JavaScript entry point
│   └── css/main.css        # Tailwind CSS v4 entry
├── template-parts/         # Reusable PHP components
├── inc/                    # PHP includes and utilities
│   ├── constants.php       # Business info & service areas
│   └── helpers.php         # Utility functions
├── dist/                   # Built assets (generated)
├── functions.php           # Theme setup and WordPress hooks
├── vite.config.js          # Vite configuration
├── package.json            # Node.js dependencies
├── composer.json           # PHP dependencies
└── .env                    # Environment variables
```

### Custom Post Types

- **Cities** (`city`) - Service areas throughout South Florida
- **Services** (`service`) - HVAC services offered
- **Brands** (`brand`) - Equipment brands serviced

### Custom URL Routing

Sophisticated routing for service-city combinations:
- Pattern: `/service/{city-slug}`
- Custom rewrite rules with validation
- SEO-friendly URL structure

**⚠️ Important**: Always run `ddev wp rewrite flush` after modifying CPTs or rewrite rules.

## 🎨 Component Development

Template parts follow a self-contained pattern:

```php
<?php
// Component data (like React props)
$items = [
    ['title' => 'Item 1', 'description' => 'Description'],
];

$config = [
    'background_color' => 'bg-blue-500',
    'title' => 'Section Title'
];
?>

<!-- Component HTML -->
<section class="<?php echo $config['background_color']; ?>">
    <!-- Use $items and $config -->
</section>
```

### Available Components

- `hero-section.php` - Hero with statistics
- `why-choose-us.php` - Value proposition
- `logo-marquee.php` - Brand showcase
- `areas-we-serve.php` - Service locations
- `customer-reviews.php` - Testimonials
- `faq-section.php` - FAQ accordion
- `contact-us.php` - Contact form
- And many more...

## 🎯 Tailwind CSS v4

This theme uses **Tailwind CSS v4** with CSS-native configuration:

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

No `tailwind.config.js` file - configuration is done directly in CSS.

## 🔧 Configuration

### Environment Variables

Create `.env` in the theme directory:

```env
# Vite Dev Server
VITE_DEV_SERVER_PROTOCOL=http
VITE_DEV_SERVER_HOST=localhost
VITE_DEV_SERVER_PORT=3000

# Environment
APP_ENV=development

# Analytics (optional)
POSTHOG_API_KEY=your_api_key
POSTHOG_HOST=https://us.i.posthog.com

# CDN (production)
CDN_ENABLED=true
CDN_BASE_URL=https://cdn.yourdomain.com
```

### Business Constants

Centralized business information in `inc/constants.php`:

```php
// Contact info
SUNNYSIDE_PHONE_DISPLAY    // (305) 978-9382
SUNNYSIDE_EMAIL_ADDRESS    // support@sunnyside247ac.com
SUNNYSIDE_ADDRESS_FULL     // 6609 Emerald Lake Dr, Miramar, FL 33023

// Service areas (30+ cities)
SUNNYSIDE_SERVICE_AREAS    // Array of service locations
```

## 🚀 Production Deployment

### Building Assets

```bash
# Build optimized assets
ddev build

# Critical CSS extraction
ddev exec npm run build:prod
```

### Deployment Notes

- Commit the `dist/` directory for deployment
- Theme automatically detects absence of dev server
- Configure production variables in `.env`
- Set up CDN for optimal performance

## 🔍 Helper Functions

### Asset Management
```php
sunnysideac_asset_url('assets/images/logo.png')
```

### Debug Tools
```php
dd($variable1, $variable2);  // Development only
```

### Vite Detection
```php
sunnysideac_is_vite_dev_server_running()
sunnysideac_get_vite_dev_server_url()
```

### Navigation
```php
sunnysideac_desktop_nav_menu()
sunnysideac_mobile_nav_menu()
sunnysideac_get_service_icon($service_name)
```

## 🌐 Browser Support

- Chrome/Edge 90+
- Firefox 88+
- Safari 14+
- iOS Safari 14+
- Android Chrome 90+

## 📄 License

GNU General Public License v2 or later

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📞 Support

Built by [Kelvin Perez](https://kelvinperez.com) for Sunnyside AC.

For WordPress development questions:
- Use `ddev xdebug on` for debugging
- Check the WordPress admin for theme-specific settings
- Review `functions.php` for custom functionality

## 🛠 Tech Stack

- **Frontend**: Vite 6.3.6, Tailwind CSS v4, ES modules
- **Backend**: PHP 8.3+, WordPress 6.7+, Composer
- **Development**: DDEV (Docker), Xdebug, WP-CLI
- **Database**: MariaDB 10.11
- **Analytics**: PostHog (optional)

---

**Built with ❤️ using modern web technologies**
