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
 * Initialize Whoops error handler for development
 */
if ($_ENV['APP_ENV'] === 'development') {
    $whoops = new \Whoops\Run;
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}

/**
 * Helper function to format array/object data hierarchically
 */
if (!function_exists('dd_format_value')) {
    function dd_format_value($value, $depth = 0, $maxDepth = 10) {
        if ($depth > $maxDepth) {
            return '... (max depth reached)';
        }

        $indent = str_repeat('    ', $depth); // 4 spaces per level
        $output = '';

        if (is_array($value)) {
            $count = count($value);
            $output .= "Array ({$count})\n";
            foreach ($value as $key => $val) {
                $output .= $indent . "    [{$key}] => ";
                if (is_array($val) || is_object($val)) {
                    $output .= dd_format_value($val, $depth + 1, $maxDepth);
                } else {
                    $output .= dd_format_scalar($val) . "\n";
                }
            }
        } elseif (is_object($value)) {
            $className = get_class($value);
            $output .= "Object ({$className})\n";
            $properties = (array) $value;
            foreach ($properties as $key => $val) {
                $output .= $indent . "    {$key} => ";
                if (is_array($val) || is_object($val)) {
                    $output .= dd_format_value($val, $depth + 1, $maxDepth);
                } else {
                    $output .= dd_format_scalar($val) . "\n";
                }
            }
        } else {
            $output .= dd_format_scalar($value);
        }

        return $output;
    }
}

/**
 * Helper function to format scalar values
 */
if (!function_exists('dd_format_scalar')) {
    function dd_format_scalar($value) {
        if (is_null($value)) {
            return 'NULL';
        } elseif (is_bool($value)) {
            return $value ? 'true' : 'false';
        } elseif (is_string($value)) {
            return '"' . $value . '"';
        } else {
            return (string) $value;
        }
    }
}

/**
 * Debug helper function - Dump and Die (with pretty hierarchical output)
 *
 * @param mixed ...$vars Variables to dump
 * @param bool $explode Whether to explode array items into separate tables (default: true)
 */
if (!function_exists('dd')) {
    function dd(...$vars) {
        // Check if last parameter is a boolean to control explode behavior
        $explode = true;
        if (count($vars) > 0 && is_bool(end($vars))) {
            $explode = array_pop($vars);
        }

        // Use Whoops for beautiful output if in development
        if ($_ENV['APP_ENV'] === 'development') {
            $whoops = new \Whoops\Run;
            $handler = new \Whoops\Handler\PrettyPageHandler;

            // Add the variables to inspect with hierarchical formatting
            foreach ($vars as $varIndex => $var) {
                $varType = gettype($var);

                // If it's an array and explode is true, add each item as a separate table
                if ($explode && is_array($var) && !empty($var)) {
                    // Check if this is a numeric array (list) vs associative array
                    $isNumericArray = array_keys($var) === range(0, count($var) - 1);

                    if ($isNumericArray) {
                        // Explode numeric arrays into separate tables
                        foreach ($var as $itemIndex => $item) {
                            $itemType = gettype($item);

                            if (is_array($item) || is_object($item)) {
                                $displayValue = '<pre style="white-space: pre-wrap; font-family: monospace; line-height: 1.5;">' .
                                               dd_format_value($item, 0, 5) .
                                               '</pre>';
                            } else {
                                $displayValue = dd_format_scalar($item);
                            }

                            $handler->addDataTable("Variable #" . ($varIndex + 1) . " - Item [{$itemIndex}] ({$itemType})", [
                                'Value' => $displayValue
                            ]);
                        }
                    } else {
                        // For associative arrays, show each key as a separate row
                        $tableData = [];
                        foreach ($var as $key => $value) {
                            if (is_array($value) || is_object($value)) {
                                $tableData[$key] = '<pre style="white-space: pre-wrap; font-family: monospace; line-height: 1.5;">' .
                                                  dd_format_value($value, 0, 5) .
                                                  '</pre>';
                            } else {
                                $tableData[$key] = dd_format_scalar($value);
                            }
                        }
                        $handler->addDataTable("Variable #" . ($varIndex + 1) . " ({$varType}) - " . count($var) . " items", $tableData);
                    }
                } else {
                    // Normal single-value display
                    if (is_array($var) || is_object($var)) {
                        $displayValue = '<pre style="white-space: pre-wrap; font-family: monospace; line-height: 1.5;">' .
                                       dd_format_value($var, 0, 5) .
                                       '</pre>';
                    } else {
                        $displayValue = dd_format_scalar($var);
                    }

                    $handler->addDataTable("Variable #" . ($varIndex + 1) . " ({$varType})", [
                        'Value' => $displayValue
                    ]);
                }
            }

            $whoops->pushHandler($handler);
            $whoops->handleException(
                new \Exception('Debug Dump (dd) called - Inspect the variables in the data tables above')
            );
        } else {
            // Fallback for production (though dd shouldn't be used in production)
            echo '<pre style="background: #f5f5f5; padding: 15px; border: 1px solid #ddd; border-radius: 4px; font-family: monospace; line-height: 1.5;">';
            echo '<strong>Debug Dump:</strong>' . PHP_EOL . PHP_EOL;

            foreach ($vars as $index => $var) {
                $varType = gettype($var);
                echo "Variable #" . ($index + 1) . " ({$varType}):" . PHP_EOL;

                if (is_array($var) || is_object($var)) {
                    echo dd_format_value($var);
                } else {
                    echo dd_format_scalar($var);
                }
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

