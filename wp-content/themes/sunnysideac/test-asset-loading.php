<?php
/**
 * Test WordPress asset loading simulation
 * This simulates what happens when WordPress loads the theme
 */

// Simulate WordPress functions
function get_template_directory() {
    return __DIR__;
}

function get_template_directory_uri() {
    return 'http://sunnyside-ac.local/wp-content/themes/sunnysideac';
}

// Include the actual functions from your theme
function sunnysideac_get_vite_dev_server_url() {
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

    return "{$protocol}://{$host}:{$port}";
}

function sunnysideac_is_vite_dev_server_running() {
    $vite_dev_server = sunnysideac_get_vite_dev_server_url();

    $context = stream_context_create([
        'http' => [
            'timeout' => 1,
            'ignore_errors' => true
        ]
    ]);

    $result = @file_get_contents($vite_dev_server, false, $context);
    return $result !== false || (isset($http_response_header) && !empty($http_response_header));
}

// Simulate asset loading
echo "=== WordPress Asset Loading Test ===\n\n";
echo "WordPress Site: http://sunnyside-ac.local/\n";
echo "Theme Directory: " . get_template_directory() . "\n\n";

$is_dev = sunnysideac_is_vite_dev_server_running();
$vite_server_url = sunnysideac_get_vite_dev_server_url();

echo "Vite Dev Server URL: {$vite_server_url}\n";
echo "Dev Server Running: " . ($is_dev ? "YES ✓" : "NO ✗") . "\n\n";

if ($is_dev) {
    echo "=== DEVELOPMENT MODE ===\n\n";
    echo "Assets will be loaded from Vite dev server:\n\n";

    echo "1. Vite Client Script:\n";
    echo "   <script type=\"module\" src=\"{$vite_server_url}/@vite/client\"></script>\n\n";

    echo "2. Main Entry Script:\n";
    echo "   <script type=\"module\" src=\"{$vite_server_url}/src/main.js\"></script>\n\n";

    echo "Benefits:\n";
    echo "  • Hot Module Replacement (HMR) enabled\n";
    echo "  • Instant updates on file changes\n";
    echo "  • Source maps for debugging\n\n";

} else {
    echo "=== PRODUCTION MODE ===\n\n";
    echo "Assets will be loaded from dist/ folder:\n\n";

    $manifest_path = get_template_directory() . '/dist/.vite/manifest.json';

    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);

        if (isset($manifest['src/main.js'])) {
            $main = $manifest['src/main.js'];

            echo "Manifest file found ✓\n\n";

            if (isset($main['css'])) {
                echo "CSS Files:\n";
                foreach ($main['css'] as $css_file) {
                    $url = get_template_directory_uri() . '/dist/' . $css_file;
                    echo "  • {$url}\n";

                    // Check if file exists
                    $local_path = get_template_directory() . '/dist/' . $css_file;
                    if (file_exists($local_path)) {
                        $size = round(filesize($local_path) / 1024, 2);
                        echo "    File exists ✓ ({$size} KB)\n";
                    } else {
                        echo "    File missing ✗\n";
                    }
                }
                echo "\n";
            }

            echo "JS Files:\n";
            $js_url = get_template_directory_uri() . '/dist/' . $main['file'];
            echo "  • {$js_url}\n";

            $local_js_path = get_template_directory() . '/dist/' . $main['file'];
            if (file_exists($local_js_path)) {
                $size = round(filesize($local_js_path) / 1024, 2);
                echo "    File exists ✓ ({$size} KB)\n";
            } else {
                echo "    File missing ✗\n";
            }

            echo "\nBenefits:\n";
            echo "  • Minified and optimized\n";
            echo "  • Fast loading\n";
            echo "  • Production-ready\n\n";

        } else {
            echo "ERROR: src/main.js not found in manifest\n";
        }
    } else {
        echo "ERROR: Manifest file not found!\n";
        echo "Location: {$manifest_path}\n\n";
        echo "Please run: npm run build\n\n";
    }
}

echo "=== Configuration Test ===\n\n";
$env_file = get_template_directory() . '/.env';
if (file_exists($env_file)) {
    echo ".env file found ✓\n";
    echo "Contents:\n";
    $env_contents = file_get_contents($env_file);
    echo $env_contents;
    echo "\n";
} else {
    echo ".env file not found (using defaults)\n";
    echo "You can create one with: cp .env.example .env\n\n";
}

echo "\n=== Test Complete ===\n";
