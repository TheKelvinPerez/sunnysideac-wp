<?php
/**
 * Plugin Name: API Content Length Fix
 * Description: Fixes 411 Length Required errors for API uploads
 * Version: 1.0
 * Author: You
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class API_Content_Length_Fix {

    public function __construct() {
        // Handle content-length issues for API requests
        add_action( 'init', [ $this, 'fix_api_content_length' ] );
    }

    /**
     * Fix content-length header issues for API requests
     */
    public function fix_api_content_length() {
        // Check if this is an API request with potential content-length issues
        if ( ( strpos( $_SERVER['REQUEST_URI'], '/seobot/v1/' ) !== false ||
               strpos( $_SERVER['REQUEST_URI'], '/wp-json/' ) !== false ) &&
             $_SERVER['REQUEST_METHOD'] === 'POST' &&
             ! isset( $_SERVER['HTTP_CONTENT_LENGTH'] ) &&
             isset( $_SERVER['HTTP_CONTENT_TYPE'] ) &&
             strpos( $_SERVER['HTTP_CONTENT_TYPE'], 'multipart/form-data' ) !== false ) {

            // Set content-length from the actual input
            $input = file_get_contents( 'php://input' );
            $_SERVER['HTTP_CONTENT_LENGTH'] = strlen( $input );

            // Put the input back for WordPress to process
            file_put_contents( 'php://input', $input );
        }
    }
}

new API_Content_Length_Fix();