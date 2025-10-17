<?php
/**
 * Footer Template
 * Main footer component with all subsections
 */

// Component data (like props in React)
$quick_links = [
	[
		'text' => 'Home Page',
		'href' => '/',
	],
	[
		'text' => 'About Us',
		'href' => '/about',
	],
	[
		'text' => 'Our Services',
		'href' => '/services',
	],
	[
		'text' => 'Our Blog',
		'href' => '/blog',
	],
	[
		'text' => 'Contact Us',
		'href' => '/contact',
	],
	[
		'text' => 'Get a Free Quote',
		'href' => '/quote',
	],
];

$services = [
	[
		'text' => 'Air Conditioning Installation',
		'href' => '/services/air-conditioning-installation',
	],
	[
		'text' => 'Indoor Air Quality Solutions',
		'href' => '/services/indoor-air-quality',
	],
	[
		'text' => 'HVAC Maintenance Plans',
		'href' => '/services/hvac-maintenance',
	],
	[
		'text' => 'Emergency HVAC Services',
		'href' => '/services/emergency-hvac',
	],
	[
		'text' => 'Commercial HVAC Services',
		'href' => '/services/commercial-hvac',
	],
];
?>


<footer
	class="mt-10 mb-10 bg-white rounded-2xl"
	role="contentinfo"
	aria-label="Site footer"
>
	<div class="mx-auto px-5 py-8 lg:px-0 lg:py-12">
		<div class="px-4 sm:px-6 lg:px-8">
			<!-- Main footer content -->
			<div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
						<?php get_template_part( 'template-parts/footer-company-info' ); ?>
				<?php get_template_part( 'template-parts/footer-quick-links', null, array( 'links' => $quick_links ) ); ?>
				<?php get_template_part( 'template-parts/footer-services-subsection', null, array( 'services' => $services ) ); ?>
				<?php get_template_part( 'template-parts/footer-contact-subsection' ); ?>
			</div>

			<!-- Divider line -->
			<div class="my-8 border-t border-gray-200"></div>

			<!-- Bottom section with copyright and legal links -->
			<div class="flex flex-col items-center justify-between space-y-4 sm:flex-row sm:space-y-0">
				<div class="text-center sm:text-left">
					<p class="font-light text-gray-600">
						2025 Sunny Side Air Conditioning Corp AllRights Reserved.
					</p>
					<p class="font-light text-gray-500 text-sm mt-1">
						Made with ❤️ by <a href="https://kelvinperez.com" target="_blank" rel="noopener noreferrer" class="hover:text-gray-700 hover:underline focus:outline-2 focus:outline-blue-500">Kelvin Perez</a>
					</p>
				</div>

				<nav
					class="flex items-center space-x-6"
					aria-label="Footer legal links"
				>
					<a
						href="/privacy-policy"
						class="font-light text-[10px] md:text-base text-gray-600 hover:text-gray-900 hover:underline focus:outline-2 focus:outline-blue-500"
						aria-label="Privacy Policy"
					>
						Privacy Policy
					</a>

					<span class="text-gray-300">|</span>

					<a
						href="/terms-conditions"
						class="font-light text-[10px] md:text-base text-gray-600 hover:text-gray-900 hover:underline focus:outline-2 focus:outline-blue-500"
						aria-label="Terms and Conditions"
					>
						Terms &amp; Conditions
					</a>
				</nav>
			</div>
		</div>
	</div>
</footer>

</div><!-- Close global content wrapper from header -->
<?php wp_footer(); ?>

</body>
</html>
