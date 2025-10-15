<?php

/**
 * Load Composer autoloader
 */
require_once get_template_directory() . '/vendor/autoload.php';

/**
 * Load environment variables
 */
$dotenv = Dotenv\Dotenv::createImmutable(get_template_directory());
$dotenv->load();

/**
 * Initialize Timber
 */
Timber\Timber::init();

/**
 * Configure Timber settings
 */
Timber\Timber::$dirname = array('views', 'templates');


/**
 * Initialize Whoops error handler for development
 */
if ($_ENV['APP_ENV'] === 'development') {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

/**
 * Helper function to flatten nested arrays for Whoops display
 * Converts nested arrays into dot notation strings
 */
if (!function_exists('dd_flatten_array')) {
    function dd_flatten_array($array, $prefix = '') {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $prefix === '' ? $key : $prefix . '.' . $key;

            if (is_array($value) && !empty($value)) {
                // Recursively flatten nested arrays
                $result = array_merge($result, dd_flatten_array($value, $newKey));
            } else {
                // Convert value to string for display
                if (is_bool($value)) {
                    $result[$newKey] = $value ? 'true' : 'false';
                } elseif (is_null($value)) {
                    $result[$newKey] = 'null';
                } elseif (is_string($value) && $value === '') {
                    $result[$newKey] = '(empty string)';
                } else {
                    $result[$newKey] = $value;
                }
            }
        }
        return $result;
    }
}

/**
 * Debug helper function - Dump and Die
 * Outputs clean, formatted data to Whoops error handler
 *
 * @param mixed ...$vars Variables to dump
 */
if (!function_exists('dd')) {
    function dd(...$vars) {
        // Use Whoops for beautiful output if in development
        if ($_ENV['APP_ENV'] === 'development') {
            $whoops = new \Whoops\Run;
            $handler = new \Whoops\Handler\PrettyPageHandler;

            // Add each variable as a data table
            foreach ($vars as $varIndex => $var) {
                $varType = gettype($var);
                $varLabel = "Variable #" . ($varIndex + 1) . " ({$varType})";

                // Handle different data types appropriately
                if (is_scalar($var) || is_null($var)) {
                    // For scalar values (string, int, bool, etc.), show as simple key-value
                    $displayValue = $var;
                    if (is_bool($var)) {
                        $displayValue = $var ? 'true' : 'false';
                    } elseif (is_null($var)) {
                        $displayValue = 'null';
                    }
                    $handler->addDataTable($varLabel, ['Value' => $displayValue]);
                } elseif (is_array($var) && !empty($var)) {
                    // Check if it's a numeric array with complex items (arrays/objects)
                    $isNumericArray = array_keys($var) === range(0, count($var) - 1);
                    $hasComplexItems = false;

                    if ($isNumericArray) {
                        foreach ($var as $item) {
                            if (is_array($item) || is_object($item)) {
                                $hasComplexItems = true;
                                break;
                            }
                        }
                    }

                    // Split into separate tables only for numeric arrays with complex items
                    if ($isNumericArray && $hasComplexItems) {
                        foreach ($var as $itemIndex => $item) {
                            $itemLabel = $varLabel . " - Item #" . $itemIndex;
                            $flattened = dd_flatten_array((array) $item);
                            $handler->addDataTable($itemLabel, $flattened);
                        }
                    } else {
                        // For simple arrays or associative arrays, flatten and show in one table
                        $flattened = dd_flatten_array($var);
                        $handler->addDataTable($varLabel, $flattened);
                    }
                } elseif (is_object($var)) {
                    // For objects, flatten and show properties
                    $flattened = dd_flatten_array((array) $var);
                    $handler->addDataTable($varLabel, $flattened);
                } else {
                    // Fallback for any other type
                    $handler->addDataTable($varLabel, ['Value' => print_r($var, true)]);
                }
            }

            $whoops->pushHandler($handler);
            $whoops->handleException(
                new \Exception('Debug Dump (dd)')
            );
        } else {
            // Fallback for production
            echo '<pre style="background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 8px; font-family: monospace; line-height: 1.5; overflow: auto;">';
            echo '<strong style="color: #4ec9b0;">Debug Dump:</strong>' . PHP_EOL . PHP_EOL;

            foreach ($vars as $index => $var) {
                echo '<strong style="color: #569cd6;">Variable #' . ($index + 1) . ':</strong>' . PHP_EOL;
                echo json_encode($var, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
                echo PHP_EOL . PHP_EOL;
            }

            echo '</pre>';
        }

        exit(1);
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
    // Get values from environment variables loaded by phpdotenv
    $protocol = $_ENV['VITE_DEV_SERVER_PROTOCOL'] ?? 'http';
    $host = $_ENV['VITE_DEV_SERVER_HOST'] ?? 'localhost';
    $port = $_ENV['VITE_DEV_SERVER_PORT'] ?? '3000';

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
 * Add custom data to Timber context
 */
add_filter('timber/context', function($context) {
    $context['menu'] = Timber\Timber::get_menu('primary');
    return $context;
});

/**
 * Add custom Twig functions
 */
add_filter('timber/twig', function($twig) {
    // Add dd() function to Twig
    $twig->addFunction(new \Twig\TwigFunction('dd', function(...$vars) {
        dd(...$vars);
    }));

    return $twig;
});

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

