<?php
/**
 * Services Mega Menu Template Part
 *
 * Renders the services mega menu dropdown with categories.
 *
 * @package SunnysideAC
 *
 * Expected $args:
 * @var array  $service_categories Categories of services (from constants)
 * @var string $current_service_name Currently active service name
 */

// Extract args
$service_categories   = $args['service_categories'] ?? [];
$current_service_name = $args['current_service_name'] ?? '';

// Safety check
if ( empty( $service_categories ) ) {
	return;
}

?>
<div class="fixed top-[210px] left-1/2 -translate-x-1/2 z-[9999] w-[900px] max-w-[95vw] rounded-[20px] border-2 border-[#e6d4b8] bg-white shadow-[0_8px_25px_rgba(0,0,0,0.15)] overflow-hidden hidden services-dropdown">
	<!-- Header -->
	<div class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4">
		<div class="text-2xl font-bold text-white " role="heading" aria-level="4">Our Services</div>
		<p class="text-sm text-white/90 mt-1 font-normal ">Professional HVAC Solutions for Your Comfort</p>
	</div>

	<!-- Content -->
	<div class="p-6">
		<div class="grid grid-cols-3 gap-6 mb-6">
			<?php foreach ( $service_categories as $category_key => $services ) : ?>
				<?php $category_label = ucwords( str_replace( '_', ' ', $category_key ) ); ?>
				<div class="space-y-1.5">
					<h4 class="text-xs font-bold uppercase tracking-wide bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent] mb-2">
						<?php echo esc_html( $category_label ); ?>
					</h4>

					<?php foreach ( $services as $service_name ) : ?>
						<?php
						$service_slug = sanitize_title( $service_name );
						$service_url  = home_url( sprintf( SUNNYSIDE_SERVICE_URL_PATTERN, $service_slug ) );
						$icon_path    = sunnysideac_get_service_icon( $service_name );
						$is_active    = ( $current_service_name === $service_name );

						// Build CSS classes
						$base_classes   = 'flex items-center gap-2 p-2 rounded-[20px] transition-all duration-200 focus:outline-none group';
						$hover_classes  = 'hover:bg-[#ffc549] hover:scale-105 hover:shadow-md focus:bg-[#ffc549]';
						$active_classes = 'bg-[#ffc549] shadow-md scale-105';

						$css_classes = $base_classes . ' ' . $hover_classes;
						if ( $is_active ) {
							$css_classes .= ' ' . $active_classes;
						}

						$icon_classes = 'h-4 w-4 transition-colors duration-200 ' . ( $is_active ? 'text-[#e5462f]' : 'text-gray-600 group-hover:text-[#e5462f]' );
						$text_classes = ' text-sm font-medium transition-colors duration-200 ' . ( $is_active ? 'text-[#e5462f]' : 'text-black group-hover:text-[#e5462f]' );
						?>
						<a href="<?php echo esc_url( $service_url ); ?>" class="<?php echo esc_attr( $css_classes ); ?>" aria-label="Navigate to <?php echo esc_attr( $service_name ); ?>" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
							<div class="h-4 w-4 flex">
								<svg class="<?php echo esc_attr( $icon_classes ); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr( $icon_path ); ?>" />
								</svg>
							</div>
							<span class="<?php echo esc_attr( $text_classes ); ?>">
								<?php echo esc_html( $service_name ); ?>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		</div>

		<!-- View All CTA -->
		<div class="pt-4 border-t-2 border-[#e6d4b8]">
			<a href="<?php echo esc_url( home_url( '/services' ) ); ?>" class="flex items-center justify-center gap-2 rounded-[20px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-3 text-center font-bold text-white text-base transition-all duration-200 hover:scale-105 hover:shadow-lg focus:scale-105 focus:outline-none ">
				View All HVAC Services
				<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
				</svg>
			</a>
		</div>
	</div>
</div>
