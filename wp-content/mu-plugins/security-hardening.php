<?php
/**
 * Core Security Hardening
 *
 * @package SunnysideAC
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Disable XML-RPC (major attack vector)
 */
add_filter('xmlrpc_enabled', '__return_false');

/**
 * Block XML-RPC requests at PHP level
 */
function block_xmlrpc_requests() {
    if (strpos($_SERVER['REQUEST_URI'], 'xmlrpc.php') !== false) {
        header('HTTP/1.1 403 Forbidden');
        exit;
    }
}
add_action('init', 'block_xmlrpc_requests', 1);

/**
 * Hide login errors (security through obscurity)
 */
function hide_login_errors() {
    return 'Invalid username or password.';
}
add_filter('login_errors', 'hide_login_errors');

/**
 * Remove WordPress version from head and feeds (information disclosure)
 */
remove_action('wp_head', 'wp_generator');
add_filter('the_generator', '__return_empty_string');

/**
 * SEO-Friendly: Ensure sitemaps can be indexed properly
 */
function ensure_sitemap_indexing() {
    if (strpos($_SERVER['REQUEST_URI'], 'sitemap') !== false) {
        header_remove('X-Robots-Tag');
        header('X-Robots-Tag: index, follow');
    }
}
add_action('send_headers', 'ensure_sitemap_indexing');

/**
 * Disable file editing from WordPress admin
 */
if (!defined('DISALLOW_FILE_EDIT')) {
    define('DISALLOW_FILE_EDIT', true);
}

/**
 * Remove unnecessary WordPress meta tags
 */
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_shortlink_wp_head');
