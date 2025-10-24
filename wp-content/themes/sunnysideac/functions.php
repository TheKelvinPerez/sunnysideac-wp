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
 * Include navigation functions and walker
 */
require_once get_template_directory() . '/inc/navigation.php';
require_once get_template_directory() . '/inc/main-navigation-helper.php';
require_once get_template_directory() . '/inc/footer-menu-helper.php';

/**
 * Get Vite dev server URL from environment or use defaults
 */
function sunnysideac_get_vite_dev_server_url() {
	// For DDEV, use HMR host/port which is the browser-accessible URL
	// For non-DDEV, fall back to dev server host/port
	$protocol = $_ENV['VITE_HMR_PROTOCOL'] ?? $_ENV['VITE_DEV_SERVER_PROTOCOL'] ?? 'http';
	$host     = $_ENV['VITE_HMR_HOST'] ?? $_ENV['VITE_DEV_SERVER_HOST'] ?? 'localhost';
	$port     = $_ENV['VITE_HMR_PORT'] ?? $_ENV['VITE_DEV_SERVER_PORT'] ?? '3000';

	// Allow filtering via WordPress hooks for more flexibility
	$protocol = apply_filters( 'sunnysideac_vite_protocol', $protocol );
	$host     = apply_filters( 'sunnysideac_vite_host', $host );
	$port     = apply_filters( 'sunnysideac_vite_port', $port );

	return "{$protocol}://{$host}:{$port}";
}

/**
 * Check if Vite dev server is running
 */
function sunnysideac_is_vite_dev_server_running() {
	$vite_dev_server = sunnysideac_get_vite_dev_server_url();

	// Use file_get_contents with stream context for better compatibility
	$context = stream_context_create(
		array(
			'http' => array(
				'timeout'       => 1,
				'ignore_errors' => true,
			),
		)
	);

	$result = @file_get_contents( $vite_dev_server, false, $context );

	// Check if we got a response (even if it's an error page, server is running)
	return $result !== false || ( isset( $http_response_header ) && ! empty( $http_response_header ) );
}

/**
 * Enqueue Vite assets
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
		// Production mode: Load built assets
		$manifest_path = get_template_directory() . '/dist/.vite/manifest.json';

		if ( file_exists( $manifest_path ) ) {
			$manifest = json_decode( file_get_contents( $manifest_path ), true );

			if ( isset( $manifest['src/main.js'] ) ) {
				$main = $manifest['src/main.js'];

				// Enqueue CSS
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
 * Get Inter font URL for preloading
 * Returns the fingerprinted font URL in production, null in development
 */
function sunnysideac_get_inter_font_url() {
	// Don't preload in development mode
	if ( sunnysideac_is_vite_dev_server_running() ) {
		return null;
	}

	// In production, look for the font in the manifest
	$manifest_path = get_template_directory() . '/dist/.vite/manifest.json';

	if ( file_exists( $manifest_path ) ) {
		$manifest = json_decode( file_get_contents( $manifest_path ), true );

		// Look for the font file in the manifest
		// Vite may include it under its source path or as an asset
		$font_key = 'src/assets/fonts/Inter-Variable.woff2';

		if ( isset( $manifest[ $font_key ] ) ) {
			return get_template_directory_uri() . '/dist/' . $manifest[ $font_key ]['file'];
		}

		// Alternative: scan manifest for any Inter font file
		foreach ( $manifest as $key => $entry ) {
			if ( isset( $entry['file'] ) && strpos( $entry['file'], 'Inter-Variable' ) !== false ) {
				return get_template_directory_uri() . '/dist/' . $entry['file'];
			}
		}
	}

	return null;
}

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
 * Add root-level rewrite for /{city}/{service}/ → single service
 * Excludes known base URLs like 'services', 'category', 'tag', 'page', 'cities'
 */
function sunnysideac_add_city_service_root_rewrite() {
	add_rewrite_rule(
		'^(?!services|category|tag|page|cities)([^/]+)/([^/]+)/?$',
		'index.php?post_type=service&name=$matches[2]&city_slug=$matches[1]',
		'top'
	);
}
add_action( 'init', 'sunnysideac_add_city_service_root_rewrite', 16 );


/**
 * Add custom query vars for city routing
 */
function sunnysideac_add_city_query_vars( $query_vars ) {
	$query_vars[] = 'city';
	return $query_vars;
}
add_filter( 'query_vars', 'sunnysideac_add_city_query_vars' );

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
		'logo'          => get_template_directory_uri() . '/assets/images/images/logos/sunny-side-logo.png',
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
	$logo_url         = get_template_directory_uri() . '/assets/images/images/logos/sunny-side-logo.png';

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

/**
 * Register a custom sitemap provider for service-city combinations
 *
 * This generates sitemap entries for all /{city}/{service} URLs (e.g., /miami/ac-repair)
 * The provider automatically adds the sitemaps to the index via get_index_links()
 */
function sunnysideac_register_service_city_sitemap_provider( $providers ) {
	require_once get_template_directory() . '/inc/class-service-city-sitemap-provider.php';
	$providers['service-city'] = new Service_City_Sitemap_Provider();
	return $providers;
}
add_filter( 'rank_math/sitemap/providers', 'sunnysideac_register_service_city_sitemap_provider' );
