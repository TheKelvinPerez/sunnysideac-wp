<?php

/**
 * Performance Monitoring for Sunnyside AC Theme
 *
 * This file provides performance monitoring utilities to track
 * Core Web Vitals, page load times, and optimization opportunities.
 */

/**
 * Performance metrics collection class
 */
class Sunnyside_Performance_Monitor {

    private $start_time;
    private $memory_start;
    private $queries_start;

    public function __construct() {
        $this->start_time = microtime(true);
        $this->memory_start = memory_get_usage(true);
        $this->queries_start = $this->get_query_count();
    }

    /**
     * Get current number of database queries
     */
    private function get_query_count() {
        global $wpdb;
        return $wpdb->num_queries ?? 0;
    }

    /**
     * Get performance metrics
     */
    public function get_metrics() {
        global $wpdb;

        return [
            'page_load_time' => round((microtime(true) - $this->start_time) * 1000, 2), // ms
            'memory_usage' => round((memory_get_usage(true) - $this->memory_start) / 1024 / 1024, 2), // MB
            'total_memory' => round(memory_get_usage(true) / 1024 / 1024, 2), // MB
            'peak_memory' => round(memory_get_peak_usage(true) / 1024 / 1024, 2), // MB
            'database_queries' => $this->get_query_count() - $this->queries_start,
            'total_queries' => $wpdb->num_queries ?? 0,
            'cache_hits' => wp_cache_get_stats()['hits'] ?? 0,
            'cache_misses' => wp_cache_get_stats()['misses'] ?? 0,
            'redis_connected' => class_exists('Redis') && defined('WP_REDIS_HOST'),
        ];
    }

    /**
     * Log performance metrics (only in development or when debugging is enabled)
     */
    public function log_metrics() {
        if (!defined('WP_DEBUG') || !WP_DEBUG) {
            return;
        }

        $metrics = $this->get_metrics();
        error_log(sprintf(
            '[Sunnyside Performance] Page: %s | Load: %sms | Memory: %sMB | Queries: %d | Redis: %s',
            $_SERVER['REQUEST_URI'] ?? 'unknown',
            $metrics['page_load_time'],
            $metrics['memory_usage'],
            $metrics['database_queries'],
            $metrics['redis_connected'] ? 'Yes' : 'No'
        ));
    }

    /**
     * Add performance metrics to page source (for debugging)
     */
    public function add_debug_info() {
        if (!defined('WP_DEBUG') || !WP_DEBUG || !current_user_can('administrator')) {
            return;
        }

        $metrics = $this->get_metrics();
        ?>
        <!-- Sunnyside Performance Metrics -->
        <div style="position: fixed; bottom: 10px; right: 10px; background: rgba(0,0,0,0.8); color: white; padding: 10px; font: 12px monospace; z-index: 99999; border-radius: 4px;">
            <div>⚡ Load: <?php echo $metrics['page_load_time']; ?>ms</div>
            <div>💾 Memory: <?php echo $metrics['memory_usage']; ?>MB</div>
            <div>🗄️ Queries: <?php echo $metrics['database_queries']; ?></div>
            <div>🚀 Redis: <?php echo $metrics['redis_connected'] ? '✅' : '❌'; ?></div>
        </div>
        <?php
    }
}

// Initialize performance monitor
global $sunnyside_performance_monitor;
$sunnyside_performance_monitor = new Sunnyside_Performance_Monitor();

// Add hooks for performance monitoring
add_action('wp_footer', function() {
    global $sunnyside_performance_monitor;
    $sunnyside_performance_monitor->log_metrics();
    $sunnyside_performance_monitor->add_debug_info();
});

/**
 * Core Web Vitals monitoring
 */
function sunnyside_add_web_vitals_tracking() {
    ?>
    <script>
        // Core Web Vitals monitoring
        function trackWebVitals() {
            // Largest Contentful Paint (LCP)
            new PerformanceObserver((entryList) => {
                const entries = entryList.getEntries();
                const lastEntry = entries[entries.length - 1];
                console.log('LCP:', lastEntry.renderTime || lastEntry.loadTime);

                // Send to analytics if available
                if (typeof gtag !== 'undefined') {
                    gtag('event', 'LCP', {
                        'event_category': 'Web Vitals',
                        'value': Math.round(lastEntry.renderTime || lastEntry.loadTime)
                    });
                }
            }).observe({entryTypes: ['largest-contentful-paint']});

            // First Input Delay (FID)
            new PerformanceObserver((entryList) => {
                for (const entry of entryList.getEntries()) {
                    console.log('FID:', entry.processingStart - entry.startTime);

                    if (typeof gtag !== 'undefined') {
                        gtag('event', 'FID', {
                            'event_category': 'Web Vitals',
                            'value': Math.round(entry.processingStart - entry.startTime)
                        });
                    }
                }
            }).observe({entryTypes: ['first-input']});

            // Cumulative Layout Shift (CLS)
            let clsValue = 0;
            new PerformanceObserver((entryList) => {
                for (const entry of entryList.getEntries()) {
                    if (!entry.hadRecentInput) {
                        clsValue += entry.value;
                    }
                }
                console.log('CLS:', clsValue);

                if (typeof gtag !== 'undefined') {
                    gtag('event', 'CLS', {
                        'event_category': 'Web Vitals',
                        'value': Math.round(clsValue * 1000)
                    });
                }
            }).observe({entryTypes: ['layout-shift']});
        }

        // Start monitoring when page loads
        if ('PerformanceObserver' in window) {
            trackWebVitals();
        }
    </script>
    <?php
}
add_action('wp_head', 'sunnyside_add_web_vitals_tracking', 999);

/**
 * Add performance hints to HTML head
 */
function sunnyside_add_performance_hints() {
    // DNS prefetch for external domains
    $external_domains = [
        'fonts.googleapis.com',
        'fonts.gstatic.com',
        'www.google-analytics.com',
        'stats.wp.com'
    ];

    foreach ($external_domains as $domain) {
        echo '<link rel="dns-prefetch" href="//' . esc_attr($domain) . '">' . "\n";
    }

    // Preconnect to critical external domains
    echo '<link rel="preconnect" href="https://fonts.googleapis.com" crossorigin>' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";

    // Note: Hero image preloads moved to header.php with better conditional logic
}
add_action('wp_head', 'sunnyside_add_performance_hints', 1);

/**
 * Optimize WordPress performance
 */
function sunnyside_optimize_performance() {
    // Remove unnecessary WordPress features
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'wp_shortlink_wp_head');
    remove_action('wp_head', 'adjacent_posts_rel_link_wp_head');
    remove_action('wp_head', 'feed_links_extra', 3);
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'index_rel_link');

    // Remove emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('admin_print_styles', 'print_emoji_styles');

    // Remove WordPress version from scripts and styles
    add_filter('style_loader_src', function($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    });

    add_filter('script_loader_src', function($src) {
        if (strpos($src, 'ver=')) {
            $src = remove_query_arg('ver', $src);
        }
        return $src;
    });

    // Limit post revisions
    if (!defined('WP_POST_REVISIONS')) {
        define('WP_POST_REVISIONS', 3);
    }

    // Optimize database queries
    add_filter('posts_fields', function($fields, $query) {
        // Only select necessary fields in post queries
        if ($query->is_main_query()) {
            return 'ID, post_title, post_content, post_name, post_type, post_status, post_parent, post_date, post_modified, guid, menu_order, comment_count';
        }
        return $fields;
    }, 10, 2);

    // Enable HTTP/2 Server Push support (if server supports it)
    add_filter('script_loader_src', function($src) {
        header('Link: <' . $src . '>; rel=preload; as=script', false);
        return $src;
    });

    add_filter('style_loader_src', function($src) {
        header('Link: <' . $src . '>; rel=preload; as=style', false);
        return $src;
    });
}
add_action('init', 'sunnyside_optimize_performance');

/**
 * Add lazy loading to images
 */
function sunnyside_add_lazy_loading_to_images($content) {
    // Add loading="lazy" to all images
    $content = preg_replace('/<img([^>]+)>/i', '<img$1 loading="lazy">', $content);

    // Add WebP sources to pictures
    $content = preg_replace_callback('/<picture([^>]*)>(.*?)<\/picture>/is', function($matches) {
        $picture_tag = $matches[0];
        // Add WebP source if not present
        if (strpos($picture_tag, 'type="image/webp"') === false) {
            // Extract src from img tag inside picture
            preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $picture_tag, $img_matches);
            if (!empty($img_matches[1])) {
                $webp_src = preg_replace('/\.(jpg|jpeg|png)$/i', '.webp', $img_matches[1]);
                $webp_source = '<source srcset="' . esc_url($webp_src) . '" type="image/webp">';
                $picture_tag = str_replace('<picture', '<picture>' . $webp_source, $picture_tag);
            }
        }
        return $picture_tag;
    }, $content);

    return $content;
}
add_filter('the_content', 'sunnyside_add_lazy_loading_to_images');
add_filter('post_thumbnail_html', 'sunnyside_add_lazy_loading_to_images');