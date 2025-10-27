<?php
/**
 * Brands Mega Menu Template Part
 *
 * Renders the brands mega menu dropdown with Daikin products expandable.
 *
 * @package SunnysideAC
 *
 * Expected $args:
 * @var array  $brands              All brands to display
 * @var array  $daikin_products     Daikin products to show under Daikin
 * @var string $current_brand_name  Currently active brand name
 */

// Extract args
$brands             = $args['brands'] ?? [];
$daikin_products    = $args['daikin_products'] ?? [];
$current_brand_name = $args['current_brand_name'] ?? '';

// Safety check
if ( empty( $brands ) ) {
	return;
}

?>
<div class="fixed top-[210px] left-1/2 -translate-x-1/2 z-[9999] w-[900px] max-w-[95vw] rounded-[20px] border-2 border-[#e6d4b8] bg-white shadow-[0_8px_25px_rgba(0,0,0,0.15)] overflow-hidden hidden brands-dropdown">
	<!-- Header -->
	<div class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4">
		<div class="flex items-center justify-between">
			<div>
				<div class="text-2xl font-bold text-white " role="heading" aria-level="4">Brands We Service</div>
				<p class="text-sm text-white/90 mt-1 font-normal ">Premium HVAC Equipment Brands</p>
			</div>
			<div class="text-white/80">
				<svg class="h-10 w-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
				</svg>
			</div>
		</div>
	</div>

	<!-- Content -->
	<div class="p-6">
		<div class="grid grid-cols-3 gap-4 mb-6">
			<?php foreach ( $brands as $brand_key => $brand_name ) : ?>
				<?php
				$brand_slug = sanitize_title( $brand_name );
				$brand_url  = home_url( '/brands/' . $brand_slug );
				$is_active  = ( strtolower( $current_brand_name ) === strtolower( $brand_name ) );

				// Get brand logo path
				$logo_filename = match ( $brand_key ) {
					'daikin'  => 'daikin-logo.png',
					'carrier' => 'Carrier-Logo.png',
					'trane'   => 'Trane-Logo.png',
					'goodman' => 'Goodman-Logo.png',
					'rheem'   => 'Rheem-Logo.png',
					'lennox'  => 'Lennox-Logo.png',
					'bryant'  => 'Bryant-Logo.png',
					default   => null,
				};
				$brand_logo = $logo_filename ? get_template_directory_uri() . '/assets/images/company-logos/' . $logo_filename : '';

				// Build CSS classes for brand item - removed scale-105 to prevent clipping
				$base_classes   = 'flex items-center gap-3 p-3 rounded-[20px] transition-all duration-200 focus:outline-none group';
				$hover_classes  = 'hover:bg-[#ffc549] hover:shadow-md focus:bg-[#ffc549]';
				$active_classes = 'bg-[#ffc549] shadow-md';

				$css_classes = $base_classes . ' ' . $hover_classes;
				if ( $is_active ) {
					$css_classes .= ' ' . $active_classes;
				}

				$text_classes = ' text-base font-medium transition-colors duration-200 ' . ( $is_active ? 'text-[#e5462f]' : 'text-black group-hover:text-[#e5462f]' );
				?>

				<?php if ( $brand_key === 'daikin' && ! empty( $daikin_products ) ) : ?>
					<!-- Daikin with expandable products -->
					<div class="col-span-3">
						<div class="border-2 border-[#e6d4b8] rounded-[20px] overflow-hidden">
							<!-- Daikin Main Item -->
							<a href="<?php echo esc_url( $brand_url ); ?>" class="<?php echo esc_attr( $css_classes ); ?> !rounded-none border-b-2 border-[#e6d4b8]" aria-label="Navigate to <?php echo esc_attr( $brand_name ); ?>" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
								<?php if ( $brand_logo ) : ?>
									<div class="h-8 w-auto flex-shrink-0">
										<img src="<?php echo esc_url( $brand_logo ); ?>" alt="<?php echo esc_attr( $brand_name ); ?> logo" class="h-full w-auto object-contain" loading="lazy" decoding="async" />
									</div>
								<?php endif; ?>
								<span class="<?php echo esc_attr( $text_classes ); ?> flex-1">
									<?php echo esc_html( $brand_name ); ?>
								</span>
								<span class="text-xs text-gray-500 group-hover:text-[#e5462f] transition-colors duration-200">
									(<?php echo count( $daikin_products ); ?> products)
								</span>
							</a>

							<!-- Daikin Products Sub-items -->
							<div class="bg-gray-50 p-4">
								<div class="grid grid-cols-2 gap-2">
									<?php foreach ( $daikin_products as $product ) : ?>
										<?php
										$product_url  = home_url( '/brands/daikin/' . $product['slug'] );
										$product_name = $product['short_name'] ?? $product['name'];

										$product_classes = 'flex items-center gap-2 p-2 rounded-[20px] transition-all duration-200 hover:bg-white hover:shadow-sm focus:outline-none group';
										?>
										<a href="<?php echo esc_url( $product_url ); ?>" class="<?php echo esc_attr( $product_classes ); ?>" aria-label="Navigate to <?php echo esc_attr( $product_name ); ?>">
											<div class="h-4 w-4 flex-shrink-0">
												<svg class="h-4 w-4 text-gray-500 group-hover:text-[#e5462f] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr( $product['icon'] ?? 'M9 5l7 7-7 7' ); ?>" />
												</svg>
											</div>
											<span class="text-sm font-medium text-gray-700 group-hover:text-[#e5462f] transition-colors duration-200">
												<?php echo esc_html( $product_name ); ?>
											</span>
										</a>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					</div>

				<?php else : ?>
					<!-- Regular Brand Item -->
					<a href="<?php echo esc_url( $brand_url ); ?>" class="<?php echo esc_attr( $css_classes ); ?>" aria-label="Navigate to <?php echo esc_attr( $brand_name ); ?>" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
						<?php if ( $brand_logo ) : ?>
							<div class="h-8 w-auto flex-shrink-0">
								<img src="<?php echo esc_url( $brand_logo ); ?>" alt="<?php echo esc_attr( $brand_name ); ?> logo" class="h-full w-auto object-contain" loading="lazy" decoding="async" />
							</div>
						<?php endif; ?>
						<span class="<?php echo esc_attr( $text_classes ); ?>">
							<?php echo esc_html( $brand_name ); ?>
						</span>
					</a>
				<?php endif; ?>

			<?php endforeach; ?>
		</div>

		<!-- View All CTA -->
		<div class="pt-4 border-t-2 border-[#e6d4b8]">
			<a href="<?php echo esc_url( home_url( '/brands' ) ); ?>" class="flex items-center justify-center gap-2 rounded-[20px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-3 text-center font-bold text-white text-base transition-all duration-200 hover:scale-105 hover:shadow-lg focus:scale-105 focus:outline-none ">
				View All Brands
				<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
				</svg>
			</a>
		</div>
	</div>
</div>
