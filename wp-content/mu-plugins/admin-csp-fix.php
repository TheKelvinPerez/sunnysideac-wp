<?php
/**
 * Plugin Name: Admin CSP Fix
 * Description: Allow unsafe-eval for WordPress admin area functionality
 * Version: 1.0
 * Author: System Administrator
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add CSP headers for admin area with unsafe-eval
 */
function admin_csp_fix_headers() {
    // Only apply to admin area and login page
    if (is_admin() || $GLOBALS['pagenow'] === 'wp-login.php') {
        // Remove existing CSP headers if any
        header_remove('Content-Security-Policy');
        header_remove('Permissions-Policy');

        // Add permissive CSP for admin functionality
        header('Content-Security-Policy: default-src \'self\'; script-src \'self\' \'unsafe-inline\' \'unsafe-eval\' blob: https://connect.facebook.net https://www.facebook.com https://us-assets.i.posthog.com https://eu-assets.i.posthog.com https://app.posthog.com https://us.i.posthog.com https://eu.i.posthog.com; worker-src \'self\' blob:; style-src \'self\' \'unsafe-inline\' https://fonts.googleapis.com; font-src \'self\' data: https://fonts.gstatic.com; img-src \'self\' data: blob: https://www.facebook.com https://connect.facebook.net https://sunnysideac.b-cdn.net https://secure.gravatar.com https://images.unsplash.com https://assets.seobotai.com; connect-src \'self\' https://www.facebook.com https://graph.facebook.com https://us.i.posthog.com https://eu.i.posthog.com https://app.posthog.com https://us-assets.i.posthog.com https://eu-assets.i.posthog.com https://sunnysideac.b-cdn.net; frame-src \'self\' blob: https://www.facebook.com; object-src \'none\'; base-uri \'self\'; form-action \'self\'; upgrade-insecure-requests;');
    }
}

// Hook early before headers are sent
add_action('send_headers', 'admin_csp_fix_headers', 1);

// Also hook into admin_init for login page specific handling
add_action('login_init', 'admin_csp_fix_headers', 1);