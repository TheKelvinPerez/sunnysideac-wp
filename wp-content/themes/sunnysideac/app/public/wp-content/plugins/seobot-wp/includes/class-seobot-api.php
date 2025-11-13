<?php
/**
 * Main SEObot API Class
 *
 * @package SEObot API
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Main plugin class
 */
class SEObot_API
{
    /**
     * API Endpoints instance
     *
     * @var SEObot_API_Endpoints
     */
    private $endpoints;

    /**
     * API Settings instance
     *
     * @var SEObot_API_Settings
     */
    private $settings;

    /**
     * API Authentication instance
     *
     * @var SEObot_API_Auth
     */
    private $auth;

    /**
     * SEO Integration instance
     *
     * @var SEObot_API_SEO_Integration
     */
    private $seo;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Nothing to do here, initialization happens in init() method
    }

    /**
     * Initialize the plugin
     */
    public function init()
    {
        // Initialize auth class
        $this->auth = new SEObot_API_Auth();

        // Initialize settings class
        $this->settings = new SEObot_API_Settings();

        // Initialize SEO integration class
        $this->seo = new SEObot_API_SEO_Integration();

        // Initialize endpoints class with SEO integration
        $this->endpoints = new SEObot_API_Endpoints($this->seo);

        // Add admin menu
        add_action('admin_menu', array($this->settings, 'add_admin_menu'));

        // Register settings
        add_action('admin_init', array($this->settings, 'register_settings'));

        // Add the direct endpoint handler for all API requests
        add_action('init', array($this, 'add_direct_endpoint_rules'), 0);
        add_action('parse_request', array($this, 'handle_direct_api_requests'), 0);
    }

    /**
     * Add direct endpoint rules for the API
     */
    public function add_direct_endpoint_rules()
    {
        // This route will handle all API requests
        add_rewrite_rule(
            '^seobot/v1/(.+)/?$',
            'index.php?seobot_direct_endpoint=$matches[1]',
            'top'
        );

        // Add a rule for the base endpoint without a trailing endpoint identifier
        add_rewrite_rule(
            '^seobot/v1/?$',
            'index.php?seobot_direct_endpoint=',
            'top'
        );

        // Register the query var
        add_filter('query_vars', function ($vars) {
            $vars[] = 'seobot_direct_endpoint';
            return $vars;
        });

        // Flush rewrite rules only on plugin activation or settings save
        if (get_option('seobot_api_flush_rewrite', false)) {
            flush_rewrite_rules();
            delete_option('seobot_api_flush_rewrite');
        }
    }

    /**
     * Handle direct API requests
     *
     * @param WP_Query $query The WordPress query object
     * @return void
     */
    public function handle_direct_api_requests($query)
    {
        $endpoint = isset($query->query_vars['seobot_direct_endpoint']) ? $query->query_vars['seobot_direct_endpoint'] : '';

        if (empty($endpoint)) {
            return;
        }

        // Authentication check
        $stored_api_key = get_option('seobot_api_key');

        // Get API key from Basic Auth (now with debug info)
        $auth_result = $this->get_auth_key_from_request_with_debug();
        $api_key = $auth_result['api_key'];
        $auth_debug = $auth_result['debug'];

        if (empty($api_key) || $api_key !== $stored_api_key) {
            // Prepare debug information
            $debug_info = array(
                'headers_received' => array(),
                'auth_method_tried' => array(),
                'api_key_found' => !empty($api_key),
                'api_key_valid' => !empty($api_key) && $api_key === $stored_api_key,
                'auth_extraction_debug' => $auth_debug,
            );

            // Check which headers were received
            if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
                $debug_info['headers_received']['Authorization'] = 'Present';
                $debug_info['auth_method_tried'][] = 'Authorization header';
            }
            if (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
                $debug_info['headers_received']['REDIRECT_HTTP_AUTHORIZATION'] = 'Present';
                $debug_info['auth_method_tried'][] = 'Redirect Authorization header';
            }
            if (isset($_SERVER['HTTP_X_SEOBOT_AUTH'])) {
                $debug_info['headers_received']['X-SEObot-Auth'] = 'Present';
                $debug_info['auth_method_tried'][] = 'X-SEObot-Auth header';
            }
            if (isset($_SERVER['PHP_AUTH_USER'])) {
                $debug_info['headers_received']['PHP_AUTH_USER'] = 'Present';
                $debug_info['auth_method_tried'][] = 'PHP Auth User';
            }

            // Add all headers for debugging (sanitized)
            $all_headers = array();
            foreach ($_SERVER as $key => $value) {
                if (strpos($key, 'HTTP_') === 0) {
                    $header_name = str_replace('HTTP_', '', $key);
                    $header_name = str_replace('_', '-', $header_name);
                    // Don't expose the actual auth values
                    if (in_array($key, array('HTTP_AUTHORIZATION', 'HTTP_X_SEOBOT_AUTH', 'REDIRECT_HTTP_AUTHORIZATION'))) {
                        $all_headers[$header_name] = 'Present (value hidden)';
                    } else {
                        $all_headers[$header_name] = is_string($value) ? substr($value, 0, 100) : 'Non-string value';
                    }
                }
            }
            $debug_info['all_http_headers'] = $all_headers;

            $this->send_json_response(array(
                'code' => 'authentication_error',
                'message' => 'Invalid API key or missing authentication',
                'data' => array('status' => 401),
                'debug' => $debug_info
            ), 401);
            exit;
        }

        // Set the default author for this request
        $default_author_id = get_option('seobot_api_default_author');
        if ($default_author_id) {
            wp_set_current_user($default_author_id);
        }

        // Parse the endpoint and the HTTP method
        $http_method = $_SERVER['REQUEST_METHOD'];

        // Forward the request to our endpoints handler
        $this->process_direct_endpoint($endpoint, $http_method);
        exit;
    }

    /**
     * Process a direct endpoint request
     *
     * @param string $endpoint The endpoint path
     * @param string $method The HTTP method
     * @return void
     */
    private function process_direct_endpoint($endpoint, $method)
    {
        // Parse endpoint parts
        $endpoint_parts = explode('/', trim($endpoint, '/'));
        $main_endpoint = array_shift($endpoint_parts);

        // Get query parameters
        $query_params = $_GET;

        // Remove our query var
        if (isset($query_params['seobot_direct_endpoint'])) {
            unset($query_params['seobot_direct_endpoint']);
        }

        // Create a WP_REST_Request-compatible object
        $request = new WP_REST_Request($method, '/seobot/v1/' . $main_endpoint);

        // Add query parameters
        foreach ($query_params as $key => $value) {
            $request->set_param($key, $value);
        }

        // Add any URL path parameters (like ID)
        if (!empty($endpoint_parts[0]) && is_numeric($endpoint_parts[0])) {
            $request->set_param('id', (int) $endpoint_parts[0]);
        }

        // Set post_type for post-related endpoints
        if ($main_endpoint === 'post' || $main_endpoint === 'posts') {
            // If post_type is not specified in the request, use the default from settings
            if (!isset($query_params['post_type'])) {
                $request->set_param('post_type', get_option('seobot_api_default_post_type', 'post'));
            }
        }

        // For POST/PUT requests, handle JSON or form data
        if ($method === 'POST' || $method === 'PUT') {
            // Handle JSON body
            $json_data = json_decode(file_get_contents('php://input'), true);
            if ($json_data) {
                foreach ($json_data as $key => $value) {
                    $request->set_param($key, $value);
                }
            } else {
                // Handle form data
                foreach ($_POST as $key => $value) {
                    $request->set_param($key, $value);
                }
            }

            // Handle file uploads
            if (!empty($_FILES)) {
                $request->set_file_params($_FILES);
            }
        }

        // Helper function to handle response from endpoint methods
        $process_response = function ($result) {
            if (is_wp_error($result)) {
                $this->send_json_response(
                    array(
                        'code' => $result->get_error_code(),
                        'message' => $result->get_error_message(),
                        'data' => array(
                            'status' => 400,
                        ),
                    ),
                    400
                );
            } else {
                $this->send_json_response($result->get_data());
            }
        };

        // Process standard endpoints
        switch ($main_endpoint) {
            case 'categories':
                $result = $this->endpoints->get_categories($request);
                $process_response($result);
                break;

            case 'post':
            case 'posts':
                if ($method === 'GET') {
                    // Get posts
                    $result = $this->endpoints->get_posts($request);
                    $process_response($result);
                } elseif ($method === 'POST') {
                    // Create post
                    $result = $this->endpoints->create_post($request);
                    $process_response($result);
                } elseif ($method === 'PUT' && !empty($endpoint_parts[0])) {
                    // Update post
                    $result = $this->endpoints->update_post($request);
                    $process_response($result);
                } elseif ($method === 'DELETE' && !empty($endpoint_parts[0])) {
                    // Delete post
                    $result = $this->endpoints->delete_post($request);
                    $process_response($result);
                }
                break;

            case 'media':
                if ($method === 'GET') {
                    // Get media
                    $result = $this->endpoints->get_media($request);
                    $process_response($result);
                } elseif ($method === 'POST') {
                    // Upload media
                    $result = $this->endpoints->upload_media($request);
                    $process_response($result);
                }
                break;

            default:
                // Unknown endpoint
                $this->send_json_response(array(
                    'code' => 'invalid_endpoint',
                    'message' => 'Endpoint not found: ' . $main_endpoint,
                    'data' => array('status' => 404)
                ), 404);
                break;
        }
    }

    /**
     * Send a JSON response
     *
     * @param mixed $data The data to send
     * @param int $status_code HTTP status code
     * @return void
     */
    private function send_json_response($data, $status_code = 200)
    {
        status_header($status_code);
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode($data);
        exit;
    }

    /**
     * Get API key from Basic Auth in the current request with debug info
     *
     * @return array Array with 'api_key' and 'debug' information
     */
    private function get_auth_key_from_request_with_debug()
    {
        $debug = array(
            'method_used' => null,
            'header_found' => false,
            'decode_success' => false,
            'credentials_format_valid' => false,
        );

        // Check for Authorization header (Basic Auth)
        $authorization_header = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : '';

        // For some servers, HTTP_AUTHORIZATION isn't available but REDIRECT_HTTP_AUTHORIZATION is
        if (empty($authorization_header) && isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $authorization_header = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
            $debug['redirect_auth_used'] = true;
        }

        if (!empty($authorization_header) && strpos($authorization_header, 'Basic ') === 0) {
            $debug['method_used'] = 'Authorization header';
            $debug['header_found'] = true;

            // Basic auth - extract username as API key (password can be anything)
            $auth_header = base64_decode(substr($authorization_header, 6));
            $debug['decode_success'] = !empty($auth_header);

            if (strpos($auth_header, ':') !== false) {
                list($username, $password) = explode(':', $auth_header, 2);
                $debug['credentials_format_valid'] = true;
                $debug['username_length'] = strlen($username);
                return array(
                    'api_key' => sanitize_text_field($username),
                    'debug' => $debug
                );
            }
        }

        // Check for custom X-SEObot-Auth header as fallback
        // This is useful when the Authorization header is stripped by the server
        if (isset($_SERVER['HTTP_X_SEOBOT_AUTH'])) {
            $x_seobot_auth = $_SERVER['HTTP_X_SEOBOT_AUTH'];
            $debug['method_used'] = 'X-SEObot-Auth header';
            $debug['header_found'] = true;

            // X-SEObot-Auth should contain the same base64 encoded credentials as Authorization header
            if (!empty($x_seobot_auth)) {
                // If it starts with "Basic ", strip it
                if (strpos($x_seobot_auth, 'Basic ') === 0) {
                    $x_seobot_auth = substr($x_seobot_auth, 6);
                    $debug['basic_prefix_stripped'] = true;
                }

                // Decode the base64 credentials
                $auth_header = base64_decode($x_seobot_auth);
                $debug['decode_success'] = !empty($auth_header);

                if (strpos($auth_header, ':') !== false) {
                    list($username, $password) = explode(':', $auth_header, 2);
                    $debug['credentials_format_valid'] = true;
                    $debug['username_length'] = strlen($username);
                    return array(
                        'api_key' => sanitize_text_field($username),
                        'debug' => $debug
                    );
                }
            }
        }

        // Special handling for WordPress compatibility mode
        if (isset($_SERVER['PHP_AUTH_USER'])) {
            $debug['method_used'] = 'PHP_AUTH_USER';
            $debug['header_found'] = true;
            $debug['username_length'] = strlen($_SERVER['PHP_AUTH_USER']);
            return array(
                'api_key' => sanitize_text_field($_SERVER['PHP_AUTH_USER']),
                'debug' => $debug
            );
        }

        return array(
            'api_key' => null,
            'debug' => $debug
        );
    }

    /**
     * Get API key from Basic Auth in the current request
     *
     * @return string|null API key if found, null otherwise
     */
    private function get_auth_key_from_request()
    {
        $result = $this->get_auth_key_from_request_with_debug();
        return $result['api_key'];
    }
}