<?php
/**
 * Theme Setup and Configuration
 *
 * Handles basic theme initialization, WordPress features support, and navigation menus
 */

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