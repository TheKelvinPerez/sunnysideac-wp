<?php
/**
 * Plugin Name: SEObot Media Upload Fix
 * Description: Fixes media upload issues for SEObot API by handling different content types
 * Version: 1.0
 * Author: You
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class SEObot_Media_Fix {

    public function __construct() {
        add_filter( 'wp_handle_upload_prefilter', [ $this, 'handle_seobot_upload' ], 10, 1 );
        add_filter( 'upload_mimes', [ $this, 'allow_additional_mimes' ], 10, 1 );
    }

    /**
     * Handle SEObot uploads with different content types
     */
    public function handle_seobot_upload( $file ) {
        // Check if this is a SEObot request
        if ( isset( $_SERVER['HTTP_USER_AGENT'] ) && strpos( $_SERVER['HTTP_USER_AGENT'], 'SEObot' ) !== false ) {
            // Fix file type detection for SEObot uploads
            if ( isset( $file['type'] ) && $file['type'] === 'application/octet-stream' ) {
                // Try to detect actual file type from extension
                $wp_filetype = wp_check_filetype( $file['name'] );
                if ( ! empty( $wp_filetype['type'] ) ) {
                    $file['type'] = $wp_filetype['type'];
                }
            }
        }
        return $file;
    }

    /**
     * Allow additional MIME types for SEObot
     */
    public function allow_additional_mimes( $mimes ) {
        // Add common image types if not already present
        if ( ! isset( $mimes['jpg'] ) ) {
            $mimes['jpg'] = 'image/jpeg';
        }
        if ( ! isset( $mimes['jpeg'] ) ) {
            $mimes['jpeg'] = 'image/jpeg';
        }
        if ( ! isset( $mimes['png'] ) ) {
            $mimes['png'] = 'image/png';
        }
        if ( ! isset( $mimes['webp'] ) ) {
            $mimes['webp'] = 'image/webp';
        }

        return $mimes;
    }
}

new SEObot_Media_Fix();