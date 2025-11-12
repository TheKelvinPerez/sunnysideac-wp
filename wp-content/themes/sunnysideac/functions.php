<?php
/**
 * Sunnyside AC Theme Functions
 *
 * Main orchestrator file that loads modular functionality.
 * This file has been refactored for better maintainability and organization.
 *
 * @package SunnysideAC
 */

/**
 * Load Composer autoloader
 */
require_once __DIR__ . '/vendor/autoload.php';

/**
 * Load environment variables
 */
$dotenv = Dotenv\Dotenv::createImmutable( __DIR__ );
$dotenv->load();

/**
 * Load core constants and helpers first
 */
require_once __DIR__ . '/inc/constants.php';
require_once __DIR__ . '/inc/helpers.php';

/**
 * Load development and environment setup
 */
require_once __DIR__ . '/inc/development/environment.php';
require_once __DIR__ . '/inc/development/debug-helpers.php';

/**
 * Load core theme functionality
 */
require_once __DIR__ . '/inc/core/theme-setup.php';
require_once __DIR__ . '/inc/core/post-types-taxonomies.php';
require_once __DIR__ . '/inc/core/routing.php';
require_once __DIR__ . '/inc/core/template-hierarchy.php';

/**
 * Load asset management and optimizations
 */
require_once __DIR__ . '/inc/assets/enqueue.php';
require_once __DIR__ . '/inc/assets/cdn.php';
require_once __DIR__ . '/inc/assets/optimizations.php';

/**
 * Load SEO functionality
 */
require_once __DIR__ . '/inc/seo/schema.php';
require_once __DIR__ . '/inc/seo/meta-tags.php';

/**
 * Load form handlers
 */
require_once __DIR__ . '/inc/forms/careers-handler.php';
require_once __DIR__ . '/inc/forms/warranty-handler.php';

/**
 * Load performance optimizations
 */
require_once __DIR__ . '/inc/performance/optimizations.php';
require_once __DIR__ . '/inc/performance/webp-support.php';

/**
 * Load content utilities
 */
require_once __DIR__ . '/inc/content/blog-helpers.php';
require_once __DIR__ . '/inc/content/faq-helpers.php';
require_once __DIR__ . '/inc/content/content-cleanup.php';

/**
 * Load existing navigation and tracking systems
 * These files contain established functionality that hasn't been refactored yet
 */
require_once __DIR__ . '/inc/navigation.php';
require_once __DIR__ . '/inc/main-navigation-helper.php';
require_once __DIR__ . '/inc/footer-menu-helper.php';
require_once __DIR__ . '/inc/posthog-tracking.php';
require_once __DIR__ . '/inc/custom-sitemap-generator.php';

/**
 * AJAX Pagination
 */
require_once __DIR__ . '/inc/ajax-pagination.php';

/**
 * Note: Performance monitoring temporarily disabled
 * Uncomment when ready to re-enable:
 * require_once __DIR__ . '/inc/performance-monitor.php';
 */