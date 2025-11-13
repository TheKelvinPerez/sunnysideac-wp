<?php
/**
 * Plugin Name: SEObot
 * Plugin URI: https://seobotai.com/
 * Description: Custom API endpoints for managing blog posts with additional features for SEObot integration.
 * Version: 1.0.0
 * Author: SEObot
 * Author URI: https://seobotai.com/
 * Text Domain: seobot-api
 * Domain Path: /languages
 * License: GPL v2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('SEOBOT_API_VERSION', '1.0.0');
define('SEOBOT_API_FILE', __FILE__);
define('SEOBOT_API_PATH', plugin_dir_path(__FILE__));
define('SEOBOT_API_URL', plugin_dir_url(__FILE__));
define('SEOBOT_API_BASENAME', plugin_basename(__FILE__));

// Include required files
require_once SEOBOT_API_PATH . 'includes/class-seobot-api.php';
require_once SEOBOT_API_PATH . 'includes/class-seobot-api-endpoints.php';
require_once SEOBOT_API_PATH . 'includes/class-seobot-api-settings.php';
require_once SEOBOT_API_PATH . 'includes/class-seobot-api-auth.php';
require_once SEOBOT_API_PATH . 'includes/class-seobot-api-seo-integration.php';

// Add settings link to plugins page
add_filter('plugin_action_links_' . SEOBOT_API_BASENAME, 'seobot_api_add_settings_link');
function seobot_api_add_settings_link($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=seobot-settings') . '">' . __('Settings', 'seobot-api') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

// Initialize the plugin
function seobot_api_init() {
    $seobot_api = new SEObot_API();
    $seobot_api->init();
}
add_action('plugins_loaded', 'seobot_api_init');

// Activation hook
register_activation_hook(__FILE__, 'seobot_api_activate');
function seobot_api_activate() {
    // Generate and save API key if it doesn't exist
    if (!get_option('seobot_api_key')) {
        $api_key = seobot_api_generate_key();
        update_option('seobot_api_key', $api_key);
    }

    // Set default settings if they don't exist
    if (!get_option('seobot_api_default_category')) {
        update_option('seobot_api_default_category', 1); // Default category ID
    }

    if (!get_option('seobot_api_default_author')) {
        $users = get_users(['role' => 'administrator', 'number' => 1]);
        if (!empty($users)) {
            update_option('seobot_api_default_author', $users[0]->ID);
        }
    }

    // Set default post type if it doesn't exist
    if (!get_option('seobot_api_default_post_type')) {
        update_option('seobot_api_default_post_type', 'post'); // Default to 'post' type
    }

    // Set flag to flush rewrite rules
    update_option('seobot_api_flush_rewrite', true);
}

// Deactivation hook
register_deactivation_hook(__FILE__, 'seobot_api_deactivate');
function seobot_api_deactivate() {
    // Flush rewrite rules
    flush_rewrite_rules();
}

// Generate API key
function seobot_api_generate_key() {
    $site_url = get_site_url();
    $wp_version = get_bloginfo('version');
    $salt = wp_generate_password(32, true, true);

    // Create a unique key based on site URL, WP version, and a random salt
    $data = $site_url . $wp_version . $salt . time();
    $api_key = wp_hash($data);

    return $api_key;
}

// AJAX action to regenerate API key
add_action('wp_ajax_seobot_regenerate_api_key', 'seobot_ajax_regenerate_api_key');
function seobot_ajax_regenerate_api_key() {
    // Check nonce
    check_ajax_referer('seobot_regenerate_api_key', 'nonce');

    // Check user permissions
    if (!current_user_can('manage_options')) {
        wp_send_json_error(__('You do not have permission to perform this action.', 'seobot-api'));
        return;
    }

    // Generate a new API key
    $api_key = seobot_api_generate_key();

    // Save the new key
    update_option('seobot_api_key', $api_key);

    // Return the new key
    wp_send_json_success($api_key);
}