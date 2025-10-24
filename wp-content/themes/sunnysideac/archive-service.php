<?php
/**
 * Template for displaying Service archive page
 * URL: /services
 */

// SEO Meta Configuration
$page_title       = 'HVAC Services in South Florida | AC Repair, Installation & Maintenance';
$page_description = 'Complete HVAC services including AC repair, installation, maintenance, heating, heat pumps, and indoor air quality solutions. Serving 30+ South Florida cities with 24/7 emergency service.';
$page_url         = home_url( '/services/' );
$page_image       = sunnysideac_asset_url( 'assets/images/home-page/hero/hero-image.png' );

// Build comprehensive service list for schema
$all_services = array();
foreach ( SUNNYSIDE_SERVICES_BY_CATEGORY as $services ) {
	$all_services = array_merge( $all_services, $services );
}

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

<!-- JSON-LD Structured Data: Service Catalog -->
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "ItemList",
	"name": "HVAC Services",
	"description": "Complete HVAC service offerings from Sunnyside AC",
	"numberOfItems": <?php echo count( $all_services ); ?>,
	"itemListElement": [
		<?php
		$service_items = array();
		$position      = 1;
		foreach ( $all_services as $service_name ) {
			$service_items[] = sprintf(
				'{"@type":"ListItem","position":%d,"item":{"@type":"Service","name":"%s","description":"Professional %s services across South Florida","provider":{"@id":"%s#organization"},"serviceType":"HVAC","areaServed":%s}}',
				$position++,
				esc_attr( $service_name ),
				esc_attr( strtolower( $service_name ) ),
				esc_url( home_url( '/' ) ),
				json_encode(
					array_map(
						function ( $city ) {
							return array(
								'@type' => 'City',
								'name'  => $city,
							);
						},
						SUNNYSIDE_SERVICE_AREAS
					)
				)
			);
		}
		echo implode( ',', $service_items );
		?>
	]
}
</script>

<!-- JSON-LD Structured Data: HVACBusiness -->
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
	"priceRange": "$$",
	"openingHoursSpecification": {
		"@type": "OpeningHoursSpecification",
		"dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday"],
		"opens": "00:00",
		"closes": "23:59"
	},
	"hasOfferCatalog": {
		"@type": "OfferCatalog",
		"name": "HVAC Services",
		"itemListElement": [
			<?php
			$offer_items = array();
			foreach ( $all_services as $service_name ) {
				$offer_items[] = sprintf(
					'{"@type":"Offer","itemOffered":{"@type":"Service","name":"%s","serviceType":"HVAC"}}',
					esc_attr( $service_name )
				);
			}
			echo implode( ',', $offer_items );
			?>
		]
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
			"name": "Services",
			"item": "<?php echo esc_url( $page_url ); ?>"
		}
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
			"name": "What HVAC services does Sunnyside AC provide?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "Sunnyside AC provides comprehensive HVAC services including air conditioning repair, AC installation and replacement, AC maintenance, heating repair, heating installation, heat pumps, ductless mini-split systems, indoor air quality solutions, and water heater services throughout South Florida."
			}
		},
		{
			"@type": "Question",
			"name": "Do you offer emergency AC repair services?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "Yes! We provide 24/7 emergency air conditioning repair services across all our South Florida service areas. We understand AC emergencies can happen anytime, especially during Florida's hot summers, and our technicians are ready to help day or night."
			}
		},
		{
			"@type": "Question",
			"name": "How often should I schedule AC maintenance?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "We recommend scheduling professional AC maintenance at least twice a year - once before the cooling season (spring) and once before the heating season (fall). Regular maintenance helps prevent breakdowns, improves efficiency, extends system life, and maintains warranty coverage."
			}
		},
		{
			"@type": "Question",
			"name": "What areas does Sunnyside AC serve?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "Sunnyside AC serves over 30 cities across South Florida including Miami, Fort Lauderdale, Boca Raton, West Palm Beach, Pembroke Pines, Miramar, Hollywood, Weston, Coral Springs, Plantation, and many more. Contact us to confirm service availability in your specific area."
			}
		},
		{
			"@type": "Question",
			"name": "How long does AC installation take?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "Most residential AC installations can be completed in 1-2 days, depending on the system complexity and any necessary modifications to ductwork or electrical systems. Our technicians will provide a detailed timeline during your consultation and work efficiently to minimize disruption to your home."
			}
		},
		{
			"@type": "Question",
			"name": "Do you offer financing for HVAC services?",
			"acceptedAnswer": {
				"@type": "Answer",
				"text": "Yes, we offer flexible financing options to make HVAC services more affordable. Contact us for details on current financing programs, payment plans, and any available promotions or seasonal offers."
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
							'name' => 'Services',
							'url'  => '',
						),
					),
					'title'       => 'Our HVAC Services',
					'description' => 'Professional heating, cooling, and air quality solutions for your home or business across South Florida',
					'show_ctas'   => true,
					'bg_color'    => 'gradient',
				)
			);
			?>
			<!-- Services Categories -->
			<?php
			// Group services by category
			$service_categories = SUNNYSIDE_SERVICES_BY_CATEGORY;

			if ( ! empty( $service_categories ) ) :
				foreach ( $service_categories as $category_key => $services ) :
					$category_label = ucwords( str_replace( '_', ' ', $category_key ) );
					?>
					<section class="services-category bg-white rounded-[20px] p-6 md:p-10">
						<header class="text-center mb-8">
							<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									<?php echo esc_html( $category_label ); ?>
								</span>
							</h2>
							<p class="text-lg text-gray-600">
								Expert <?php echo esc_html( strtolower( $category_label ) ); ?> services for your home and business
							</p>
						</header>

						<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
							<?php
							foreach ( $services as $service_name ) :
								$service_slug = sanitize_title( $service_name );
								$service_url  = home_url( sprintf( SUNNYSIDE_SERVICE_URL_PATTERN, $service_slug ) );
								$icon_path    = sunnysideac_get_service_icon( $service_name );
								?>
								<a href="<?php echo esc_url( $service_url ); ?>"
									class="group block bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-lg">
									<!-- Icon Circle -->
									<div class="mb-4">
										<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
											<svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr( $icon_path ); ?>" />
											</svg>
										</div>
									</div>

									<!-- Service Content -->
									<h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500">
										<?php echo esc_html( $service_name ); ?>
									</h3>

									<p class="text-gray-600 text-sm mb-4">
										Professional <?php echo esc_html( strtolower( $service_name ) ); ?> services across South Florida
									</p>

									<span class="inline-flex items-center text-orange-500 font-medium text-sm">
										Learn More
										<svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
										</svg>
									</span>
								</a>
							<?php endforeach; ?>
						</div>
					</section>
					<?php
				endforeach;
			else :
				// Fallback to default WP query if no categories defined
				?>
				<section class="services-fallback bg-white rounded-[20px] p-6 md:p-10">
					<header class="text-center mb-8">
						<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
							<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
								All Our Services
							</span>
						</h2>
						<p class="text-lg text-gray-600">
							Complete HVAC solutions for your comfort
						</p>
					</header>

					<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
						<?php if ( have_posts() ) : ?>
							<?php
							while ( have_posts() ) :
								the_post();
								?>
								<a href="<?php the_permalink(); ?>"
									class="group block bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-lg">
									<h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-orange-500">
										<?php the_title(); ?>
									</h3>
									<?php if ( has_excerpt() ) : ?>
										<p class="text-gray-600 text-sm mb-4">
											<?php echo get_the_excerpt(); ?>
										</p>
									<?php endif; ?>

									<span class="inline-flex items-center text-orange-500 font-medium text-sm">
										Learn More
										<svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
										</svg>
									</span>
								</a>
							<?php endwhile; ?>
						<?php else : ?>
							<div class="col-span-full text-center py-12">
								<p class="text-xl text-gray-600">No services found.</p>
							</div>
						<?php endif; ?>
					</div>
				</section>
			<?php endif; ?>

			<!-- FAQ Section -->
			<?php
			// Archive Services FAQ data
			$archive_services_faqs = array(
				array(
					'question' => 'What HVAC services does Sunnyside AC provide?',
					'answer'   => 'Sunnyside AC provides comprehensive HVAC services including air conditioning repair, AC installation and replacement, AC maintenance, heating repair, heating installation, heat pumps, ductless mini-split systems, indoor air quality solutions, and water heater services throughout South Florida.',
				),
				array(
					'question' => 'Do you offer emergency AC repair services?',
					'answer'   => 'Yes! We provide 24/7 emergency air conditioning repair services across all our South Florida service areas. We understand AC emergencies can happen anytime, especially during Florida\'s hot summers, and our technicians are ready to help day or night.',
				),
				array(
					'question' => 'How often should I schedule AC maintenance?',
					'answer'   => 'We recommend scheduling professional AC maintenance at least twice a year - once before the cooling season (spring) and once before the heating season (fall). Regular maintenance helps prevent breakdowns, improves efficiency, extends system life, and maintains warranty coverage.',
				),
				array(
					'question' => 'What areas does Sunnyside AC serve?',
					'answer'   => 'Sunnyside AC serves over 30 cities across South Florida including Miami, Fort Lauderdale, Boca Raton, West Palm Beach, Pembroke Pines, Miramar, Hollywood, Weston, Coral Springs, Plantation, and many more. Contact us to confirm service availability in your specific area.',
				),
				array(
					'question' => 'How long does AC installation take?',
					'answer'   => 'Most residential AC installations can be completed in 1-2 days, depending on the system complexity and any necessary modifications to ductwork or electrical systems. Our technicians will provide a detailed timeline during your consultation and work efficiently to minimize disruption to your home.',
				),
				array(
					'question' => 'Do you offer financing for HVAC services?',
					'answer'   => 'Yes, we offer flexible financing options to make HVAC services more affordable. Contact us for details on current financing programs, payment plans, and any available promotions or seasonal offers.',
				),
			);

			get_template_part(
				'template-parts/faq-component',
				null,
				array(
					'faq_data'     => $archive_services_faqs,
					'title'        => 'Frequently Asked Questions',
					'mobile_title' => 'FAQ',
					'subheading'   => 'Common Questions About Our HVAC Services',
					'description'  => 'Get answers to frequently asked questions about our HVAC services throughout South Florida.',
					'show_schema'  => false, // Schema already added in page head
					'section_id'   => 'services-archive-faq-section',
				)
			);
			?>

			<!-- CTA Section -->
			<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center">
				<h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
					Need Help Choosing a Service?
				</h2>
				<p class="text-xl text-white mb-8 max-w-2xl mx-auto">
					Our expert team is here to help you find the perfect HVAC solution for your home or business
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
							Request a Quote
						</span>
					</a>
				</div>
			</section>

		</section>
	</div>

</main>

<?php get_footer(); ?>
