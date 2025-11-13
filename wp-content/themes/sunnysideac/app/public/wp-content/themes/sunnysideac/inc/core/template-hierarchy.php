<?php
/**
 * Template Hierarchy and Routing
 *
 * Handles template selection and routing for custom post types
 */

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