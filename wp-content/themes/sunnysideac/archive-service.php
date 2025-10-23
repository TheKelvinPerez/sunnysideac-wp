<?php
/**
 * Template for displaying Service archive page
 * URL: /services
 */

get_header();
?>

<main class="min-h-screen bg-gray-50" role="main">
	<!-- Container matching front-page style -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">

			<!-- Hero Section -->
			<header class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-6 md:p-10 text-white text-center">
				<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
					<span class="bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent">
						Our HVAC Services
					</span>
				</h1>
				<p class="text-lg md:text-xl text-white/90 max-w-4xl mx-auto leading-relaxed">
					Professional heating, cooling, and air quality solutions for your home or business across South Florida
				</p>

				<!-- CTA Buttons -->
				<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
					<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
						class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-6 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none"
						aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
						<span class="text-base lg:text-lg font-medium text-orange-500 whitespace-nowrap">
							Schedule Service Now
						</span>
					</a>

					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
						class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
						<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
							Get a Free Quote
						</span>
					</a>
				</div>
			</header>
			<!-- Services Categories -->
			<?php
			// Group services by category
			$service_categories = SUNNYSIDE_SERVICES_BY_CATEGORY;

			if ( ! empty( $service_categories ) ) :
				foreach ( $service_categories as $category_key => $services ) :
					$category_label = ucwords( str_replace( '_', ' ', $category_key ) );
					?>
					<section class="services-category bg-white rounded-[20px] p-6 md:p-10">
						<header class="text-center mb-8">
							<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									<?php echo esc_html( $category_label ); ?>
								</span>
							</h2>
							<p class="text-lg text-gray-600">
								Expert <?php echo esc_html( strtolower( $category_label ) ); ?> services for your home and business
							</p>
						</header>

						<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
							<?php foreach ( $services as $service_name ) :
								$service_slug = sanitize_title( $service_name );
								$service_url = home_url( sprintf( SUNNYSIDE_SERVICE_URL_PATTERN, $service_slug ) );
								$icon_path = sunnysideac_get_service_icon( $service_name );
							?>
								<a href="<?php echo esc_url( $service_url ); ?>"
									class="group block bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-lg">
									<!-- Icon Circle -->
									<div class="mb-4">
										<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
											<svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr( $icon_path ); ?>" />
											</svg>
										</div>
									</div>

									<!-- Service Content -->
									<h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500">
										<?php echo esc_html( $service_name ); ?>
									</h3>

									<p class="text-gray-600 text-sm mb-4">
										Professional <?php echo esc_html( strtolower( $service_name ) ); ?> services across South Florida
									</p>

									<span class="inline-flex items-center text-orange-500 font-medium text-sm">
										Learn More
										<svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
										</svg>
									</span>
								</a>
							<?php endforeach; ?>
						</div>
					</section>
				<?php endforeach;
			else :
				// Fallback to default WP query if no categories defined
				?>
				<section class="services-fallback bg-white rounded-[20px] p-6 md:p-10">
					<header class="text-center mb-8">
						<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
							<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
								All Our Services
							</span>
						</h2>
						<p class="text-lg text-gray-600">
							Complete HVAC solutions for your comfort
						</p>
					</header>

					<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
						<?php if ( have_posts() ) : ?>
							<?php while ( have_posts() ) : the_post(); ?>
								<a href="<?php the_permalink(); ?>"
									class="group block bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-lg">
									<h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500">
										<?php the_title(); ?>
									</h3>
									<?php if ( has_excerpt() ) : ?>
										<p class="text-gray-600 text-sm mb-4">
											<?php echo get_the_excerpt(); ?>
										</p>
									<?php endif; ?>

									<span class="inline-flex items-center text-orange-500 font-medium text-sm">
										Learn More
										<svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
										</svg>
									</span>
								</a>
							<?php endwhile; ?>
						<?php else : ?>
							<div class="col-span-full text-center py-12">
								<p class="text-xl text-gray-600">No services found.</p>
							</div>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- CTA Section -->
			<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center">
				<h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
					Need Help Choosing a Service?
				</h2>
				<p class="text-xl text-white mb-8 max-w-2xl mx-auto">
					Our expert team is here to help you find the perfect HVAC solution for your home or business
				</p>

				<div class="flex flex-col sm:flex-row justify-center gap-4">
					<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
						class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
						<span class="text-lg font-bold text-orange-500">
							Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
						</span>
					</a>

					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
						class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
						<span class="text-lg font-bold text-white">
							Request a Quote
						</span>
					</a>
				</div>
			</section>

		</section>
	</div>

</main>

<?php get_footer(); ?>
