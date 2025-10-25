<?php
/**
 * Template Name: Single Brand
 * Template for displaying individual brand posts
 * URL: /brands/{brand-slug}/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$brand_id    = get_the_ID();
	$brand_title = get_the_title();

	// Get ACF fields (you can add these later if needed)
	$brand_description = get_field( 'brand_description', $brand_id );
	$brand_history     = get_field( 'brand_history', $brand_id );
	$brand_specialties = get_field( 'brand_specialties', $brand_id );
	$brand_warranty    = get_field( 'brand_warranty_info', $brand_id );
	$brand_website     = get_field( 'brand_website', $brand_id );

	// SEO Variables
	$page_title       = $brand_title . ' HVAC Service & Repair | Sunnyside AC';
	$page_description = 'Expert ' . $brand_title . ' HVAC service, repair, and installation throughout South Florida. Factory-trained technicians, warranty service, and 24/7 emergency repairs.';
	$page_url         = get_permalink();
	$page_image       = has_post_thumbnail() ? get_the_post_thumbnail_url( $brand_id, 'large' ) : sunnysideac_asset_url( 'assets/images/home-page/hero/hero-image.png' );

	?>

	<!-- SEO Meta Tags -->
	<meta name="description" content="<?php echo esc_attr( $page_description ); ?>">
	<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
	<link rel="canonical" href="<?php echo esc_url( $page_url ); ?>">

	<!-- Open Graph / Facebook -->
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo esc_url( $page_url ); ?>">
	<meta property="og:title" content="<?php echo esc_attr( $page_title ); ?>">
	<meta property="og:description" content="<?php echo esc_attr( $page_description ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $page_image ); ?>">
	<meta property="og:locale" content="en_US">
	<meta property="og:site_name" content="Sunnyside AC">

	<!-- Twitter -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:url" content="<?php echo esc_url( $page_url ); ?>">
	<meta name="twitter:title" content="<?php echo esc_attr( $page_title ); ?>">
	<meta name="twitter:description" content="<?php echo esc_attr( $page_description ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( $page_image ); ?>">

	<!-- Geographic Meta Tags -->
	<meta name="geo.region" content="US-FL">
	<meta name="geo.placename" content="South Florida">

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
				"name": "Brands We Service",
				"item": "<?php echo esc_url( home_url( '/brands/' ) ); ?>"
			},
			{
				"@type": "ListItem",
				"position": 3,
				"name": "<?php echo esc_js( $brand_title ); ?>",
				"item": "<?php echo esc_url( $page_url ); ?>"
			}
		]
	}
	</script>

	<!-- JSON-LD Structured Data: Product/Brand -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Brand",
		"name": "<?php echo esc_js( $brand_title ); ?>",
		"url": "<?php echo esc_url( $page_url ); ?>",
		<?php if ( has_post_thumbnail() ) : ?>
		"logo": "<?php echo esc_url( $page_image ); ?>",
		"image": "<?php echo esc_url( $page_image ); ?>",
		<?php endif; ?>
		"description": "<?php echo esc_js( $page_description ); ?>"
	}
	</script>

	<!-- JSON-LD Structured Data: Service Provider -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "HVACBusiness",
		"@id": "<?php echo esc_url( home_url( '/' ) ); ?>#organization",
		"name": "Sunnyside AC",
		"image": "<?php echo esc_url( $page_image ); ?>",
		"logo": {
			"@type": "ImageObject",
			"url": "<?php echo esc_url( sunnysideac_asset_url( 'assets/images/logo.png' ) ); ?>"
		},
		"description": "<?php echo esc_attr( $page_description ); ?>",
		"url": "<?php echo esc_url( home_url( '/' ) ); ?>",
		"telephone": "<?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>",
		"email": "<?php echo esc_attr( SUNNYSIDE_EMAIL_ADDRESS ); ?>",
		"address": {
			"@type": "PostalAddress",
			"streetAddress": "<?php echo esc_attr( SUNNYSIDE_ADDRESS_STREET ); ?>",
			"addressLocality": "<?php echo esc_attr( SUNNYSIDE_ADDRESS_CITY ); ?>",
			"addressRegion": "<?php echo esc_attr( SUNNYSIDE_ADDRESS_STATE ); ?>",
			"postalCode": "<?php echo esc_attr( SUNNYSIDE_ADDRESS_ZIP ); ?>",
			"addressCountry": "US"
		},
		"geo": {
			"@type": "GeoCoordinates",
			"latitude": "<?php echo esc_attr( SUNNYSIDE_LATITUDE ); ?>",
			"longitude": "<?php echo esc_attr( SUNNYSIDE_LONGITUDE ); ?>"
		},
		"priceRange": "$$",
		"openingHoursSpecification": {
			"@type": "OpeningHoursSpecification",
			"dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
			"opens": "00:00",
			"closes": "23:59"
		},
		"areaServed": [
			<?php
			$city_objects = array();
			foreach ( SUNNYSIDE_SERVICE_AREAS as $city ) {
				$city_objects[] = sprintf(
					'{"@type":"City","name":"%s"}',
					esc_attr( $city )
				);
			}
			echo implode( ',', $city_objects );
			?>
		],
		"sameAs": [
			"<?php echo esc_url( SUNNYSIDE_FACEBOOK_URL ); ?>",
			"<?php echo esc_url( SUNNYSIDE_INSTAGRAM_URL ); ?>",
			"<?php echo esc_url( SUNNYSIDE_TWITTER_URL ); ?>",
			"<?php echo esc_url( SUNNYSIDE_YOUTUBE_URL ); ?>",
			"<?php echo esc_url( SUNNYSIDE_LINKEDIN_URL ); ?>"
		]
	}
	</script>

	<main class="min-h-screen bg-gray-50" role="main">

		<!-- Container matching front-page style -->
		<div class="lg:px-0 max-w-7xl mx-auto">
			<section class="">

				<!-- Hero Section with Brand Title & CTA -->
				<article class="flex gap-10 flex-col" id="post-<?php the_ID(); ?>" <?php post_class( 'brand-page' ); ?>>

					<?php
					// Page Header with Breadcrumbs (using template part)
					get_template_part(
						'template-parts/page-header',
						null,
						array(
							'breadcrumbs' => array(
								array(
									'name' => 'Home',
									'url'  => home_url( '/' ),
								),
								array(
									'name' => 'Brands We Service',
									'url'  => home_url( '/brands/' ),
								),
								array(
									'name' => $brand_title,
									'url'  => '',
								),
							),
							'title'       => $brand_title . ' HVAC Service',
							'description' => 'Expert service, repair, and installation for ' . $brand_title . ' HVAC systems across South Florida',
							'show_ctas'   => true,
							'bg_color'    => 'white',
						)
					);
					?>

					<!-- Featured Image / Brand Logo (if exists) -->
					<?php if ( has_post_thumbnail() ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10">
							<div class="max-w-3xl mx-auto">
								<figure class="flex items-center justify-center">
									<?php if ( $brand_website ) : ?>
										<a href="<?php echo esc_url( $brand_website ); ?>"
											target="_blank"
											rel="noopener noreferrer"
											class="transition-transform hover:scale-105 duration-300"
											title="Visit <?php echo esc_attr( $brand_title ); ?> official website">
											<?php
											the_post_thumbnail(
												'large',
												array(
													'class' => 'max-h-64 w-auto object-contain',
													'alt'   => $brand_title . ' HVAC systems',
												)
											);
											?>
										</a>
									<?php else : ?>
										<?php
										the_post_thumbnail(
											'large',
											array(
												'class' => 'max-h-64 w-auto object-contain',
												'alt'   => $brand_title . ' HVAC systems',
											)
										);
										?>
									<?php endif; ?>
								</figure>

								<?php if ( $brand_website ) : ?>
									<div class="mt-8 text-center">
										<a href="<?php echo esc_url( $brand_website ); ?>"
											target="_blank"
											rel="noopener noreferrer"
											class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
											<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
												Visit Official <?php echo esc_html( $brand_title ); ?> Website
											</span>
											<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
											</svg>
										</a>
									</div>
								<?php endif; ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- Brand Overview / Content -->
					<?php if ( get_the_content() || $brand_description ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10">
							<div class="max-w-4xl mx-auto">
								<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
									<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
										About <?php echo esc_html( $brand_title ); ?>
									</span>
								</h2>

								<div class="prose prose-lg max-w-none">
									<?php
									if ( get_the_content() ) {
										the_content();
									} elseif ( $brand_description ) {
										echo wp_kses_post( $brand_description );
									}
									?>
								</div>
							</div>
						</section>
					<?php endif; ?>

					<!-- Brand History (if exists) -->
					<?php if ( $brand_history ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10">
							<div class="max-w-4xl mx-auto">
								<h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">
									<?php echo esc_html( $brand_title ); ?> History & Innovation
								</h2>
								<div class="prose prose-lg max-w-none">
									<?php echo wp_kses_post( $brand_history ); ?>
								</div>
							</div>
						</section>
					<?php endif; ?>

					<!-- Why Choose This Brand -->
					<section class="bg-white rounded-[20px] p-6 md:p-10">
						<div class="text-center mb-12">
							<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									Why Choose <?php echo esc_html( $brand_title ); ?>?
								</span>
							</h2>
							<p class="text-lg text-gray-600">Trusted quality and performance you can count on</p>
						</div>

						<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
							<!-- Factory-Trained Technicians -->
							<div class="bg-gray-50 rounded-lg p-8 text-center">
								<div class="h-16 w-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
									<svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
									</svg>
								</div>
								<div class="text-xl font-bold text-gray-900 mb-2" role="heading" aria-level="4">Factory-Trained Technicians</div>
								<p class="text-gray-600">
									Our team is certified and trained specifically on <?php echo esc_html( $brand_title ); ?> systems
								</p>
							</div>

							<!-- Genuine Parts -->
							<div class="bg-gray-50 rounded-lg p-8 text-center">
								<div class="h-16 w-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
									<svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
									</svg>
								</div>
								<div class="text-xl font-bold text-gray-900 mb-2" role="heading" aria-level="4">Genuine OEM Parts</div>
								<p class="text-gray-600">
									We use only authentic <?php echo esc_html( $brand_title ); ?> parts to maintain your warranty
								</p>
							</div>

							<!-- Warranty Service -->
							<div class="bg-gray-50 rounded-lg p-8 text-center">
								<div class="h-16 w-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
									<svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
									</svg>
								</div>
								<div class="text-xl font-bold text-gray-900 mb-2" role="heading" aria-level="4">Full Warranty Support</div>
								<p class="text-gray-600">
									We handle all <?php echo esc_html( $brand_title ); ?> warranty claims and manufacturer relations
								</p>
							</div>
						</div>
					</section>

					<!-- Services Available for This Brand -->
					<section class="bg-white rounded-[20px] p-6 md:p-10">
						<div class="text-center mb-12">
							<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									<?php echo esc_html( $brand_title ); ?> Services We Offer
								</span>
							</h2>
							<p class="text-lg text-gray-600">Complete HVAC solutions for your <?php echo esc_html( $brand_title ); ?> system</p>
						</div>

						<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
							<?php
							$services = SUNNYSIDE_SERVICES_BY_CATEGORY;
							foreach ( $services as $category => $service_list ) :
								foreach ( $service_list as $service_name ) :
									$service_slug = sanitize_title( $service_name );
									$service_url  = home_url( sprintf( '/services/%s/', $service_slug ) );
									?>
								<a href="<?php echo esc_url( $service_url ); ?>"
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
										Professional <?php echo strtolower( esc_html( $service_name ) ); ?> for <?php echo esc_html( $brand_title ); ?> systems
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

					<!-- Service Areas for This Brand -->
					<section class="bg-white rounded-[20px] p-6 md:p-10">
						<div class="text-center mb-12">
							<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									Where We Service <?php echo esc_html( $brand_title ); ?>
								</span>
							</h2>
							<p class="text-lg text-gray-600">Expert <?php echo esc_html( $brand_title ); ?> service across South Florida</p>
						</div>

						<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
							<?php
							$displayed_cities = array_slice( SUNNYSIDE_SERVICE_AREAS, 0, 16 ); // Show first 16 cities
							foreach ( $displayed_cities as $city ) :
								$city_slug = sanitize_title( $city );
								$city_url  = home_url( sprintf( '/cities/%s/', $city_slug ) );
								?>
								<a href="<?php echo esc_url( $city_url ); ?>"
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
							<?php endforeach; ?>
						</div>

						<div class="text-center mt-8">
							<a href="<?php echo esc_url( home_url( '/cities/' ) ); ?>"
								class="inline-flex items-center text-[#e5462f] font-medium hover:text-[#fb9939] transition-colors">
								View all service areas
								<svg class="ml-1 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
								</svg>
							</a>
						</div>
					</section>

					<!-- FAQ Section -->
					<?php
					// Brand-specific FAQs
					$brand_faqs = array(
						array(
							'question' => 'Do you service ' . $brand_title . ' HVAC systems?',
							'answer'   => 'Yes! We are certified to service, repair, and install ' . $brand_title . ' HVAC systems throughout South Florida. Our technicians receive specialized training on ' . $brand_title . ' products to ensure expert service and proper maintenance of your system.',
						),
						array(
							'question' => 'Are your technicians certified to work on ' . $brand_title . ' equipment?',
							'answer'   => 'Absolutely. Our team includes factory-trained and certified technicians specifically for ' . $brand_title . ' systems. This specialized training ensures we can properly diagnose, repair, and maintain your ' . $brand_title . ' HVAC equipment according to manufacturer specifications.',
						),
						array(
							'question' => 'Do you use genuine ' . $brand_title . ' parts?',
							'answer'   => 'Yes, we use only authentic OEM (Original Equipment Manufacturer) parts from ' . $brand_title . '. Using genuine parts ensures compatibility, maintains your warranty coverage, and provides the reliability and performance your ' . $brand_title . ' system was designed to deliver.',
						),
						array(
							'question' => 'Can you handle warranty service for my ' . $brand_title . ' system?',
							'answer'   => 'Yes, we handle all ' . $brand_title . ' warranty claims and work directly with the manufacturer on your behalf. If your system is still under manufacturer warranty, we\'ll ensure your repairs are properly documented and covered according to the warranty terms.',
						),
						array(
							'question' => 'How often should I service my ' . $brand_title . ' HVAC system?',
							'answer'   => 'We recommend servicing your ' . $brand_title . ' HVAC system at least twice per year - once before the cooling season in spring and once before winter. Regular maintenance by certified technicians helps prevent breakdowns, maintains efficiency, and keeps your warranty valid.',
						),
						array(
							'question' => 'Do you install new ' . $brand_title . ' systems?',
							'answer'   => 'Yes, we are authorized to install new ' . $brand_title . ' HVAC systems. Our certified technicians can help you select the right ' . $brand_title . ' system for your home or business and ensure proper installation for optimal performance and efficiency.',
						),
					);

					get_template_part(
						'template-parts/faq-component',
						null,
						array(
							'faq_data'     => $brand_faqs,
							'title'        => 'Frequently Asked Questions',
							'mobile_title' => 'FAQ',
							'subheading'   => 'Common Questions About ' . $brand_title . ' Service',
							'description'  => 'Get answers to frequently asked questions about our ' . $brand_title . ' HVAC services.',
							'show_schema'  => true,
							'section_id'   => 'brand-faq-section',
						)
					);
					?>

					<!-- CTA Section -->
					<section class="bg-gradient-to-r from-[#e5462f] to-[#fb9939] text-white rounded-[20px] p-6 md:p-10">
						<div class="text-center">
							<h2 class="text-4xl font-bold mb-6">Need <?php echo esc_html( $brand_title ); ?> HVAC Service?</h2>
							<p class="text-xl mb-8 max-w-2xl mx-auto">
								Call us now for expert <?php echo esc_html( $brand_title ); ?> service, repair, or installation. Factory-trained technicians ready 24/7.
							</p>
							<div class="flex flex-col sm:flex-row gap-4 justify-center">
								<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
									class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
									<span class="text-lg font-bold text-orange-500">
										Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
									</span>
								</a>

								<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
									class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
									<span class="text-lg font-bold text-white">
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
					<h1 class="text-4xl font-bold text-gray-900 mb-4">Brand Not Found</h1>
					<p class="text-xl text-gray-600 mb-8">The brand you're looking for doesn't exist.</p>
					<a href="<?php echo esc_url( home_url( '/brands/' ) ); ?>" class="bg-[#e5462f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#fb9939] transition-colors inline-block">
						View All Brands
					</a>
				</div>
			</section>
		</div>
	</main>
<?php endif; ?>

<?php get_footer(); ?>
