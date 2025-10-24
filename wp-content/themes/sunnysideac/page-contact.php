<?php
/**
 * Template Name: Contact Page
 * Template for displaying the Contact page
 */

get_header();

// Get icons from theme assets
$contact_us_icon    = sunnysideac_asset_url( 'assets/images/home-page/contact-us/contact-us-icon.svg' );
$service_areas_icon = sunnysideac_asset_url( 'assets/icons/best-at-keeping-refreshed-icon.svg' );

// Page breadcrumbs
$breadcrumbs = array(
	array(
		'name' => 'Home',
		'url'  => home_url( '/' ),
	),
	array(
		'name' => 'Contact',
		'url'  => '',
	),
);
?>

<!-- Page Header with Breadcrumbs -->
<?php
get_template_part(
	'template-parts/page-header',
	null,
	array(
		'breadcrumbs' => $breadcrumbs,
		'title'       => 'Contact Us',
		'description' => '',
		'show_ctas'   => false,
		'bg_color'    => 'white',
	)
);
?>

<main class="px-5 lg:px-0 max-w-7xl mx-auto">
	<div class="flex gap-10 flex-col py-12">
		<!-- Contact Info Section -->
		<section
			class="w-full bg-white rounded-[20px]"
			role="main"
			aria-labelledby="contact-info-heading"
		>
			<div class="mx-auto text-center max-w-4xl p-6 md:p-8 lg:p-10">
				<?php
				get_template_part(
					'template-parts/title',
					null,
					array(
						'icon'  => $contact_us_icon,
						'title' => 'Get In Touch',
						'id'    => 'contact-info-heading',
						'align' => 'center',
					)
				);
				?>

				<?php
				get_template_part(
					'template-parts/subheading',
					null,
					array(
						'text' => '24/7 emergency service • Same-day repairs • Professional HVAC solutions',
					)
				);
				?>

				<div class="mt-8 text-left space-y-6">
					<p class="text-sm md:text-base font-light leading-snug text-gray-700">
						When your HVAC system breaks down, every minute matters. That's why SunnySide 24/7 AC offers round-the-clock emergency services to restore comfort to your home or business quickly and efficiently.
					</p>
					<p class="text-sm md:text-base font-light leading-snug text-gray-700">
						Our certified technicians are standing by to handle everything from emergency repairs to scheduled maintenance and new installations. Contact us today for fast, reliable service you can trust.
					</p>
				</div>
			</div>
		</section>

		<!-- Service Areas Section -->
		<section
			class="w-full bg-gray-50 rounded-[20px]"
			role="contentinfo"
			aria-labelledby="service-areas-heading"
		>
			<div class="">
				<div class="text-center mb-8">
					<?php
					get_template_part(
						'template-parts/title',
						null,
						array(
							'icon'  => $service_areas_icon,
							'title' => 'Service Areas',
							'id'    => 'service-areas-heading',
							'align' => 'center',
						)
					);
					?>
					<?php
					get_template_part(
						'template-parts/subheading',
						null,
						array(
							'text' => 'Proudly serving South Florida communities',
						)
					);
					?>
				</div>

				<div class="lg:grid lg:grid-cols-3 lg:gap-6 mb-8">
					<article class="rounded-[20px] bg-white p-6 md:p-8 lg:p-10 mb-6 lg:mb-0">
						<h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">
							Emergency Services
						</h3>
						<p class="text-sm md:text-base font-light leading-snug text-gray-700">
							24/7 emergency repair service for when your AC breaks down unexpectedly. We prioritize emergency calls and aim for same-day service.
						</p>
						<div class="mt-4 text-orange-600 font-medium text-sm">
							Available 24/7 • Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
						</div>
					</article>

					<article class="rounded-[20px] bg-white p-6 md:p-8 lg:p-10 mb-6 lg:mb-0">
						<h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">
							Scheduled Service
						</h3>
						<p class="text-sm md:text-base font-light leading-snug text-gray-700">
							Regular maintenance, installations, and non-emergency repairs. Schedule online or call during business hours for convenient appointment times.
						</p>
						<div class="mt-4 text-orange-600 font-medium text-sm">
							Mon-Fri 8AM-6PM • Sat 9AM-4PM
						</div>
					</article>

					<article class="rounded-[20px] bg-white p-6 md:p-8 lg:p-10">
						<h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">
							Free Estimates
						</h3>
						<p class="text-sm md:text-base font-light leading-snug text-gray-700">
							Get a free, no-obligation estimate for new installations, system replacements, or major repairs. We'll assess your needs and provide transparent pricing.
						</p>
						<div class="mt-4 text-orange-600 font-medium text-sm">
							Call for free estimate • Licensed & insured
						</div>
					</article>
				</div>
			</div>
		</section>

		<!-- Contact Form Section -->
		<section
			class="w-full bg-white rounded-[20px]"
			role="contentinfo"
			aria-labelledby="contact-form-heading"
		>
			<div class="p-6 md:p-8 lg:p-10">
				<?php get_template_part( 'template-parts/contact-us' ); ?>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
