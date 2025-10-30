<?php
/**
 * Template Name: Single City
 * Template for displaying individual city posts
 * URL: /cities/{city-slug}/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$city_id    = get_the_ID();
	$city_title = get_the_title();

	// Get ACF fields
	$city_description       = get_field( 'city_description', $city_id );
	$city_service_hours     = get_field( 'city_service_hours', $city_id );
	$city_service_area_note = get_field( 'city_service_area_note', $city_id );
	$city_video_url         = get_field( 'city_video_url', $city_id );
	$city_video_title       = get_field( 'city_video_title', $city_id );
	$city_video_description = get_field( 'city_video_description', $city_id );

	// SEO Variables
	$seo_title       = 'HVAC Services in ' . $city_title . ' | AC Repair & Installation | Sunnyside AC';
	$seo_description = 'Professional HVAC services in ' . $city_title . ', FL. Expert AC repair, installation, heating, and air quality solutions. 24/7 emergency service. Licensed & insured technicians serving ' . $city_title . ' and surrounding areas.';
	$page_url        = get_permalink();
	$page_image      = has_post_thumbnail() ? get_the_post_thumbnail_url( $city_id, 'large' ) : sunnysideac_asset_url( 'assets/images/home-page/hero/hero-image.png' );

	// Page breadcrumbs
	$breadcrumbs = array(
		array(
			'name' => 'Home',
			'url'  => home_url( '/' ),
		),
		array(
			'name' => 'Service Areas',
			'url'  => home_url( '/cities/' ),
		),
		array(
			'name' => $city_title,
			'url'  => '',
		),
	);

	?>

	<!-- SEO Meta Tags -->
	<meta name="description" content="<?php echo esc_attr( $seo_description ); ?>">
	<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
	<link rel="canonical" href="<?php echo esc_url( $page_url ); ?>">

	<!-- Open Graph / Facebook -->
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo esc_url( $page_url ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( $seo_title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $seo_description ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $page_image ); ?>">
	<meta property="og:locale" content="en_US">
	<meta property="og:site_name" content="Sunnyside AC">

	<!-- Twitter -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:url" content="<?php echo esc_url( $page_url ); ?>">
	<meta name="twitter:title" content="<?php echo esc_attr( $seo_title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( $seo_description ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( $page_image ); ?>">

	<!-- Geographic Meta Tags -->
	<meta name="geo.region" content="US-FL">
	<meta name="geo.placename" content="<?php echo esc_attr( $city_title ); ?>, Florida">

	<!-- JSON-LD Structured Data: BreadcrumbList -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "BreadcrumbList",
		"itemListElement": [
			{
				"@type": "ListItem",
				"position": 1,
				"name": "Home",
				"item": "<?php echo esc_url( home_url( '/' ) ); ?>"
			},
			{
				"@type": "ListItem",
				"position": 2,
				"name": "Service Areas",
				"item": "<?php echo esc_url( home_url( '/cities/' ) ); ?>"
			},
			{
				"@type": "ListItem",
				"position": 3,
				"name": "<?php echo esc_js( $city_title ); ?>",
				"item": "<?php echo esc_url( $page_url ); ?>"
			}
		]
	}
	</script>

	<!-- JSON-LD Structured Data: LocalBusiness with Service -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "LocalBusiness",
		"@id": "<?php echo esc_url( home_url( '/' ) ); ?>#organization",
		"name": "Sunnyside AC",
		"image": "<?php echo esc_url( $page_image ); ?>",
		"url": "<?php echo esc_url( $page_url ); ?>",
		"telephone": "<?php echo esc_js( SUNNYSIDE_TEL_HREF ); ?>",
		"email": "<?php echo esc_js( SUNNYSIDE_EMAIL_ADDRESS ); ?>",
		"address": {
			"@type": "PostalAddress",
			"streetAddress": "<?php echo esc_js( SUNNYSIDE_ADDRESS_FULL ); ?>",
			"addressLocality": "South Florida",
			"addressRegion": "FL",
			"postalCode": "",
			"addressCountry": "US"
		},
		"geo": {
			"@type": "GeoCoordinates",
			"latitude": "",
			"longitude": ""
		},
		"areaServed": {
			"@type": "City",
			"name": "<?php echo esc_js( $city_title ); ?>",
			"@id": "<?php echo esc_url( $page_url ); ?>"
		},
		"hasOfferCatalog": {
			"@type": "OfferCatalog",
			"name": "HVAC Services",
			"itemListElement": [
				<?php
				$services = SUNNYSIDE_SERVICES_BY_CATEGORY;
				$service_schemas = array();
				foreach ( $services as $category => $service_list ) {
					foreach ( $service_list as $service_name ) {
						$service_schemas[] = '{
							"@type": "Offer",
							"itemOffered": {
								"@type": "Service",
								"name": "' . esc_js( $service_name ) . '",
								"description": "Professional ' . strtolower( esc_js( $service_name ) ) . ' services for ' . esc_js( $city_title ) . ' residents and businesses",
								"areaServed": {
									"@type": "City",
									"name": "' . esc_js( $city_title ) . '"
								},
								"provider": {
									"@type": "LocalBusiness",
									"name": "Sunnyside AC"
								}
							}
						}';
					}
				}
				echo implode( ",\n\t\t\t\t", $service_schemas );
				?>
			]
		},
		"priceRange": "$$",
		"openingHoursSpecification": {
			"@type": "OpeningHoursSpecification",
			"dayOfWeek": [
				"Monday",
				"Tuesday",
				"Wednesday",
				"Thursday",
				"Friday",
				"Saturday",
				"Sunday"
			],
			"opens": "00:00",
			"closes": "23:59"
		}
	}
	</script>

	<?php

	?>

	<main class="min-h-screen bg-gray-50" role="main" itemscope itemtype="https://schema.org/Service">

		<!-- Container matching front-page style -->
		<div class="lg:px-0 max-w-7xl mx-auto">
			<section class="flex gap-10 flex-col">

				<!-- Hero Section with City Title & CTA -->
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'city-page' ); ?>>

					<?php
					// Page Header with Breadcrumbs (using template part)
					get_template_part(
						'template-parts/page-header',
						null,
						array(
							'breadcrumbs' => $breadcrumbs,
							'title'       => 'HVAC Services in ' . $city_title,
							'description' => 'Professional heating, cooling, and air quality services for the ' . $city_title . ' community',
							'show_ctas'   => true,
							'bg_color'    => 'white',
						)
					);
					?>

					<!-- Featured Image (if exists) -->
					<?php if ( has_post_thumbnail() ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10 mb-6">
							<figure itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
								<?php
								the_post_thumbnail(
									'large',
									array(
										'class'    => 'w-full h-auto rounded-lg shadow-lg',
										'itemprop' => 'url',
										'alt'      => get_the_title(),
									)
								);
								?>
								<meta itemprop="width" content="1200">
								<meta itemprop="height" content="630">
							</figure>
						</section>
					<?php endif; ?>

					<!-- City Content (if exists) -->
					<?php if ( get_the_content() || $city_service_hours || $city_service_area_note ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10 mb-6">
							<div class="max-w-4xl mx-auto">
								<?php if ( get_the_content() ) : ?>
									<div class="prose prose-lg max-w-none mb-12">
										<?php the_content(); ?>
									</div>
								<?php endif; ?>

								<?php if ( $city_service_hours ) : ?>
									<div class="bg-gray-50 rounded-lg p-8 mb-8">
										<div class="text-xl font-bold text-gray-900 mb-4" role="heading" aria-level="4">Service Hours in <?php echo esc_html( $city_title ); ?></div>
										<div class="text-gray-700">
											<?php echo wp_kses_post( $city_service_hours ); ?>
										</div>
									</div>
								<?php endif; ?>

								<?php if ( $city_service_area_note ) : ?>
									<div class="bg-blue-50 rounded-lg p-8 mb-8">
										<div class="text-xl font-bold text-gray-900 mb-4" role="heading" aria-level="4">About Our <?php echo esc_html( $city_title ); ?> Service</div>
										<div class="text-gray-700">
											<?php echo wp_kses_post( $city_service_area_note ); ?>
										</div>
									</div>
								<?php endif; ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- Video Section (if exists) -->
					<?php if ( $city_video_url ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10 mb-6">
							<div class="max-w-4xl mx-auto">
								<h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">
									<?php echo esc_html( $city_video_title ?: 'HVAC Services in ' . $city_title ); ?>
								</h2>
								<div class="rounded-lg overflow-hidden shadow-xl">
									<?php echo sunnysideac_get_video_embed( $city_video_url, array( 'class' => 'w-full aspect-video' ) ); ?>
								</div>
								<?php if ( $city_video_description ) : ?>
									<p class="mt-4 text-gray-600 text-center">
										<?php echo esc_html( $city_video_description ); ?>
									</p>
								<?php endif; ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- Services Available in This City -->
					<section class="bg-white rounded-[20px] p-6 md:p-10 mb-6">
						<div class="text-center mb-12">
							<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									Services Available in <?php echo esc_html( $city_title ); ?>
								</span>
							</h2>
							<p class="text-lg text-gray-600">Complete HVAC solutions for your home or business</p>
						</div>

						<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
							<?php
							$services = SUNNYSIDE_SERVICES_BY_CATEGORY;
							foreach ( $services as $category => $service_list ) :
								foreach ( $service_list as $service_name ) :
									?>
								<a href="<?php echo esc_url( home_url( sprintf( '/%s/%s', sanitize_title( $city_title ), sanitize_title( $service_name ) ) ) ); ?>"
									class="group block bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-lg">
									<!-- Icon Circle -->
									<div class="mb-4">
										<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
											<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr( sunnysideac_get_service_icon( $service_name ) ); ?>" />
											</svg>
										</div>
									</div>

									<!-- Service Content -->
									<div class="text-lg font-bold text-gray-900 mb-2 group-hover:text-orange-500" role="heading" aria-level="4">
										<?php echo esc_html( $service_name ); ?>
									</div>

									<p class="text-gray-600 text-sm mb-4">
										Professional <?php echo strtolower( esc_html( $service_name ) ); ?> services for <?php echo esc_html( $city_title ); ?> residents and businesses
									</p>

									<span class="inline-flex items-center text-orange-500 font-medium text-sm">
										Learn more
										<svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
										</svg>
									</span>
								</a>
									<?php
								endforeach;
							endforeach;
							?>
						</div>
					</section>

					<!-- Why Choose Us in This City -->
					<section class="bg-white rounded-[20px] p-6 md:p-10 mb-6">
						<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-16">
							<!-- Emergency Services -->
							<div class="bg-gray-50 rounded-lg p-8 text-center">
								<div class="h-16 w-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
									<svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
									</svg>
								</div>
								<div class="text-xl font-bold text-gray-900 mb-2" role="heading" aria-level="4">24/7 Emergency Service</div>
								<p class="text-gray-600">
									Fast response HVAC emergencies available around the clock in <?php echo esc_html( $city_title ); ?>
								</p>
							</div>

							<!-- Expert Technicians -->
							<div class="bg-gray-50 rounded-lg p-8 text-center">
								<div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
									<svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
									</svg>
								</div>
								<div class="text-xl font-bold text-gray-900 mb-2" role="heading" aria-level="4">Expert Technicians</div>
								<p class="text-gray-600">
									Licensed, insured professionals serving <?php echo esc_html( $city_title ); ?> with pride
								</p>
							</div>

							<!-- Local Service -->
							<div class="bg-gray-50 rounded-lg p-8 text-center">
								<div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
									<svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
									</svg>
								</div>
								<div class="text-xl font-bold text-gray-900 mb-2" role="heading" aria-level="4">Local Expertise</div>
								<p class="text-gray-600">
									Knowledge of <?php echo esc_html( $city_title ); ?>'s specific HVAC needs and climate challenges
								</p>
							</div>
						</div>
					</section>

					<!-- Nearby Cities -->
					<section class="bg-white rounded-[20px] p-6 md:p-10 mb-6">
						<div class="text-center mb-12">
							<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									Nearby Service Areas
								</span>
							</h2>
							<p class="text-lg text-gray-600">We also serve these surrounding communities</p>
						</div>

						<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
							<?php
							$current_city  = get_the_title();
							$nearby_cities = array_slice( SUNNYSIDE_SERVICE_AREAS, 0, 12 ); // Show first 12 cities

							foreach ( $nearby_cities as $city ) :
								if ( $city !== $current_city ) :
									?>
								<a href="<?php echo esc_url( home_url( sprintf( '/cities/%s', sanitize_title( $city ) ) ) ); ?>"
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
									<div class="text-lg font-bold text-gray-900 group-hover:text-orange-500" role="heading" aria-level="4">
										<?php echo esc_html( $city ); ?>
									</div>
								</a>
									<?php
								endif;
							endforeach;
							?>
						</div>

						<div class="text-center mt-8">
							<a href="<?php echo esc_url( home_url( '/cities' ) ); ?>"
								class="inline-flex items-center text-[#e5462f] font-medium hover:text-[#fb9939] transition-colors">
								View all service cities
								<svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
								</svg>
							</a>
						</div>
					</section>

					<!-- FAQ Section -->
					<?php
					// City-specific FAQs
					$city_faqs = array(
						array(
							'question' => 'Do you provide HVAC services in ' . $city_title . '?',
							'answer'   => 'Yes! We proudly serve ' . $city_title . ' with comprehensive HVAC services including air conditioning repair, installation, heating services, and preventive maintenance. Our technicians are familiar with the specific climate needs of ' . $city_title . ' and respond quickly to service calls in your area.',
						),
						array(
							'question' => 'What is the average response time for service calls in ' . $city_title . '?',
							'answer'   => 'We typically respond to service calls in ' . $city_title . ' within 2-4 hours during business hours. For emergency HVAC situations, we offer 24/7 emergency service with even faster response times to ensure your comfort is restored as quickly as possible.',
						),
						array(
							'question' => 'How often should ' . $city_title . ' residents service their AC units?',
							'answer'   => 'Due to South Florida's hot and humid climate, we recommend ' . $city_title . ' residents service their AC units at least twice per year - once before cooling season in spring and once in fall. Regular maintenance helps prevent breakdowns during peak summer heat and extends your system's lifespan.',
						),
						array(
							'question' => 'What HVAC services do you offer in ' . $city_title . '?',
							'answer'   => 'We offer complete HVAC services in ' . $city_title . ' including AC installation and replacement, AC repair and maintenance, heating services, indoor air quality solutions, duct cleaning and sealing, thermostat installation, and emergency 24/7 repairs. Our licensed technicians handle all major HVAC brands.',
						),
						array(
							'question' => 'Are your technicians licensed to work in ' . $city_title . '?',
							'answer'   => 'Yes, all our HVAC technicians are fully licensed, insured, and certified to work throughout South Florida including ' . $city_title . '. We maintain all required local permits and follow all building codes specific to ' . $city_title . ' and the surrounding areas.',
						),
						array(
							'question' => 'Do you offer financing for HVAC systems in ' . $city_title . '?',
							'answer'   => 'Yes, we offer flexible financing options for ' . $city_title . ' residents to make AC installation and replacement affordable. We work with various lenders to provide competitive rates and terms that fit your budget. Ask about our current financing programs when you schedule your consultation.',
						),
					);

					get_template_part(
						'template-parts/faq-component',
						null,
						array(
							'faq_data'     => $city_faqs,
							'title'        => 'HVAC FAQs for ' . $city_title,
							'mobile_title' => 'FAQ',
							'subheading'   => 'Common Questions About Our ' . $city_title . ' HVAC Services',
							'description'  => 'Get answers to frequently asked questions about heating and cooling services in ' . $city_title . '.',
							'show_schema'  => true,
							'section_id'   => 'city-faq-section',
						)
					);
					?>

					<!-- CTA Section -->
					<section class="bg-gradient-to-r from-[#e5462f] to-[#fb9939] text-white rounded-[20px] p-6 md:p-10 mb-6">
						<div class="text-center">
							<h2 class="text-4xl font-bold mb-6">Ready for HVAC Service in <?php echo esc_html( $city_title ); ?>?</h2>
							<p class="text-xl text-[#ffc549] mb-8 max-w-2xl mx-auto">
								Call us now for fast, reliable HVAC service. Emergency repairs available 24/7.
							</p>
							<div class="flex flex-col sm:flex-row gap-4 justify-center">
								<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
									class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white text-[#e5462f] px-8 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
									<span class="text-base lg:text-lg font-medium whitespace-nowrap">
										<?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
									</span>
								</a>
								<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
									class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-transparent border-2 border-white text-white px-8 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
									<span class="text-base lg:text-lg font-medium whitespace-nowrap">
										Schedule Service
									</span>
								</a>
							</div>
						</div>
					</section>

				</article>
			</section>
		</div>
	</main>

<?php else : ?>
	<main class="min-h-screen bg-gray-50">
		<div class="px-5 lg:px-0 max-w-7xl mx-auto">
			<section class="py-16">
				<div class="container mx-auto px-4 text-center">
					<h1 class="text-4xl font-bold text-gray-900 mb-4">City Not Found</h1>
					<p class="text-xl text-gray-600 mb-8">The service area you're looking for doesn't exist.</p>
					<a href="<?php echo esc_url( home_url( '/cities' ) ); ?>" class="bg-[#e5462f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#fb9939] transition-colors inline-block">
						View All Cities
					</a>
				</div>
			</section>
		</div>
	</main>
<?php endif; ?>

<?php get_footer(); ?>
