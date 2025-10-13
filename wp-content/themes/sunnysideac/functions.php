<?php

/**
 * Get Vite dev server URL from environment or use defaults
 */
function vite_get_dev_server_url() {
    // Try to load .env file if it exists
    $env_file = get_template_directory() . '/.env';
    $protocol = 'http';
    $host = 'localhost';
    $port = '3000';

    if (file_exists($env_file)) {
        $env_vars = parse_ini_file($env_file);
        if ($env_vars) {
            $protocol = $env_vars['VITE_DEV_SERVER_PROTOCOL'] ?? $protocol;
            $host = $env_vars['VITE_DEV_SERVER_HOST'] ?? $host;
            $port = $env_vars['VITE_DEV_SERVER_PORT'] ?? $port;
        }
    }

    // Allow filtering via WordPress hooks for more flexibility
    $protocol = apply_filters('vite_protocol', $protocol);
    $host = apply_filters('vite_host', $host);
    $port = apply_filters('vite_port', $port);

    return "{$protocol}://{$host}:{$port}";
}

/**
 * Check if Vite dev server is running
 */
function vite_is_dev_server_running() {
    $vite_dev_server = vite_get_dev_server_url();

    // Use file_get_contents with stream context for better compatibility
    $context = stream_context_create([
        'http' => [
            'timeout' => 1,
            'ignore_errors' => true
        ]
    ]);

    $result = @file_get_contents($vite_dev_server, false, $context);

    // Check if we got a response (even if it's an error page, server is running)
    return $result !== false || (isset($http_response_header) && !empty($http_response_header));
}

/**
 * Enqueue Vite assets
 */
function theme_enqueue_assets() {
    $is_dev = vite_is_dev_server_running();
    $vite_server_url = vite_get_dev_server_url();

    if ($is_dev) {
        // Development mode: Load from Vite dev server
        wp_enqueue_script(
            'theme-vite-client',
            $vite_server_url . '/@vite/client',
            array(),
            null,
            false
        );
        wp_script_add_data('theme-vite-client', 'type', 'module');

        wp_enqueue_script(
            'theme-main',
            $vite_server_url . '/src/main.js',
            array('theme-vite-client'),
            null,
            false
        );
        wp_script_add_data('theme-main', 'type', 'module');
    } else {
        // Production mode: Load built assets
        $manifest_path = get_template_directory() . '/dist/.vite/manifest.json';

        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);

            if (isset($manifest['src/main.js'])) {
                $main = $manifest['src/main.js'];

                // Enqueue CSS
                if (isset($main['css'])) {
                    foreach ($main['css'] as $css_file) {
                        wp_enqueue_style(
                            'theme-main',
                            get_template_directory_uri() . '/dist/' . $css_file,
                            array(),
                            null
                        );
                    }
                }

                // Enqueue JS
                wp_enqueue_script(
                    'theme-main',
                    get_template_directory_uri() . '/dist/' . $main['file'],
                    array(),
                    null,
                    true
                );
                wp_script_add_data('theme-main', 'type', 'module');
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'theme_enqueue_assets');

/**
 * Theme setup
 */
function theme_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
    ));

    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'theme'),
        'footer' => __('Footer Menu', 'theme'),
    ));
}
add_action('after_setup_theme', 'theme_setup');

/**
 * Add type="module" to Vite scripts
 */
function theme_add_type_attribute($tag, $handle, $src) {
    if ('theme-vite-client' === $handle || 'theme-main' === $handle) {
        // Remove the type="text/javascript" attribute and replace with type="module"
        $tag = str_replace("type='text/javascript'", "type='module'", $tag);
        $tag = str_replace('type="text/javascript"', 'type="module"', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'theme_add_type_attribute', 10, 3);
