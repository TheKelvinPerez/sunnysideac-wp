<?php
/**
 * Plugin Name: Suppress WordPress 6.8 Deprecated Warnings
 * Description: Suppresses deprecated function warnings in WordPress admin for better editor experience
 * Version: 1.0
 * Author: You
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Suppress_Deprecated_Warnings {

    public function __construct() {
        // Only suppress in admin area for better debugging
        if ( is_admin() ) {
            // Suppress deprecated warnings from WordPress core
            add_action( 'init', [ $this, 'suppress_deprecated_notices' ] );
        }
    }

    public function suppress_deprecated_notices() {
        // Suppress specific WordPress 6.8 deprecation warnings
        // These are from WordPress core updating Gutenberg APIs
        if ( defined( 'WP_DEBUG' ) && WP_DEBUG && defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG_DISPLAY ) {
            // Temporarily disable debug display for deprecated warnings only
            error_reporting( E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED );
        }
    }
}

// Only activate if we're in WordPress admin
if ( is_admin() ) {
    new Suppress_Deprecated_Warnings();
}