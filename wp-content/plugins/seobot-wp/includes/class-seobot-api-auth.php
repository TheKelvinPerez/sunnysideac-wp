<?php
/**
 * SEObot API Authentication Class
 *
 * @package SEObot API
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Authentication class
 */
class SEObot_API_Auth
{
    /**
     * Constructor
     */
    public function __construct()
    {
        // Authentication is now handled directly in the API class
    }

    /**
     * Authenticate API requests using API key via Basic Auth
     *
     * @param WP_Error|null|bool $error Error from another authentication handler,
     *                                   null if we should handle it, or another value
     *                                   if not.
     * @return WP_Error|null|bool
     */
    public function authenticate_api_key($error)
    {
        // Pass through errors from other authentication methods
        if (is_wp_error($error) || null !== $error) {
            return $error;
        }

        // Check if we're on our custom API route
        $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';

        // More lenient check for our route path
        if (strpos($request_uri, 'seobot/v1') === false) {
            return $error;
        }

        // Get stored API key
        $stored_api_key = get_option('seobot_api_key');
        if (empty($stored_api_key)) {
            return new WP_Error(
                'rest_authentication_error',
                __('API key is not configured.', 'seobot-api'),
                array('status' => 403)
            );
        }

        // Get API key from Basic Auth
        $api_key = $this->get_auth_key();

        // If no API key is provided, return an error
        if (empty($api_key)) {
            return new WP_Error(
                'rest_authentication_error',
                __('No API key provided. Please use Basic Authentication with your API key as the username.', 'seobot-api'),
                array('status' => 401)
            );
        }

        // Validate API key
        if ($api_key !== $stored_api_key) {
            return new WP_Error(
                'rest_authentication_error',
                __('Invalid API key.', 'seobot-api'),
                array('status' => 401)
            );
        }

        // Set the default author for this request
        $default_author_id = get_option('seobot_api_default_author');
        if ($default_author_id) {
            wp_set_current_user($default_author_id);
        }

        // Authentication successful
        return true;
    }

    /**
     * Get API key from Basic Auth
     *
     * @return string|null API key if found, null otherwise
     */
    private function get_auth_key()
    {
        // 1. Check for Authorization header (Basic Auth)
        $authorization_header = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';

        // For some servers, HTTP_AUTHORIZATION isn't available but REDIRECT_HTTP_AUTHORIZATION is
        if (empty($authorization_header) && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $authorization_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        }

        if (!empty($authorization_header) && strpos($authorization_header, 'Basic ') === 0) {
            // Basic auth - extract username as API key (password can be anything)
            $auth_header = base64_decode(substr($authorization_header, 6));
            if (strpos($auth_header, ':') !== false) {
                list($username, $password) = explode(':', $auth_header, 2);
                return sanitize_text_field($username);
            }
        }

        // 2. Check for custom X-SEObot-Auth header as fallback
        // This is useful when the Authorization header is stripped by the server
        if (isset($_SERVER['HTTP_X_SEOBOT_AUTH'])) {
            $x_seobot_auth = $_SERVER['HTTP_X_SEOBOT_AUTH'];

            // X-SEObot-Auth should contain the same base64 encoded credentials as Authorization header
            if (!empty($x_seobot_auth)) {
                // If it starts with "Basic ", strip it
                if (strpos($x_seobot_auth, 'Basic ') === 0) {
                    $x_seobot_auth = substr($x_seobot_auth, 6);
                }

                // Decode the base64 credentials
                $auth_header = base64_decode($x_seobot_auth);
                if (strpos($auth_header, ':') !== false) {
                    list($username, $password) = explode(':', $auth_header, 2);
                    return sanitize_text_field($username);
                }
            }
        }

        // Special handling for WordPress compatibility mode
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            return sanitize_text_field($_SERVER['PHP_AUTH_USER']);
        }

        return null;
    }
}