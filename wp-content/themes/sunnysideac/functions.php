<?php

/**
 * Load Composer autoloader
 */
require_once get_template_directory() . '/vendor/autoload.php';

/**
 * Load environment variables
 */
$dotenv = Dotenv\Dotenv::createImmutable( get_template_directory() );
$dotenv->load();

// Initialize PostHog with environment variables
if ( ! empty( $_ENV['POSTHOG_API_KEY'] ) ) {
	PostHog\PostHog::init(
		$_ENV['POSTHOG_API_KEY'],
		array(
			'host'                           => $_ENV['POSTHOG_HOST'] ?? 'https://us.i.posthog.com',
			'debug'                          => $_ENV['APP_ENV'] === 'development',
			'feature_flag_request_timeout_ms' => 3000,
		)
	);
}

/**
 * Setup CDN for WordPress uploads and assets
 */
if ( ! empty( $_ENV['CDN_ENABLED'] ) && $_ENV['CDN_ENABLED'] === 'true' && ! empty( $_ENV['CDN_BASE_URL'] ) && $_ENV['APP_ENV'] !== 'development' ) {

	// Replace upload URLs with CDN URLs
	add_filter(
		'wp_get_attachment_url',
		function ( $url ) {
			$upload_dir = wp_upload_dir();
			$base_url   = $upload_dir['baseurl'];
			$cdn_base   = rtrim( $_ENV['CDN_BASE_URL'], '/' ) . '/wp-content/uploads';

			return str_replace( $base_url, $cdn_base, $url );
		},
		99
	);

	// Replace attachment image sources with CDN URLs
	add_filter(
		'wp_get_attachment_image_src',
		function ( $image ) {
			if ( is_array( $image ) && ! empty( $image[0] ) ) {
				$upload_dir = wp_upload_dir();
				$base_url   = $upload_dir['baseurl'];
				$cdn_base   = rtrim( $_ENV['CDN_BASE_URL'], '/' ) . '/wp-content/uploads';

				$image[0] = str_replace( $base_url, $cdn_base, $image[0] );
			}
			return $image;
		},
		99
	);

	// Replace content URLs with CDN URLs
	add_filter(
		'the_content',
		function ( $content ) {
			$upload_dir = wp_upload_dir();
			$base_url   = $upload_dir['baseurl'];
			$cdn_base   = rtrim( $_ENV['CDN_BASE_URL'], '/' ) . '/wp-content/uploads';

			return str_replace( $base_url, $cdn_base, $content );
		},
		99
	);
}


/**
 * Simple cache headers for theme assets (safe version)
 */
add_action(
	'template_redirect',
	function () {
		// Only set headers for direct asset access
		if ( is_admin() || is_login() ) {
			return;
		}

		// Simple string check for theme assets
		$request_uri = $_SERVER['REQUEST_URI'] ?? '';
		$theme_name  = basename( get_template_directory() );

		if ( strpos( $request_uri, '/wp-content/themes/' . $theme_name . '/assets/' ) !== false ||
		strpos( $request_uri, '/wp-content/themes/' . $theme_name . '/dist/' ) !== false ) {

			if ( ! headers_sent() ) {
				header( 'Cache-Control: public, max-age=31536000, immutable' );
				header( 'Vary: Accept-Encoding' );
			}
		}
	}
);

/**
 * Initialize Whoops error handler for development
 */
if ( $_ENV['APP_ENV'] === 'development' ) {
	$whoops = new \Whoops\Run();
	$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
	$whoops->register();
}

/**
 * Helper function to flatten nested arrays for Whoops display
 * Converts nested arrays into dot notation strings
 */
if ( ! function_exists( 'dd_flatten_array' ) ) {
	function dd_flatten_array( $array, $prefix = '' ) {
		$result = array();
		foreach ( $array as $key => $value ) {
			$newKey = $prefix === '' ? $key : $prefix . '.' . $key;

			if ( is_array( $value ) && ! empty( $value ) ) {
				// Recursively flatten nested arrays
				$result = array_merge( $result, dd_flatten_array( $value, $newKey ) );
			} else {
				// Convert value to string for display
				if ( is_bool( $value ) ) {
					$result[ $newKey ] = $value ? 'true' : 'false';
				} elseif ( is_null( $value ) ) {
					$result[ $newKey ] = 'null';
				} elseif ( is_string( $value ) && $value === '' ) {
					$result[ $newKey ] = '(empty string)';
				} else {
					$result[ $newKey ] = $value;
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
if ( ! function_exists( 'dd' ) ) {
	function dd( ...$vars ) {
		// Use Whoops for beautiful output if in development
		if ( $_ENV['APP_ENV'] === 'development' ) {
			$whoops  = new \Whoops\Run();
			$handler = new \Whoops\Handler\PrettyPageHandler();

			// Add each variable as a data table
			foreach ( $vars as $varIndex => $var ) {
				$varType  = gettype( $var );
				$varLabel = 'Variable #' . ( $varIndex + 1 ) . " ({$varType})";

				// Handle different data types appropriately
				if ( is_scalar( $var ) || is_null( $var ) ) {
					// For scalar values (string, int, bool, etc.), show as simple key-value
					$displayValue = $var;
					if ( is_bool( $var ) ) {
						$displayValue = $var ? 'true' : 'false';
					} elseif ( is_null( $var ) ) {
						$displayValue = 'null';
					}
					$handler->addDataTable( $varLabel, array( 'Value' => $displayValue ) );
				} elseif ( is_array( $var ) && ! empty( $var ) ) {
					// Check if it's a numeric array with complex items (arrays/objects)
					$isNumericArray  = array_keys( $var ) === range( 0, count( $var ) - 1 );
					$hasComplexItems = false;

					if ( $isNumericArray ) {
						foreach ( $var as $item ) {
							if ( is_array( $item ) || is_object( $item ) ) {
								$hasComplexItems = true;
								break;
							}
						}
					}

					// Split into separate tables only for numeric arrays with complex items
					if ( $isNumericArray && $hasComplexItems ) {
						foreach ( $var as $itemIndex => $item ) {
							$itemLabel = $varLabel . ' - Item #' . $itemIndex;
							$flattened = dd_flatten_array( (array) $item );
							$handler->addDataTable( $itemLabel, $flattened );
						}
					} else {
						// For simple arrays or associative arrays, flatten and show in one table
						$flattened = dd_flatten_array( $var );
						$handler->addDataTable( $varLabel, $flattened );
					}
				} elseif ( is_object( $var ) ) {
					// For objects, flatten and show properties
					$flattened = dd_flatten_array( (array) $var );
					$handler->addDataTable( $varLabel, $flattened );
				} else {
					// Fallback for any other type
					$handler->addDataTable( $varLabel, array( 'Value' => print_r( $var, true ) ) );
				}
			}

			$whoops->pushHandler( $handler );
			$whoops->handleException(
				new \Exception( 'Debug Dump (dd)' )
			);
		} else {
			// Fallback for production
			echo '<pre style="background: #1e1e1e; color: #d4d4d4; padding: 20px; border-radius: 8px; font-family: monospace; line-height: 1.5; overflow: auto;">';
			echo '<strong style="color: #4ec9b0;">Debug Dump:</strong>' . PHP_EOL . PHP_EOL;

			foreach ( $vars as $index => $var ) {
				echo '<strong style="color: #569cd6;">Variable #' . ( $index + 1 ) . ':</strong>' . PHP_EOL;
				echo json_encode( $var, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES );
				echo PHP_EOL . PHP_EOL;
			}

			echo '</pre>';
		}

		exit( 1 );
	}
}

/**
 * Include global constants
 */
require_once get_template_directory() . '/inc/constants.php';

/**
 * Include helper functions
 */
require_once get_template_directory() . '/inc/helpers.php';

/**
 * Include performance monitoring
 * Temporarily disabled for rollback testing
 */
// require_once get_template_directory() . '/inc/performance-monitor.php';

/**
 * Include navigation functions and walker
 */
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/main-navigation-helper.php';
require_once get_template_directory() . '/inc/footer-menu-helper.php';

/**
 * Include PostHog tracking
 */
require_once get_template_directory() . '/inc/posthog-tracking.php';

/**
 * Google Analytics removed - using PostHog exclusively for analytics
 * PostHog provides comprehensive analytics without requiring 'unsafe-eval' in CSP
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

				// Enqueue CSS (simple)
				if ( isset( $main['css'] ) ) {
					foreach ( $main['css'] as $css_file ) {
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
 * Add SVG protection script to prevent path removal
 */
function sunnysideac_add_svg_protection() {
	?>
	<script>
	// SVG Path Protection - Prevents SVG paths from being removed from DOM
	(function() {
		'use strict';

		// Store original SVG paths when page loads
		const originalPaths = new Map();

		// Function to capture only specific SVG path data (feature icons, not navigation)
		function captureOriginalPaths() {
			const paths = document.querySelectorAll('svg path[d]');
			paths.forEach((path, index) => {
				const d = path.getAttribute('d');
				const svg = path.closest('svg');

				// Only protect SVGs in feature sections, not navigation or UI elements
				if (d && d.trim() && shouldProtectSVG(svg, path)) {
					originalPaths.set(path, {
						d: d,
						originalHTML: path.outerHTML
					});
					// Add debugging attribute
					path.setAttribute('data-svg-protect', 'true');
					path.setAttribute('data-svg-index', index);
				}
			});
			console.log('SVG Protection: Captured', originalPaths.size, 'protected SVG paths');
		}

		// Function to determine if an SVG should be protected
		function shouldProtectSVG(svg, path) {
			if (!svg) return false;

			// Don't protect navigation-related SVGs
			const dontProtectSelectors = [
				'.nav',
				'.navigation',
				'.menu',
				'.hamburger',
				'.mobile-nav',
				'.header',
				'.burger',
				'.menu-toggle'
			];

			for (const selector of dontProtectSelectors) {
				if (svg.closest(selector)) {
					return false;
				}
			}

			// Protect SVGs in feature sections, cards, service areas, etc.
			const protectSelectors = [
				'.feature',
				'.service',
				'.icon',
				'.why-choose',
				'.benefit',
				'.process',
				'[class*="feature"]',
				'[class*="service"]',
				'[class*="icon"]'
			];

			for (const selector of protectSelectors) {
				if (svg.closest(selector)) {
					return true;
				}
			}

			// Also protect SVGs with complex paths (likely icons, not simple UI elements)
			const pathLength = path.getAttribute('d')?.length || 0;
			if (pathLength > 100) { // Complex paths are likely icons
				return true;
			}

			return false;
		}

		// Function to restore missing paths
		function restoreMissingPaths() {
			const paths = document.querySelectorAll('svg path[data-svg-protect="true"]');
			let restored = 0;

			paths.forEach(path => {
				const original = originalPaths.get(path);
				if (original && !path.getAttribute('d')) {
					// Restore the d attribute
					path.setAttribute('d', original.d);
					restored++;
					console.log('SVG Protection: Restored path for element', path);
				}
			});

			if (restored > 0) {
				console.log('SVG Protection: Restored', restored, 'missing SVG paths');
			}
		}

		// MutationObserver to detect and prevent path removal
		const observer = new MutationObserver((mutations) => {
			let needsRestoration = false;

			mutations.forEach((mutation) => {
				if (mutation.type === 'attributes' && mutation.attributeName === 'd') {
					const target = mutation.target;
					if (target.tagName === 'path' && target.hasAttribute('data-svg-protect')) {
						const original = originalPaths.get(target);
						if (original && !target.getAttribute('d')) {
							// IMMEDIATE restoration - don't wait
							target.setAttribute('d', original.d);
							needsRestoration = true;
							console.warn('SVG Protection: Detected removal of path d attribute - IMMEDIATELY restored', target);
						}
					}
				}

				// Check for removed path elements
				mutation.removedNodes.forEach((node) => {
					if (node.nodeType === Node.ELEMENT_NODE && node.tagName === 'path') {
						if (node.hasAttribute && node.hasAttribute('data-svg-protect')) {
							console.warn('SVG Protection: Detected removed path element - re-adding to parent', node);
							needsRestoration = true;

							// Try to re-insert the removed node back to its parent
							if (mutation.target && mutation.target.appendChild) {
								mutation.target.appendChild(node.cloneNode(true));
							}
						}
					}
				});

				// Detect optimizeDOM function calls and prevent them
				if (mutation.type === 'childList' && mutation.removedNodes.length > 0) {
					// Check if any SVG paths were removed by optimizeDOM
					let svgPathsRemoved = false;
					mutation.removedNodes.forEach((node) => {
						if (node.nodeType === Node.ELEMENT_NODE) {
							const paths = node.querySelectorAll ? node.querySelectorAll('path[data-svg-protect]') : [];
							if (paths.length > 0 || (node.tagName === 'path' && node.hasAttribute('data-svg-protect'))) {
								svgPathsRemoved = true;
							}
						}
					});

					if (svgPathsRemoved && !window.SVGProtection.optimizeDOMBlocked) {
						console.error('SVG Protection: optimizeDOM function detected removing SVG paths! Blocking further calls.');
						// Try to disable optimizeDOM if it exists
						if (window.optimizeDOM) {
							window.optimizeDOM = function() {
								console.warn('SVG Protection: Blocked optimizeDOM call to protect SVG paths');
								return;
							};
							window.SVGProtection.optimizeDOMBlocked = true;
						}
					}
				}
			});

			if (needsRestoration) {
				// Schedule additional restoration as backup
				setTimeout(restoreMissingPaths, 10);
				setTimeout(restoreMissingPaths, 100);
			}
		});

		// Start observing
		function startObserving() {
			observer.observe(document.body, {
				attributes: true,
				attributeOldValue: true,
				childList: true,
				subtree: true
			});
		}

		// Initialize when DOM is ready
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', () => {
				captureOriginalPaths();
				startObserving();
				// Initial restoration check
				setTimeout(restoreMissingPaths, 100);
			});
		} else {
			captureOriginalPaths();
			startObserving();
			setTimeout(restoreMissingPaths, 100);
		}

		// Also check periodically as a backup
		setInterval(restoreMissingPaths, 2000);

		// Aggressive protection - Override common DOM manipulation methods
		const originalRemoveChild = Node.prototype.removeChild;
		const originalRemoveAttribute = Element.prototype.removeAttribute;
		const originalSetAttribute = Element.prototype.setAttribute;

		Node.prototype.removeChild = function(child) {
			if (child.tagName === 'path' && child.hasAttribute('data-svg-protect')) {
				console.warn('SVG Protection: Blocked removeChild() call on protected SVG path', child);
				return child; // Don't actually remove it
			}
			return originalRemoveChild.call(this, child);
		};

		Element.prototype.removeAttribute = function(attrName) {
			if (this.tagName === 'path' && attrName === 'd' && this.hasAttribute('data-svg-protect')) {
				console.warn('SVG Protection: Blocked removeAttribute("d") call on protected SVG path', this);
				return; // Don't actually remove the attribute
			}
			return originalRemoveAttribute.call(this, attrName);
		};

		Element.prototype.setAttribute = function(name, value) {
			// Allow our own protection script to set attributes
			if (this.tagName === 'path' && name === 'd' && this.hasAttribute('data-svg-protect') && (!value || value.trim() === '')) {
				console.warn('SVG Protection: Blocked setAttribute("d", "") call on protected SVG path', this);
				return; // Don't allow clearing the d attribute
			}
			return originalSetAttribute.call(this, name, value);
		};

		// Override innerHTML to protect SVG paths
		const originalInnerHTML = Object.getOwnPropertyDescriptor(Element.prototype, 'innerHTML');
		Object.defineProperty(Element.prototype, 'innerHTML', {
			set: function(value) {
				if (this.tagName === 'path' && this.hasAttribute('data-svg-protect')) {
					console.warn('SVG Protection: Blocked innerHTML modification on protected SVG path', this);
					return;
				}
				return originalInnerHTML.set.call(this, value);
			},
			get: originalInnerHTML.get
		});

		// Expose for debugging
		window.SVGProtection = {
			originalPaths: originalPaths,
			restoreMissingPaths: restoreMissingPaths,
			captureOriginalPaths: captureOriginalPaths,
			disabled: false
		};
	})();
	</script>
	<?php
}
// SVG protection no longer needed since optimizeDOM was fixed
// add_action('wp_footer', 'sunnysideac_add_svg_protection');

// Only load on specific pages (commented out to make it site-wide)
// add_action('wp_footer', 'sunnysideac_add_svg_protection', 999);

/**
 * Remove complex performance hooks that might cause conflicts
 */
function sunnysideac_remove_complex_performance_hooks() {
	// Remove critical CSS functions
	remove_action( 'wp_head', 'sunnysideac_inline_critical_css', 1 );
	remove_action( 'wp_head', 'sunnysideac_preload_css', 2 );
	remove_action( 'wp_head', 'sunnysideac_preload_critical_js', 3 );

	// Remove performance hints that might conflict
	remove_action( 'wp_head', 'sunnysideac_add_performance_hints', 1 );
}
add_action( 'init', 'sunnysideac_remove_complex_performance_hooks', 5 );

/**
 * Theme setup
 */
function sunnysideac_setup() {
	// Add theme support
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		)
	);

	// Register navigation menus
	// Note: 'primary' menu removed - main navigation now uses JSON config (config/main-navigation.json)
	// Note: 'footer' menu kept for backwards compatibility, but footer also uses JSON config (config/footer-menu.json)
	register_nav_menus(
		array(
			'footer' => __( 'Footer Menu (Legacy - uses JSON config)', 'sunnysideac' ),
		)
	);
}
add_action( 'after_setup_theme', 'sunnysideac_setup' );

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

// Register "City" caption
function register_city_cpt() {
	register_post_type(
		'city',
		array(
			'labels'       => array(
				'name'          => 'Cities',
				'singular_name' => 'City',
				'add_new_item'  => 'Add New City',
				'edit_item'     => 'Edit City',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array(
				'slug'       => 'cities',
				'with_front' => false,
			),
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'    => 'dashicons-location-alt',
			'show_in_rest' => true,
		)
	);
}

add_action( 'init', 'register_city_cpt' );


function register_service_cpt() {
	register_post_type(
		'service',
		array(
			'labels'       => array(
				'name'          => 'Services',
				'singular_name' => 'Service',
				'add_new_item'  => 'Add New Service',
				'edit_item'     => 'Edit Service',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array(
				'slug'       => 'services',
				'with_front' => false,
			),
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'    => 'dashicons-hammer',
			'show_in_rest' => true,
		)
	);
}

add_action( 'init', 'register_service_cpt' );


function register_brand_cpt() {
	register_post_type(
		'brand',
		array(
			'labels'       => array(
				'name'          => 'Brands',
				'singular_name' => 'Brand',
				'add_new_item'  => 'Add New Brand',
				'edit_item'     => 'Edit Brand',
			),
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => array( 'slug' => 'brands' ),
			'supports'     => array( 'title', 'editor', 'thumbnail' ),
			'menu_icon'    => 'dashicons-awards',
			'show_in_rest' => true,
		)
	);
}

add_action( 'init', 'register_brand_cpt' );

function register_service_category_taxonomy() {
	register_taxonomy(
		'service_category',
		array( 'service' ),
		array(
			'labels'            => array(
				'name'          => 'Service Categories',
				'singular_name' => 'Service Category',
				'search_items'  => 'Search Service Categoreis',
				'all_items'     => 'All Service Categories',
				'parent_item'   => 'Parent Service Category:',
				'edit_item'     => 'Edit Service Category',
				'update_item'   => 'Update Service Category',
				'add_new_item'  => 'Add New Service Category',
				'new_item_name' => 'New Service Category Name',
				'menu_name'     => 'Categories',
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array(
				'slug'       => 'service-category',
				'with_front' => false,
			),
			'show_in_rest'      => true,
		)
	);
}

add_action( 'init', 'register_service_category_taxonomy' );

/**
 * =========================================================================
 * CITY ↔ SERVICE CPT RELATIONSHIP (Meta-based, no taxonomy)
 * Using ACF Pro for field management
 * =========================================================================
 */

/**
 * Register ACF Field Group for Service → City relationship
 */
if ( function_exists( 'acf_add_local_field_group' ) ) {
	acf_add_local_field_group(
		array(
			'key'                   => 'group_service_city_relationship',
			'title'                 => 'City Assignment',
			'fields'                => array(
				array(
					'key'           => 'field_service_city_id',
					'label'         => 'Cities',
					'name'          => '_service_city_id',
					'type'          => 'post_object',
					'instructions'  => 'Select the cities where this service is offered.',
					'required'      => 0,
					'post_type'     => array(
						0 => 'city',
					),
					'taxonomy'      => '',
					'allow_null'    => 1,
					'multiple'      => 1,
					'return_format' => 'id',
					'ui'            => 1,
				),
			),
			'location'              => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		)
	);

	/**
	 * City CPT - Local SEO Content Fields
	 */
	acf_add_local_field_group(
		array(
			'key'      => 'group_city_seo_content',
			'title'    => 'Local SEO Content',
			'fields'   => array(
				array(
					'key'          => 'field_city_neighborhoods',
					'label'        => 'Neighborhoods & Areas Served',
					'name'         => 'neighborhoods',
					'type'         => 'textarea',
					'instructions' => 'List specific neighborhoods, zip codes, or areas. This makes content unique per city.',
					'placeholder'  => 'Example: Downtown Miami, Brickell, Coral Way, Little Havana, Coconut Grove',
				),
				array(
					'key'          => 'field_city_climate_note',
					'label'        => 'Climate/Weather Note',
					'name'         => 'climate_note',
					'type'         => 'textarea',
					'instructions' => 'Local climate considerations for HVAC services.',
					'placeholder'  => 'Example: Miami\'s humid subtropical climate means AC systems work harder year-round...',
				),
				array(
					'key'          => 'field_city_service_area_note',
					'label'        => 'Service Area Details',
					'name'         => 'service_area_note',
					'type'         => 'wysiwyg',
					'instructions' => 'Additional details about serving this city (response time, local permits, etc.)',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				),
				array(
					'key'          => 'field_city_video_url',
					'label'        => 'City Video URL',
					'name'         => 'city_video_url',
					'type'         => 'url',
					'instructions' => 'YouTube or Vimeo URL for city-specific video (e.g., "HVAC Services in Miami"). HIGHLY recommended for local SEO!',
					'placeholder'  => 'https://www.youtube.com/watch?v=...',
				),
				array(
					'key'               => 'field_city_video_title',
					'label'             => 'Video Title',
					'name'              => 'city_video_title',
					'type'              => 'text',
					'instructions'      => 'Title of the video (for schema markup). Example: "HVAC Services in Miami, Florida"',
					'placeholder'       => 'HVAC Services in Miami, Florida',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							),
						),
					),
				),
				array(
					'key'               => 'field_city_video_description',
					'label'             => 'Video Description',
					'name'              => 'city_video_description',
					'type'              => 'textarea',
					'instructions'      => 'Brief description of the video content (for schema markup)',
					'placeholder'       => 'Watch our HVAC technicians servicing homes and businesses throughout Miami...',
					'rows'              => 3,
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							),
						),
					),
				),
				array(
					'key'               => 'field_city_video_thumbnail',
					'label'             => 'Video Thumbnail (Optional)',
					'name'              => 'city_video_thumbnail',
					'type'              => 'image',
					'instructions'      => 'Custom thumbnail image (1280x720). If empty, will use video platform\'s default thumbnail.',
					'return_format'     => 'url',
					'preview_size'      => 'medium',
					'library'           => 'all',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							),
						),
					),
				),
				array(
					'key'               => 'field_city_video_duration',
					'label'             => 'Video Duration',
					'name'              => 'city_video_duration',
					'type'              => 'text',
					'instructions'      => 'Duration in ISO 8601 format (e.g., PT2M30S for 2 minutes 30 seconds). Used for schema markup.',
					'placeholder'       => 'PT2M30S',
					'conditional_logic' => array(
						array(
							array(
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'city',
					),
				),
			),
			'position' => 'normal',
		)
	);

	/**
	 * Service CPT - SEO Content Blocks
	 */
	acf_add_local_field_group(
		array(
			'key'      => 'group_service_seo_content',
			'title'    => 'Service Content Blocks',
			'fields'   => array(
				array(
					'key'          => 'field_service_description',
					'label'        => 'Service Description',
					'name'         => 'service_description',
					'type'         => 'wysiwyg',
					'instructions' => 'Main service description (applies to all cities)',
					'toolbar'      => 'full',
					'media_upload' => 1,
				),
				array(
					'key'          => 'field_service_benefits',
					'label'        => 'Key Benefits',
					'name'         => 'service_benefits',
					'type'         => 'repeater',
					'instructions' => 'List of benefits for this service',
					'layout'       => 'table',
					'button_label' => 'Add Benefit',
					'sub_fields'   => array(
						array(
							'key'   => 'field_benefit_text',
							'label' => 'Benefit',
							'name'  => 'benefit',
							'type'  => 'text',
						),
					),
				),
				array(
					'key'          => 'field_service_process',
					'label'        => 'Service Process/Steps',
					'name'         => 'service_process',
					'type'         => 'repeater',
					'instructions' => 'Step-by-step process',
					'layout'       => 'row',
					'button_label' => 'Add Step',
					'sub_fields'   => array(
						array(
							'key'   => 'field_process_step_title',
							'label' => 'Step Title',
							'name'  => 'title',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_process_step_description',
							'label' => 'Description',
							'name'  => 'description',
							'type'  => 'textarea',
							'rows'  => 3,
						),
					),
				),
				array(
					'key'          => 'field_service_faqs',
					'label'        => 'FAQs',
					'name'         => 'service_faqs',
					'type'         => 'repeater',
					'instructions' => 'Frequently asked questions about this service',
					'layout'       => 'row',
					'button_label' => 'Add FAQ',
					'sub_fields'   => array(
						array(
							'key'   => 'field_faq_question',
							'label' => 'Question',
							'name'  => 'question',
							'type'  => 'text',
						),
						array(
							'key'   => 'field_faq_answer',
							'label' => 'Answer',
							'name'  => 'answer',
							'type'  => 'textarea',
							'rows'  => 4,
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					),
				),
			),
			'position' => 'normal',
		)
	);
}

/**
 * Register city_slug query var
 */
function sunnysideac_add_city_slug_query_var( $vars ) {
	$vars[] = 'city_slug';
	return $vars;
}
add_filter( 'query_vars', 'sunnysideac_add_city_slug_query_var' );

/**
 * Add rewrite for /cities/{city}/ → city page
 */
function sunnysideac_add_cities_city_rewrite() {
	add_rewrite_rule(
		'^cities/([^/]+)/?$',
		'index.php?city=$matches[1]',
		'top'
	);
}
add_action( 'init', 'sunnysideac_add_cities_city_rewrite', 15 );

/**
 * Add rewrite for /daikin/{product}/ → Daikin product page
 */
function sunnysideac_add_daikin_product_rewrite() {
	add_rewrite_rule(
		'^daikin/([^/]+)/?$',
		'index.php?pagename=$matches[1]',
		'top'
	);
}
add_action( 'init', 'sunnysideac_add_daikin_product_rewrite', 15 );

/**
 * Add root-level rewrite for /{city}/{service}/ → single service
 * Excludes known base URLs like 'services', 'category', 'tag', 'page', 'cities', 'brands', 'daikin'
 */
function sunnysideac_add_city_service_root_rewrite() {
	add_rewrite_rule(
		'^(?!services|category|tag|page|cities|brands|daikin)([^/]+)/([^/]+)/?$',
		'index.php?post_type=service&name=$matches[2]&city_slug=$matches[1]',
		'top'
	);
}
add_action( 'init', 'sunnysideac_add_city_service_root_rewrite', 16 );

/**
 * Add rewrite rules for custom sitemaps
 */
function sunnysideac_add_custom_sitemap_rewrites() {
	// Main sitemap index
	add_rewrite_rule(
		'^sitemap\.xml$',
		'index.php?custom_sitemap=index',
		'top'
	);

	// Individual sitemap handlers
	add_rewrite_rule(
		'^areas-sitemap\.xml$',
		'index.php?custom_sitemap=areas',
		'top'
	);

	add_rewrite_rule(
		'^brands-sitemap\.xml$',
		'index.php?custom_sitemap=brands',
		'top'
	);

	add_rewrite_rule(
		'^service-city-sitemap\.xml$',
		'index.php?custom_sitemap=service-city',
		'top'
	);
}
add_action( 'init', 'sunnysideac_add_custom_sitemap_rewrites', 17 );


/**
 * Add custom query vars for city routing
 */
function sunnysideac_add_city_query_vars( $query_vars ) {
	$query_vars[] = 'city';
	return $query_vars;
}
add_filter( 'query_vars', 'sunnysideac_add_city_query_vars' );

/**
 * Add custom sitemap query vars
 */
function sunnysideac_add_custom_sitemap_query_vars( $query_vars ) {
	$query_vars[] = 'custom_sitemap';
	return $query_vars;
}
add_filter( 'query_vars', 'sunnysideac_add_custom_sitemap_query_vars' );

/**
 * Handle city query var requests
 */
function sunnysideac_handle_city_request( $query ) {
	// Only on frontend requests
	if ( is_admin() ) {
		return;
	}

	// Check if this is a city query var request
	$city_slug = get_query_var( 'city' );
	if ( ! empty( $city_slug ) ) {
		// Find the city post by slug
		$city_post = get_page_by_path( $city_slug, OBJECT, 'city' );

		if ( $city_post && $city_post->post_status === 'publish' ) {
			// Set the correct query vars for single city display
			$query->set( 'post_type', 'city' );
			$query->set( 'p', $city_post->ID );
			$query->set( 'name', $city_slug );
			$query->is_single   = true;
			$query->is_singular = true;
		}
	}
}
add_action( 'pre_get_posts', 'sunnysideac_handle_city_request' );


/**
 * Handle 404s for bare category and tag URLs
 */
function sunnysideac_handle_bare_urls_404s() {
	// Only on frontend 404 requests
	if ( is_404() && ! is_admin() ) {
		$requested_url = $_SERVER['REQUEST_URI'];

		// Handle bare category URL - redirect to blog
		if ( trim( $requested_url, '/' ) === 'category' ) {
			wp_safe_redirect( home_url( '/blog/' ), 301 );
			exit;
		}

		// Handle bare tag URL - redirect to blog
		if ( trim( $requested_url, '/' ) === 'tag' ) {
			wp_safe_redirect( home_url( '/blog/' ), 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'sunnysideac_handle_bare_urls_404s' );

/**
 * Handle custom sitemap requests - Improved pattern matching
 */
function sunnysideac_handle_custom_sitemaps() {
	$request_uri = $_SERVER['REQUEST_URI'] ?? '';

	// Determine sitemap type with more specific patterns
	$sitemap_type = null;

	if ( $request_uri === '/sitemap.xml' || $request_uri === '/sitemap.xml/' ) {
		$sitemap_type = 'index';
	} elseif ( $request_uri === '/areas-sitemap.xml' || $request_uri === '/areas-sitemap.xml/' ) {
		$sitemap_type = 'areas';
	} elseif ( $request_uri === '/brands-sitemap.xml' || $request_uri === '/brands-sitemap.xml/' ) {
		$sitemap_type = 'brands';
	} elseif ( $request_uri === '/service-city-sitemap.xml' || $request_uri === '/service-city-sitemap.xml/' ) {
		$sitemap_type = 'service-city';
	}

	if ( $sitemap_type ) {
		// Load the generator
		require_once get_template_directory() . '/inc/custom-sitemap-generator.php';
		$generator = new Sunnyside_Custom_Sitemap_Generator();

		// Set query var and handle
		set_query_var( 'custom_sitemap', $sitemap_type );
		$generator->handle_sitemap_requests();
	}
}
// Temporarily disabled - add_action( 'template_redirect', 'sunnysideac_handle_custom_sitemaps', 1 );

/**
 * Force city templates for proper routing
 */
function sunnysideac_force_city_templates( $template ) {
	// Only modify template for city requests on frontend
	if ( ! is_admin() ) {
		// Force single-city.php for single city requests
		if ( is_singular( 'city' ) ) {
			$city_template = locate_template( 'single-city.php' );
			if ( $city_template ) {
				return $city_template;
			}
		}

		// Force archive-cities.php for city archive requests
		if ( is_post_type_archive( 'city' ) ) {
			$cities_template = locate_template( 'archive-cities.php' );
			if ( $cities_template ) {
				return $cities_template;
			}
		}
	}
	return $template;
}
add_filter( 'template_include', 'sunnysideac_force_city_templates' );

/**
 * Validate the route at template_redirect
 * Since all services are available in all cities, we just verify both posts exist
 */
function sunnysideac_validate_city_service_route() {
	// Only on frontend single service requests that have our city_slug filled
	if ( is_admin() ) {
		return;
	}

	$city_slug    = get_query_var( 'city_slug' );
	$service_slug = get_query_var( 'name' );

	if ( empty( $city_slug ) || empty( $service_slug ) ) {
		return;
	}

	// Resolve city post by path/slug
	$city_post = get_page_by_path( $city_slug, OBJECT, 'city' );

	// Resolve service post by path/slug
	$service_post = get_page_by_path( $service_slug, OBJECT, 'service' );

	// Valid if both posts exist and are published
	$valid = ( $city_post && $service_post && $city_post->post_status === 'publish' && $service_post->post_status === 'publish' );

	if ( ! $valid ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		// Load 404 template and halt
		$notfound = get_404_template();
		if ( $notfound ) {
			include $notfound;
		} else {
			wp_redirect( home_url( '/' ) );
			exit;
		}
		exit;
	}
}
add_action( 'template_redirect', 'sunnysideac_validate_city_service_route', 1 );

/**
 * Use single-service-city.php template when city_slug is present
 */
add_filter(
	'template_include',
	function ( $template ) {
		if ( is_singular( 'service' ) && get_query_var( 'city_slug' ) ) {
			$new_template = locate_template( array( 'single-service-city.php' ) );
			if ( $new_template ) {
				return $new_template;
			}
		}
		return $template;
	}
);

/**
 * Generate LocalBusiness JSON-LD Schema for Homepage
 *
 * @return string JSON-LD schema markup
 */
function sunnysideac_get_localbusiness_schema() {
	$schema = array(
		'@context'                  => 'https://schema.org',
		'@type'                     => 'LocalBusiness',
		'name'                      => 'SunnySide 24/7 AC',
		'alternateName'             => 'Sunnyside AC',
		'description'               => 'Professional HVAC services including AC repair, installation, and maintenance. Family-owned and operated since 2014, serving South Florida with 24/7 emergency service.',
		'url'                       => home_url( '/' ),
		'telephone'                 => SUNNYSIDE_PHONE_DISPLAY,
		'email'                     => SUNNYSIDE_EMAIL_ADDRESS,
		'address'                   => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => SUNNYSIDE_ADDRESS_STREET,
			'addressLocality' => SUNNYSIDE_ADDRESS_CITY,
			'addressRegion'   => SUNNYSIDE_ADDRESS_STATE,
			'postalCode'      => SUNNYSIDE_ADDRESS_ZIP,
			'addressCountry'  => 'US',
		),
		'geo'                       => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => SUNNYSIDE_LATITUDE,
			'longitude' => SUNNYSIDE_LONGITUDE,
		),
		'openingHoursSpecification' => array(),
		'areaServed'                => array(),
		'hasOfferCatalog'           => array(
			'@type'           => 'OfferCatalog',
			'name'            => 'HVAC Services',
			'itemListElement' => array(),
		),
		'sameAs'                    => array(
			SUNNYSIDE_FACEBOOK_URL,
			SUNNYSIDE_INSTAGRAM_URL,
			SUNNYSIDE_TWITTER_URL,
			SUNNYSIDE_YOUTUBE_URL,
			SUNNYSIDE_LINKEDIN_URL,
		),
		'foundingDate'              => SUNNYSIDE_FOUNDING_DATE . '-01-01',
		'priceRange'                => '$$',
		'paymentAccepted'           => array( 'Cash', 'Credit Card', 'Check' ),
		'languagesSpoken'           => array( 'English', 'Spanish' ),
	);

	// Add opening hours
	foreach ( SUNNYSIDE_BUSINESS_HOURS as $day => $hours ) {
		$schema['openingHoursSpecification'][] = array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => $day,
			'opens'     => substr( $hours, 0, 5 ),
			'closes'    => substr( $hours, -5 ),
		);
	}

	// Add service areas
	foreach ( SUNNYSIDE_SERVICE_AREAS as $area ) {
		$schema['areaServed'][] = array(
			'@type'         => 'City',
			'name'          => $area,
			'addressRegion' => 'FL',
		);
	}

	// Add services to catalog
	$services_by_category = SUNNYSIDE_SERVICES_BY_CATEGORY;
	$all_services         = array_merge( $services_by_category['cooling'], $services_by_category['heating'], $services_by_category['air_quality'] );

	foreach ( $all_services as $service ) {
		$schema['hasOfferCatalog']['itemListElement'][] = array(
			'@type'             => 'Offer',
			'itemOffered'       => array(
				'@type' => 'Service',
				'name'  => $service,
			),
			'areaServed'        => 'South Florida',
			'availableAtOrFrom' => array(
				'@type'   => 'Place',
				'address' => array(
					'@type'         => 'PostalAddress',
					'addressRegion' => 'FL',
				),
			),
		);
	}

	return json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
}

/**
 * Generate Organization JSON-LD Schema for Homepage
 *
 * @return string JSON-LD schema markup
 */
function sunnysideac_get_organization_schema() {
	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Organization',
		'name'          => 'SunnySide 24/7 AC',
		'alternateName' => 'Sunnyside AC',
		'url'           => home_url( '/' ),
		'logo'          => get_template_directory_uri() . '/assets/images/logos/sunny-side-logo.png',
		'description'   => 'Family-owned and operated HVAC company serving South Florida since 2014. Specializing in AC repair, installation, and maintenance with 24/7 emergency service.',
		'contactPoint'  => array(
			'@type'             => 'ContactPoint',
			'telephone'         => SUNNYSIDE_PHONE_DISPLAY,
			'contactType'       => 'customer service',
			'areaServed'        => 'South Florida',
			'availableLanguage' => array( 'English', 'Spanish' ),
		),
		'address'       => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => SUNNYSIDE_ADDRESS_STREET,
			'addressLocality' => SUNNYSIDE_ADDRESS_CITY,
			'addressRegion'   => SUNNYSIDE_ADDRESS_STATE,
			'postalCode'      => SUNNYSIDE_ADDRESS_ZIP,
			'addressCountry'  => 'US',
		),
		'sameAs'        => array(
			SUNNYSIDE_FACEBOOK_URL,
			SUNNYSIDE_INSTAGRAM_URL,
			SUNNYSIDE_TWITTER_URL,
			SUNNYSIDE_YOUTUBE_URL,
			SUNNYSIDE_LINKEDIN_URL,
		),
		'foundingDate'  => SUNNYSIDE_FOUNDING_DATE . '-01-01',
		'areaServed'    => array(),
	);

	// Add service areas
	foreach ( SUNNYSIDE_SERVICE_AREAS as $area ) {
		$schema['areaServed'][] = array(
			'@type' => 'City',
			'name'  => $area,
		);
	}

	return json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT );
}

/**
 * Output homepage schemas in head
 */
function sunnysideac_homepage_schemas() {
	// Only run on homepage
	if ( ! is_front_page() ) {
		return;
	}
	?>
	<!-- Local SEO Schema Markup -->
	<script type="application/ld+json">
	<?php echo sunnysideac_get_localbusiness_schema(); ?>
	</script>

	<script type="application/ld+json">
	<?php echo sunnysideac_get_organization_schema(); ?>
	</script>
	<?php
}
add_action( 'wp_head', 'sunnysideac_homepage_schemas', 5 );

/**
 * Add homepage meta tags
 */
function sunnysideac_homepage_meta_tags() {
	// Only run on homepage
	if ( ! is_front_page() ) {
		return;
	}

	$site_title       = get_bloginfo( 'name' );
	$site_description = get_bloginfo( 'description' );
	$home_url         = home_url( '/' );
	$logo_url         = get_template_directory_uri() . '/assets/images/logos/sunny-side-logo.png';

	?>
	<!-- Enhanced Meta Tags for Local SEO -->
	<meta name="description" content="<?php echo esc_attr( $site_description . ' | 24/7 Emergency AC Repair, Installation & Maintenance in South Florida. Family-owned since 2014. Call ' . SUNNYSIDE_PHONE_DISPLAY . ' for fast service!' ); ?>">

	<!-- Open Graph Meta Tags -->
	<meta property="og:title" content="<?php echo esc_attr( $site_title ); ?> | 24/7 AC Repair South Florida">
	<meta property="og:description" content="<?php echo esc_attr( $site_description . ' | Professional HVAC services with 24/7 emergency response. Serving all of South Florida.' ); ?>">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo esc_url( $home_url ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $logo_url ); ?>">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:site_name" content="<?php echo esc_attr( $site_title ); ?>">
	<meta property="og:locale" content="en_US">

	<!-- Twitter Card Meta Tags -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo esc_attr( $site_title ); ?> | 24/7 AC Repair">
	<meta name="twitter:description" content="<?php echo esc_attr( $site_description . ' | Fast, professional HVAC service available 24/7.' ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( $logo_url ); ?>">
	<meta name="twitter:site" content="@sunnyside247ac">

	<!-- Additional Local SEO Meta Tags -->
	<meta name="geo.region" content="US-FL">
	<meta name="geo.placename" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_CITY ); ?>">
	<meta name="geo.position" content="<?php echo esc_attr( SUNNYSIDE_LATITUDE . ';' . SUNNYSIDE_LONGITUDE ); ?>">
	<meta name="ICBM" content="<?php echo esc_attr( SUNNYSIDE_LATITUDE . ',' . SUNNYSIDE_LONGITUDE ); ?>">

	<!-- Business Information -->
	<meta name="business:contact_data:street_address" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_STREET ); ?>">
	<meta name="business:contact_data:locality" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_CITY ); ?>">
	<meta name="business:contact_data:region" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_STATE ); ?>">
	<meta name="business:contact_data:postal_code" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_ZIP ); ?>">
	<meta name="business:contact_data:country_name" content="USA">
	<meta name="business:contact_data:phone_number" content="<?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
	<meta name="business:contact_data:email" content="<?php echo esc_attr( SUNNYSIDE_EMAIL_ADDRESS ); ?>">
	<?php
}
add_action( 'wp_head', 'sunnysideac_homepage_meta_tags', 1 );

// Note: RankMath sitemap providers removed - using custom sitemap system instead

/**
 * Handle careers form submission via AJAX
 */
function sunnysideac_handle_careers_form() {
	// Verify nonce for security
	if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'careers_form_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Security verification failed.' ) );
	}

	// Sanitize and validate form data
	$first_name     = sanitize_text_field( $_POST['first_name'] ?? '' );
	$last_name      = sanitize_text_field( $_POST['last_name'] ?? '' );
	$email          = sanitize_email( $_POST['email'] ?? '' );
	$phone          = sanitize_text_field( $_POST['phone'] ?? '' );
	$position       = sanitize_text_field( $_POST['position'] ?? '' );
	$other_position = sanitize_text_field( $_POST['other_position'] ?? '' );
	$experience     = intval( $_POST['experience'] ?? 0 );
	$availability   = sanitize_text_field( $_POST['availability'] ?? '' );
	$message        = sanitize_textarea_field( $_POST['message'] ?? '' );

	// Validate required fields
	if ( empty( $first_name ) || empty( $last_name ) || empty( $email ) || empty( $phone ) || empty( $position ) || empty( $availability ) ) {
		wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ) );
	}

	// Validate email format
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Please enter a valid email address.' ) );
	}

	// Handle "Other" position
	if ( $position === 'Other' && empty( $other_position ) ) {
		wp_send_json_error( array( 'message' => 'Please specify the position you are applying for.' ) );
	}
	$final_position = $position === 'Other' ? $other_position : $position;

	// Handle file upload
	$resume_content = '';
	if ( isset( $_FILES['resume'] ) && $_FILES['resume']['error'] === UPLOAD_ERR_OK ) {
		$file = $_FILES['resume'];

		// Validate file type
		$allowed_types = array( 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' );
		if ( ! in_array( $file['type'], $allowed_types ) ) {
			wp_send_json_error( array( 'message' => 'Resume must be a PDF, DOC, or DOCX file.' ) );
		}

		// Validate file size (5MB max)
		if ( $file['size'] > 5 * 1024 * 1024 ) {
			wp_send_json_error( array( 'message' => 'Resume file must be smaller than 5MB.' ) );
		}

		// Read file content
		$resume_content = file_get_contents( $file['tmp_name'] );
		if ( $resume_content === false ) {
			wp_send_json_error( array( 'message' => 'Error reading resume file.' ) );
		}
	}

	// Create email content
	$to_email = SUNNYSIDE_EMAIL_ADDRESS;
	$subject  = 'New Job Application: ' . $final_position . ' - ' . $first_name . ' ' . $last_name;
	$headers  = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . $first_name . ' ' . $last_name . ' <' . $email . '>' );

	$email_body = '
		<h2>New Job Application</h2>
		<p><strong>Position:</strong> ' . esc_html( $final_position ) . '</p>
		<p><strong>Name:</strong> ' . esc_html( $first_name ) . ' ' . esc_html( $last_name ) . '</p>
		<p><strong>Email:</strong> ' . esc_html( $email ) . '</p>
		<p><strong>Phone:</strong> ' . esc_html( $phone ) . '</p>
		<p><strong>Years of Experience:</strong> ' . esc_html( $experience ) . '</p>
		<p><strong>Availability:</strong> ' . esc_html( $availability ) . '</p>';

	if ( ! empty( $message ) ) {
		$email_body .= '<p><strong>Additional Information:</strong></p><p>' . nl2br( esc_html( $message ) ) . '</p>';
	}

	$email_body .= '
		<p><strong>Submitted:</strong> ' . date( 'F j, Y, g:i a' ) . '</p>
		<hr>
		<p><em>This application was submitted via the Sunnyside AC website careers form.</em></p>';

	// Attach resume if uploaded
	if ( ! empty( $resume_content ) ) {
		$uploads     = wp_upload_dir();
		$filename    = sanitize_file_name( 'resume_' . $first_name . '_' . $last_name . '_' . time() . '.' . pathinfo( $_FILES['resume']['name'], PATHINFO_EXTENSION ) );
		$upload_file = $uploads['path'] . '/' . $filename;

		// Save the file
		if ( file_put_contents( $upload_file, $resume_content ) !== false ) {
			// Add attachment
			$headers[] = 'Bcc: careers@sunnysideac.com';

			// For simplicity, we'll note the attachment in the email
			// In a production environment, you might want to use wp_mail() with proper attachments
			$email_body .= '<p><strong>Resume:</strong> Attached (saved as: ' . esc_html( $filename ) . ')</p>';
		}
	}

	// Send email to company
	$company_email_sent = wp_mail( $to_email, $subject, $email_body, $headers );

	// Send confirmation email to applicant
	if ( $company_email_sent ) {
		$confirmation_subject = 'We Received Your Application - Sunnyside AC';
		$confirmation_body    = '
			<h2>Thank You for Your Interest in Sunnyside AC!</h2>
			<p>Dear ' . esc_html( $first_name ) . ' ' . esc_html( $last_name ) . ',</p>
			<p>We have successfully received your application for the <strong>' . esc_html( $final_position ) . "</strong> position.</p>
			<p>Our hiring team will review your application and contact you within 2-3 business days if your qualifications match our current needs.</p>
			<p>If you have any questions in the meantime, please don't hesitate to contact us at:</p>
			<ul>
				<li>Phone: <a href='tel:" . esc_attr( SUNNYSIDE_TEL_HREF ) . "'>" . esc_html( SUNNYSIDE_PHONE_DISPLAY ) . "</a></li>
				<li>Email: <a href='mailto:" . esc_attr( SUNNYSIDE_EMAIL_ADDRESS ) . "'>" . esc_html( SUNNYSIDE_EMAIL_ADDRESS ) . '</a></li>
			</ul>
			<p>We look forward to learning more about you!</p>
			<p>Best regards,<br>The Sunnyside AC Team</p>';

		wp_mail( $email, $confirmation_subject, $confirmation_body, array( 'Content-Type: text/html; charset=UTF-8' ) );
	}

	if ( $company_email_sent ) {
		wp_send_json_success( array( 'message' => 'Application submitted successfully!' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Error submitting application. Please try again.' ) );
	}
}
add_action( 'wp_ajax_sunnysideac_handle_careers_form', 'sunnysideac_handle_careers_form' );
add_action( 'wp_ajax_nopriv_sunnysideac_handle_careers_form', 'sunnysideac_handle_careers_form' );

/**
 * Add nonce field to careers form
 */
function sunnysideac_add_careers_form_nonce() {
	?>
	<input type="hidden" name="action" value="sunnysideac_handle_careers_form">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'careers_form_nonce' ); ?>">
	<?php
}

/**
 * Unregister Service Worker and clear all caches
 * This script will run on page load to clean up the old service worker
 * TODO: Remove this function after 1-2 weeks once all users' service workers are cleared
 */
function sunnysideac_unregister_service_worker() {
	?>
	<script>
		if ('serviceWorker' in navigator) {
			navigator.serviceWorker.getRegistrations().then(function(registrations) {
				for(let registration of registrations) {
					registration.unregister().then(function(success) {
						if (success) {
							console.log('✅ ServiceWorker unregistered successfully');
						}
					});
				}
			});

			// Clear all caches
			if ('caches' in window) {
				caches.keys().then(function(cacheNames) {
					return Promise.all(
						cacheNames.map(function(cacheName) {
							console.log('🗑️ Deleting cache:', cacheName);
							return caches.delete(cacheName);
						})
					);
				}).then(function() {
					console.log('✅ All caches cleared');
				});
			}
		}
	</script>
	<?php
}
add_action('wp_footer', 'sunnysideac_unregister_service_worker', 1);

/**
 * DOM size optimization for mobile performance
 */
function sunnysideac_optimize_dom_size() {
	?>
	<script>
		// Remove unnecessary DOM elements for mobile performance
		function optimizeDOM() {
			// Remove empty elements
			const emptyElements = document.querySelectorAll('*:empty:not(script):not(style):not(link):not(meta):not(br):not(input):not(img):not(hr):not(svg):not(path)');
			emptyElements.forEach(el => {
				if (el.children.length === 0 && el.textContent.trim() === '') {
					// Don't remove elements that have styling or specific attributes
					const hasStyling = el.className ||
										el.getAttribute('role') ||
										el.getAttribute('style') ||
										['H1', 'H2', 'H3', 'H4', 'H5', 'H6', 'DIV', 'SPAN'].includes(el.tagName);
					if (!hasStyling) {
						el.remove();
					}
				}
			});

			// Remove duplicate navigation for mobile (keep only one)
			if (window.innerWidth <= 768) {
				const mobileNav = document.querySelector('#mobile-menu');
				const desktopNav = document.querySelector('#desktop-nav');
				if (mobileNav && desktopNav) {
					// Hide desktop nav on mobile
					desktopNav.style.display = 'none';
				}
			}

			// Lazy load non-critical sections
			const lazySections = document.querySelectorAll('[data-lazy-section]');
			if ('IntersectionObserver' in window) {
				const sectionObserver = new IntersectionObserver((entries) => {
					entries.forEach(entry => {
						if (entry.isIntersecting) {
							const section = entry.target;
							section.classList.remove('lazy-section');
							sectionObserver.unobserve(section);
						}
					});
				});

				lazySections.forEach(section => {
					section.classList.add('lazy-section');
					sectionObserver.observe(section);
				});
			}

			// Remove unused WordPress elements (but preserve SVGs)
			const wpElements = document.querySelectorAll('.wp-block-spacer, .has-background, .wp-block-group__inner-container:empty');
			wpElements.forEach(el => {
				if (el.children.length === 0 && el.textContent.trim() === '') {
					// Don't remove if this element contains SVGs
					if (!el.querySelector('svg')) {
						el.remove();
					}
				}
			});
		}

		// Run DOM optimization when DOM is loaded
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', optimizeDOM);
		} else {
			optimizeDOM();
		}

		// Also run after page load to catch dynamic content
		window.addEventListener('load', function() {
			setTimeout(optimizeDOM, 1000);
		});
	</script>

	<style>
		/* Lazy section styling */
		.lazy-section {
			opacity: 0;
			transform: translateY(20px);
			transition: opacity 0.3s ease, transform 0.3s ease;
		}

		.lazy-section:not(.lazy-section) {
			opacity: 1;
			transform: translateY(0);
		}

		/* Hide desktop navigation on mobile */
		@media (max-width: 768px) {
			#desktop-nav {
				display: none !important;
			}
		}

		/* Hide mobile navigation on desktop */
		@media (min-width: 769px) {
			#mobile-menu {
				display: none !important;
			}
		}
	</style>
	<?php
}
add_action('wp_footer', 'sunnysideac_optimize_dom_size', 50);

/**
 * Mobile-specific performance optimizations
 */
function sunnysideac_mobile_optimizations() {
	?>
	<script>
		// Mobile performance optimizations
		if (window.innerWidth <= 768) {
			// Reduce JavaScript execution on mobile
			function throttle(func, limit) {
				let inThrottle;
				return function() {
					const args = arguments;
					const context = this;
					if (!inThrottle) {
						func.apply(context, args);
						inThrottle = true;
						setTimeout(() => inThrottle = false, limit);
					}
				};
			}

			// Throttle scroll events on mobile
			if (window.addEventListener) {
				const originalAddEventListener = EventTarget.prototype.addEventListener;
				EventTarget.prototype.addEventListener = function(type, listener, options) {
					if (type === 'scroll') {
						listener = throttle(listener, 100);
					}
					return originalAddEventListener.call(this, type, listener, options);
				};
			}

			// Disable hover effects on touch devices
			if ('ontouchstart' in window) {
				document.documentElement.classList.add('touch-device');
			}

			// Reduce animation complexity on mobile
			const style = document.createElement('style');
			style.textContent = `
				@media (max-width: 768px) {
					*, *::before, *::after {
						animation-duration: 0.2s !important;
						transition-duration: 0.2s !important;
					}

					.hero-section {
						min-height: 50vh !important;
					}

					.logo-marquee {
						animation-duration: 20s !important;
					}
				}
			`;
			document.head.appendChild(style);
		}

		// Preload critical resources based on device type
		function preloadCriticalResources() {
			const isMobile = window.innerWidth <= 768;

			if (isMobile) {
				// Preload mobile-specific critical resources
				const mobileCSS = document.createElement('link');
				mobileCSS.rel = 'preload';
				mobileCSS.href = '<?php
				$manifest_path = get_template_directory() . "/dist/.vite/manifest.json";
				if (file_exists($manifest_path)) {
					$manifest = json_decode(file_get_contents($manifest_path), true);
					if (isset($manifest["src/main.js"]["css"][0])) {
						echo get_template_directory_uri() . "/dist/" . $manifest["src/main.js"]["css"][0];
					} else {
						echo get_template_directory_uri() . "/dist/assets/main.css"; // fallback
					}
				} else {
					echo get_template_directory_uri() . "/dist/assets/main.css"; // fallback
				}
			?>';
				mobileCSS.as = 'style';
				document.head.appendChild(mobileCSS);

				// Preload critical images for mobile
				const heroImage = document.querySelector('.hero-section img');
				if (heroImage && heroImage.dataset.src) {
					const imgPreload = document.createElement('link');
					imgPreload.rel = 'preload';
					imgPreload.as = 'image';
					imgPreload.href = heroImage.dataset.src;
					document.head.appendChild(imgPreload);
				}
			}
		}

		// Run preloading early
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', preloadCriticalResources);
		} else {
			preloadCriticalResources();
		}
	</script>
	<?php
}
add_action('wp_head', 'sunnysideac_mobile_optimizations', 1);

/**
 * Add WebP support for mobile
 */
function sunnysideac_webp_support() {
	?>
	<script>
		// WebP detection and fallback
		function supportsWebP() {
			return new Promise(resolve => {
				const webP = new Image();
				webP.onload = webP.onerror = function() {
					resolve(webP.height === 2);
				};
				webP.src = 'data:image/webp;base64,UklGRjoAAABXRUJQVlA4IC4AAACyAgCdASoCAAIALmk0mk0iIiIiIgBoSygABc6WWgAA/veff/0PP8bA//LwYAAA';
			});
		}

		// Convert images to WebP on mobile if supported
		supportsWebP().then(supported => {
			if (supported && window.innerWidth <= 768) {
				const images = document.querySelectorAll('img[data-webp]');
				images.forEach(img => {
					const webpSrc = img.dataset.webp;
					if (webpSrc && img.src !== webpSrc) {
						img.src = webpSrc;
					}
				});
			}
		});
	</script>
	<?php
}
add_action('wp_footer', 'sunnysideac_webp_support', 1);

add_action( 'wp_footer', 'sunnysideac_add_careers_form_nonce' );

/**
 * Handle warranty claim form submission via AJAX
 */
function sunnysideac_handle_warranty_claim_form() {
	// Verify nonce for security
	if ( ! wp_verify_nonce( $_POST['nonce'] ?? '', 'warranty_claim_form_nonce' ) ) {
		wp_send_json_error( array( 'message' => 'Security verification failed.' ) );
	}

	// Sanitize and validate form data
	$first_name        = sanitize_text_field( $_POST['claim_first_name'] ?? '' );
	$last_name         = sanitize_text_field( $_POST['claim_last_name'] ?? '' );
	$email             = sanitize_email( $_POST['claim_email'] ?? '' );
	$phone             = sanitize_text_field( $_POST['claim_phone'] ?? '' );
	$address           = sanitize_text_field( $_POST['claim_address'] ?? '' );
	$equipment_type    = sanitize_text_field( $_POST['equipment_type'] ?? '' );
	$equipment_brand   = sanitize_text_field( $_POST['equipment_brand'] ?? '' );
	$install_date      = sanitize_text_field( $_POST['install_date'] ?? '' );
	$warranty_type     = sanitize_text_field( $_POST['warranty_type'] ?? '' );
	$issue_description = sanitize_textarea_field( $_POST['issue_description'] ?? '' );
	$urgent_service    = sanitize_text_field( $_POST['urgent_service'] ?? '' );

	// Validate required fields
	$required_fields = array( $first_name, $last_name, $email, $phone, $address, $equipment_type, $equipment_brand, $install_date, $warranty_type, $issue_description );
	foreach ( $required_fields as $field ) {
		if ( empty( $field ) ) {
			wp_send_json_error( array( 'message' => 'Please fill in all required fields.' ) );
		}
	}

	// Validate email format
	if ( ! is_email( $email ) ) {
		wp_send_json_error( array( 'message' => 'Please enter a valid email address.' ) );
	}

	// Handle file upload
	$document_content = '';
	if ( isset( $_FILES['warranty_documents'] ) && $_FILES['warranty_documents']['error'] === UPLOAD_ERR_OK ) {
		$file = $_FILES['warranty_documents'];

		// Validate file type
		$allowed_types = array( 'application/pdf', 'image/jpeg', 'image/jpg', 'image/png' );
		if ( ! in_array( $file['type'], $allowed_types ) ) {
			wp_send_json_error( array( 'message' => 'Documents must be PDF, JPG, or PNG files.' ) );
		}

		// Validate file size (5MB max)
		if ( $file['size'] > 5 * 1024 * 1024 ) {
			wp_send_json_error( array( 'message' => 'Document file must be smaller than 5MB.' ) );
		}

		// Read file content
		$document_content = file_get_contents( $file['tmp_name'] );
		if ( $document_content === false ) {
			wp_send_json_error( array( 'message' => 'Error reading document file.' ) );
		}
	}

	// Create email content
	$to_email = SUNNYSIDE_EMAIL_ADDRESS;
	$subject  = 'Warranty Claim Request - ' . $first_name . ' ' . $last_name;
	$headers  = array( 'Content-Type: text/html; charset=UTF-8', 'From: ' . $first_name . ' ' . $last_name . ' <' . $email . '>' );

	$priority_text = $urgent_service === 'yes' ? 'URGENT - ' : '';
	$subject       = $priority_text . $subject;

	$email_body = '
		<h2>Warranty Claim Request</h2>
		<p><strong>Priority:</strong> ' . ( $urgent_service === 'yes' ? 'URGENT - Immediate attention required' : 'Normal' ) . '</p>
		<p><strong>Name:</strong> ' . esc_html( $first_name ) . ' ' . esc_html( $last_name ) . '</p>
		<p><strong>Email:</strong> ' . esc_html( $email ) . '</p>
		<p><strong>Phone:</strong> ' . esc_html( $phone ) . '</p>
		<p><strong>Service Address:</strong> ' . esc_html( $address ) . '</p>
		<hr>
		<h4>Equipment Information</h4>
		<p><strong>Equipment Type:</strong> ' . esc_html( $equipment_type ) . '</p>
		<p><strong>Equipment Brand:</strong> ' . esc_html( $equipment_brand ) . '</p>
		<p><strong>Installation Date:</strong> ' . esc_html( $install_date ) . '</p>
		<p><strong>Warranty Type:</strong> ' . esc_html( $warranty_type ) . '</p>
		<hr>
		<h4>Issue Description</h4>
		<p>' . nl2br( esc_html( $issue_description ) ) . '</p>';

	if ( ! empty( $document_content ) ) {
		$uploads     = wp_upload_dir();
		$filename    = sanitize_file_name( 'warranty_docs_' . $first_name . '_' . $last_name . '_' . time() . '.' . pathinfo( $_FILES['warranty_documents']['name'], PATHINFO_EXTENSION ) );
		$upload_file = $uploads['path'] . '/' . $filename;

		// Save the file
		if ( file_put_contents( $upload_file, $document_content ) !== false ) {
			$email_body .= '<p><strong>Documents:</strong> Attached (saved as: ' . esc_html( $filename ) . ')</p>';
		}
	}

	$email_body .= '
		<hr>
		<p><strong>Submitted:</strong> ' . date( 'F j, Y, g:i a' ) . '</p>
		<p><em>This warranty claim was submitted via the Sunnyside AC website.</em></p>';

	// Send email to company
	$company_email_sent = wp_mail( $to_email, $subject, $email_body, $headers );

	// Send confirmation email to customer
	if ( $company_email_sent ) {
		$confirmation_subject = 'We Received Your Warranty Claim - Sunnyside AC';
		$confirmation_body    = '
			<h2>Warranty Claim Received</h2>
			<p>Dear ' . esc_html( $first_name ) . ' ' . esc_html( $last_name ) . ',</p>
			<p>We have successfully received your warranty claim request for your ' . esc_html( $equipment_type ) . '.</p>
			<p>Our warranty team will review your claim and contact you within 24 hours to:</p>
			<ul>
				<li>Schedule a diagnostic visit if needed</li>
				<li>Review your warranty coverage</li>
				<li>Process any necessary parts orders</li>
				<li>Provide next steps and timeline</li>
			</ul>';

		if ( $urgent_service === 'yes' ) {
			$confirmation_body .= '<p><strong>Since you marked this as urgent, we will prioritize your claim and contact you as soon as possible.</strong></p>';
		}

		$confirmation_body .= "
			<p>If you need immediate assistance, please call us at:</p>
			<ul>
				<li>Phone: <a href='tel:" . esc_attr( SUNNYSIDE_TEL_HREF ) . "'>" . esc_html( SUNNYSIDE_PHONE_DISPLAY ) . '</a></li>
			</ul>
			<p>Best regards,<br>The Sunnyside AC Warranty Team</p>';

		wp_mail( $email, $confirmation_subject, $confirmation_body, array( 'Content-Type: text/html; charset=UTF-8' ) );
	}

	if ( $company_email_sent ) {
		wp_send_json_success( array( 'message' => 'Warranty claim submitted successfully!' ) );
	} else {
		wp_send_json_error( array( 'message' => 'Error submitting warranty claim. Please try again.' ) );
	}
}
add_action( 'wp_ajax_sunnysideac_handle_warranty_claim_form', 'sunnysideac_handle_warranty_claim_form' );
add_action( 'wp_ajax_nopriv_sunnysideac_handle_warranty_claim_form', 'sunnysideac_handle_warranty_claim_form' );

/**
 * Add nonce field to warranty claim form
 */
function sunnysideac_add_warranty_claim_form_nonce() {
	?>
	<input type="hidden" name="action" value="sunnysideac_handle_warranty_claim_form">
	<input type="hidden" name="nonce" value="<?php echo wp_create_nonce( 'warranty_claim_form_nonce' ); ?>">
	<?php
}
add_action( 'wp_footer', 'sunnysideac_add_warranty_claim_form_nonce' );

