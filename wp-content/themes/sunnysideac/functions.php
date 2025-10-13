<?php

/**
 * Get Vite dev server URL from environment or use defaults
 */
function sunnysideac_get_vite_dev_server_url() {
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
    $protocol = apply_filters('sunnysideac_vite_protocol', $protocol);
    $host = apply_filters('sunnysideac_vite_host', $host);
    $port = apply_filters('sunnysideac_vite_port', $port);

    return "{$protocol}://{$host}:{$port}";
}

/**
 * Check if Vite dev server is running
 */
function sunnysideac_is_vite_dev_server_running() {
    $vite_dev_server = sunnysideac_get_vite_dev_server_url();

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
function sunnysideac_enqueue_assets() {
    $is_dev = sunnysideac_is_vite_dev_server_running();
    $vite_server_url = sunnysideac_get_vite_dev_server_url();

    if ($is_dev) {
        // Development mode: Load from Vite dev server
        wp_enqueue_script(
            'sunnysideac-vite-client',
            $vite_server_url . '/@vite/client',
            array(),
            null,
            false
        );
        wp_script_add_data('sunnysideac-vite-client', 'type', 'module');

        wp_enqueue_script(
            'sunnysideac-main',
            $vite_server_url . '/src/main.js',
            array('sunnysideac-vite-client'),
            null,
            false
        );
        wp_script_add_data('sunnysideac-main', 'type', 'module');
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
                            'sunnysideac-main',
                            get_template_directory_uri() . '/dist/' . $css_file,
                            array(),
                            null
                        );
                    }
                }

                // Enqueue JS
                wp_enqueue_script(
                    'sunnysideac-main',
                    get_template_directory_uri() . '/dist/' . $main['file'],
                    array(),
                    null,
                    true
                );
                wp_script_add_data('sunnysideac-main', 'type', 'module');
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'sunnysideac_enqueue_assets');
