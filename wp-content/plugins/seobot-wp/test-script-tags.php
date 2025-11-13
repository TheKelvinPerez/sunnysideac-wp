<?php
/**
 * Test script for SEObot API script tag functionality
 * 
 * This script demonstrates how to test whether script tags are preserved
 * when creating/updating posts via the SEObot API.
 * 
 * Usage:
 * 1. Update the $api_key and $base_url variables below
 * 2. Run: php test-script-tags.php
 */

// Configuration - UPDATE THESE VALUES
$api_key = 'your-api-key-here';  // Get this from SEObot settings page
$base_url = 'https://your-site.com/seobot/v1';  // Update with your site URL

// Test content with script tags
$test_content = '<p>This is a test post with a script tag:</p>
<script>
console.log("Hello from SEObot API!");
alert("Script tags are working!");
</script>
<p>End of test content.</p>';

// Test data
$post_data = array(
  'title' => 'Test Post with Script Tags - ' . date('Y-m-d H:i:s'),
  'content' => $test_content,
  'status' => 'draft',  // Use draft for testing
);

// Create the post
echo "Testing SEObot API script tag functionality...\n";
echo "Creating test post...\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $base_url . '/post');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post_data));
curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  'Content-Type: application/json',
  'Authorization: Basic ' . base64_encode($api_key . ':password')
));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "HTTP Response Code: $http_code\n";

if ($http_code == 200 || $http_code == 201) {
  $result = json_decode($response, true);

  if ($result && isset($result['id'])) {
    echo "✅ Post created successfully!\n";
    echo "Post ID: " . $result['id'] . "\n";
    echo "Post URL: " . $result['link'] . "\n";

    // Check if script tags are preserved
    $raw_content = $result['content']['raw'];
    if (strpos($raw_content, '<script>') !== false) {
      echo "✅ Script tags are preserved in the content!\n";
    } else {
      echo "❌ Script tags were stripped from the content.\n";
      echo "This should not happen with SEObot API as it allows unfiltered HTML by default.\n";
    }

    echo "\nRaw content:\n";
    echo $raw_content . "\n";

  } else {
    echo "❌ Unexpected response format:\n";
    echo $response . "\n";
  }
} else {
  echo "❌ Request failed with HTTP code: $http_code\n";
  echo "Response: $response\n";

  if ($http_code == 401) {
    echo "Check your API key in the configuration above.\n";
  }
}

echo "\nTest completed.\n";
?>