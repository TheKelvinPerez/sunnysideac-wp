<?php
/**
 * CPT Template Loader
 *
 * Loads Custom Post Type templates from templates/cpt/ directory
 * while maintaining WordPress template hierarchy.
 *
 * @package SunnysideAC
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Load CPT templates from organized directory
 *
 * @param string $template Original template path.
 * @return string Modified template path
 */
function sunnyside_load_cpt_templates( $template ) {
	global $wp_query;

	// Handle single CPT templates.
	if ( $wp_query->is_single ) {
		$post_type = get_post_type();
		if ( $post_type ) {
			$cpt_template = get_theme_file_path( "templates/cpt/single-{$post_type}.php" );
			if ( file_exists( $cpt_template ) ) {
				return $cpt_template;
			}
		}
	}

	// Handle CPT archive templates.
	// Check multiple conditions for CPT archives
	$post_type = get_query_var( 'post_type' );

	// Handle when post_type is an array
	if ( is_array( $post_type ) ) {
		$post_type = reset( $post_type );
	}

	// Check if this is a CPT archive
	$is_cpt_archive = (
		$wp_query->is_post_type_archive ||           // Standard CPT archive
		( $wp_query->is_archive && $post_type ) ||   // Archive with post_type set
		get_query_var( 'city' ) ||                   // Special case for cities
		get_query_var( 'service' ) ||                // Special case for services
		get_query_var( 'brand' ) ||                  // Special case for brands
		get_query_var( 'review' )                    // Special case for reviews
	);

	if ( $is_cpt_archive ) {
		// Determine the actual post type
		if ( ! $post_type ) {
			if ( get_query_var( 'city' ) ) {
				$post_type = 'city';
			} elseif ( get_query_var( 'service' ) ) {
				$post_type = 'service';
			} elseif ( get_query_var( 'brand' ) ) {
				$post_type = 'brand';
			} elseif ( get_query_var( 'review' ) ) {
				$post_type = 'review';
			}
		}

		// Handle custom taxonomy archives
		if ( ! $post_type && is_tax() ) {
			$taxonomy = get_query_var( 'taxonomy' );
			$tax_obj = get_taxonomy( $taxonomy );
			if ( $tax_obj && ! empty( $tax_obj->object_type ) ) {
				$post_type = reset( $tax_obj->object_type );
			}
		}

		if ( $post_type && in_array( $post_type, array( 'city', 'service', 'brand', 'review' ) ) ) {
			$cpt_template = get_theme_file_path( "templates/cpt/archive-{$post_type}.php" );
			if ( file_exists( $cpt_template ) ) {
				return $cpt_template;
			}
		}
	}

	return $template;
}

/**
 * Specific filter for CPT archives
 *
 * @param string $template Original template path
 * @return string Modified template path
 */
function sunnyside_cpt_archive_template( $template ) {
	if ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );

		// Handle when post_type is an array
		if ( is_array( $post_type ) ) {
			$post_type = reset( $post_type );
		}

		if ( $post_type && in_array( $post_type, array( 'city', 'service', 'brand', 'review' ) ) ) {
			$cpt_template = get_theme_file_path( "templates/cpt/archive-{$post_type}.php" );
			if ( file_exists( $cpt_template ) ) {
				do_action( 'qm/info', "Sunnyside CPT: Loading archive template for {$post_type}", [
					'template' => $cpt_template,
					'original_template' => $template,
					'post_type' => $post_type,
				] );
				return $cpt_template;
			} else {
				do_action( 'qm/warning', "Sunnyside CPT: Archive template file not found for {$post_type}", [
					'expected_template' => "templates/cpt/archive-{$post_type}.php",
					'post_type' => $post_type,
				] );
			}
		}
	}

	return $template;
}

/**
 * Specific filter for CPT single templates
 *
 * @param string $template Original template path
 * @return string Modified template path
 */
function sunnyside_cpt_single_template( $template ) {
	if ( is_singular() ) {
		$post_type = get_post_type();

		if ( $post_type && in_array( $post_type, array( 'city', 'service', 'brand', 'review' ) ) ) {
			$cpt_template = get_theme_file_path( "templates/cpt/single-{$post_type}.php" );
			if ( file_exists( $cpt_template ) ) {
				do_action( 'qm/info', "Sunnyside CPT: Loading single template for {$post_type}", [
					'template' => $cpt_template,
					'original_template' => $template,
					'post_type' => $post_type,
				] );
				return $cpt_template;
			} else {
				do_action( 'qm/warning', "Sunnyside CPT: Single template file not found for {$post_type}", [
					'expected_template' => "templates/cpt/single-{$post_type}.php",
					'post_type' => $post_type,
				] );
			}
		}
	}

	return $template;
}

// Add the correct filters with proper priority
add_filter( 'archive_template', 'sunnyside_cpt_archive_template', 10 );
add_filter( 'single_template', 'sunnyside_cpt_single_template', 10 );

/**
 * Debug CPT template loading with Query Monitor
 */
function sunnyside_debug_cpt_templates() {
	$post_type = get_post_type();
	$query_post_type = get_query_var( 'post_type' );
	$is_archive = is_archive() ? 'true' : 'false';
	$is_single = is_single() ? 'true' : 'false';
	$is_cpt_archive = is_post_type_archive() ? 'true' : 'false';
	$is_singular = is_singular() ? 'true' : 'false';

	do_action( 'qm/debug', "Sunnyside CPT Template Loading Debug", [
		'post_type' => $post_type,
		'query_post_type' => $query_post_type,
		'is_archive' => $is_archive,
		'is_single' => $is_single,
		'is_cpt_archive' => $is_cpt_archive,
		'is_singular' => $is_singular,
		'request_uri' => $_SERVER['REQUEST_URI'] ?? '',
		'template_filter_running' => 'CPT Template Loader Active',
	] );
}
add_action( 'wp_head', 'sunnyside_debug_cpt_templates', 1 );

