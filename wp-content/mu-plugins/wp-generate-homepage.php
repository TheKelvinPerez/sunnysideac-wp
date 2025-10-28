<?php
/**
 * Plugin Name: Static Front Page Generator (minimal)
 * Description: On post/page/menu/theme updates, fetches the home URL as an anonymous request and writes /index.html in the site root.
 * Version: 0.1
 * Author: You
 */

if ( ! defined( 'ABSPATH' ) ) exit;

class Static_Front_Generator {

    private $output_file;

    public function __construct() {
        $this->output_file = ABSPATH . 'index.html'; // adjust if you want a different name
        add_action( 'save_post', [ $this, 'regenerate_if_needed' ], 20, 3 );
        add_action( 'wp_update_nav_menu', [ $this, 'regenerate_now' ] ); // Regenerate homepage if menu changes
        add_action( 'switch_theme', [ $this, 'regenerate_now' ] );
        add_action( 'customize_save_after', [ $this, 'regenerate_now' ] );
        add_action( 'update_option', [ $this, 'maybe_regenerate_on_option' ], 10, 3 );
    }

    public function maybe_regenerate_on_option( $option_name, $old_value, $value ) {
        // regenerate on front page change or homepage-related options
        $interesting = [ 'show_on_front', 'page_on_front', 'page_for_posts' ];
        if ( in_array( $option_name, $interesting, true ) ) {
            $this->regenerate_now();
        }
    }

    public function regenerate_if_needed( $post_ID = 0, $post = null, $update = false ) {
        // if this is an autosave, revision, or non-public, skip
        if ( wp_is_post_autosave( $post_ID ) || wp_is_post_revision( $post_ID ) ) return;
        if ( $post && $post->post_status !== 'publish' ) return;

        // Only regenerate if the changed post is the front page
        $front_id = get_option( 'page_on_front' );
        if ( $front_id && $post_ID && (int)$post_ID !== (int)$front_id ) {
            // Not the front page - don't regenerate static homepage
            return;
        }

        $this->regenerate_now();
    }

    public function regenerate_now() {
        // only generate if ABSPATH is writeable
        if ( ! is_writable( ABSPATH ) ) {
            error_log( 'Static_Front_Generator: ABSPATH not writable; cannot write index.html' );
            return;
        }

        // fetch the home page as an anonymous request (so we capture the public HTML)
        $home = home_url( '/' );
        $args = [
            'timeout' => 15,
            'blocking' => true,
            // Force no cookies / no auth
            'headers' => [
                'User-Agent' => 'StaticFrontGenerator/1.0',
            ],
        ];

        $resp = wp_remote_get( $home, $args );

        if ( is_wp_error( $resp ) ) {
            error_log( 'Static_Front_Generator: wp_remote_get failed: ' . $resp->get_error_message() );
            return;
        }

        $code = wp_remote_retrieve_response_code( $resp );
        $body = wp_remote_retrieve_body( $resp );

        if ( $code !== 200 || empty( $body ) ) {
            error_log( 'Static_Front_Generator: unexpected response code ' . $code );
            return;
        }

        // Optionally, strip admin bars or set canonical to root etc. (skip here)

        $tmpfile = $this->output_file . '.tmp';
        if ( file_put_contents( $tmpfile, $body ) === false ) {
            error_log( 'Static_Front_Generator: failed writing tmp file' );
            @unlink( $tmpfile );
            return;
        }
        chmod( $tmpfile, 0644 );
        rename( $tmpfile, $this->output_file );
        // Note: atomic rename is used to avoid partially written files being served.

        error_log( 'Static_Front_Generator: regenerated ' . $this->output_file );
    }
}

new Static_Front_Generator();