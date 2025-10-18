<?php get_header(); ?>

<?php if ( have_posts() ) : ?>
	<?php while ( have_posts() ) : the_post(); ?>
		<main class="min-h-screen bg-gray-50">
			<!-- Mobile constraint wrapper - applies 20px padding on mobile only -->
			<div class="px-5 lg:px-0 max-w-7xl mx-auto">
				<section class="flex gap-10 flex-col">
					<!-- City Hero Section -->
					<header class="py-16 bg-gradient-to-r from-[#fb9939] to-[#e5462f] text-white">
						<div class="container mx-auto px-4 text-center">
							<h1 class="text-4xl md:text-5xl font-bold mb-4">
								HVAC Services in <?php the_title(); ?>
							</h1>
							<p class="text-xl text-[#ffc549] max-w-3xl mx-auto">
								Professional heating, cooling, and air quality services for the <?php the_title(); ?> community
							</p>
							<div class="mt-8 flex flex-col sm:flex-row gap-4 justify-center">
								<a href="<?php echo esc_attr(SUNNYSIDE_TEL_HREF); ?>"
								   class="bg-white text-[#e5462f] px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all transform hover:scale-105 inline-block">
									<?php echo esc_html(SUNNYSIDE_PHONE_DISPLAY); ?>
								</a>
								<a href="<?php echo esc_url(home_url('/contact')); ?>"
								   class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-[#e5462f] transition-all inline-block">
									Schedule Service
								</a>
							</div>
						</div>
					</header>

					<!-- City Services Overview -->
					<section class="py-16">
						<div class="container mx-auto px-4">
							<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
								<!-- Emergency Services -->
								<div class="bg-white rounded-lg shadow-md p-8 text-center">
									<div class="h-16 w-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
										<svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
										</svg>
									</div>
									<h3 class="text-xl font-bold text-gray-900 mb-2">24/7 Emergency Service</h3>
									<p class="text-gray-600">
										Fast response HVAC emergencies available around the clock in <?php the_title(); ?>
									</p>
								</div>

								<!-- Expert Technicians -->
								<div class="bg-white rounded-lg shadow-md p-8 text-center">
									<div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
										<svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
										</svg>
									</div>
									<h3 class="text-xl font-bold text-gray-900 mb-2">Expert Technicians</h3>
									<p class="text-gray-600">
										Licensed, insured professionals serving <?php the_title(); ?> with pride
									</p>
								</div>

								<!-- Local Service -->
								<div class="bg-white rounded-lg shadow-md p-8 text-center">
									<div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
										<svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
										</svg>
									</div>
									<h3 class="text-xl font-bold text-gray-900 mb-2">Local Expertise</h3>
									<p class="text-gray-600">
										Knowledge of <?php the_title(); ?>'s specific HVAC needs and climate challenges
									</p>
								</div>
							</div>

							<!-- Services Available -->
							<div class="text-center mb-12">
								<h2 class="text-3xl font-bold text-gray-900 mb-4">Services Available in <?php the_title(); ?></h2>
								<p class="text-xl text-gray-600">Complete HVAC solutions for your home or business</p>
							</div>

							<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
								<?php
								$services = SUNNYSIDE_SERVICES_BY_CATEGORY;
								foreach ($services as $category => $service_list) :
									foreach ($service_list as $service_name) :
								?>
									<div class="bg-white rounded-lg border border-gray-200 p-6 hover:border-[#fb9939] hover:shadow-md transition-all">
										<div class="flex items-center gap-3 mb-3">
											<div class="h-8 w-8 rounded-lg bg-[#ffc549] p-1">
												<svg class="h-6 w-6 text-[#e5462f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr(sunnysideac_get_service_icon($service_name)); ?>" />
												</svg>
											</div>
											<h3 class="font-semibold text-gray-900"><?php echo esc_html($service_name); ?></h3>
										</div>
										<p class="text-gray-600 text-sm mb-4">
											Professional <?php echo strtolower(esc_html($service_name)); ?> services for <?php the_title(); ?> residents and businesses
										</p>
										<a href="<?php echo esc_url(home_url(sprintf('/%s/%s', sanitize_title($service_name), sanitize_title(get_the_title())))); ?>"
										   class="text-[#e5462f] font-medium hover:text-[#fb9939] transition-colors">
											Learn more →
										</a>
									</div>
								<?php
									endforeach;
								endforeach;
								?>
							</div>
						</div>
					</section>

					<!-- City Content -->
					<?php if (get_the_content() || get_field('city_service_hours') || get_field('city_service_area_note')) : ?>
						<section class="py-16 bg-white">
							<div class="container mx-auto px-4">
								<div class="max-w-4xl mx-auto">
									<?php if (get_the_content()) : ?>
										<div class="prose prose-lg max-w-none mb-12">
											<?php the_content(); ?>
										</div>
									<?php endif; ?>

									<?php if (get_field('city_service_hours')) : ?>
										<div class="bg-gray-50 rounded-lg p-8 mb-8">
											<h3 class="text-xl font-bold text-gray-900 mb-4">Service Hours in <?php the_title(); ?></h3>
											<div class="text-gray-700">
												<?php echo wp_kses_post(get_field('city_service_hours')); ?>
											</div>
										</div>
									<?php endif; ?>

									<?php if (get_field('city_service_area_note')) : ?>
										<div class="bg-blue-50 rounded-lg p-8 mb-8">
											<h3 class="text-xl font-bold text-gray-900 mb-4">About Our <?php the_title(); ?> Service</h3>
											<div class="text-gray-700">
												<?php echo wp_kses_post(get_field('city_service_area_note')); ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</section>
					<?php endif; ?>

					<!-- Video Section -->
					<?php if (get_field('city_video_url')) : ?>
						<section class="py-16">
							<div class="container mx-auto px-4">
								<div class="max-w-4xl mx-auto">
									<h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
										<?php echo esc_html(get_field('city_video_title') ?: "HVAC Services in " . get_the_title()); ?>
									</h2>
									<div class="rounded-lg overflow-hidden shadow-xl">
										<?php echo sunnysideac_get_video_embed(get_field('city_video_url'), ['class' => 'w-full aspect-video']); ?>
									</div>
									<?php if (get_field('city_video_description')) : ?>
										<p class="mt-4 text-gray-600 text-center">
											<?php echo esc_html(get_field('city_video_description')); ?>
										</p>
									<?php endif; ?>
								</div>
							</div>
						</section>
					<?php endif; ?>

					<!-- Nearby Cities -->
					<section class="py-16 bg-white">
						<div class="container mx-auto px-4">
							<div class="text-center mb-12">
								<h2 class="text-3xl font-bold text-gray-900 mb-4">Nearby Service Areas</h2>
								<p class="text-xl text-gray-600">We also serve these surrounding communities</p>
							</div>

							<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
								<?php
								$current_city = get_the_title();
								$nearby_cities = array_slice(SUNNYSIDE_SERVICE_AREAS, 0, 12); // Show first 12 cities

								foreach ($nearby_cities as $city) :
									if ($city !== $current_city) :
								?>
									<a href="<?php echo esc_url(home_url(sprintf('/areas/%s', sanitize_title($city)))); ?>"
									   class="flex items-center gap-2 p-3 rounded-lg border border-gray-200 hover:border-[#fb9939] hover:bg-[#ffc549] transition-all text-center justify-center">
										<svg class="h-4 w-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
										</svg>
										<span class="text-sm font-medium text-gray-700"><?php echo esc_html($city); ?></span>
									</a>
								<?php
									endif;
								endforeach;
								?>
							</div>

							<div class="text-center mt-8">
								<a href="<?php echo esc_url(home_url('/areas')); ?>"
								   class="inline-flex items-center text-[#e5462f] font-medium hover:text-[#fb9939] transition-colors">
									View all service areas
									<svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
									</svg>
								</a>
							</div>
						</div>
					</section>

					<!-- CTA Section -->
					<section class="py-20 bg-gradient-to-r from-[#e5462f] to-[#fb9939] text-white">
						<div class="container mx-auto px-4 text-center">
							<h2 class="text-4xl font-bold mb-6">Ready for HVAC Service in <?php the_title(); ?>?</h2>
							<p class="text-xl text-[#ffc549] mb-8 max-w-2xl mx-auto">
								Call us now for fast, reliable HVAC service. Emergency repairs available 24/7.
							</p>
							<div class="flex flex-col sm:flex-row gap-4 justify-center">
								<a href="<?php echo esc_attr(SUNNYSIDE_TEL_HREF); ?>"
								   class="bg-white text-[#e5462f] px-8 py-4 rounded-lg font-semibold text-lg hover:bg-gray-50 transition-all transform hover:scale-105 inline-block">
									<?php echo esc_html(SUNNYSIDE_PHONE_DISPLAY); ?>
								</a>
								<a href="<?php echo esc_url(home_url('/contact')); ?>"
								   class="border-2 border-white text-white px-8 py-4 rounded-lg font-semibold text-lg hover:bg-white hover:text-[#e5462f] transition-all inline-block">
									Schedule Service
								</a>
							</div>
						</div>
					</section>
				</section>
			</div>
		</main>
	<?php endwhile; ?>
<?php else : ?>
	<main class="min-h-screen bg-gray-50">
		<div class="px-5 lg:px-0 max-w-7xl mx-auto">
			<section class="py-16">
				<div class="container mx-auto px-4 text-center">
					<h1 class="text-4xl font-bold text-gray-900 mb-4">City Not Found</h1>
					<p class="text-xl text-gray-600 mb-8">The service area you're looking for doesn't exist.</p>
					<a href="<?php echo esc_url(home_url('/areas')); ?>" class="bg-[#e5462f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#fb9939] transition-colors inline-block">
						View All Areas
					</a>
				</div>
			</section>
		</div>
	</main>
<?php endif; ?>

<?php get_footer(); ?>