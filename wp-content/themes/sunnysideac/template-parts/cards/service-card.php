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

// Set image path - use custom image if provided, otherwise use theme assets
if ( ! empty( $args['custom_image'] ) ) {
	$image_path = $args['custom_image'];
} else {
	// Use the helper function to get service image from theme assets
	$image_path = sunnysideac_get_service_featured_image_url( $args['service_name'] );
}

// Card configuration based on size
$card_config = array(
	'featured' => array(
		'height'         => 'h-64',
		'icon_size'      => 'w-16 h-16',
		'icon_svg_size'  => 'h-8 w-8',
		'text_size'      => 'text-xl',
		'description'    => ! empty( $args['description'] ) ? $args['description'] : 'Expert HVAC solutions',
		'padding'        => 'p-6',
		'margin_bottoms' => 'mb-4 mb-2 mb-4',
		'button_classes' => 'bg-orange-500 text-white px-4 py-2 rounded-full',
	),
	'archive'  => array(
		'height'         => 'h-52',
		'icon_size'      => 'w-14 h-14',
		'icon_svg_size'  => 'h-7 w-7',
		'text_size'      => 'text-lg',
		'description'    => '', // No description for archive cards
		'padding'        => 'p-5',
		'margin_bottoms' => 'mb-3',
		'button_classes' => '', // No button for archive cards
	),
);

$config = $card_config[ $args['card_size'] ] ?? $card_config['archive'];

// Additional classes
$additional_classes = ! empty( $args['custom_classes'] ) ? ' ' . $args['custom_classes'] : '';
?>

<a href="<?php echo esc_url( $args['service_url'] ); ?>"
	class="group block relative <?php echo esc_attr( $config['height'] ); ?> rounded-2xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-lg<?php echo esc_attr( $additional_classes ); ?>"
	style="background-image: url('<?php echo esc_url( $image_path ); ?>'); background-size: cover; background-position: center;">

	<!-- Gradient Overlay -->
	<div class="absolute inset-0 bg-gradient-to-br from-[#fb9939]/90 via-gray-500/50 to-transparent"></div>

	<!-- Content -->
	<div class="relative h-full flex flex-col justify-end <?php echo esc_attr( $config['padding'] ); ?> text-center">
		<!-- Service Name -->
		<div class="<?php echo esc_attr( $config['text_size'] ); ?> font-bold text-white <?php echo ! empty( $config['margin_bottoms'] ) ? $config['margin_bottoms'] : ''; ?>" role="heading" aria-level="4">
			<?php echo esc_html( $args['service_name'] ); ?>
		</div>

		<?php if ( ! empty( $config['description'] ) ) : ?>
			<p class="text-white/90 text-sm <?php echo ! empty( $config['margin_bottoms'] ) ? 'mb-3' : ''; ?>">
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