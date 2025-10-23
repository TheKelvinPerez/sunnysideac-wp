<?php
/**
 * Template Name: Projects Page
 * Template for displaying the Projects portfolio page
 */

get_header();

// Get star icon from theme assets
$star_icon = sunnysideac_asset_url( 'assets/icons/star-icon.svg' );

// Page breadcrumbs
$breadcrumbs = [
	[ 'name' => 'Home', 'url' => home_url( '/' ) ],
	[ 'name' => 'Projects', 'url' => '' ],
];

// Project cards data
$projects = [
	[
		'image'       => 'https://images.unsplash.com/photo-1581094794329-c8112a89af12?w=400&h=250&fit=crop&crop=center',
		'image_alt'   => 'Commercial multi-zone cooling system installation showing modern HVAC equipment',
		'category'    => 'Commercial',
		'title'       => 'Multi-Zone Cooling Solutions',
		'description' => 'Complete HVAC system installation for a 15,000 sq ft office building with individual zone controls.',
		'duration'    => '2 weeks',
		'location'    => 'Downtown Office Complex',
	],
	[
		'image'       => 'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=400&h=250&fit=crop&crop=center',
		'image_alt'   => 'Hotel HVAC maintenance showing technician working on rooftop unit',
		'category'    => 'Maintenance',
		'title'       => 'Hotel HVAC Maintenance',
		'description' => 'Annual maintenance program for a 120-room hotel including preventive care and emergency repairs.',
		'duration'    => 'Ongoing',
		'location'    => 'Beachfront Resort',
	],
	[
		'image'       => 'https://images.unsplash.com/photo-1621905251189-08b45d6a269e?w=400&h=250&fit=crop&crop=center',
		'image_alt'   => 'Residential AC installation showing new outdoor unit and indoor components',
		'category'    => 'Installation',
		'title'       => 'Multi-Zone AC Installation',
		'description' => 'New central air system installation for a 3,500 sq ft family home with smart thermostat integration.',
		'duration'    => '3 days',
		'location'    => 'Suburban Residence',
	],
	[
		'image'       => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?w=400&h=250&fit=crop&crop=center',
		'image_alt'   => 'Smart thermostat upgrade showing modern digital display and installation process',
		'category'    => 'Upgrade',
		'title'       => 'Smart Thermostat Upgrade',
		'description' => 'Installation of WiFi-enabled smart thermostats in 25 residential units with mobile app integration.',
		'duration'    => '1 week',
		'location'    => 'Apartment Complex',
	],
	[
		'image'       => 'https://images.unsplash.com/photo-1504309092620-4d0ec726efa4?w=400&h=250&fit=crop&crop=center',
		'image_alt'   => 'Industrial warehouse heating system with large commercial HVAC units',
		'category'    => 'Industrial',
		'title'       => 'Warehouse Climate Control',
		'description' => 'Industrial heating and cooling solution for a 50,000 sq ft distribution warehouse with energy recovery.',
		'duration'    => '4 weeks',
		'location'    => 'Industrial Park',
	],
	[
		'image'       => 'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=400&h=250&fit=crop&crop=center',
		'image_alt'   => 'Emergency HVAC repair showing technician working on system during after-hours service call',
		'category'    => 'Emergency',
		'title'       => '24/7 Emergency Repairs',
		'description' => 'Round-the-clock emergency repair services for residential and commercial clients during peak season.',
		'duration'    => 'Same day',
		'location'    => '24/7 Service',
	],
];
?>

<!-- Page Header with Breadcrumbs -->
<?php
get_template_part(
	'template-parts/page-header',
	null,
	[
		'breadcrumbs' => $breadcrumbs,
		'title'       => 'Our Projects',
		'description' => '',
		'show_ctas'   => false,
		'bg_color'    => 'white',
	]
);
?>

<main class="px-5 lg:px-0 max-w-7xl mx-auto">
	<div class="flex gap-10 flex-col py-12">
		<!-- Projects Overview Section -->
		<section
			class="w-full bg-white rounded-[20px]"
			role="main"
			aria-labelledby="overview-heading"
		>
			<div class="mx-auto text-center max-w-4xl p-6 md:p-8 lg:p-10">
				<?php
				get_template_part(
					'template-parts/title',
					null,
					[
						'icon'  => $star_icon,
						'title' => 'Featured Projects',
						'id'    => 'overview-heading',
						'align' => 'center',
					]
				);
				?>

				<?php
				get_template_part(
					'template-parts/subheading',
					null,
					[
						'text' => 'Showcasing our expertise in residential and commercial HVAC solutions',
					]
				);
				?>

				<div class="mt-8 text-left space-y-6">
					<p class="text-sm md:text-base font-light leading-snug text-gray-700">
						Over the years, SunnySide 24/7 AC has completed hundreds of successful installations, repairs, and maintenance projects across residential and commercial properties. Each project showcases our commitment to quality workmanship and customer satisfaction.
					</p>
					<p class="text-sm md:text-base font-light leading-snug text-gray-700">
						From simple residential repairs to complex commercial HVAC systems, we bring the same level of professionalism and attention to detail to every job. Browse our featured projects below to see the quality of work you can expect from our certified technicians.
					</p>
				</div>
			</div>
		</section>

		<!-- Projects Grid Section -->
		<section
			class="w-full bg-gray-50 rounded-[20px]"
			role="contentinfo"
			aria-labelledby="projects-grid-heading"
		>
			<div class="mx-auto max-w-6xl p-6 md:p-8 lg:p-10">
				<!-- Residential vs Commercial Overview -->
				<div class="lg:grid lg:grid-cols-2 lg:gap-6 mb-8">
					<article class="rounded-[20px] bg-white p-6 md:p-8 lg:p-10 mb-6 lg:mb-0">
						<h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">
							Residential Projects
						</h3>
						<p class="text-sm md:text-base font-light leading-snug text-gray-700">
							From single-family homes to apartment complexes, we provide comprehensive HVAC solutions including installations, repairs, and maintenance services that keep families comfortable year-round.
						</p>
					</article>

					<article class="rounded-[20px] bg-white p-6 md:p-8 lg:p-10">
						<h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-4">
							Commercial Projects
						</h3>
						<p class="text-sm md:text-base font-light leading-snug text-gray-700">
							We handle large-scale commercial HVAC systems for offices, retail spaces, warehouses, and industrial facilities, ensuring optimal climate control for your business operations.
						</p>
					</article>
				</div>

				<!-- Project Cards Grid -->
				<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="projects-grid-heading">
					<?php foreach ( $projects as $project ) : ?>
						<article class="rounded-[20px] bg-white p-6 md:p-8 lg:p-10 transition-all duration-300 hover:shadow-md cursor-pointer group">
							<!-- Project Image with Category Badge -->
							<div class="relative mb-4">
								<img
									src="<?php echo esc_url( $project['image'] ); ?>"
									alt="<?php echo esc_attr( $project['image_alt'] ); ?>"
									class="w-full h-48 object-cover rounded-[12px] group-hover:scale-105 transition-transform duration-300"
									loading="lazy"
								/>
								<div class="absolute top-3 left-3">
									<span class="bg-gradient-to-r from-[#F79E37] to-[#E5462F] text-white text-xs font-medium px-3 py-1 rounded-full">
										<?php echo esc_html( $project['category'] ); ?>
									</span>
								</div>
							</div>

							<!-- Project Title -->
							<h3 class="text-lg md:text-xl font-semibold text-gray-900 mb-2">
								<?php echo esc_html( $project['title'] ); ?>
							</h3>

							<!-- Project Description -->
							<p class="text-sm md:text-base font-light leading-snug text-gray-700 mb-3">
								<?php echo esc_html( $project['description'] ); ?>
							</p>

							<!-- Project Meta Information -->
							<div class="text-sm text-gray-500">
								<span class="font-medium">Duration:</span> <?php echo esc_html( $project['duration'] ); ?> |
								<span class="font-medium">Location:</span> <?php echo esc_html( $project['location'] ); ?>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
