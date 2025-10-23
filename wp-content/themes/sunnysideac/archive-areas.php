<?php
/**
 * Template for displaying City archive page
 * URL: /city (or /areas if redirected)
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
						Service Areas
					</span>
				</h1>
				<p class="text-lg md:text-xl text-white/90 max-w-4xl mx-auto leading-relaxed">
					Expert HVAC services throughout South Florida communities
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

			<!-- Featured Cities Section -->
			<section class="featured-cities bg-white rounded-[20px] p-6 md:p-10">
				<header class="text-center mb-8">
					<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
						<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
							Primary Service Areas
						</span>
					</h2>
					<p class="text-lg text-gray-600">
						Our most frequently serviced communities with fast response times
					</p>
				</header>

				<div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-4">
					<?php foreach (SUNNYSIDE_PRIORITY_CITIES as $city) : ?>
						<?php
						// Check if this city has a city post
						$city_post = get_page_by_title($city, OBJECT, 'city');
						$city_url = $city_post ? get_permalink($city_post->ID) : home_url(sprintf('/areas/%s', sanitize_title($city)));
						?>
						<a href="<?php echo esc_url($city_url); ?>"
						   class="group block bg-gray-50 rounded-2xl p-6 text-center transition-all duration-300 hover:scale-105 hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-lg">
							<!-- Icon Circle -->
							<div class="mb-4">
								<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
									<svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
									</svg>
								</div>
							</div>

							<!-- City Content -->
							<h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500">
								<?php echo esc_html($city); ?>
							</h3>

							<p class="text-gray-600 text-sm mb-4">
								Expert HVAC services in <?php echo esc_html( strtolower( $city ) ); ?>
							</p>

							<span class="inline-flex items-center text-orange-500 font-medium text-sm">
								View Services
								<svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
								</svg>
							</span>
						</a>
					<?php endforeach; ?>
				</div>
			</section>

			<!-- All Cities Archive Section -->
			<section class="cities-archive bg-white rounded-[20px] p-6 md:p-10">
				<header class="text-center mb-8">
					<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
						<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
							All Service Areas
						</span>
					</h2>
					<p class="text-lg text-gray-600">
						Proudly serving our neighbors across South Florida
					</p>
				</header>

				<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
					<?php if ( have_posts() ) : ?>
						<?php while ( have_posts() ) : the_post(); ?>
							<a href="<?php the_permalink(); ?>"
								class="group block bg-gray-50 rounded-2xl p-4 text-center transition-all duration-300 hover:scale-105 hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-lg">
								<!-- Icon Circle -->
								<div class="mb-3">
									<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
										<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
										</svg>
									</div>
								</div>

								<!-- City Name -->
								<h3 class="text-lg font-bold text-gray-900 group-hover:text-orange-500">
									<?php echo esc_html( get_the_title() ); ?>
								</h3>
							</a>
						<?php endwhile; ?>
					<?php else : ?>
						<div class="col-span-full text-center py-12">
							<p class="text-xl text-gray-600">No service areas found.</p>
						</div>
					<?php endif; ?>
				</div>

				<!-- Pagination -->
				<?php if ( have_posts() ) : ?>
					<div class="mt-12 text-center">
						<?php
						the_posts_pagination(
							[
								'mid_size'  => 2,
								'prev_text' => '← Previous',
								'next_text' => 'Next →',
								'class'     => 'inline-flex gap-2',
							]
						);
						?>
					</div>
				<?php endif; ?>
			</section>

			<!-- CTA Section -->
			<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center">
				<h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
					Don't See Your City Listed?
				</h2>
				<p class="text-xl text-white mb-8 max-w-2xl mx-auto">
					Contact us to see if we service your area - we're always expanding our service territory throughout South Florida
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
							Contact Us
						</span>
					</a>
				</div>
			</section>

		</section>
	</div>

</main>

<?php get_footer(); ?>