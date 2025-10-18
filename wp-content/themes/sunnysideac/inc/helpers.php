<?php
/**
 * Helper Functions
 * Utility functions used throughout the theme
 */

/**
 * Get asset URL helper function
 *
 * @param string $path Path relative to theme directory
 * @return string Full URL to asset
 */
function sunnysideac_asset_url($path) {
    return get_template_directory_uri() . '/' . ltrim($path, '/');
}

/**
 * Parse video URL and return embed details
 *
 * @param string $url Video URL (YouTube or Vimeo)
 * @return array|false Array with 'type', 'id', 'embed_url', 'thumbnail_url' or false if invalid
 */
function sunnysideac_parse_video_url( $url ) {
	if ( empty( $url ) ) {
		return false;
	}

	// YouTube patterns
	if ( preg_match( '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]+)/', $url, $matches ) ) {
		$video_id = $matches[1];
		return [
			'type'          => 'youtube',
			'id'            => $video_id,
			'embed_url'     => 'https://www.youtube.com/embed/' . $video_id,
			'watch_url'     => 'https://www.youtube.com/watch?v=' . $video_id,
			'thumbnail_url' => 'https://img.youtube.com/vi/' . $video_id . '/maxresdefault.jpg',
		];
	}

	// Vimeo patterns
	if ( preg_match( '/vimeo\.com\/(?:video\/)?([0-9]+)/', $url, $matches ) ) {
		$video_id = $matches[1];
		return [
			'type'          => 'vimeo',
			'id'            => $video_id,
			'embed_url'     => 'https://player.vimeo.com/video/' . $video_id,
			'watch_url'     => 'https://vimeo.com/' . $video_id,
			'thumbnail_url' => null, // Vimeo requires API call for thumbnail
		];
	}

	return false;
}

/**
 * Get video embed HTML
 *
 * @param string $url Video URL
 * @param array $args Optional arguments (width, height, class, title, use_facade)
 * @return string HTML embed code or empty string
 */
function sunnysideac_get_video_embed( $url, $args = [] ) {
	$video = sunnysideac_parse_video_url( $url );

	if ( ! $video ) {
		return '';
	}

	$defaults = [
		'width'      => '100%',
		'height'     => '100%',
		'class'      => 'video-embed',
		'title'      => 'Video',
		'use_facade' => false, // Option to use click-to-play facade for better performance
	];

	$args = wp_parse_args( $args, $defaults );

	// Build iframe with performance optimizations
	$iframe = sprintf(
		'<iframe src="%s" width="%s" height="%s" class="%s" title="%s" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen loading="lazy"%s></iframe>',
		esc_url( $video['embed_url'] ),
		esc_attr( $args['width'] ),
		esc_attr( $args['height'] ),
		esc_attr( $args['class'] ),
		esc_attr( $args['title'] ),
		// Add importance="low" for better resource prioritization
		' importance="low"'
	);

	return $iframe;
}

/**
 * Get video thumbnail URL
 *
 * @param string $url Video URL
 * @param string $size Thumbnail size (default, medium, high, maxres for YouTube)
 * @return string|null Thumbnail URL or null if not available
 */
function sunnysideac_get_video_thumbnail( $url, $size = 'maxresdefault' ) {
	$video = sunnysideac_parse_video_url( $url );

	if ( ! $video ) {
		return null;
	}

	if ( $video['type'] === 'youtube' ) {
		// YouTube thumbnail sizes: default (120x90), mqdefault (320x180), hqdefault (480x360), sddefault (640x480), maxresdefault (1280x720)
		return 'https://img.youtube.com/vi/' . $video['id'] . '/' . $size . '.jpg';
	}

	// Vimeo requires API call for thumbnails (return null for now)
	return null;
}

/**
 * Get appropriate icon for service name
 *
 * @param string $service_name The name of the service
 * @return string SVG icon path data
 */
function sunnysideac_get_service_icon($service_name) {
	$icons = [
		'AC Repair' => 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z',
		'AC Installation' => 'M12 6v6m0 0v6m0-6h6m-6 0H6',
		'AC Maintenance' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z',
		'AC Replacement' => 'M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15',
		'Heating Repair' => 'M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z',
		'Heating Installation' => 'M13 10V3L4 14h7v7l9-11h-7z',
		'Heat Pumps' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12',
		'Ductless / Mini Split' => 'M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z',
		'Indoor Air Quality' => 'M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z',
		'Water Heaters' => 'M12 2v2m0 16v2M4.93 4.93l1.41 1.41m11.32 11.32l1.41 1.41M2 12h2m16 0h2M4.93 19.07l1.41-1.41m11.32-11.32l1.41-1.41'
	];

	return $icons[$service_name] ?? 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2';
}