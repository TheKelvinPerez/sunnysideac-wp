<?php
/**
 * Template for displaying Service archive page
 * URL: /services
 */

get_header();
?>

<main class="min-h-screen bg-gray-50">
	<!-- Hero Section -->
	<header class="py-16 bg-gradient-to-r from-[#fb9939] to-[#e5462f] text-white">
		<div class="container mx-auto px-4 text-center">
			<h1 class="text-4xl md:text-5xl font-bold mb-4 [font-family:'Inter-Bold',Helvetica]">
				Our HVAC Services
			</h1>
			<p class="text-xl text-white/90 max-w-3xl mx-auto [font-family:'Inter',Helvetica]">
				Professional heating, cooling, and air quality solutions for your home or business
			</p>
		</div>
	</header>

	<!-- Services Grid -->
	<div class="py-16 px-4 max-w-7xl mx-auto">
		<?php
		// Group services by category
		$service_categories = SUNNYSIDE_SERVICES_BY_CATEGORY;

		if ( ! empty( $service_categories ) ) :
			foreach ( $service_categories as $category_key => $services ) :
				$category_label = ucwords( str_replace( '_', ' ', $category_key ) );
				?>
				<section class="mb-16">
					<h2 class="text-3xl font-bold mb-8 bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent] [font-family:'Inter-Bold',Helvetica]">
						<?php echo esc_html( $category_label ); ?>
					</h2>

					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
						<?php foreach ( $services as $service_name ) :
							$service_slug = sanitize_title( $service_name );
							$service_url = home_url( sprintf( SUNNYSIDE_SERVICE_URL_PATTERN, $service_slug ) );
							$icon_path = sunnysideac_get_service_icon( $service_name );
						?>
							<a href="<?php echo esc_url( $service_url ); ?>"
							   class="group bg-white rounded-[20px] p-6 shadow-md hover:shadow-xl transition-all duration-200 hover:scale-105 border-2 border-transparent hover:border-[#ffc549]">
								<div class="flex items-start gap-4">
									<div class="flex-shrink-0 h-12 w-12 bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-full flex items-center justify-center">
										<svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr( $icon_path ); ?>" />
										</svg>
									</div>
									<div class="flex-1">
										<h3 class="text-lg font-bold text-gray-900 group-hover:text-[#e5462f] transition-colors duration-200 [font-family:'Inter-Bold',Helvetica]">
											<?php echo esc_html( $service_name ); ?>
										</h3>
										<p class="text-sm text-gray-600 mt-1 [font-family:'Inter',Helvetica]">
											Expert <?php echo esc_html( strtolower( $service_name ) ); ?> services
										</p>
									</div>
									<svg class="h-5 w-5 text-gray-400 group-hover:text-[#e5462f] transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
									</svg>
								</div>
							</a>
						<?php endforeach; ?>
					</div>
				</section>
			<?php endforeach;
		else :
			// Fallback to default WP query if no categories defined
			?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<a href="<?php the_permalink(); ?>"
						   class="group bg-white rounded-[20px] p-6 shadow-md hover:shadow-xl transition-all duration-200 hover:scale-105 border-2 border-transparent hover:border-[#ffc549]">
							<h3 class="text-lg font-bold text-gray-900 group-hover:text-[#e5462f] transition-colors duration-200 [font-family:'Inter-Bold',Helvetica]">
								<?php the_title(); ?>
							</h3>
							<?php if ( has_excerpt() ) : ?>
								<p class="text-sm text-gray-600 mt-2 [font-family:'Inter',Helvetica]">
									<?php echo get_the_excerpt(); ?>
								</p>
							<?php endif; ?>
						</a>
					<?php endwhile; ?>
				<?php else : ?>
					<div class="col-span-full text-center py-12">
						<p class="text-xl text-gray-600">No services found.</p>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>

		<!-- CTA Section -->
		<section class="mt-16 bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 text-center">
			<h2 class="text-3xl font-bold text-white mb-4 [font-family:'Inter-Bold',Helvetica]">
				Need Help Choosing a Service?
			</h2>
			<p class="text-white/90 text-lg mb-6 [font-family:'Inter',Helvetica]">
				Our expert team is here to help you find the perfect HVAC solution
			</p>
			<div class="flex flex-col sm:flex-row gap-4 justify-center">
				<a href="<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
				   class="inline-flex items-center justify-center gap-2 bg-white text-[#e5462f] px-8 py-3 rounded-full font-bold hover:bg-[#ffc549] transition-colors duration-200 [font-family:'Inter-Bold',Helvetica]">
					<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
					</svg>
					Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
				</a>
				<a href="<?php echo home_url( '/contact' ); ?>"
				   class="inline-flex items-center justify-center gap-2 bg-white/10 text-white border-2 border-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-[#e5462f] transition-colors duration-200 [font-family:'Inter-Bold',Helvetica]">
					Contact Us
					<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
					</svg>
				</a>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
