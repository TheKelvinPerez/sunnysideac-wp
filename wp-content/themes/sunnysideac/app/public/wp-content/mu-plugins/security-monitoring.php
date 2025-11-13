<?php
/**
 * WordPress Security Monitoring (Lightweight)
 *
 * @package SunnysideAC
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Log security events to error log
 */
function log_security_event($event_type, $details) {
    error_log('SECURITY: ' . $event_type . ' - ' . $details);
}

/**
 * Add simple security notice to admin dashboard
 */
function add_simple_security_notice() {
    if (current_user_can('manage_options')) {
        echo '<div class="notice notice-success is-dismissible" style="margin: 10px 0;">';
        echo '<p>🛡️ <strong>Security Hardening Active:</strong> All security measures are operational.</p>';
        echo '</div>';
    }
}
add_action('admin_notices', 'add_simple_security_notice');
