<?php
/**
 * 404 Page Template
 * Displayed when a page is not found
 * Based on Astro site design with gradient hero and emergency contact section
 */

get_header(); ?>

<!-- Hero Section with Gradient Background -->
<section class="bg-gradient-to-r from-red-500 via-orange-500 to-yellow-500 rounded-2xl py-16">
	<div class="max-w-4xl mx-auto px-6 text-center">
		<div class="text-8xl font-bold text-white mb-4">404</div>
		<h1 class="text-3xl md:text-4xl font-bold text-white mb-4">Page Not Found</h1>
		<!-- Breadcrumbs -->
		<nav class="text-white/80 text-sm" aria-label="Breadcrumb">
			<ol class="flex items-center justify-center space-x-2">
				<li>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-white transition-colors">Home</a>
				</li>
				<li class="flex items-center">
					<svg class="w-4 h-4 mx-2" fill="currentColor" viewBox="0 0 20 20">
						<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
					</svg>
					<span class="text-white">404 Error</span>
				</li>
			</ol>
		</nav>
	</div>
</section>

<main class="py-16">
	<div class="max-w-4xl mx-auto px-6 text-center">
		<!-- Error Icon -->
		<div class="mb-8">
			<svg class="w-24 h-24 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
			</svg>
		</div>

		<h2 class="text-2xl font-semibold text-gray-800 mb-4">
			Oops! The page you're looking for doesn't exist.
		</h2>

		<p class="text-lg text-gray-600 mb-8 max-w-2xl mx-auto">
			It looks like the page you were trying to reach has been moved or deleted.
			Don't worry, our HVAC experts are here to help keep you cool while you find what you're looking for!
		</p>

		<!-- Action Buttons -->
		<div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
			<a
				href="<?php echo esc_url( home_url( '/' ) ); ?>"
				class="inline-flex items-center gap-2 bg-gradient-to-r from-[#fb9939] to-[#e5462f] text-white px-8 py-3 rounded-full font-medium hover:scale-105 transition-transform duration-200"
			>
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
				</svg>
				Go Home
			</a>

			<a
				href="<?php echo esc_url( home_url( '/about' ) ); ?>"
				class="inline-flex items-center gap-2 border-2 border-gray-300 text-gray-700 px-8 py-3 rounded-full font-medium hover:border-[#fb9939] hover:text-[#fb9939] transition-colors duration-200"
			>
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
				</svg>
				About Us
			</a>
		</div>

		<!-- Quick Links -->
		<div class="bg-gray-100 rounded-2xl p-8">
			<h3 class="text-xl font-bold text-gray-900 mb-6">Popular Pages</h3>
			<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 text-center group border border-gray-200"
				>
					<div class="text-[#fb9939] mb-2 group-hover:text-[#e5462f] transition-colors duration-200">
						<svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
						</svg>
					</div>
					<span class="text-sm font-semibold text-gray-900">Home</span>
				</a>

				<a
					href="<?php echo esc_url( home_url( '/projects' ) ); ?>"
					class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 text-center group border border-gray-200"
				>
					<div class="text-[#fb9939] mb-2 group-hover:text-[#e5462f] transition-colors duration-200">
						<svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
						</svg>
					</div>
					<span class="text-sm font-semibold text-gray-900">Projects</span>
				</a>

				<a
					href="<?php echo esc_url( home_url( '/about' ) ); ?>"
					class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 text-center group border border-gray-200"
				>
					<div class="text-[#fb9939] mb-2 group-hover:text-[#e5462f] transition-colors duration-200">
						<svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
						</svg>
					</div>
					<span class="text-sm font-semibold text-gray-900">About</span>
				</a>

				<a
					href="<?php echo SUNNYSIDE_TEL_HREF; ?>"
					class="bg-white p-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 text-center group border border-gray-200"
				>
					<div class="text-[#fb9939] mb-2 group-hover:text-[#e5462f] transition-colors duration-200">
						<svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
						</svg>
					</div>
					<span class="text-sm font-semibold text-gray-900">Call Now</span>
				</a>
			</div>
		</div>

		<!-- Emergency Contact -->
		<div class="mt-12 p-6 bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-2xl text-white">
			<h3 class="text-xl font-semibold mb-2">Need Emergency HVAC Service?</h3>
			<p class="mb-4">SunnySide 24/7 AC is available around the clock for emergency repairs!</p>
			<a
				href="<?php echo SUNNYSIDE_TEL_HREF; ?>"
				class="inline-flex items-center gap-2 bg-white text-[#e5462f] px-6 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors duration-200"
			>
				<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
					<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
				</svg>
				Call <?php echo SUNNYSIDE_PHONE_DISPLAY; ?>
			</a>
		</div>
	</div>
</main>

<?php get_footer(); ?>
