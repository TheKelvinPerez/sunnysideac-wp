<?php

/**
 * Debug helper function - Dump and Die (with pretty output)
 *
 * @param mixed $var Variable to dump
 */
if (!function_exists('dd')) {
    function dd($var) {
        echo '<pre>';

        if (is_array($var) || is_object($var)) {
            echo json_encode($var, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } else {
            var_dump($var);
        }

        echo '</pre>';
        // exit;
    }
}

/**
 * Include global constants
 */
require_once get_template_directory() . '/inc/constants.php';

/**
 * Include hero configuration
 */
require_once get_template_directory() . '/inc/hero-config.php';

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

/**
 * Theme setup
 */
function sunnysideac_setup() {
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
        'primary' => __('Primary Menu', 'sunnysideac'),
        'footer' => __('Footer Menu', 'sunnysideac'),
    ));
}
add_action('after_setup_theme', 'sunnysideac_setup');

/**
 * Add SEO meta box to pages and posts
 */
function sunnysideac_add_seo_meta_box() {
    add_meta_box(
        'sunnysideac_seo_meta',
        'SEO Settings',
        'sunnysideac_seo_meta_box_callback',
        array('page', 'post'),
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'sunnysideac_add_seo_meta_box');

/**
 * SEO meta box callback
 */
function sunnysideac_seo_meta_box_callback($post) {
    wp_nonce_field('sunnysideac_save_seo_meta', 'sunnysideac_seo_nonce');

    $description = get_post_meta($post->ID, '_seo_description', true);
    $keywords = get_post_meta($post->ID, '_seo_keywords', true);
    $canonical = get_post_meta($post->ID, '_seo_canonical', true);
    ?>
    <div style="margin: 10px 0;">
        <label for="seo_description" style="display: block; margin-bottom: 5px; font-weight: bold;">Meta Description</label>
        <textarea id="seo_description" name="seo_description" rows="3" style="width: 100%;"><?php echo esc_textarea($description); ?></textarea>
        <p class="description">Recommended length: 150-160 characters</p>
    </div>

    <div style="margin: 10px 0;">
        <label for="seo_keywords" style="display: block; margin-bottom: 5px; font-weight: bold;">Meta Keywords</label>
        <input type="text" id="seo_keywords" name="seo_keywords" value="<?php echo esc_attr($keywords); ?>" style="width: 100%;">
        <p class="description">Comma-separated keywords (e.g., hvac, air conditioning, repair)</p>
    </div>

    <div style="margin: 10px 0;">
        <label for="seo_canonical" style="display: block; margin-bottom: 5px; font-weight: bold;">Canonical URL</label>
        <input type="url" id="seo_canonical" name="seo_canonical" value="<?php echo esc_url($canonical); ?>" style="width: 100%;">
        <p class="description">Leave blank to use the default permalink</p>
    </div>
    <?php
}

/**
 * Save SEO meta box data
 */
function sunnysideac_save_seo_meta($post_id) {
    // Check nonce
    if (!isset($_POST['sunnysideac_seo_nonce']) || !wp_verify_nonce($_POST['sunnysideac_seo_nonce'], 'sunnysideac_save_seo_meta')) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save meta fields
    if (isset($_POST['seo_description'])) {
        update_post_meta($post_id, '_seo_description', sanitize_textarea_field($_POST['seo_description']));
    }

    if (isset($_POST['seo_keywords'])) {
        update_post_meta($post_id, '_seo_keywords', sanitize_text_field($_POST['seo_keywords']));
    }

    if (isset($_POST['seo_canonical'])) {
        update_post_meta($post_id, '_seo_canonical', esc_url_raw($_POST['seo_canonical']));
    }
}
add_action('save_post', 'sunnysideac_save_seo_meta');

/**
 * Add type="module" to Vite scripts
 */
function sunnysideac_add_type_attribute($tag, $handle) {
    if ('sunnysideac-vite-client' === $handle || 'sunnysideac-main' === $handle) {
        // Remove the type="text/javascript" attribute and replace with type="module"
        $tag = str_replace("type='text/javascript'", "type='module'", $tag);
        $tag = str_replace('type="text/javascript"', 'type="module"', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'sunnysideac_add_type_attribute', 10, 3);
