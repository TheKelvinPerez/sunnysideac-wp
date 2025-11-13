<?php
/**
 * WordPress Configuration Template with Security Hardening
 *
 * INSTRUCTIONS:
 * 1. Copy this file to wp-config.php
 * 2. Generate new security keys: https://api.wordpress.org/secret-key/1.1/salt/
 * 3. Update database credentials (or use DDEV auto-config)
 * 4. Adjust security settings for your environment (see comments)
 *
 * @package WordPress
 */

define( 'WP_CACHE', false );

/**
 * Database Configuration
 *
 * For DDEV: This is auto-loaded from wp-config-ddev.php
 * For production: Set DB_NAME, DB_USER, DB_PASSWORD, DB_HOST manually
 */

// Include for ddev-managed settings in wp-config-ddev.php.
$ddev_settings = __DIR__ . '/wp-config-ddev.php';
if ( is_readable( $ddev_settings ) && ! defined( 'DB_USER' ) ) {
	require_once $ddev_settings;
}

// For production, uncomment and fill in:
// define( 'DB_NAME', 'database_name_here' );
// define( 'DB_USER', 'username_here' );
// define( 'DB_PASSWORD', 'password_here' );
// define( 'DB_HOST', 'localhost' );
// define( 'DB_CHARSET', 'utf8mb4' );
// define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * IMPORTANT: Generate new keys from https://api.wordpress.org/secret-key/1.1/salt/
 * These should be unique for each installation!
 */
define( 'AUTH_KEY',         'put your unique phrase here' );
define( 'SECURE_AUTH_KEY',  'put your unique phrase here' );
define( 'LOGGED_IN_KEY',    'put your unique phrase here' );
define( 'NONCE_KEY',        'put your unique phrase here' );
define( 'AUTH_SALT',        'put your unique phrase here' );
define( 'SECURE_AUTH_SALT', 'put your unique phrase here' );
define( 'LOGGED_IN_SALT',   'put your unique phrase here' );
define( 'NONCE_SALT',       'put your unique phrase here' );
define( 'WP_CACHE_KEY_SALT', 'put your unique phrase here' );
/**#@-*/

/**
 * WordPress database table prefix.
 */
$table_prefix = 'wp_';

/**
 * WordPress debugging mode.
 *
 * Set to true for local development, false for production
 */
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

/**
 * Environment type
 *
 * Options: 'local', 'development', 'staging', 'production'
 */
define( 'WP_ENVIRONMENT_TYPE', 'local' );

/**
 * ============================================================================
 * SECURITY HARDENING CONFIGURATION
 * ============================================================================
 *
 * These settings work in conjunction with the mu-plugins security files:
 * - wp-content/mu-plugins/waf-protection.php
 * - wp-content/mu-plugins/login-security.php
 * - wp-content/mu-plugins/file-security.php
 * - wp-content/mu-plugins/security-hardening.php
 * - wp-content/mu-plugins/security-monitoring.php
 */

// Disable file editing from WordPress admin
define('DISALLOW_FILE_EDIT', true);

// Disable plugin/theme installation from admin (set true for production)
define('DISALLOW_FILE_MODS', false); // false for local dev, true for production

// Debug logging (disable in production)
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);

// Force SSL for admin (set true for production)
define('FORCE_SSL_ADMIN', false); // false for DDEV/local, true for production with SSL

// Automatic WordPress core updates
define('WP_AUTO_UPDATE_CORE', true);
define('AUTOMATIC_UPDATER_DISABLED', false);

// Cookie security
define('COOKIE_HTTPONLY', true);
// Uncomment for production with SSL:
// define('COOKIE_SECURE', true);
// define('COOKIE_DOMAIN', '.yourdomain.com');

// Disable unfiltered file uploads
define('ALLOW_UNFILTERED_UPLOADS', false);

/**
 * ============================================================================
 * PRODUCTION DEPLOYMENT CHECKLIST
 * ============================================================================
 *
 * Before deploying to production:
 *
 * 1. Security Settings:
 *    - Set DISALLOW_FILE_MODS to true
 *    - Set FORCE_SSL_ADMIN to true
 *    - Set COOKIE_SECURE to true
 *    - Set COOKIE_DOMAIN to your domain
 *    - Set WP_DEBUG to false
 *    - Set WP_ENVIRONMENT_TYPE to 'production'
 *
 * 2. Generate Fresh Security Keys:
 *    - Visit https://api.wordpress.org/secret-key/1.1/salt/
 *    - Replace all keys above
 *
 * 3. Update Database Credentials:
 *    - Remove DDEV auto-config section
 *    - Set production DB_NAME, DB_USER, DB_PASSWORD, DB_HOST
 *
 * 4. Verify Security Plugins Active:
 *    - Check wp-content/mu-plugins/ directory exists
 *    - Verify all 5 security .php files are present
 *    - Verify .htaccess is present in mu-plugins/
 *
 * 5. Update robots.txt:
 *    - Copy robots-template.txt to robots.txt
 *    - Update sitemap URL to production domain
 *
 * 6. Test Security:
 *    - Test XSS protection: /?test=<script>alert('xss') (should return 403)
 *    - Test SQL injection: /?test=union+select (should return 403)
 *    - Test XML-RPC: /xmlrpc.php (should return 403)
 *    - Test sitemap: /sitemap_index.xml (should return 200)
 */

/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
