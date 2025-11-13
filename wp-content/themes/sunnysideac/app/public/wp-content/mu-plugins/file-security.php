<?php
/**
 * WordPress File System Security
 *
 * @package SunnysideAC
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Block access to sensitive directories
 */
function block_sensitive_directories() {
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';

    $blocked_paths = [
        '/wp-content/mu-plugins/',
        '/wp-content/backup/',
        '/wp-content/cache/',
        '/wp-content/upgrade/',
        'wp-config.php',
        '.htaccess',
        '.env'
    ];

    foreach ($blocked_paths as $path) {
        if (strpos($request_uri, $path) !== false && !is_admin()) {
            error_log('FILE SECURITY: Blocked access to: ' . $request_uri);
            header('HTTP/1.1 403 Forbidden');
            wp_die('Access denied.');
        }
    }
}
add_action('template_redirect', 'block_sensitive_directories');

/**
 * Restrict upload file types to prevent dangerous uploads
 */
function restrict_upload_mimes($mimes) {
    // Remove dangerous file types
    unset($mimes['php']);
    unset($mimes['php3']);
    unset($mimes['php4']);
    unset($mimes['php5']);
    unset($mimes['phtml']);
    unset($mimes['exe']);
    unset($mimes['com']);
    unset($mimes['bat']);
    unset($mimes['cmd']);

    return $mimes;
}
add_filter('upload_mimes', 'restrict_upload_mimes');
