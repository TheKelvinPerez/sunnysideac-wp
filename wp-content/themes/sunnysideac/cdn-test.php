<?php
/**
 * CDN Configuration Test Script
 * Run this script to verify CDN setup is working correctly
 *
 * Usage: php cdn-test.php
 */

// Load theme environment
require_once __DIR__ . '/vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Load helper functions
require_once __DIR__ . '/inc/helpers.php';

echo "=== CDN Configuration Test ===\n\n";

// Test 1: Environment Variables
echo "1. Environment Variables:\n";
echo "   APP_ENV: " . ($_ENV['APP_ENV'] ?? 'NOT SET') . "\n";
echo "   CDN_ENABLED: " . ($_ENV['CDN_ENABLED'] ?? 'NOT SET') . "\n";
echo "   CDN_BASE_URL: " . ($_ENV['CDN_BASE_URL'] ?? 'NOT SET') . "\n";
echo "\n";

// Test 2: Asset URL Generation
echo "2. Asset URL Generation:\n";
$test_paths = [
    'assets/images/optimize/hero-right-image.webp',
    'dist/assets/main.css',
    'assets/icons/call-us-now-icon.svg'
];

foreach ($test_paths as $path) {
    // Mock WordPress function for testing
    if (!function_exists('get_template_directory_uri')) {
        function get_template_directory_uri() {
            return 'https://sunnyside247ac.com/wp-content/themes/sunnysideac';
        }
    }

    $original_url = get_template_directory_uri() . '/' . ltrim($path, '/');
    $cdn_url = sunnysideac_asset_url($path);

    echo "   $path:\n";
    echo "     Original: $original_url\n";
    echo "     CDN:      $cdn_url\n";
    echo "     Status:   " . ($original_url !== $cdn_url ? '✅ CDN Active' : '❌ CDN Inactive') . "\n\n";
}

// Test 3: WordPress Upload URLs
echo "3. WordPress Upload URLs:\n";

// Mock WordPress upload directory for testing
if (!function_exists('wp_upload_dir')) {
    function wp_upload_dir() {
        return [
            'baseurl' => 'https://sunnyside247ac.com/wp-content/uploads',
            'basedir' => '/wp-content/uploads'
        ];
    }
}

$upload_dir = wp_upload_dir();
echo "   Upload Base URL: " . $upload_dir['baseurl'] . "\n";
echo "   CDN would replace: /wp-content/uploads/ with CDN base\n\n";

// Test 4: Cache Headers Simulation
echo "4. Cache Headers Configuration:\n";
$file_types = [
    'css' => 'text/css; charset=utf-8',
    'js' => 'application/javascript; charset=utf-8',
    'webp' => 'image/webp',
    'avif' => 'image/avif',
    'svg' => 'image/svg+xml; charset=utf-8',
    'woff2' => 'font/woff2'
];

foreach ($file_types as $ext => $mime) {
    echo "   .$ext files: $mime\n";
}
echo "\n";

// Test 5: Configuration Status
echo "5. Configuration Status:\n";
$cdn_enabled = !empty($_ENV['CDN_ENABLED']) && $_ENV['CDN_ENABLED'] === 'true';
$cdn_base_set = !empty($_ENV['CDN_BASE_URL']);
$production_env = ($_ENV['APP_ENV'] ?? 'development') === 'production';

echo "   CDN Enabled: " . ($cdn_enabled ? '✅' : '❌') . "\n";
echo "   CDN Base URL Set: " . ($cdn_base_set ? '✅' : '❌') . "\n";
echo "   Production Environment: " . ($production_env ? '✅' : '❌') . "\n";
echo "   CDN Active: " . ($cdn_enabled && $cdn_base_set && $production_env ? '✅' : '❌') . "\n\n";

// Test 6: Recommendations
echo "6. Recommendations:\n";
if (!$production_env) {
    echo "   ⚠️  Set APP_ENV=production to enable CDN\n";
}
if (!$cdn_enabled) {
    echo "   ⚠️  Set CDN_ENABLED=true to activate CDN\n";
}
if (!$cdn_base_set || strpos($_ENV['CDN_BASE_URL'] ?? '', 'your-domain.com') !== false || strpos($_ENV['CDN_BASE_URL'] ?? '', 'your-zone.b-cdn.net') !== false) {
    echo "   ⚠️  Set CDN_BASE_URL to your actual CDN domain\n";
    echo "      Example (BunnyCDN): CDN_BASE_URL=https://your-zone.b-cdn.net\n";
    echo "      Example (Custom): CDN_BASE_URL=https://cdn.sunnyside247ac.com\n";
}
if ($cdn_enabled && $cdn_base_set && !$production_env) {
    echo "   ℹ️  CDN is configured but disabled (development mode)\n";
}
if ($cdn_enabled && $cdn_base_set && $production_env) {
    echo "   ✅ CDN is properly configured and active\n";
}

echo "\n7. BunnyCDN Quick Start:\n";
echo "   1. Create account at bunny.net\n";
echo "   2. Add Pull Zone with origin: https://sunnyside247ac.com\n";
echo "   3. Enable WebP/AVIF conversion\n";
echo "   4. Update .env: CDN_ENABLED=true, CDN_BASE_URL=https://your-zone.b-cdn.net\n";
echo "   5. Deploy and test\n";

echo "\n=== Test Complete ===\n";