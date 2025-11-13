<?php
/**
 * Asset Enqueue and Management
 *
 * Handles Vite integration, asset loading, and script/style management
 */

/**
 * Check if Vite dev server is running
 */
function sunnysideac_is_vite_dev_server_running() {
	$vite_dev_server = sunnysideac_get_vite_dev_server_url();

	// Use curl for more reliable server detection
	$ch = curl_init();
	curl_setopt_array(
		$ch,
		array(
			CURLOPT_URL            => $vite_dev_server,
			CURLOPT_TIMEOUT        => 2,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_NOBODY         => true,
			CURLOPT_FOLLOWLOCATION => false,
		)
	);

	$result    = curl_exec( $ch );
	$http_code = curl_getinfo( $ch, CURLINFO_HTTP_CODE );
	curl_close( $ch );

	// Server is running if we got a valid response (even 404 is fine)
	return $result !== false && $http_code >= 200 && $http_code < 500;
}

/**
 * Get Vite dev server URL from environment or use defaults
 */
function sunnysideac_get_vite_dev_server_url() {
	// For DDEV, use HMR host/port which is the browser-accessible URL
	$protocol = $_ENV['VITE_HMR_PROTOCOL'] ?? $_ENV['VITE_DEV_SERVER_PROTOCOL'] ?? 'http';
	$host     = $_ENV['VITE_HMR_HOST'] ?? $_ENV['VITE_DEV_SERVER_HOST'] ?? 'sunnyside-ac.ddev.site';
	$port     = $_ENV['VITE_HMR_PORT'] ?? $_ENV['VITE_DEV_SERVER_PORT'] ?? '3000';

	// Allow filtering via WordPress hooks for more flexibility
	$protocol = apply_filters( 'sunnysideac_vite_protocol', $protocol );
	$host     = apply_filters( 'sunnysideac_vite_host', $host );
	$port     = apply_filters( 'sunnysideac_vite_port', $port );

	return "{$protocol}://{$host}:{$port}";
}

/**
 * Simple asset loading
 */
function sunnysideac_enqueue_assets() {
	$is_dev          = sunnysideac_is_vite_dev_server_running();
	$vite_server_url = sunnysideac_get_vite_dev_server_url();

	if ( $is_dev ) {
		// Development mode: Load from Vite dev server
		wp_enqueue_script(
			'sunnysideac-vite-client',
			$vite_server_url . '/@vite/client',
			array(),
			null,
			false
		);
		wp_script_add_data( 'sunnysideac-vite-client', 'type', 'module' );

		wp_enqueue_script(
			'sunnysideac-main',
			$vite_server_url . '/src/main.js',
			array( 'sunnysideac-vite-client' ),
			null,
			false
		);
		wp_script_add_data( 'sunnysideac-main', 'type', 'module' );
	} else {
		// Production mode: Load built assets with simple manifest
		$manifest_path = get_template_directory() . '/dist/.vite/manifest.json';

		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );

			if ( isset( $manifest['src/main.js'] ) ) {
				$main = $manifest['src/main.js'];

				// Enqueue CSS with preload for non-blocking
				if ( isset( $main['css'] ) ) {
					foreach ( $main['css'] as $css_file ) {
						$css_url = get_template_directory_uri() . '/dist/' . $css_file;

						// Register the style
						wp_register_style(
							'sunnysideac-main',
							$css_url,
							array(),
							null
						);

						// Add preload link for non-blocking CSS loading
						add_action( 'wp_head', function() use ( $css_url ) {
							echo '<link rel="preload" href="' . esc_url( $css_url ) . '" as="style" fetchpriority="high" onload="this.onload=null;this.rel=\'stylesheet\'">' . "\n";
							echo '<noscript><link rel="stylesheet" href="' . esc_url( $css_url ) . '"></noscript>' . "\n";
						}, 5 );
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
				wp_script_add_data( 'sunnysideac-main', 'type', 'module' );
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', 'sunnysideac_enqueue_assets' );

/**
 * Remove Gutenberg block library CSS - using Tailwind typography instead
 * Tailwind typography plugin provides better styling for blog content
 */
function sunnysideac_remove_gutenberg_css() {
	// Remove Gutenberg CSS everywhere - using Tailwind typography instead
	if ( ! is_admin() ) {
		wp_dequeue_style( 'wp-block-library' );
		wp_deregister_style( 'wp-block-library' );
		wp_dequeue_style( 'wp-block-library-theme' );
		wp_deregister_style( 'wp-block-library-theme' );
	}
}
add_action( 'wp_enqueue_scripts', 'sunnysideac_remove_gutenberg_css', 999 );

/**
 * Ensure registered CSS is actually loaded
 */
function sunnysideac_ensure_css_loaded() {
	if ( ! is_admin() && ! sunnysideac_is_vite_dev_server_running() ) {
		wp_enqueue_style( 'sunnysideac-main' );
	}
}
add_action( 'wp_enqueue_scripts', 'sunnysideac_ensure_css_loaded', 999 );

/**
 * Add type="module" to Vite scripts
 */
function sunnysideac_add_type_attribute( $tag, $handle ) {
	if ( 'sunnysideac-vite-client' === $handle || 'sunnysideac-main' === $handle ) {
		// Remove the type="text/javascript" attribute and replace with type="module"
		$tag = str_replace( "type='text/javascript'", "type='module'", $tag );
		$tag = str_replace( 'type="text/javascript"', 'type="module"', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'sunnysideac_add_type_attribute', 10, 3 );