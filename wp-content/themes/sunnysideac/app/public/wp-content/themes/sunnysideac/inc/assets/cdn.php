<?php
/**
 * CDN Integration
 *
 * Handles CDN URL replacement for uploads and assets
 */

/**
 * Setup CDN for WordPress uploads and assets
 */
function sunnysideac_setup_cdn() {
	if ( ! empty( $_ENV['CDN_ENABLED'] ) && $_ENV['CDN_ENABLED'] === 'true' && ! empty( $_ENV['CDN_BASE_URL'] ) && $_ENV['APP_ENV'] !== 'development' ) {

		// Replace upload URLs with CDN URLs
		add_filter(
			'wp_get_attachment_url',
			function ( $url ) {
				$upload_dir = wp_upload_dir();
				$base_url   = $upload_dir['baseurl'];
				$cdn_base   = rtrim( $_ENV['CDN_BASE_URL'], '/' ) . '/wp-content/uploads';

				return str_replace( $base_url, $cdn_base, $url );
			},
			99
		);

		// Replace attachment image sources with CDN URLs
		add_filter(
			'wp_get_attachment_image_src',
			function ( $image ) {
				if ( is_array( $image ) && ! empty( $image[0] ) ) {
					$upload_dir = wp_upload_dir();
					$base_url   = $upload_dir['baseurl'];
					$cdn_base   = rtrim( $_ENV['CDN_BASE_URL'], '/' ) . '/wp-content/uploads';

					$image[0] = str_replace( $base_url, $cdn_base, $image[0] );
				}
				return $image;
			},
			99
		);

		// Replace content URLs with CDN URLs
		add_filter(
			'the_content',
			function ( $content ) {
				$upload_dir = wp_upload_dir();
				$base_url   = $upload_dir['baseurl'];
				$cdn_base   = rtrim( $_ENV['CDN_BASE_URL'], '/' ) . '/wp-content/uploads';

				return str_replace( $base_url, $cdn_base, $content );
			},
			99
		);
	}
}
add_action( 'init', 'sunnysideac_setup_cdn' );