<?php
/**
 * Static Homepage Generator using HTTP Request to WordPress
 */

// Get the WordPress directory
$wp_dir = dirname(__FILE__) . '/..';
$static_file = $wp_dir . '/index.html.tmp';

// Build cURL request to WordPress
$ch = curl_init();

// Set up cURL options
curl_setopt_array($ch, [
    CURLOPT_URL => 'http://127.0.0.1/index.php',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => false,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_MAXREDIRS => 3,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; StaticGenerator/2.0)',
    CURLOPT_HTTPHEADER => [
        'Host: sunnyside247ac.com',
        'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
        'Cache-Control: no-cache, no-store, must-revalidate',
        'Pragma: no-cache',
        'Expires: 0'
    ],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => false
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

?>