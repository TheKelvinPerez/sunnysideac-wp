<?php
/**
 * WordPress Login Security Enhancement
 *
 * @package SunnysideAC
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enhanced login rate limiting
 */
function enhanced_login_rate_limiting() {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $current_time = time();

    // Get failed attempts from last 15 minutes
    $recent_attempts = get_transient('login_failed_attempts_' . $ip) ?: [];
    $recent_attempts = array_filter($recent_attempts, function($timestamp) use ($current_time) {
        return ($current_time - $timestamp) < 900; // 15 minutes
    });

    // Block if too many recent attempts (5 in 15 minutes)
    if (count($recent_attempts) >= 5) {
        header('HTTP/1.1 429 Too Many Requests');
        wp_die('Too many login attempts. Please try again in 15 minutes.');
    }

    $recent_attempts[] = $current_time;
    set_transient('login_failed_attempts_' . $ip, $recent_attempts, 900);
}
add_action('login_init', 'enhanced_login_rate_limiting');

/**
 * Log failed login attempts
 */
function log_failed_login_attempts($username) {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    $current_time = time();

    $log_entry = sprintf(
        "[%s] Failed login attempt - Username: %s, IP: %s\n",
        date('Y-m-d H:i:s'),
        $username,
        $ip
    );

    error_log('LOGIN FAILED: ' . $log_entry);

    // Update transient for rate limiting
    $recent_attempts = get_transient('login_failed_attempts_' . $ip) ?: [];
    $recent_attempts[] = $current_time;
    set_transient('login_failed_attempts_' . $ip, $recent_attempts, 900);
}
add_action('wp_login_failed', 'log_failed_login_attempts');

/**
 * Hide login error details (security through obscurity)
 */
function enhance_login_error_hiding($error) {
    return 'ERROR: Invalid username or password.';
}
add_filter('login_errors', 'enhance_login_error_hiding');
