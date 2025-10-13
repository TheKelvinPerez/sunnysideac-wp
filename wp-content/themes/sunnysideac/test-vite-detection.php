<?php
/**
 * Test script to verify Vite dev server detection
 */

// Test dev server detection
function test_vite_dev_server_detection() {
    $vite_dev_server = 'http://localhost:3000';

    $context = stream_context_create([
        'http' => [
            'timeout' => 1,
            'ignore_errors' => true
        ]
    ]);

    $result = @file_get_contents($vite_dev_server, false, $context);
    $is_running = $result !== false || (isset($http_response_header) && !empty($http_response_header));

    return $is_running;
}

// Run test
$dev_server_running = test_vite_dev_server_detection();

echo "=== Vite Dev Server Detection Test ===\n\n";
echo "Checking: http://localhost:3000\n";
echo "Status: " . ($dev_server_running ? "DETECTED ✓" : "NOT DETECTED ✗") . "\n\n";

if ($dev_server_running) {
    echo "The WordPress theme will load assets from the Vite dev server.\n";
    echo "URLs that will be used:\n";
    echo "  - http://localhost:3000/@vite/client\n";
    echo "  - http://localhost:3000/src/main.js\n";
} else {
    echo "The WordPress theme will load assets from the dist/ folder.\n";
    echo "Make sure you've run 'npm run build' to generate production assets.\n";
}

echo "\n";
