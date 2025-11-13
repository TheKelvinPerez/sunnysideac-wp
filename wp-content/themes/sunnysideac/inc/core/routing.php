<?php
/**
 * URL Routing and Rewrite Rules
 *
 * Handles custom URL routing for cities, services, and special URLs
 */

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
 * Add rewrite for /areas/{city}/ → city page (legacy support)
 */
function sunnysideac_add_areas_city_rewrite() {
	add_rewrite_rule(
		'^areas/([^/]+)/?$',
		'index.php?city=$matches[1]',
		'top'
	);
}
add_action( 'init', 'sunnysideac_add_areas_city_rewrite', 15 );

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
add_action( 'template_redirect', 'sunnysideac_handle_bare_urls_404s', 1 );

/**
 * Handle emergency service redirects early
 */
function sunnysideac_handle_emergency_redirects() {
	$request_uri = $_SERVER['REQUEST_URI'] ?? '';

	// Check for emergency service URLs and redirect
	if ( strpos( $request_uri, '/services/emergency-hvac' ) !== false ||
	     strpos( $request_uri, '/services/emergency-ac' ) !== false ||
	     strpos( $request_uri, '/services/24-hour-emergency' ) !== false ||
	     strpos( $request_uri, '/services/emergency-service' ) !== false ) {
		wp_redirect( home_url( '/services/ac-repair/' ), 301 );
		exit;
	}

	// Check for plural ductless mini splits URL and redirect to singular
	if ( strpos( $request_uri, '/services/ductless-mini-splits' ) !== false ) {
		wp_redirect( home_url( '/services/ductless-mini-split/' ), 301 );
		exit;
	}

	// Check for /areas/ URLs and redirect to /cities/ for SEO consistency
	if ( preg_match( '/^\/areas\/([^\/]+)\/?$/', $request_uri, $matches ) ) {
		$city_slug = $matches[1];
		wp_redirect( home_url( "/cities/{$city_slug}/" ), 301 );
		exit;
	}

	// Check for city-service plural ductless mini splits URLs and redirect to singular
	if ( preg_match( '/^\/([^\/]+)\/ductless-mini-splits\/?$/', $request_uri, $matches ) ) {
		$city_slug = $matches[1];
		wp_redirect( home_url( "/{$city_slug}/ductless-mini-split/" ), 301 );
		exit;
	}
}
add_action( 'template_redirect', 'sunnysideac_handle_emergency_redirects', 0 );

/**
 * Handle custom sitemap requests - Updated for new sitemap structure
 */
function sunnysideac_handle_custom_sitemaps() {
	$request_uri = $_SERVER['REQUEST_URI'] ?? '';

	// Strip query string for pattern matching
	$request_uri_clean = strtok($request_uri, '?');

	// Determine sitemap type with more specific patterns
	$sitemap_type = null;

	if ( $request_uri_clean === '/sitemap.xml' || $request_uri_clean === '/sitemap.xml/' ) {
		$sitemap_type = 'index';
	} elseif ( $request_uri_clean === '/cities-sitemap.xml' || $request_uri_clean === '/cities-sitemap.xml/' ) {
		$sitemap_type = 'cities';
	} elseif ( $request_uri_clean === '/brands-sitemap.xml' || $request_uri_clean === '/brands-sitemap.xml/' ) {
		$sitemap_type = 'brands';
	} elseif ( $request_uri_clean === '/services-sitemap.xml' || $request_uri_clean === '/services-sitemap.xml/' ) {
		$sitemap_type = 'services';
	} elseif ( $request_uri_clean === '/service-city-sitemap.xml' || $request_uri_clean === '/service-city-sitemap.xml/' ) {
		$sitemap_type = 'service-city';
	} elseif ( $request_uri_clean === '/page-sitemap.xml' || $request_uri_clean === '/page-sitemap.xml/' ) {
		$sitemap_type = 'page';
	} elseif ( $request_uri_clean === '/category-sitemap.xml' || $request_uri_clean === '/category-sitemap.xml/' ) {
		$sitemap_type = 'category';
	} elseif ( $request_uri_clean === '/post-sitemap.xml' || $request_uri_clean === '/post-sitemap.xml/' ) {
		$sitemap_type = 'post';
	} elseif ( $request_uri_clean === '/tag-sitemap.xml' || $request_uri_clean === '/tag-sitemap.xml/' ) {
		$sitemap_type = 'tag';
	}

	if ( $sitemap_type ) {
		// Load the generator
		require_once __DIR__ . '/../custom-sitemap-generator.php';
		$generator = new Sunnyside_Custom_Sitemap_Generator();

		// Set query var and handle
		set_query_var( 'custom_sitemap', $sitemap_type );
		$generator->handle_sitemap_requests();
	}
}
add_action( 'template_redirect', 'sunnysideac_handle_custom_sitemaps', 1 );