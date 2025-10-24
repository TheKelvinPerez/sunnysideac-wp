<?php
/**
 * Custom Sitemap Provider for Service-City Combinations
 *
 * This class generates sitemap entries for all dynamic /{city}/{service} URLs
 * Example: /miami/ac-repair, /coral-gables/heating-installation, etc.
 *
 * @package SunnysideAC
 */

use RankMath\Sitemap\Providers\Provider;
use RankMath\Sitemap\Router;

/**
 * Service-City Sitemap Provider
 */
class Service_City_Sitemap_Provider implements Provider {

	/**
	 * Check if this provider handles the given type
	 *
	 * @param string $type Sitemap type.
	 * @return bool
	 */
	public function handles_type( $type ) {
		return 'service-city' === $type;
	}

	/**
	 * Get index links for sitemap index
	 *
	 * @param int $max_entries Max entries per sitemap.
	 * @return array
	 */
	public function get_index_links( $max_entries ) {
		// Get counts
		$cities_count   = wp_count_posts( 'city' )->publish;
		$services_count = wp_count_posts( 'service' )->publish;
		$total_urls     = $cities_count * $services_count;

		// Calculate number of pages needed
		$pages_needed = (int) ceil( $total_urls / $max_entries );

		// Get the most recent modification date from cities and services
		$lastmod = $this->get_last_modified_date();

		$index = array();

		for ( $page = 1; $page <= $pages_needed; $page++ ) {
			$index[] = array(
				'loc'     => Router::get_base_url( 'service-city-sitemap' . ( $page > 1 ? $page : '' ) . '.xml' ),
				'lastmod' => $lastmod,
			);
		}

		return $index;
	}

	/**
	 * Get sitemap links for a specific page
	 *
	 * @param string $type         Sitemap type.
	 * @param int    $max_entries  Max entries per page.
	 * @param int    $current_page Current page number.
	 * @return array
	 */
	public function get_sitemap_links( $type, $max_entries, $current_page ) {
		$links = array();

		// Get all published cities
		$cities = get_posts(
			array(
				'post_type'      => 'city',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids', // Only get IDs for performance
			)
		);

		// Get all published services
		$services = get_posts(
			array(
				'post_type'      => 'service',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => 'title',
				'order'          => 'ASC',
				'fields'         => 'ids', // Only get IDs for performance
			)
		);

		// Calculate pagination
		$offset = ( $current_page - 1 ) * $max_entries;
		$count  = 0;

		// Generate all city-service combinations
		foreach ( $cities as $city_id ) {
			foreach ( $services as $service_id ) {
				// Skip to offset
				if ( $count < $offset ) {
					$count++;
					continue;
				}

				// Stop if we've reached max entries for this page
				if ( count( $links ) >= $max_entries ) {
					break 2;
				}

				$city_post    = get_post( $city_id );
				$service_post = get_post( $service_id );

				if ( ! $city_post || ! $service_post ) {
					$count++;
					continue;
				}

				// Build the URL: /{city-slug}/{service-slug}
				$url     = home_url( '/' . $city_post->post_name . '/' . $service_post->post_name . '/' );
				$lastmod = max( $city_post->post_modified_gmt, $service_post->post_modified_gmt );

				$links[] = array(
					'loc' => $url,
					'mod' => mysql2date( 'c', $lastmod, false ),
				);

				$count++;
			}
		}

		return $links;
	}

	/**
	 * Get the most recent modification date from cities and services
	 *
	 * @return string ISO 8601 formatted date
	 */
	private function get_last_modified_date() {
		global $wpdb;

		// Get the most recent modification date from cities
		$city_date = $wpdb->get_var(
			"SELECT post_modified_gmt FROM {$wpdb->posts}
			WHERE post_type = 'city'
			AND post_status = 'publish'
			ORDER BY post_modified_gmt DESC
			LIMIT 1"
		);

		// Get the most recent modification date from services
		$service_date = $wpdb->get_var(
			"SELECT post_modified_gmt FROM {$wpdb->posts}
			WHERE post_type = 'service'
			AND post_status = 'publish'
			ORDER BY post_modified_gmt DESC
			LIMIT 1"
		);

		// Use the most recent of the two
		$lastmod = max( $city_date, $service_date );

		return mysql2date( 'c', $lastmod, false );
	}
}
