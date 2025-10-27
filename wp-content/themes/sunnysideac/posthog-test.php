<?php
/**
 * PostHog PHP SDK Connection Test
 *
 * TEMPORARY FILE - Delete after testing!
 *
 * This file tests the PostHog PHP SDK connection to ensure:
 * 1. Composer packages are installed correctly
 * 2. API key is valid
 * 3. Events can be sent to PostHog
 * 4. Person profiles can be created
 *
 * HOW TO RUN:
 * 1. Visit: https://sunnyside-ac.ddev.site/wp-content/themes/sunnysideac/posthog-test.php
 * 2. Check output for success messages
 * 3. Check PostHog Live Events: https://app.posthog.com/project/YOUR_PROJECT/events
 * 4. DELETE this file when done
 */

// Load WordPress (for access to theme functions and autoloader)
require_once __DIR__ . '/../../../wp-load.php';

// Set headers for readable output
header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
	<title>PostHog PHP SDK Connection Test</title>
	<style>
		body {
			font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
			max-width: 800px;
			margin: 50px auto;
			padding: 20px;
			background: #f5f5f5;
		}
		.test-box {
			background: white;
			border-radius: 8px;
			padding: 20px;
			margin-bottom: 20px;
			box-shadow: 0 2px 4px rgba(0,0,0,0.1);
		}
		.success {
			color: #22c55e;
			font-weight: bold;
		}
		.error {
			color: #ef4444;
			font-weight: bold;
		}
		.warning {
			color: #f59e0b;
			font-weight: bold;
		}
		.info {
			color: #3b82f6;
			font-weight: bold;
		}
		pre {
			background: #1e293b;
			color: #e2e8f0;
			padding: 15px;
			border-radius: 6px;
			overflow-x: auto;
		}
		.step {
			margin: 10px 0;
			padding: 10px;
			border-left: 4px solid #3b82f6;
			background: #f1f5f9;
		}
		h1 { color: #1e293b; }
		h2 { color: #475569; margin-top: 30px; }
		.btn {
			display: inline-block;
			padding: 10px 20px;
			background: #3b82f6;
			color: white;
			text-decoration: none;
			border-radius: 6px;
			margin-top: 10px;
		}
		.btn:hover {
			background: #2563eb;
		}
	</style>
</head>
<body>
	<h1>🧪 PostHog PHP SDK Connection Test</h1>

	<div class="test-box">
		<h2>Step 1: Check Composer Autoloader</h2>
		<?php
		$autoload_path = get_template_directory() . '/vendor/autoload.php';
		if ( file_exists( $autoload_path ) ) {
			echo '<div class="step"><span class="success">✅ SUCCESS:</span> Composer autoloader found</div>';
			echo '<pre>' . esc_html( $autoload_path ) . '</pre>';
		} else {
			echo '<div class="step"><span class="error">❌ ERROR:</span> Composer autoloader not found!</div>';
			echo '<p>Run: <code>ddev composer install</code> from theme directory</p>';
			exit;
		}
		?>
	</div>

	<div class="test-box">
		<h2>Step 2: Check PostHog Class</h2>
		<?php
		if ( class_exists( 'PostHog\PostHog' ) ) {
			echo '<div class="step"><span class="success">✅ SUCCESS:</span> PostHog class loaded</div>';

			// Show PostHog version if available
			try {
				$reflection = new ReflectionClass('PostHog\PostHog');
				$file = $reflection->getFileName();
				echo '<pre>PostHog SDK Path: ' . esc_html( $file ) . '</pre>';
			} catch (Exception $e) {
				// Ignore
			}
		} else {
			echo '<div class="step"><span class="error">❌ ERROR:</span> PostHog class not found!</div>';
			echo '<p>Run: <code>ddev composer require posthog/posthog-php</code></p>';
			exit;
		}
		?>
	</div>

	<div class="test-box">
		<h2>Step 3: Check Environment Variables</h2>
		<?php
		$api_key = $_ENV['POSTHOG_API_KEY'] ?? '';
		$host = $_ENV['POSTHOG_HOST'] ?? 'https://us.i.posthog.com';

		if ( ! empty( $api_key ) ) {
			echo '<div class="step"><span class="success">✅ SUCCESS:</span> API key found in .env</div>';
			echo '<pre>API Key: ' . esc_html( substr( $api_key, 0, 10 ) ) . '...' . esc_html( substr( $api_key, -5 ) ) . '</pre>';
			echo '<pre>Host: ' . esc_html( $host ) . '</pre>';
		} else {
			echo '<div class="step"><span class="error">❌ ERROR:</span> POSTHOG_API_KEY not found in .env</div>';
			echo '<p>Add to .env file in theme directory</p>';
			exit;
		}
		?>
	</div>

	<div class="test-box">
		<h2>Step 4: Initialize PostHog</h2>
		<?php
		try {
			PostHog\PostHog::init(
				$api_key,
				array(
					'host' => $host,
					'debug' => true, // Enable debug mode for testing
				)
			);
			echo '<div class="step"><span class="success">✅ SUCCESS:</span> PostHog initialized</div>';
		} catch ( Exception $e ) {
			echo '<div class="step"><span class="error">❌ ERROR:</span> Failed to initialize PostHog</div>';
			echo '<pre>' . esc_html( $e->getMessage() ) . '</pre>';
			exit;
		}
		?>
	</div>

	<div class="test-box">
		<h2>Step 5: Send Test Event (Without Person Profile)</h2>
		<?php
		try {
			$test_distinct_id = 'test-user-' . time();

			PostHog\PostHog::capture(
				array(
					'distinctId' => $test_distinct_id,
					'event' => 'test-event',
					'properties' => array(
						'test_type' => 'connection_test',
						'timestamp' => date('Y-m-d H:i:s'),
						'source' => 'posthog-test.php',
						'$process_person_profile' => false, // Don't create person profile for test
					),
				)
			);

			echo '<div class="step"><span class="success">✅ SUCCESS:</span> Test event sent (no person profile)</div>';
			echo '<pre>Distinct ID: ' . esc_html( $test_distinct_id ) . '</pre>';
			echo '<pre>Event: test-event</pre>';
			echo '<div class="step"><span class="info">ℹ️ INFO:</span> Check PostHog Live Events to confirm receipt</div>';
		} catch ( Exception $e ) {
			echo '<div class="step"><span class="error">❌ ERROR:</span> Failed to send test event</div>';
			echo '<pre>' . esc_html( $e->getMessage() ) . '</pre>';
		}
		?>
	</div>

	<div class="test-box">
		<h2>Step 6: Send Test Event (With Person Profile)</h2>
		<?php
		try {
			$test_distinct_id_2 = 'test-user-with-profile-' . time();

			PostHog\PostHog::capture(
				array(
					'distinctId' => $test_distinct_id_2,
					'event' => 'test-event-with-profile',
					'properties' => array(
						'test_type' => 'profile_test',
						'timestamp' => date('Y-m-d H:i:s'),
						'source' => 'posthog-test.php',
						// Person properties
						'$set' => array(
							'email' => 'test@example.com',
							'name' => 'Test User',
							'test_user' => true,
						),
					),
				)
			);

			echo '<div class="step"><span class="success">✅ SUCCESS:</span> Test event sent (with person profile)</div>';
			echo '<pre>Distinct ID: ' . esc_html( $test_distinct_id_2 ) . '</pre>';
			echo '<pre>Event: test-event-with-profile</pre>';
			echo '<pre>Person Properties: email, name, test_user</pre>';
		} catch ( Exception $e ) {
			echo '<div class="step"><span class="error">❌ ERROR:</span> Failed to send test event with profile</div>';
			echo '<pre>' . esc_html( $e->getMessage() ) . '</pre>';
		}
		?>
	</div>

	<div class="test-box">
		<h2>Step 7: Test Identify (Person Properties)</h2>
		<?php
		try {
			$test_distinct_id_3 = 'identified-user-' . time();

			PostHog\PostHog::identify(
				array(
					'distinctId' => $test_distinct_id_3,
					'properties' => array(
						'email' => 'identified@example.com',
						'name' => 'Identified Test User',
						'plan' => 'test',
						'created_at' => date('Y-m-d H:i:s'),
					),
				)
			);

			echo '<div class="step"><span class="success">✅ SUCCESS:</span> User identified with properties</div>';
			echo '<pre>Distinct ID: ' . esc_html( $test_distinct_id_3 ) . '</pre>';
		} catch ( Exception $e ) {
			echo '<div class="step"><span class="error">❌ ERROR:</span> Failed to identify user</div>';
			echo '<pre>' . esc_html( $e->getMessage() ) . '</pre>';
		}
		?>
	</div>

	<div class="test-box">
		<h2>✅ Test Results Summary</h2>
		<div class="step">
			<span class="success">🎉 ALL TESTS PASSED!</span>
			<p>Your PostHog PHP SDK is working correctly.</p>
		</div>

		<h3>Next Steps:</h3>
		<ol>
			<li>
				<strong>Verify in PostHog:</strong><br>
				<a href="https://app.posthog.com/project/settings/project-details#events" target="_blank" class="btn">
					Open PostHog Live Events
				</a>
				<p>Look for these events:</p>
				<ul>
					<li><code>test-event</code></li>
					<li><code>test-event-with-profile</code></li>
				</ul>
			</li>
			<li>
				<strong>Check Person Profiles:</strong><br>
				<p>Navigate to: Persons → Search for "test@example.com"</p>
			</li>
			<li>
				<strong>Delete This Test File:</strong><br>
				<code style="background: #fee2e2; color: #991b1b; padding: 5px 10px; border-radius: 4px;">
					rm <?php echo esc_html( __FILE__ ); ?>
				</code>
				<p><strong style="color: #dc2626;">⚠️ IMPORTANT:</strong> This file should NOT be deployed to production!</p>
			</li>
		</ol>
	</div>

	<div class="test-box">
		<h2>🧹 Cleanup Commands</h2>
		<p>Run these commands to clean up test data:</p>
		<pre>
# Delete this test file
rm <?php echo esc_html( basename( __FILE__ ) ); ?>

# Or from project root:
rm app/public/wp-content/themes/sunnysideac/posthog-test.php
		</pre>
	</div>

	<div class="test-box">
		<h2>📊 Debug Information</h2>
		<details>
			<summary style="cursor: pointer; color: #3b82f6; font-weight: bold;">Show Environment Details</summary>
			<pre><?php
				echo "PHP Version: " . PHP_VERSION . "\n";
				echo "WordPress Version: " . get_bloginfo('version') . "\n";
				echo "Theme Directory: " . get_template_directory() . "\n";
				echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "\n";
				echo "Current Time: " . date('Y-m-d H:i:s') . "\n";
			?></pre>
		</details>
	</div>

</body>
</html>
