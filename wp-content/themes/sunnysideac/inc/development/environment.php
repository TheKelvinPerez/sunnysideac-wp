<?php
/**
 * Environment Setup and Detection
 *
 * Handles environment variables, PostHog initialization, and Whoops error handler
 */

/**
 * Initialize PostHog with environment variables
 */
function sunnysideac_init_posthog() {
	if ( ! empty( $_ENV['POSTHOG_API_KEY'] ) ) {
		PostHog\PostHog::init(
			$_ENV['POSTHOG_API_KEY'],
			array(
				'host'                           => $_ENV['POSTHOG_HOST'] ?? 'https://us.i.posthog.com',
				'debug'                          => $_ENV['APP_ENV'] === 'development',
				'feature_flag_request_timeout_ms' => 3000,
			)
		);
	}
}
add_action( 'after_setup_theme', 'sunnysideac_init_posthog' );

/**
 * Initialize Whoops error handler for development
 */
function sunnysideac_init_whoops() {
	if ( $_ENV['APP_ENV'] === 'development' ) {
		$whoops = new \Whoops\Run();
		$whoops->pushHandler( new \Whoops\Handler\PrettyPageHandler() );
		$whoops->register();
	}
}
add_action( 'after_setup_theme', 'sunnysideac_init_whoops' );