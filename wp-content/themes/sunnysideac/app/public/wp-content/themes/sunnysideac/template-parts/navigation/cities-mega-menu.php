<?php
/**
 * Cities/Service Areas Mega Menu Template Part
 *
 * Renders the service areas mega menu dropdown.
 *
 * @package SunnysideAC
 *
 * Expected $args:
 * @var array  $priority_cities   Priority cities to display
 * @var string $current_city_name Currently active city name
 */

// Extract args
$priority_cities   = $args['priority_cities'] ?? [];
$current_city_name = $args['current_city_name'] ?? '';

// Safety check
if ( empty( $priority_cities ) ) {
	return;
}

?>
<div class="fixed top-[210px] left-1/2 -translate-x-1/2 z-[9999] w-[900px] max-w-[95vw] rounded-[20px] border-2 border-[#e6d4b8] bg-white shadow-[0_8px_25px_rgba(0,0,0,0.15)] overflow-hidden hidden service-areas-dropdown">
	<!-- Header -->
	<div class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4">
		<div class="flex items-center justify-between">
			<div>
				<div class="text-2xl font-bold text-white " role="heading" aria-level="4">Cities We Serve</div>
				<p class="text-sm text-white/90 mt-1 font-normal ">Proudly Serving South Florida</p>
			</div>
			<div class="text-white/80">
				<svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24">
					<path d="M19 12h-2V9h-2V6h-2V4h-2V2h-2v2H7v2H5v2H3v2H1v2h2v2h2v2h2v2h2v2h2v2h2v-2h2v-2h2v-2h2v-2h2v-2h2V12zm-4 4h-2v2h-2v-2h-2v-2H7v-2h2v-2h2V8h2v2h2v2h2v2h2v2z"/>
				</svg>
			</div>
		</div>
	</div>

	<!-- Content -->
	<div class="p-6">
		<div class="grid grid-cols-4 gap-2 mb-6">
			<?php foreach ( $priority_cities as $city ) : ?>
				<?php
				$city_slug = sanitize_title( $city );
				$city_url  = home_url( sprintf( SUNNYSIDE_CITY_URL_PATTERN, $city_slug ) );
				$is_active = ( $current_city_name === $city );

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
				<a href="<?php echo esc_url( $city_url ); ?>" class="<?php echo esc_attr( $css_classes ); ?>" aria-label="Navigate to <?php echo esc_attr( $city ); ?> service area" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
					<div class="h-4 w-4 flex">
						<svg class="<?php echo esc_attr( $icon_classes ); ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
						</svg>
					</div>
					<span class="<?php echo esc_attr( $text_classes ); ?>">
						<?php echo esc_html( $city ); ?>
					</span>
				</a>
			<?php endforeach; ?>
		</div>

		<!-- View All CTA -->
		<div class="pt-4 border-t-2 border-[#e6d4b8]">
			<a href="<?php echo esc_url( home_url( '/cities' ) ); ?>" class="flex items-center justify-center gap-2 rounded-[20px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-3 text-center font-bold text-white text-base transition-all duration-200 hover:scale-105 hover:shadow-lg focus:scale-105 focus:outline-none ">
				View All Cities
				<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
				</svg>
			</a>
		</div>
	</div>
</div>
