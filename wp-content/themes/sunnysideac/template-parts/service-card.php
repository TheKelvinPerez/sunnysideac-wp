<?php
/**
 * Service Card Template Part
 *
 * @param array $args {
 *     @type string       $service_name    Service name for display
 *     @type string       $service_slug    Service slug for image path and URL generation
 *     @type string       $service_url     URL to service page (optional - will be generated if not provided)
 *     @type string       $card_size       'featured' for larger cards (h-48), 'archive' for smaller cards (h-40)
 *     @type bool         $show_button     Whether to show "Learn More" button
 *     @type string       $button_text     Custom button text (default: "Learn More")
 *     @type string       $description     Custom description text (default: based on card type)
 *     @type string       $custom_classes  Additional CSS classes
 *     @type string       $custom_image    Custom image path (optional - will use service slug if not provided)
 *     @type int          $service_post_id Service post ID (optional - for featured image)
 * }
 */

// Default arguments
$defaults = array(
	'service_name'    => '',
	'service_slug'    => '',
	'service_url'     => '',
	'card_size'       => 'archive', // 'featured' or 'archive'
	'show_button'     => false,
	'button_text'     => 'Learn More',
	'description'     => '',
	'custom_classes'  => '',
	'custom_image'    => '',
	'service_post_id' => '',
);

$args = wp_parse_args( $args, $defaults );

// Validate required parameters
if ( empty( $args['service_name'] ) || empty( $args['service_slug'] ) ) {
	return;
}

// Generate URL if not provided
if ( empty( $args['service_url'] ) ) {
	// Check if this service has a service post
	$service_post        = get_page_by_path( $args['service_slug'], OBJECT, 'service' );
	$args['service_url'] = $service_post ? get_permalink( $service_post->ID ) : home_url( sprintf( '/services/%s', $args['service_slug'] ) );
}

// Get service post for featured image
$service_post = ! empty( $args['service_post_id'] ) ? get_post( $args['service_post_id'] ) : get_page_by_path( $args['service_slug'], OBJECT, 'service' );

// Set image path - prioritize database featured image, fallback to file system
if ( $service_post && has_post_thumbnail( $service_post->ID ) ) {
	$image_url  = get_the_post_thumbnail_url( $service_post->ID, 'medium_large' );
	$image_path = $image_url; // Use full URL for database images
} else {
	$image_path = ! empty( $args['custom_image'] ) ? $args['custom_image'] : 'assets/services-images/' . $args['service_slug'] . '.jpg';
}

// Card configuration based on size
$card_config = array(
	'featured' => array(
		'height'         => 'h-48',
		'icon_size'      => 'w-12 h-12',
		'icon_svg_size'  => 'h-6 w-6',
		'text_size'      => 'text-xl',
		'description'    => ! empty( $args['description'] ) ? $args['description'] : 'Expert HVAC solutions',
		'padding'        => 'p-6',
		'margin_bottoms' => 'mb-3 mb-1 mb-3',
		'button_classes' => 'bg-blue-500 text-white px-4 py-2 rounded-full',
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

// Get service icon
$service_icon = sunnysideac_get_service_icon( $args['service_name'] );
?>

<a href="<?php echo esc_url( $args['service_url'] ); ?>"
	class="group block relative <?php echo esc_attr( $config['height'] ); ?> rounded-2xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-lg<?php echo esc_attr( $additional_classes ); ?>"
	style="background-image: url('<?php echo esc_url( filter_var( $image_path, FILTER_VALIDATE_URL ) ? $image_path : sunnysideac_asset_url( $image_path ) ); ?>'); background-size: cover; background-position: center;">

	<!-- Gradient Overlay -->
	<div class="absolute inset-0 bg-gradient-to-br from-blue-600/90 via-blue-700/50 to-transparent"></div>

	<!-- Content -->
	<div class="relative h-full flex flex-col justify-end <?php echo esc_attr( $config['padding'] ); ?> text-center">
		<!-- Icon Circle -->
		<div class="<?php echo esc_attr( $config['margin_bottoms'] ); ?>">
			<div class="inline-flex items-center justify-center <?php echo esc_attr( $config['icon_size'] ); ?> rounded-full bg-white/90 backdrop-blur-sm">
				<?php if ( $service_icon ) : ?>
					<svg class="<?php echo esc_attr( $config['icon_svg_size'] ); ?> text-blue-500" fill="currentColor" viewBox="0 0 24 24">
						<path d="<?php echo esc_attr( $service_icon ); ?>"/>
					</svg>
				<?php else : ?>
					<!-- Default wrench icon for services -->
					<svg class="<?php echo esc_attr( $config['icon_svg_size'] ); ?> text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
					</svg>
				<?php endif; ?>
			</div>
		</div>

		<!-- Service Name -->
		<div class="<?php echo esc_attr( $config['text_size'] ); ?> font-bold text-white <?php echo ! empty( $config['margin_bottoms'] ) ? explode( ' ', $config['margin_bottoms'] )[1] : ''; ?>" role="heading" aria-level="4">
			<?php echo esc_html( $args['service_name'] ); ?>
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