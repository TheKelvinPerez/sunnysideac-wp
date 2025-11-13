<?php
/**
 * Plugin Name: Static Front Page Generator for DDEV
 * Description: Generates static HTML for the homepage to improve Lighthouse performance
 * Version: 0.1
 * Author: Sunnyside AC
 */

if (!defined('ABSPATH')) exit;

class Static_Front_Generator {
    private $output_file;
    private $static_dir;

    public function __construct() {
        $this->static_dir = ABSPATH . 'static/';
        $this->output_file = $this->static_dir . 'index.html';

        // Only enable static generation in production
        if ($this->is_production()) {
            // Create static directory if it doesn't exist
            if (!file_exists($this->static_dir)) {
                wp_mkdir_p($this->static_dir);
            }

            // Hook into content updates
            add_action('save_post', [$this, 'regenerate_if_needed'], 20, 3);
            add_action('wp_update_nav_menu', [$this, 'regenerate_if_needed']);
            add_action('switch_theme', [$this, 'regenerate_now']);
            add_action('customize_save_after', [$this, 'regenerate_now']);
            add_action('update_option', [$this, 'maybe_regenerate_on_option'], 10, 3);

            // Admin notice for regeneration status
            add_action('admin_notices', [$this, 'show_regeneration_notice']);
        }
    }

    private function is_production() {
        return defined('WP_ENV') && WP_ENV === 'production';
    }

    public function maybe_regenerate_on_option($option_name, $old_value, $value) {
        $interesting = ['show_on_front', 'page_on_front', 'page_for_posts'];
        if (in_array($option_name, $interesting, true)) {
            $this->regenerate_now();
        }
    }

    public function regenerate_if_needed($post_ID = 0, $post = null, $update = false) {
        if (wp_is_post_autosave($post_ID) || wp_is_post_revision($post_ID)) return;
        if ($post && $post->post_status !== 'publish') return;

        $front_id = get_option('page_on_front');
        if ($front_id && $post_ID && (int)$post_ID !== (int)$front_id) {
            return; // Not the front page
        }

        $this->regenerate_now();
    }

    public function regenerate_now() {
        if (!is_writable($this->static_dir)) {
            error_log('Static_Front_Generator: Static directory not writable');
            return;
        }

        $home = home_url('/');
        $args = [
            'timeout' => 15,
            'blocking' => true,
            'headers' => [
                'User-Agent' => 'StaticFrontGenerator/1.0',
                'Cache-Control' => 'no-cache',
            ],
        ];

        $resp = wp_remote_get($home, $args);

        if (is_wp_error($resp)) {
            error_log('Static_Front_Generator: wp_remote_get failed: ' . $resp->get_error_message());
            set_transient('static_generation_error', $resp->get_error_message(), 300);
            return;
        }

        $code = wp_remote_retrieve_response_code($resp);
        $body = wp_remote_retrieve_body($resp);

        if ($code !== 200 || empty($body)) {
            error_log('Static_Front_Generator: unexpected response code ' . $code);
            set_transient('static_generation_error', 'Response code: ' . $code, 300);
            return;
        }

        // Optimize the HTML for performance
        $body = $this->optimize_html($body);

        $tmpfile = $this->output_file . '.tmp';
        if (file_put_contents($tmpfile, $body) === false) {
            error_log('Static_Front_Generator: failed writing tmp file');
            set_transient('static_generation_error', 'Failed writing temporary file', 300);
            @unlink($tmpfile);
            return;
        }

        chmod($tmpfile, 0644);
        rename($tmpfile, $this->output_file);

        // Create .htaccess to allow direct access
        $this->create_htaccess();

        // Set success transient
        set_transient('static_generation_success', true, 300);
        error_log('Static_Front_Generator: regenerated ' . $this->output_file);
    }

    private function optimize_html($html) {
        // Remove HTML comments (except conditional ones)
        $html = preg_replace('/<!--(?!\s*\[if)(?!.*<!\[endif])(?!-->)[\s\S]*?-->/', '', $html);

        // Minify whitespace (preserve pre, script, style, textarea)
        $html = preg_replace_callback('/<pre[^>]*>.*?<\/pre>|<script[^>]*>.*?<\/script>|<style[^>]*>.*?<\/style>|<textarea[^>]*>.*?<\/textarea>/is', function($matches) {
            return $matches[0];
        }, $html);

        $html = preg_replace('/\s+/', ' ', $html);
        $html = str_replace('> <', '><', $html);

        return $html;
    }

    private function create_htaccess() {
        $htaccess_content = "# Allow direct access to static files\n<IfModule mod_dir.c>\n    DirectoryIndex index.html index.php\n</IfModule>\n";
        file_put_contents($this->static_dir . '.htaccess', $htaccess_content);
    }

    public function show_regeneration_notice() {
        if ($success = get_transient('static_generation_success')) {
            echo '<div class="notice notice-success is-dismissible"><p>✅ Static homepage regenerated successfully!</p></div>';
        }

        if ($error = get_transient('static_generation_error')) {
            echo '<div class="notice notice-error is-dismissible"><p>❌ Static generation failed: ' . esc_html($error) . '</p></div>';
        }
    }
}

new Static_Front_Generator();