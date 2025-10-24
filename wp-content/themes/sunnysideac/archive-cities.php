<?php
/**
 * Template for displaying City archive page
 * URL: /cities
 */

// SEO Meta Configuration
$page_title       = 'HVAC Service Areas in South Florida | Sunnyside AC';
$page_description = 'Expert air conditioning and heating services across 30+ South Florida cities including Miami, Fort Lauderdale, Boca Raton, and West Palm Beach. 24/7 emergency HVAC repair.';
$page_url         = home_url( '/cities/' );
$page_image       = sunnysideac_asset_url( 'assets/images/home-page/areas-we-serve/map-background-place-holder.png' );

get_header();
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
<meta name="geo.position" content="26.1224;-80.1373">
<meta name="ICBM" content="26.1224, -80.1373">

<!-- JSON-LD Structured Data: LocalBusiness -->
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
		"latitude": "25.9860",
		"longitude": "-80.3035"
	},
	"areaServed": [
		<?php
		$city_objects = array();
		foreach ( SUNNYSIDE_SERVICE_AREAS as $city ) {
			$city_objects[] = sprintf(
				'{"@type":"City","name":"%s","@id":"%s"}',
				esc_attr( $city ),
				esc_url( home_url( '/cities/' . sanitize_title( $city ) . '/' ) )
			);
		}
		echo implode( ',', $city_objects );
		?>
	],
	"priceRange": "$$",
	"openingHoursSpecification": {
		"@type": "OpeningHoursSpecification",
		"dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
		"opens": "00:00",
		"closes": "23:59"
	},
	"sameAs": [
		"<?php echo esc_url( SUNNYSIDE_FACEBOOK_URL ); ?>",
		"<?php echo esc_url( SUNNYSIDE_INSTAGRAM_URL ); ?>",
		"<?php echo esc_url( SUNNYSIDE_TWITTER_URL ); ?>",
		"<?php echo esc_url( SUNNYSIDE_YOUTUBE_URL ); ?>",
		"<?php echo esc_url( SUNNYSIDE_LINKEDIN_URL ); ?>"
	]
}
</script>

<!-- JSON-LD Structured Data: Service -->
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "Service",
	"serviceType": "HVAC Services",
	"provider": {
		"@id": "<?php echo esc_url( home_url( '/' ) ); ?>#organization"
	},
	"areaServed": [
		<?php
		$service_areas = array();
		foreach ( SUNNYSIDE_SERVICE_AREAS as $city ) {
			$service_areas[] = sprintf(
				'{"@type":"City","name":"%s","containedInPlace":{"@type":"State","name":"Florida"}}',
				esc_attr( $city )
			);
		}
		echo implode( ',', $service_areas );
		?>
	],
	"hasOfferCatalog": {
		"@type": "OfferCatalog",
		"name": "HVAC Services",
		"itemListElement": [
			{"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Air Conditioning Repair"}},
			{"@type": "Offer", "itemOffered": {"@type": "Service", "name": "AC Installation"}},
			{"@type": "Offer", "itemOffered": {"@type": "Service", "name": "AC Maintenance"}},
			{"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Heating Repair"}},
			{"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Heat Pump Services"}},
			{"@type": "Offer", "itemOffered": {"@type": "Service", "name": "Indoor Air Quality"}}
		]
	}
}
</script>

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
			"item": "<?php echo esc_url( $page_url ); ?>"
		}
	]
}
</script>

<!-- JSON-LD Structured Data: ItemList (All Cities) -->
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "ItemList",
	"name": "South Florida HVAC Service Areas",
	"description": "Complete list of cities and communities served by Sunnyside AC",
	"numberOfItems": <?php echo count( SUNNYSIDE_SERVICE_AREAS ); ?>,
	"itemListElement": [
		<?php
		$city_list_items = array();
		$position        = 1;
		foreach ( SUNNYSIDE_SERVICE_AREAS as $city ) {
			$city_list_items[] = sprintf(
				'{"@type":"ListItem","position":%d,"name":"%s HVAC Services","url":"%s"}',
				$position++,
				esc_attr( $city ),
				esc_url( home_url( '/cities/' . sanitize_title( $city ) . '/' ) )
			);
		}
		echo implode( ',', $city_list_items );
		?>
	]
}
</script>

<!-- JSON-LD Structured Data: FAQPage -->
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "FAQPage",
	"mainEntity": [
		{
			"@type": "Question",
			"name": "What cities does Sunnyside AC serve in South Florida?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "Sunnyside AC proudly serves over 30 cities across South Florida including Miami, Fort Lauderdale, Boca Raton, West Palm Beach, Pembroke Pines, Miramar, Hollywood, Weston, Coral Springs, and many more. We offer 24/7 emergency HVAC services throughout our service area."
			}
		},
		{
			"@type": "Question",
			"name": "Do you offer 24/7 emergency AC repair in all service areas?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "Yes! Sunnyside AC provides 24/7 emergency air conditioning repair services across all our South Florida service areas. We understand that AC breakdowns don't wait for business hours, especially in Florida's hot climate."
			}
		},
		{
			"@type": "Question",
			"name": "How quickly can you respond to service calls in different cities?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "Response times vary by location, but we strive for same-day service in most of our primary service areas including Pembroke Pines, Miramar, Weston, Hollywood, Fort Lauderdale, and Miami. Contact us for specific availability in your area."
			}
		},
		{
			"@type": "Question",
			"name": "What HVAC services do you provide in South Florida?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "We provide comprehensive HVAC services including air conditioning repair, AC installation and replacement, routine maintenance, heating services, heat pump installation and repair, ductless mini-splits, indoor air quality solutions, and water heater services across all our South Florida service areas."
			}
		}
	]
}
</script>

<main class="min-h-screen bg-gray-50" role="main">
	<!-- Container matching front-page style -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">

			<?php
			// Page Header with Breadcrumbs
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
							'name' => 'Service Areas',
							'url'  => '',
						),
					),
					'title'       => 'Service Areas',
					'description' => 'Expert HVAC services throughout South Florida communities',
					'show_ctas'   => true,
					'bg_color'    => 'gradient',
				)
			);
			?>

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
					<?php foreach ( SUNNYSIDE_PRIORITY_CITIES as $city ) : ?>
						<?php
						// Check if this city has a city post
						$city_post = get_page_by_path( sanitize_title( $city ), OBJECT, 'city' );
						$city_url  = $city_post ? get_permalink( $city_post->ID ) : home_url( sprintf( '/cities/%s', sanitize_title( $city ) ) );
						?>
						<a href="<?php echo esc_url( $city_url ); ?>"
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
								<?php echo esc_html( $city ); ?>
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
						<?php
						while ( have_posts() ) :
							the_post();
							?>
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
							<p class="text-xl text-gray-600">No service cities found.</p>
						</div>
					<?php endif; ?>
				</div>

				<!-- Pagination -->
				<?php if ( have_posts() ) : ?>
					<div class="mt-12 text-center">
						<?php
						the_posts_pagination(
							array(
								'mid_size'  => 2,
								'prev_text' => '← Previous',
								'next_text' => 'Next →',
								'class'     => 'inline-flex gap-2',
							)
						);
						?>
					</div>
				<?php endif; ?>
			</section>

			<!-- FAQ Section -->
			<?php
			// Archive Cities FAQ data
			$archive_cities_faqs = array(
				array(
					'question' => 'What cities does Sunnyside AC serve in South Florida?',
					'answer'   => 'Sunnyside AC proudly serves over 30 cities across South Florida including Miami, Fort Lauderdale, Boca Raton, West Palm Beach, Pembroke Pines, Miramar, Hollywood, Weston, Coral Springs, and many more. We offer 24/7 emergency HVAC services throughout our service area.',
				),
				array(
					'question' => 'Do you offer 24/7 emergency AC repair in all service areas?',
					'answer'   => 'Yes! Sunnyside AC provides 24/7 emergency air conditioning repair services across all our South Florida service areas. We understand that AC breakdowns don\'t wait for business hours, especially in Florida\'s hot climate.',
				),
				array(
					'question' => 'How quickly can you respond to service calls in different cities?',
					'answer'   => 'Response times vary by location, but we strive for same-day service in most of our primary service areas including Pembroke Pines, Miramar, Weston, Hollywood, Fort Lauderdale, and Miami. Contact us for specific availability in your area.',
				),
				array(
					'question' => 'What HVAC services do you provide in South Florida?',
					'answer'   => 'We provide comprehensive HVAC services including air conditioning repair, AC installation and replacement, routine maintenance, heating services, heat pump installation and repair, ductless mini-splits, indoor air quality solutions, and water heater services across all our South Florida service areas.',
				),
			);

			get_template_part(
				'template-parts/faq-component',
				null,
				array(
					'faq_data'     => $archive_cities_faqs,
					'title'        => 'Frequently Asked Questions',
					'mobile_title' => 'FAQ',
					'subheading'   => 'Common Questions About Our South Florida Service Areas',
					'description'  => 'Find answers to frequently asked questions about our HVAC services throughout South Florida.',
					'show_schema'  => false, // Schema already added in page head
					'section_id'   => 'cities-archive-faq-section',
				)
			);
			?>

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