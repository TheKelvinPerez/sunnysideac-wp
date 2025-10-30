<?php
/**
 * Static Homepage Generator using HTTP Request to WordPress
 */

// Suppress warnings for clean output
error_reporting(E_ERROR | E_PARSE);

// Get the WordPress directory
$wp_dir = dirname(__FILE__) . '/..';
$static_file = $wp_dir . '/index.html.tmp';

// Build cURL request to WordPress
$ch = curl_init();

// First clear WordPress cache if possible
echo "Clearing WordPress cache...\n";
$cache_clear_result = shell_exec('wp cache flush --allow-root 2>&1');
if ($cache_clear_result) {
    echo "✓ WordPress cache cleared\n";
} else {
    echo "⚠ WordPress cache clear failed (may not have WP-CLI)\n";
}

// Clear Redis cache if available
$redis_clear_result = shell_exec('redis-cli FLUSHALL 2>&1');
if ($redis_clear_result && strpos($redis_clear_result, 'OK') !== false) {
    echo "✓ Redis cache cleared\n";
} else {
    echo "⚠ Redis cache clear failed\n";
}

// Set up cURL options
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://127.0.0.1/index.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 3,
    CURLOPT_TIMEOUT => 60,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; StaticGenerator/2.0)',
    CURLOPT_HTTPHEADER => [
        'Host: sunnyside247ac.com',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Cache-Control: no-cache, no-store, must-revalidate',
        'Pragma: no-cache',
        'Expires: 0',
        'X-Static-Generation: true'
    ],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false,
    CURLOPT_FRESH_CONNECT => true,
    CURLOPT_FORBID_REUSE => true
]);

// Execute the request
$html = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// Check for errors
if ($error) {
    echo "Error: cURL request failed: $error\n";
    exit(1);
}

if ($http_code !== 200) {
    echo "Error: HTTP request failed with status $http_code\n";
    exit(1);
}

if (empty($html)) {
    echo "Error: Empty response received\n";
    exit(1);
}

// Check if schema markup is present - if not, try WordPress direct bootstrap
if (strpos($html, 'AggregateRating') === false) {
    echo "⚠ Schema markup not found in cURL response, trying WordPress direct bootstrap...\n";

    // Fallback: Direct WordPress bootstrap
    $_SERVER['HTTP_HOST'] = 'sunnyside247ac.com';
    $_SERVER['REQUEST_URI'] = '/';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['HTTPS'] = 'on';

    // Capture WordPress output
    ob_start();

    // Bootstrap WordPress with proper server variables
    $_SERVER['SERVER_NAME'] = 'sunnyside247ac.com';
    $_SERVER['SERVER_PORT'] = '443';
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['HTTP_HOST'] = 'sunnyside247ac.com';
    $_SERVER['REQUEST_URI'] = '/';
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PHP_SELF'] = '/index.php';

    // Disable WordPress warnings/errors for clean output
    error_reporting(E_ERROR | E_PARSE);

    // Bootstrap WordPress
    define('WP_USE_THEMES', true);
    require_once($wp_dir . '/wp-config.php');
    require_once($wp_dir . '/wp-load.php');
    wp();

    // Load the template properly - this will include header.php, index.php, footer.php
    require_once(ABSPATH . WPINC . '/template-loader.php');

    $wp_html = ob_get_clean();

    if (!empty($wp_html) && strlen($wp_html) > 1000) {
        $html = $wp_html;
        echo "✓ WordPress direct bootstrap successful\n";
    } else {
        echo "⚠ WordPress direct bootstrap also failed\n";
    }
}

// Write to static file
$bytes_written = file_put_contents($static_file, $html);

if ($bytes_written === false) {
    echo "Error: Failed to write static HTML file\n";
    exit(1);
}

echo "✓ Generated static homepage with " . number_format($bytes_written) . " bytes\n";
echo "✓ Static file saved to: $static_file\n";

// Validate key components
$checks = [
    'Logo marquee container' => 'logo-marquee-container',
    'Mobile optimizations' => 'Mobile performance optimizations',
    'getActualLogoWidth function' => 'getActualLogoWidth',
    'DOCTYPE html' => '<!DOCTYPE html',
    'Closing html tag' => '</html>'
];

echo "\nValidation checks:\n";
foreach ($checks as $description => $search) {
    $found = strpos($html, $search) !== false;
    $status = $found ? '✓' : '✗';
    echo "  $status $description\n";
}

// Additional validation for JavaScript content
$js_checks = [
    'Mobile logo width debugging' => 'Mobile logo width measured',
    'Responsive fallback logic' => 'screenWidth < 768',
    'Logo marquee speed' => 'const speed = 0.8'
];

echo "\nJavaScript checks:\n";
foreach ($js_checks as $description => $search) {
    $found = strpos($html, $search) !== false;
    $status = $found ? '✓' : '✗';
    echo "  $status $description\n";
}

// Schema validation
$schema_checks = [
    'AggregateRating schema' => 'AggregateRating',
    'JSON-LD format' => 'application/ld+json',
    'Rating value' => '"ratingValue"',
    'Review count' => '"reviewCount"'
];

echo "\nSchema checks:\n";
foreach ($schema_checks as $description => $search) {
    $found = strpos($html, $search) !== false;
    $status = $found ? '✓' : '✗';
    echo "  $status $description\n";
}

// Clean any WordPress debug output from the end
$html = preg_replace('/<!--\s*Performance optimized by.*?-->\s*$/s', '', $html);
$html = preg_replace('/PHP Warning:[^\n]*\n/', '', $html);

?>