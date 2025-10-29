<?php
/**
 * Main Navigation Helper Functions
 * Clean, manageable way to handle main navigation menus
 * Similar pattern to footer-menu-helper.php
 *
 * Refactored to use WordPress template parts for cleaner separation of concerns.
 *
 * @package SunnysideAC
 */

declare(strict_types=1);

/**
 * Get main navigation configuration from JSON file
 *
 * @return array|null Navigation configuration or null on failure
 */
function sunnysideac_get_main_nav_config(): ?array {
	$config_file = get_template_directory() . '/config/main-navigation.json';

	if ( ! file_exists( $config_file ) ) {
		return null;
	}

	$config_content = file_get_contents( $config_file );
	if ( $config_content === false ) {
		error_log( 'Failed to read main navigation config file' );
		return null;
	}

	$config = json_decode( $config_content, true );

	if ( json_last_error() !== JSON_ERROR_NONE ) {
		error_log( 'Main navigation config JSON error: ' . json_last_error_msg() );
		return null;
	}

	return $config;
}

/**
 * Simple helper to determine if current page is related to services
 *
 * @return bool True if on a service-related page
 */
function sunnysideac_is_service_page(): bool {
	return is_singular( 'service' ) || is_page( 'services' );
}

/**
 * Simple helper to determine if current page is related to cities/service areas
 *
 * @return bool True if on a city-related page
 */
function sunnysideac_is_city_page(): bool {
	$request_uri = $_SERVER['REQUEST_URI'] ?? '';

	// Check if URL contains cities pattern
	if ( strpos( $request_uri, '/cities/' ) !== false || trim( $request_uri, '/' ) === 'cities' ) {
		return true;
	}

	// Fallback to WordPress conditionals (for when query is set up)
	return is_singular( 'city' ) || is_post_type_archive( 'city' ) || is_page( 'cities' );
}

/**
 * Simple helper to get current page identifier for active states
 *
 * @return string Page type identifier (home, services, cities, projects, blog, contact, other)
 */
function sunnysideac_get_current_page_type(): string {
	return match ( true ) {
		is_front_page()              => 'home',
		is_page( 'contact' )         => 'contact',
		is_page( 'projects' )        => 'projects',
		sunnysideac_is_service_page() => 'services',
		sunnysideac_is_city_page()   => 'cities',
		is_home() || is_singular( 'post' ) => 'blog',
		default                      => 'other',
	};
}

/**
 * Process URLs in navigation configuration
 *
 * @param array $links Navigation links array
 * @return array Processed links with full URLs
 */
function sunnysideac_process_nav_links( array $links ): array {
	return array_map(
		static fn( array $link ): array => $link + [ 'href' => home_url( $link['href'] ) ],
		$links
	);
}

/**
 * Check if a menu item should be active
 *
 * @param string $item_title Menu item title to check
 * @return bool True if item should be active
 */
function sunnysideac_is_menu_item_active( string $item_title ): bool {
	static $active_map = [
		'home'     => 'Home',
		'services' => 'Services',
		'cities'   => 'Cities',
		'projects' => 'Projects',
		'blog'     => 'Blog',
		'contact'  => 'Contact Us',
	];

	$current_page = sunnysideac_get_current_page_type();

	return ( $active_map[ $current_page ] ?? '' ) === $item_title;
}

/**
 * Render desktop navigation from JSON configuration
 * Uses template parts for cleaner separation of concerns
 *
 * @return void
 */
function sunnysideac_render_desktop_nav_from_config(): void {
	$config = sunnysideac_get_main_nav_config();

	if ( ! $config || ! isset( $config['desktop_nav'] ) ) {
		sunnysideac_fallback_menu();
		return;
	}

	$nav_items    = sunnysideac_process_nav_links( $config['desktop_nav'] );
	$chevron_icon = get_template_directory_uri() . '/assets/images/logos/navigation-chevron-down.svg';

	echo '<ul role="menubar" class="flex items-center gap-2 overflow-visible">';

	foreach ( $nav_items as $item ) {
		get_template_part(
			'template-parts/navigation/desktop-item',
			null,
			[
				'item'         => $item,
				'chevron_icon' => $chevron_icon,
			]
		);
	}

	echo '</ul>';
}

/**
 * Render Services mega menu dropdown
 * Uses template part for cleaner separation of concerns
 *
 * @return void
 */
function sunnysideac_render_services_mega_menu(): void {
	if ( ! defined( 'SUNNYSIDE_SERVICES_BY_CATEGORY' ) ) {
		return;
	}

	get_template_part(
		'template-parts/navigation/services-mega-menu',
		null,
		[
			'service_categories'   => SUNNYSIDE_SERVICES_BY_CATEGORY,
			'current_service_name' => is_singular( 'service' ) ? get_the_title() : '',
		]
	);
}

/**
 * Render Service Areas / Cities mega menu dropdown
 * Uses template part for cleaner separation of concerns
 *
 * @return void
 */
function sunnysideac_render_service_areas_mega_menu(): void {
	if ( ! defined( 'SUNNYSIDE_PRIORITY_CITIES' ) ) {
		return;
	}

	// Determine current city name
	$current_city_name = '';
	if ( is_singular( 'city' ) ) {
		$current_city_name = get_the_title();
	}

	get_template_part(
		'template-parts/navigation/cities-mega-menu',
		null,
		[
			'priority_cities'   => SUNNYSIDE_PRIORITY_CITIES,
			'current_city_name' => $current_city_name,
		]
	);
}

/**
 * Render Brands mega menu dropdown
 * Uses template part for cleaner separation of concerns
 *
 * @return void
 */
function sunnysideac_render_brands_mega_menu(): void {
	if ( ! defined( 'SUNNYSIDE_BRANDS' ) || ! defined( 'SUNNYSIDE_DAIKIN_PRODUCTS' ) ) {
		return;
	}

	// Determine current brand name
	$current_brand_name = '';
	if ( is_singular( 'brand' ) ) {
		$current_brand_name = strtolower( get_the_title() );
	}

	get_template_part(
		'template-parts/navigation/brands-mega-menu',
		null,
		[
			'brands'             => SUNNYSIDE_BRANDS,
			'daikin_products'    => SUNNYSIDE_DAIKIN_PRODUCTS,
			'current_brand_name' => $current_brand_name,
		]
	);
}

/**
 * Render mobile navigation from JSON configuration
 * Uses template parts for cleaner separation of concerns
 *
 * @return void
 */
function sunnysideac_render_mobile_nav_from_config(): void {
	$config = sunnysideac_get_main_nav_config();

	if ( ! $config || ! isset( $config['mobile_nav'] ) ) {
		sunnysideac_mobile_nav_menu_fallback();
		return;
	}

	// Determine current service and city names
	$current_service_name = is_singular( 'service' ) ? get_the_title() : '';
	$current_city_name    = is_singular( 'city' ) ? get_the_title() : '';

	// Services Section
	if ( defined( 'SUNNYSIDE_SERVICES_BY_CATEGORY' ) ) {
		get_template_part(
			'template-parts/navigation/mobile-services',
			null,
			[
				'service_categories'   => SUNNYSIDE_SERVICES_BY_CATEGORY,
				'current_service_name' => $current_service_name,
			]
		);
	}

	// Areas Section
	if ( defined( 'SUNNYSIDE_PRIORITY_CITIES' ) ) {
		get_template_part(
			'template-parts/navigation/mobile-areas',
			null,
			[
				'priority_cities'   => SUNNYSIDE_PRIORITY_CITIES,
				'current_city_name' => $current_city_name,
			]
		);
	}

	// Brands Section
	if ( defined( 'SUNNYSIDE_BRANDS' ) ) {
		get_template_part(
			'template-parts/navigation/mobile-brands',
			null,
			[
				'brands'             => SUNNYSIDE_BRANDS,
				'daikin_products'    => defined( 'SUNNYSIDE_DAIKIN_PRODUCTS' ) ? SUNNYSIDE_DAIKIN_PRODUCTS : [],
				'current_brand_name' => is_singular( 'brand' ) ? get_the_title() : '',
			]
		);
	}

	// Other Navigation Links from JSON
	if ( isset( $config['mobile_nav']['main_links'] ) ) {
		$main_links = array_map(
			static fn( array $link ): array => [
				'title' => $link['title'],
				'href'  => home_url( $link['href'] ),
			],
			$config['mobile_nav']['main_links']
		);

		get_template_part(
			'template-parts/navigation/mobile-links',
			null,
			[ 'main_links' => $main_links ]
		);
	}
}

/**
 * Fallback for mobile navigation
 *
 * @return void
 */
function sunnysideac_mobile_nav_menu_fallback(): void {
	// Same as old sunnysideac_mobile_nav_menu function
	echo '<div class="mb-6">';
	echo '<div class="mb-3 border-b border-gray-200 pb-2 text-lg font-medium text-gray-800" role="heading" aria-level="4">Navigation</div>';
	echo '<div class="space-y-1">';

	$nav_items = array(
		'Home'       => '/',
		'Services'   => '/services',
		'Projects'   => '/projects',
		'Blog'       => '/blog',
		'About'      => '/about',
		'Contact Us' => '/contact',
	);

	foreach ( $nav_items as $title => $url ) {
		echo '<button class="w-full border-b border-gray-200 py-2 text-left text-gray-700 hover:text-[#fb9939] mobile-nav-link" data-href="' . esc_url( home_url( $url ) ) . '">' . esc_html( $title ) . '</button>';
	}

	echo '</div></div>';
}

