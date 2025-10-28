<?php
/**
 * Plugin Name: SEObot Media Upload Fix
 * Description: Fixes media upload issues for SEObot API by handling different content types
 * Version: 2.0
 * Author: You
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class SEObot_Media_Fix {

    public function __construct() {
        add_filter( 'wp_handle_upload_prefilter', [ $this, 'handle_seobot_upload' ], 10, 1 );
        add_filter( 'upload_mimes', [ $this, 'allow_additional_mimes' ], 10, 1 );
        add_action( 'parse_request', [ $this, 'handle_seobot_raw_upload' ], 10, 1 );
    }

    /**
     * Handle SEObot raw uploads when they send wrong content type
     */
    public function handle_seobot_raw_upload( $wp ) {
        // Check if this is a SEObot media upload request with wrong content type
        if ( isset( $_SERVER['REQUEST_URI'] ) &&
             strpos( $_SERVER['REQUEST_URI'], '/seobot/v1/media' ) !== false &&
             $_SERVER['REQUEST_METHOD'] === 'POST' &&
             isset( $_SERVER['HTTP_USER_AGENT'] ) &&
             strpos( $_SERVER['HTTP_USER_AGENT'], 'SEObot' ) !== false &&
             isset( $_SERVER['HTTP_CONTENT_TYPE'] ) &&
             strpos( $_SERVER['HTTP_CONTENT_TYPE'], 'image/jpeg' ) !== false ) {

            // Get the raw request body
            $raw_data = file_get_contents( 'php://input' );

            if ( ! empty( $raw_data ) ) {
                // Extract filename from Content-Disposition header if present
                $filename = 'seobot-image.jpg';
                if ( isset( $_SERVER['HTTP_CONTENT_DISPOSITION'] ) ) {
                    if ( preg_match( '/filename="?([^"]+)"?/', $_SERVER['HTTP_CONTENT_DISPOSITION'], $matches ) ) {
                        $filename = $matches[1];
                    }
                }

                // Create a temporary file
                $tmp_name = tempnam( sys_get_temp_dir(), 'seobot_upload_' );
                if ( file_put_contents( $tmp_name, $raw_data ) !== false ) {
                    // Override $_FILES array to make WordPress think this is a normal upload
                    $_FILES['file'] = array(
                        'name' => $filename,
                        'type' => 'image/jpeg',
                        'tmp_name' => $tmp_name,
                        'error' => UPLOAD_ERR_OK,
                        'size' => strlen( $raw_data )
                    );

                    // Override $_POST method to prevent WordPress from trying to read php://input again
                    $_SERVER['REQUEST_METHOD'] = 'POST';
                    $_SERVER['CONTENT_TYPE'] = 'multipart/form-data';
                }
            }
        }
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