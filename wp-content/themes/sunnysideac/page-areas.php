<?php
/*
Template Name: Areas
*/

get_header(); ?>

<main class="min-h-screen bg-gray-50">
	<!-- Mobile constraint wrapper - applies 20px padding on mobile only -->
	<div class="px-5 lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">
			<!-- Hero Section -->
			<header class="py-16 bg-gradient-to-r from-[#fb9939] to-[#e5462f] text-white">
				<div class="container mx-auto px-4 text-center">
					<h1 class="text-4xl md:text-5xl font-bold mb-4">
						Service Areas
					</h1>
					<p class="text-xl text-[#ffc549] max-w-3xl mx-auto">
						Proudly serving South Florida with professional HVAC services across 30+ communities
					</p>
				</div>
			</header>

			<!-- Featured Areas -->
			<section class="py-16">
				<div class="container mx-auto px-4">
					<div class="text-center mb-12">
						<h2 class="text-3xl font-bold text-gray-900 mb-4">Primary Service Areas</h2>
						<p class="text-xl text-gray-600">Our most frequently serviced communities with fast response times</p>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-16">
						<?php foreach (SUNNYSIDE_PRIORITY_CITIES as $city) : ?>
							<div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition-shadow">
								<div class="flex items-center gap-3 mb-4">
									<div class="h-10 w-10 rounded-lg bg-[#ffc549] p-2">
										<svg class="h-6 w-6 text-[#e5462f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
										</svg>
									</div>
									<h3 class="text-lg font-semibold text-gray-900"><?php echo esc_html($city); ?></h3>
								</div>
								<p class="text-gray-600 mb-4 text-sm">
									Expert HVAC repair, installation, and maintenance services available
								</p>
								<a href="<?php echo esc_url(home_url(sprintf('/areas/%s', sanitize_title($city)))); ?>"
								   class="inline-flex items-center text-[#e5462f] font-medium hover:text-[#fb9939] transition-colors">
									Learn more
									<svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
									</svg>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>

			<!-- All Service Areas -->
			<section class="py-16 bg-white">
				<div class="container mx-auto px-4">
					<div class="text-center mb-12">
						<h2 class="text-3xl font-bold text-gray-900 mb-4">All Service Areas</h2>
						<p class="text-xl text-gray-600">Complete list of communities we serve throughout South Florida</p>
					</div>

					<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
						<?php
						// Group cities alphabetically for better organization
						$all_cities = SUNNYSIDE_SERVICE_AREAS;
						sort($all_cities);
						$columns = array_chunk($all_cities, ceil(count($all_cities) / 3));

						foreach ($columns as $column) :
						?>
							<div class="space-y-3">
								<?php foreach ($column as $city) : ?>
									<div class="flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-colors">
										<div class="h-8 w-8 rounded-full bg-[#ffc549] flex items-center justify-center">
											<svg class="h-4 w-4 text-[#e5462f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
											</svg>
										</div>
										<a href="<?php echo esc_url(home_url(sprintf('/areas/%s', sanitize_title($city)))); ?>"
										   class="text-gray-700 hover:text-[#e5462f] font-medium transition-colors">
											<?php echo esc_html($city); ?>
										</a>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			</section>

			<!-- Service Information -->
			<section class="py-16">
				<div class="container mx-auto px-4">
					<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
						<div class="text-center">
							<div class="h-16 w-16 bg-[#ffc549] rounded-full flex items-center justify-center mx-auto mb-4">
								<svg class="h-8 w-8 text-[#e5462f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
							</div>
							<h3 class="text-xl font-bold text-gray-900 mb-2">Fast Response</h3>
							<p class="text-gray-600">
								Emergency services available 24/7 with rapid response times across all service areas
							</p>
						</div>

						<div class="text-center">
							<div class="h-16 w-16 bg-[#ffc549] rounded-full flex items-center justify-center mx-auto mb-4">
								<svg class="h-8 w-8 text-[#e5462f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
							</div>
							<h3 class="text-xl font-bold text-gray-900 mb-2">Licensed & Insured</h3>
							<p class="text-gray-600">
								Fully licensed, bonded, and insured technicians serving all South Florida communities
							</p>
						</div>

						<div class="text-center">
							<div class="h-16 w-16 bg-[#ffc549] rounded-full flex items-center justify-center mx-auto mb-4">
								<svg class="h-8 w-8 text-[#e5462f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
								</svg>
							</div>
							<h3 class="text-xl font-bold text-gray-900 mb-2">Local Experts</h3>
							<p class="text-gray-600">
								Serving South Florida communities with expert knowledge of local HVAC needs
							</p>
						</div>
					</div>
				</div>
			</section>

			<!-- CTA Section -->
			<section class="py-20 bg-gradient-to-r from-[#e5462f] to-[#fb9939] text-white">
				<div class="container mx-auto px-4 text-center">
					<h2 class="text-4xl font-bold mb-6">Need HVAC Service in Your Area?</h2>
					<p class="text-xl text-[#ffc549] mb-8 max-w-2xl mx-auto">
						Call us now for fast, reliable HVAC service. We serve all South Florida communities with pride.
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

<?php get_footer(); ?>