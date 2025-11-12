<?php
/**
 * City Card Template Part
 *
 * @param array $args {
 *     @type string       $city_name       City name for display
 *     @type string       $city_slug       City slug for image path and URL generation
 *     @type string       $city_url        URL to city page (optional - will be generated if not provided)
 *     @type string       $card_size       'featured' for larger cards (h-48), 'archive' for smaller cards (h-40)
 *     @type bool         $show_button     Whether to show "View Services" button
 *     @type string       $button_text     Custom button text (default: "View Services")
 *     @type string       $description     Custom description text (default: based on card type)
 *     @type string       $custom_classes  Additional CSS classes
 *     @type string       $custom_image    Custom image path (optional - will use city slug if not provided)
 * }
 */

// Default arguments
$defaults = array(
	'city_name'      => '',
	'city_slug'      => '',
	'city_url'       => '',
	'card_size'      => 'archive', // 'featured' or 'archive'
	'show_button'    => false,
	'button_text'    => 'View Services',
	'description'    => '',
	'custom_classes' => '',
	'custom_image'   => '',
);

$args = wp_parse_args( $args, $defaults );

// Validate required parameters
if ( empty( $args['city_name'] ) || empty( $args['city_slug'] ) ) {
	return;
}

// Generate URL if not provided
if ( empty( $args['city_url'] ) ) {
	// Check if this city has a city post
	$city_post        = get_page_by_path( $args['city_slug'], OBJECT, 'city' );
	$args['city_url'] = $city_post ? get_permalink( $city_post->ID ) : home_url( sprintf( '/cities/%s', $args['city_slug'] ) );
}

// Get city post for featured image
$city_post = ! empty( $args['city_post_id'] ) ? get_post( $args['city_post_id'] ) : get_page_by_path( $args['city_slug'], OBJECT, 'city' );

// Set image path - prioritize database featured image, fallback to file system
if ( $city_post && has_post_thumbnail( $city_post->ID ) ) {
	$image_url  = get_the_post_thumbnail_url( $city_post->ID, 'medium_large' );
	$image_path = $image_url; // Use full URL for database images
} else {
	$image_path = ! empty( $args['custom_image'] ) ? $args['custom_image'] : 'assets/city-images/' . $args['city_slug'] . '.jpg';
}

// Card configuration based on size
$card_config = array(
	'featured' => array(
		'height'         => 'h-48',
		'icon_size'      => 'w-12 h-12',
		'icon_svg_size'  => 'h-6 w-6',
		'text_size'      => 'text-xl',
		'description'    => ! empty( $args['description'] ) ? $args['description'] : 'Expert HVAC services',
		'padding'        => 'p-6',
		'margin_bottoms' => 'mb-3 mb-1 mb-3',
		'button_classes' => 'bg-orange-500 text-white px-4 py-2 rounded-full',
	),
	'archive'  => array(
		'height'         => 'h-40',
		'icon_size'      => 'w-10 h-10',
		'icon_svg_size'  => 'h-5 w-5',
		'text_size'      => 'text-lg',
		'description'    => '', // No description for archive cards
		'padding'        => 'p-4',
		'margin_bottoms' => 'mb-2',
		'button_classes' => '', // No button for archive cards
	),
);

$config = $card_config[ $args['card_size'] ] ?? $card_config['archive'];

// Additional classes
$additional_classes = ! empty( $args['custom_classes'] ) ? ' ' . $args['custom_classes'] : '';
?>

<a href="<?php echo esc_url( $args['city_url'] ); ?>"
	class="group block relative <?php echo esc_attr( $config['height'] ); ?> rounded-2xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-lg<?php echo esc_attr( $additional_classes ); ?>"
	style="background-image: url('<?php echo esc_url( filter_var( $image_path, FILTER_VALIDATE_URL ) ? $image_path : sunnysideac_asset_url( $image_path ) ); ?>'); background-size: cover; background-position: center;">

	<!-- Gradient Overlay -->
	<div class="absolute inset-0 bg-gradient-to-br from-[#fb9939]/90 via-gray-500/50 to-transparent"></div>

	<!-- Content -->
	<div class="relative h-full flex flex-col justify-end <?php echo esc_attr( $config['padding'] ); ?> text-center">
		<!-- Icon Circle -->
		<div class="<?php echo esc_attr( $config['margin_bottoms'] ); ?>">
			<div class="inline-flex items-center justify-center <?php echo esc_attr( $config['icon_size'] ); ?> rounded-full bg-white/90 backdrop-blur-sm">
				<svg class="<?php echo esc_attr( $config['icon_svg_size'] ); ?> text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
				</svg>
			</div>
		</div>

		<!-- City Name -->
		<div class="<?php echo esc_attr( $config['text_size'] ); ?> font-bold text-white <?php echo ! empty( $config['margin_bottoms'] ) ? explode( ' ', $config['margin_bottoms'] )[1] : ''; ?>" role="heading" aria-level="4">
			<?php echo esc_html( $args['city_name'] ); ?>
		</div>

		<?php if ( ! empty( $config['description'] ) ) : ?>
			<p class="text-white/90 text-sm <?php echo ! empty( $config['margin_bottoms'] ) ? explode( ' ', $config['margin_bottoms'] )[2] : ''; ?>">
				<?php echo esc_html( $config['description'] ); ?>
			</p>
		<?php endif; ?>

		<?php if ( $args['show_button'] && ! empty( $config['button_classes'] ) ) : ?>
			<div class="flex justify-center">
				<span class="inline-flex items-center font-medium text-sm <?php echo esc_attr( $config['button_classes'] ); ?>">
					<?php echo esc_html( $args['button_text'] ); ?>
					<svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
					</svg>
				</span>
			</div>
		<?php endif; ?>
	</div>
</a>
