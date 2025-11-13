<?php
/**
 * Brand Card Template Part
 *
 * @param array $args {
 *     @type string       $brand_name     Brand name for display
 *     @type string       $brand_slug     Brand slug for image path and URL generation
 *     @type string       $brand_url      URL to brand page (optional - will be generated if not provided)
 *     @type string       $card_size      'featured' for larger cards (h-48), 'archive' for smaller cards (h-40)
 *     @type bool         $show_button    Whether to show "View Products" button
 *     @type string       $button_text    Custom button text (default: "View Products")
 *     @type string       $description    Custom description text (default: based on card type)
 *     @type string       $custom_classes Additional CSS classes
 *     @type string       $custom_image   Custom image path (optional - will use brand slug if not provided)
 *     @type int          $brand_post_id  Brand post ID (optional - for featured image)
 * }
 */

// Default arguments
$defaults = array(
	'brand_name'     => '',
	'brand_slug'     => '',
	'brand_url'      => '',
	'card_size'      => 'archive', // 'featured' or 'archive'
	'show_button'    => false,
	'button_text'    => 'View Products',
	'description'    => '',
	'custom_classes' => '',
	'custom_image'   => '',
	'brand_post_id'  => '',
	'style'          => 'gradient', // 'gradient' for background images with gradient, 'contained' for white background with contained image
);

$args = wp_parse_args( $args, $defaults );

// Validate required parameters
if ( empty( $args['brand_name'] ) || empty( $args['brand_slug'] ) ) {
	return;
}

// Generate URL if not provided
if ( empty( $args['brand_url'] ) ) {
	// Check if this brand has a brand post
	$brand_post        = get_page_by_path( $args['brand_slug'], OBJECT, 'brand' );
	$args['brand_url'] = $brand_post ? get_permalink( $brand_post->ID ) : home_url( sprintf( '/brands/%s', $args['brand_slug'] ) );
}

// Get brand post for featured image
$brand_post = ! empty( $args['brand_post_id'] ) ? get_post( $args['brand_post_id'] ) : get_page_by_path( $args['brand_slug'], OBJECT, 'brand' );

// Set image path - prioritize database featured image, fallback to file system
if ( $brand_post && has_post_thumbnail( $brand_post->ID ) ) {
	$image_url  = get_the_post_thumbnail_url( $brand_post->ID, 'medium_large' );
	$image_path = $image_url; // Use full URL for database images
} else {
	$image_path = ! empty( $args['custom_image'] ) ? $args['custom_image'] : 'assets/brand-images/' . $args['brand_slug'] . '.jpg';
}

// Card configuration based on size
$card_config = array(
	'featured' => array(
		'height'         => 'h-64',
		'icon_size'      => 'w-16 h-16',
		'icon_svg_size'  => 'h-8 w-8',
		'text_size'      => 'text-xl',
		'description'    => ! empty( $args['description'] ) ? $args['description'] : 'Premium HVAC equipment',
		'padding'        => 'p-6',
		'margin_bottoms' => 'mb-4 mb-2 mb-4',
		'button_classes' => 'bg-green-600 text-white px-4 py-2 rounded-full',
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

if ( $args['style'] === 'contained' ) :
	?>
	<!-- Contained Style with Gradient Overlay -->
	<a href="<?php echo esc_url( $args['brand_url'] ); ?>"
		   class="group block relative <?php echo esc_attr( $config['height'] ); ?> rounded-2xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-lg bg-white border border-gray-200<?php echo esc_attr( $additional_classes ); ?>"
		   style="background-image: url('<?php echo esc_url( filter_var( $image_path, FILTER_VALIDATE_URL ) ? $image_path : sunnysideac_asset_url( $image_path ) ); ?>'); background-size: contain; background-position: center; background-repeat: no-repeat;">

		<!-- Gradient Overlay -->
		<div class="absolute inset-0 bg-gradient-to-br from-[#fb9939]/90 via-gray-500/50 to-transparent"></div>

		<!-- Content -->
		<div class="relative h-full flex flex-col justify-end <?php echo esc_attr( $config['padding'] ); ?> text-center">
			<!-- Brand Name -->
			<div class="<?php echo esc_attr( $config['text_size'] ); ?> font-bold text-white mb-3" role="heading" aria-level="4">
				<?php echo esc_html( $args['brand_name'] ); ?>
			</div>

			<?php if ( ! empty( $config['description'] ) ) : ?>
				<p class="text-white/90 text-sm mb-3">
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

<?php else : ?>
	<!-- Gradient Style (default) -->
	<a href="<?php echo esc_url( $args['brand_url'] ); ?>"
		class="group block relative <?php echo esc_attr( $config['height'] ); ?> rounded-2xl overflow-hidden transition-all duration-300 hover:scale-105 hover:shadow-lg<?php echo esc_attr( $additional_classes ); ?>"
		style="background-image: url('<?php echo esc_url( filter_var( $image_path, FILTER_VALIDATE_URL ) ? $image_path : sunnysideac_asset_url( $image_path ) ); ?>'); background-size: cover; background-position: center;">

		<!-- Gradient Overlay -->
		<div class="absolute inset-0 bg-gradient-to-br from-[#fb9939]/90 via-gray-500/50 to-transparent"></div>

		<!-- Content -->
		<div class="relative h-full flex flex-col justify-end <?php echo esc_attr( $config['padding'] ); ?> text-center">
			<!-- Brand Name -->
			<div class="<?php echo esc_attr( $config['text_size'] ); ?> font-bold text-white mb-3" role="heading" aria-level="4">
				<?php echo esc_html( $args['brand_name'] ); ?>
			</div>

			<?php if ( ! empty( $config['description'] ) ) : ?>
				<p class="text-white/90 text-sm mb-3">
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

<?php endif; ?>