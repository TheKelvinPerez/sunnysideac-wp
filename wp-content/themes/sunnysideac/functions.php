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
		$result = [];
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
					$handler->addDataTable( $varLabel, [ 'Value' => $displayValue ] );
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
					$handler->addDataTable( $varLabel, [ 'Value' => print_r( $var, true ) ] );
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
		[
			'http' => [
				'timeout'       => 1,
				'ignore_errors' => true,
			],
		]
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
			[],
			null,
			false
		);
		wp_script_add_data( 'sunnysideac-vite-client', 'type', 'module' );

		wp_enqueue_script(
			'sunnysideac-main',
			$vite_server_url . '/src/main.js',
			[ 'sunnysideac-vite-client' ],
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
							[],
							null
						);
					}
				}

				// Enqueue JS
				wp_enqueue_script(
					'sunnysideac-main',
					get_template_directory_uri() . '/dist/' . $main['file'],
					[],
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
 * Theme setup
 */
function sunnysideac_setup() {
	// Add theme support
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'custom-logo' );
	add_theme_support(
		'html5',
		[
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		]
	);

	// Register navigation menus
	register_nav_menus(
		[
			'primary' => __( 'Primary Menu', 'sunnysideac' ),
			'footer'  => __( 'Footer Menu', 'sunnysideac' ),
		]
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
		[
			'labels'       => [
				'name'          => 'Cities',
				'singular_name' => 'City',
				'add_new_item'  => 'Add New City',
				'edit_item'     => 'Edit City',
			],
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => [
				'slug'       => 'city',
				'with_front' => false,
			],
			'supports'     => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'    => 'dashicons-location-alt',
			'show_in_rest' => true,
		]
	);
}

add_action( 'init', 'register_city_cpt' );


function register_service_cpt() {
	register_post_type(
		'service',
		[
			'labels'       => [
				'name'          => 'Services',
				'singular_name' => 'Service',
				'add_new_item'  => 'Add New Service',
				'edit_item'     => 'Edit Service',
			],
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => [
				'slug'       => 'service',
				'with_front' => false,
			],
			'supports'     => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'    => 'dashicons-hammer',
			'show_in_rest' => true,
		]
	);
}

add_action( 'init', 'register_service_cpt' );


function register_brand_cpt() {
	register_post_type(
		'brand',
		[
			'labels'       => [
				'name'          => 'Brands',
				'singular_name' => 'Brand',
				'add_new_item'  => 'Add New Brand',
				'edit_item'     => 'Edit Brand',
			],
			'public'       => true,
			'has_archive'  => true,
			'rewrite'      => [ 'slug' => 'brands' ],
			'supports'     => [ 'title', 'editor', 'thumbnail' ],
			'menu_icon'    => 'dashicons-awards',
			'show_in_rest' => true,
		]
	);
}

add_action( 'init', 'register_brand_cpt' );

function register_service_category_taxonomy() {
	register_taxonomy(
		'service_category',
		[ 'service' ],
		[
			'labels'            => [
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
			],
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => [
				'slug'       => 'service-category',
				'with_front' => false,
			],
			'show_in_rest'      => true,
		]
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
		[
			'key'                   => 'group_service_city_relationship',
			'title'                 => 'City Assignment',
			'fields'                => [
				[
					'key'           => 'field_service_city_id',
					'label'         => 'Cities',
					'name'          => '_service_city_id',
					'type'          => 'post_object',
					'instructions'  => 'Select the cities where this service is offered.',
					'required'      => 0,
					'post_type'     => [
						0 => 'city',
					],
					'taxonomy'      => '',
					'allow_null'    => 1,
					'multiple'      => 1,
					'return_format' => 'id',
					'ui'            => 1,
				],
			],
			'location'              => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					],
				],
			],
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => '',
		]
	);

	/**
	 * City CPT - Local SEO Content Fields
	 */
	acf_add_local_field_group(
		[
			'key'      => 'group_city_seo_content',
			'title'    => 'Local SEO Content',
			'fields'   => [
				[
					'key'          => 'field_city_neighborhoods',
					'label'        => 'Neighborhoods & Areas Served',
					'name'         => 'neighborhoods',
					'type'         => 'textarea',
					'instructions' => 'List specific neighborhoods, zip codes, or areas. This makes content unique per city.',
					'placeholder'  => 'Example: Downtown Miami, Brickell, Coral Way, Little Havana, Coconut Grove',
				],
				[
					'key'          => 'field_city_climate_note',
					'label'        => 'Climate/Weather Note',
					'name'         => 'climate_note',
					'type'         => 'textarea',
					'instructions' => 'Local climate considerations for HVAC services.',
					'placeholder'  => 'Example: Miami\'s humid subtropical climate means AC systems work harder year-round...',
				],
				[
					'key'          => 'field_city_service_area_note',
					'label'        => 'Service Area Details',
					'name'         => 'service_area_note',
					'type'         => 'wysiwyg',
					'instructions' => 'Additional details about serving this city (response time, local permits, etc.)',
					'toolbar'      => 'basic',
					'media_upload' => 0,
				],
				[
					'key'          => 'field_city_video_url',
					'label'        => 'City Video URL',
					'name'         => 'city_video_url',
					'type'         => 'url',
					'instructions' => 'YouTube or Vimeo URL for city-specific video (e.g., "HVAC Services in Miami"). HIGHLY recommended for local SEO!',
					'placeholder'  => 'https://www.youtube.com/watch?v=...',
				],
				[
					'key'          => 'field_city_video_title',
					'label'        => 'Video Title',
					'name'         => 'city_video_title',
					'type'         => 'text',
					'instructions' => 'Title of the video (for schema markup). Example: "HVAC Services in Miami, Florida"',
					'placeholder'  => 'HVAC Services in Miami, Florida',
					'conditional_logic' => [
						[
							[
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							],
						],
					],
				],
				[
					'key'          => 'field_city_video_description',
					'label'        => 'Video Description',
					'name'         => 'city_video_description',
					'type'         => 'textarea',
					'instructions' => 'Brief description of the video content (for schema markup)',
					'placeholder'  => 'Watch our HVAC technicians servicing homes and businesses throughout Miami...',
					'rows'         => 3,
					'conditional_logic' => [
						[
							[
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							],
						],
					],
				],
				[
					'key'          => 'field_city_video_thumbnail',
					'label'        => 'Video Thumbnail (Optional)',
					'name'         => 'city_video_thumbnail',
					'type'         => 'image',
					'instructions' => 'Custom thumbnail image (1280x720). If empty, will use video platform\'s default thumbnail.',
					'return_format' => 'url',
					'preview_size' => 'medium',
					'library'      => 'all',
					'conditional_logic' => [
						[
							[
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							],
						],
					],
				],
				[
					'key'          => 'field_city_video_duration',
					'label'        => 'Video Duration',
					'name'         => 'city_video_duration',
					'type'         => 'text',
					'instructions' => 'Duration in ISO 8601 format (e.g., PT2M30S for 2 minutes 30 seconds). Used for schema markup.',
					'placeholder'  => 'PT2M30S',
					'conditional_logic' => [
						[
							[
								'field'    => 'field_city_video_url',
								'operator' => '!=empty',
							],
						],
					],
				],
			],
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'city',
					],
				],
			],
			'position' => 'normal',
		]
	);

	/**
	 * Service CPT - SEO Content Blocks
	 */
	acf_add_local_field_group(
		[
			'key'      => 'group_service_seo_content',
			'title'    => 'Service Content Blocks',
			'fields'   => [
				[
					'key'          => 'field_service_description',
					'label'        => 'Service Description',
					'name'         => 'service_description',
					'type'         => 'wysiwyg',
					'instructions' => 'Main service description (applies to all cities)',
					'toolbar'      => 'full',
					'media_upload' => 1,
				],
				[
					'key'          => 'field_service_benefits',
					'label'        => 'Key Benefits',
					'name'         => 'service_benefits',
					'type'         => 'repeater',
					'instructions' => 'List of benefits for this service',
					'layout'       => 'table',
					'button_label' => 'Add Benefit',
					'sub_fields'   => [
						[
							'key'   => 'field_benefit_text',
							'label' => 'Benefit',
							'name'  => 'benefit',
							'type'  => 'text',
						],
					],
				],
				[
					'key'          => 'field_service_process',
					'label'        => 'Service Process/Steps',
					'name'         => 'service_process',
					'type'         => 'repeater',
					'instructions' => 'Step-by-step process',
					'layout'       => 'row',
					'button_label' => 'Add Step',
					'sub_fields'   => [
						[
							'key'   => 'field_process_step_title',
							'label' => 'Step Title',
							'name'  => 'title',
							'type'  => 'text',
						],
						[
							'key'   => 'field_process_step_description',
							'label' => 'Description',
							'name'  => 'description',
							'type'  => 'textarea',
							'rows'  => 3,
						],
					],
				],
				[
					'key'          => 'field_service_faqs',
					'label'        => 'FAQs',
					'name'         => 'service_faqs',
					'type'         => 'repeater',
					'instructions' => 'Frequently asked questions about this service',
					'layout'       => 'row',
					'button_label' => 'Add FAQ',
					'sub_fields'   => [
						[
							'key'   => 'field_faq_question',
							'label' => 'Question',
							'name'  => 'question',
							'type'  => 'text',
						],
						[
							'key'   => 'field_faq_answer',
							'label' => 'Answer',
							'name'  => 'answer',
							'type'  => 'textarea',
							'rows'  => 4,
						],
					],
				],
			],
			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'service',
					],
				],
			],
			'position' => 'normal',
		]
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
 * Add root-level rewrite for /{city}/{service}/ → single service
 */
function sunnysideac_add_city_service_root_rewrite() {
	add_rewrite_rule(
		'^([^/]+)/([^/]+)/?$',
		'index.php?post_type=service&name=$matches[2]&city_slug=$matches[1]',
		'top'
	);
}
add_action( 'init', 'sunnysideac_add_city_service_root_rewrite', 15 );

/**
 * Add rewrite for /areas/{city}/ → city page
 */
function sunnysideac_add_areas_city_rewrite() {
	add_rewrite_rule(
		'^areas/([^/]+)/?$',
		'index.php?post_type=city&name=$matches[1]',
		'top'
	);
}
add_action( 'init', 'sunnysideac_add_areas_city_rewrite', 16 );

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
			$new_template = locate_template( [ 'single-service-city.php' ] );
			if ( $new_template ) {
				return $new_template;
			}
		}
		return $template;
	}
);
