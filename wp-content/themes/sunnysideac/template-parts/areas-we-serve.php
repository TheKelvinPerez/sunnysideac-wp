<?php
/**
 * Areas We Serve Section Component
 * Self-contained component with service areas and map display
 */

// Component data (like props in React)
$service_areas = SUNNYSIDE_SERVICE_AREAS;

$images = array(
	'areas_we_serve_icon'        => sunnysideac_asset_url( 'assets/images/home-page/areas-we-serve/areas-we-serve-icon.svg' ),
	'map_background_placeholder' => sunnysideac_asset_url( 'assets/optimized/map-background-place-holder.webp' ),
);
?>

<section
	class="w-full rounded-2xl bg-white px-4 py-12 sm:px-6 lg:px-8"
	aria-labelledby="areas-we-serve-heading"
>
	<div class="mx-auto max-w-7xl">
		<!-- Header Section -->
		<header class="mb-8 text-center">
			<?php
			get_template_part(
				'template-parts/title',
				null,
				array(
					'icon'  => $images['areas_we_serve_icon'],
					'title' => 'Areas We Serve',
					'id'    => 'areas-we-serve',
				)
			);
			?>
			<?php
			get_template_part(
				'template-parts/subheading',
				null,
				array(
					'text' => 'Cooling South Florida, One Home at a Time',
				)
			);
			?>
		</header>

		<!-- Mobile Layout - Service Areas First -->
		<div class="block lg:hidden">
			<!-- Service Areas Card - Mobile Top -->
			<div class="mb-6">
				<div class="rounded-xl bg-gradient-to-r from-[#FDC85F] to-[#E64B30] p-4 shadow-lg">
					<div class="mb-4 text-center">
						<h3 class="mb-2 text-lg font-extrabold text-white">
							Proudly Serving
						</h3>
						<div class="mx-auto h-0.5 w-20 bg-white opacity-75"></div>
					</div>

					<!-- Service Areas Grid - 2x10 on mobile -->
					<div class="grid grid-cols-2 gap-2">
						<?php foreach ( $service_areas as $area ) : ?>
							<?php
							$city_slug = sanitize_title( $area );
							$city_url  = home_url( "/cities/{$city_slug}/" );
							?>
							<a href="<?php echo esc_url( $city_url ); ?>" class="rounded-lg bg-white px-2 py-1 text-center shadow-sm transition-shadow duration-200 hover:shadow-md block">
								<span class="text-xs font-medium text-[#414141]">
									<?php echo esc_html( $area ); ?>
								</span>
							</a>
						<?php endforeach; ?>
					</div>
				</div>
			</div>

			<!-- Map Background - Mobile Bottom -->
			<div class="flex justify-end">
				<div class="h-[500px] w-[325px] overflow-hidden rounded-lg shadow-lg">
					<?php echo sunnysideac_responsive_image(
						'assets/images/home-page/areas-we-serve/map-background-place-holder.png',
						array(
							'alt' => 'Service area map covering South Florida',
							'class' => 'h-full w-auto object-cover object-right',
							'loading' => 'lazy'
						)
					); ?>
				</div>
			</div>
		</div>

		<!-- Desktop Layout - Map with Floating Overlay -->
		<div class="hidden lg:block">
			<div class="relative w-full">
				<div class="relative mx-auto h-[500px] w-full max-w-6xl">
					<!-- Map Background -->
					<?php echo sunnysideac_responsive_image(
						'assets/images/home-page/areas-we-serve/map-background-place-holder.png',
						array(
							'alt' => 'Service area map covering South Florida',
							'class' => 'h-full w-full rounded-lg object-cover object-center shadow-lg',
							'loading' => 'lazy'
						)
					); ?>

					<!-- Service Areas Card - Floating Overlay -->
					<div class="absolute bottom-8 -left-8">
						<div class="w-[400px] rounded-xl bg-gradient-to-r from-[#FDC85F] to-[#E64B30] p-4 shadow-lg md:w-full">
							<div class="mb-4 text-center">
								<h3 class="mb-2 text-lg font-extrabold text-white">
									Proudly Serving
								</h3>
								<div class="mx-auto h-0.5 w-24 bg-white opacity-75"></div>
							</div>

							<!-- Service Areas Grid - 4x5 on desktop -->
							<div class="grid grid-cols-4 gap-2">
								<?php foreach ( $service_areas as $area ) : ?>
									<?php
									$city_slug = sanitize_title( $area );
									$city_url  = home_url( "/cities/{$city_slug}/" );
									?>
									<a href="<?php echo esc_url( $city_url ); ?>" class="rounded-lg bg-white px-2 py-1 text-center shadow-sm transition-shadow duration-200 hover:shadow-md block">
										<span class="text-xs font-medium text-[#414141]">
											<?php echo esc_html( $area ); ?>
										</span>
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
