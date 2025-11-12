<?php
/**
 * Template for displaying Brand archive page
 * URL: /brands
 */

// SEO Meta Configuration
$page_title       = 'HVAC Brands We Service | Expert Repair for All Major Brands';
$page_description = 'Professional HVAC service and repair for all major brands including Daikin, Trane, Lennox, Carrier, Mitsubishi, and more. Expert technicians serving South Florida with 24/7 emergency service.';
$page_url         = home_url( '/brands/' );
$page_image       = sunnysideac_asset_url( 'assets/images/home-page/hero/hero-image.png' );

// Get all brands for schema
$brands_query = new WP_Query(
	array(
		'post_type'      => 'brand',
		'posts_per_page' => -1,
		'orderby'        => 'title',
		'order'          => 'ASC',
	)
);

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
			"item": "<?php echo esc_url( $page_url ); ?>"
		}
	]
}
</script>

<!-- JSON-LD Structured Data: ItemList (All Brands) -->
<script type="application/ld+json">
{
	"@context": "https://schema.org",
	"@type": "ItemList",
	"name": "HVAC Brands We Service",
	"description": "Complete list of HVAC brands serviced by Sunnyside AC",
	"numberOfItems": <?php echo $brands_query->found_posts; ?>,
	"itemListElement": [
		<?php
		$brand_list_items = array();
		$position         = 1;
		if ( $brands_query->have_posts() ) {
			while ( $brands_query->have_posts() ) {
				$brands_query->the_post();
				$brand_list_items[] = sprintf(
					'{"@type":"ListItem","position":%d,"name":"%s HVAC Service","url":"%s"}',
					$position++,
					esc_attr( get_the_title() ),
					esc_url( get_permalink() )
				);
			}
			wp_reset_postdata();
		}
		echo implode( ',', $brand_list_items );
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
							'name' => 'Brands We Service',
							'url'  => '',
						),
					),
					'title'       => 'Brands We Service',
					'description' => 'Expert service and repair for all major HVAC brands across South Florida',
					'show_ctas'   => true,
					'bg_color'    => 'gradient',
				)
			);
			?>

			<!-- Brands Grid Section -->
			<section class="brands-grid bg-white rounded-[20px] p-6 md:p-10">
				<header class="text-center mb-8">
					<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
						<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
							Trusted HVAC Brands
						</span>
					</h2>
					<p class="text-lg text-gray-600">
						Factory-trained technicians for all major HVAC manufacturers
					</p>
				</header>

				<?php
				// Reset query for brands display
				$brands_display_query = new WP_Query(
					array(
						'post_type'      => 'brand',
						'posts_per_page' => -1,
						'orderby'        => 'title',
						'order'          => 'ASC',
					)
				);

				if ( $brands_display_query->have_posts() ) :
					?>
					<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
						<?php
						while ( $brands_display_query->have_posts() ) :
							$brands_display_query->the_post();
							?>
							<?php
							get_template_part(
								'template-parts/brand-card',
								null,
								array(
									'brand_name'    => get_the_title(),
									'brand_slug'    => get_post_field( 'post_name', get_the_ID() ),
									'brand_url'     => get_permalink(),
									'brand_post_id' => get_the_ID(),
									'card_size'     => 'featured',
									'show_button'   => true,
									'button_text'   => 'View Products',
									'description'   => 'Expert service and repair',
								)
							);
							?>
						<?php endwhile; ?>
						<?php wp_reset_postdata(); ?>
					</div>
				<?php else : ?>
					<div class="text-center py-12">
						<p class="text-xl text-gray-600">No brands found.</p>
					</div>
				<?php endif; ?>
			</section>

			<!-- Featured Brands Section -->
			<section class="featured-brands bg-white rounded-[20px] p-6 md:p-10">
				<header class="text-center mb-8">
					<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
						<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
							We Service All Major Brands
						</span>
					</h2>
					<p class="text-lg text-gray-600">
						Comprehensive service and repair for industry-leading manufacturers
					</p>
				</header>

				<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
					<?php
					$major_brands = array(
						'Daikin',
						'Trane',
						'Lennox',
						'Carrier',
						'Mitsubishi',
						'Goodman',
						'Rheem',
						'York',
						'Bryant',
						'Ruud',
						'Amana',
						'American Standard',
					);

					foreach ( $major_brands as $brand ) :
						?>
						<div class="flex items-center justify-center bg-gray-50 rounded-xl p-4 text-center transition-all hover:bg-orange-50 hover:shadow-md">
							<span class="text-base font-semibold text-gray-700">
								<?php echo esc_html( $brand ); ?>
							</span>
						</div>
					<?php endforeach; ?>
				</div>
			</section>

			<!-- FAQ Section -->
			<?php
			// Archive Brands FAQ data
			$archive_brands_faqs = array(
				array(
					'question' => 'What HVAC brands does Sunnyside AC service?',
					"answer"   => "Sunnyside AC services all major HVAC brands including Daikin, Trane, Lennox, Carrier, Mitsubishi, Goodman, Rheem, York, Bryant, Ruud, Amana, American Standard, and many more. Our certified technicians have extensive experience with both residential and commercial HVAC systems across all manufacturers.",
				),
				array(
					'question' => 'Do you have certified technicians for specific brands?',
					"answer"   => "Yes! Our team includes factory-trained and certified technicians for major brands like Daikin, Trane, Carrier, and Lennox. This specialized training ensures we can properly diagnose, repair, and maintain your specific HVAC system according to manufacturer specifications.",
				),
				array(
					'question' => 'Can you service older or discontinued HVAC brands?',
					"answer"   => "Absolutely. Our experienced technicians can service older and discontinued HVAC brands. While parts may be harder to source for some older models, we have access to extensive parts networks and can often find alternatives or recommend cost-effective replacement options when needed.",
				),
				array(
					'question' => 'What brands do you recommend for new AC installations?',
					"answer"   => "We recommend several top-tier brands based on your specific needs and budget. Daikin, Trane, Lennox, and Carrier are excellent choices for reliability and efficiency. During your consultation, we'll discuss your requirements, budget, and preferences to help you select the best system for your home or business.",
				),
				array(
					'question' => 'Do you offer warranty service for different brands?',
					"answer"   => "Yes, we handle warranty service for all major HVAC brands. If your system is still under manufacturer warranty, we'll work directly with the manufacturer to ensure your repairs are covered. We also offer our own comprehensive service warranties on all installations and repairs we perform.",
				),
			);

			get_template_part(
				'template-parts/faq-component',
				null,
				array(
					'faq_data'     => $archive_brands_faqs,
					'title'        => 'Frequently Asked Questions',
					'mobile_title' => 'FAQ',
					'subheading'   => 'Common Questions About HVAC Brands We Service',
					'description'  => 'Get answers to frequently asked questions about the HVAC brands we service and repair.',
					'show_schema'  => true,
					'section_id'   => 'brands-archive-faq-section',
				)
			);
			?>

			<!-- CTA Section -->
			<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center">
				<h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
					Don't See Your Brand Listed?
				</h2>
				<p class="text-xl text-white mb-8 max-w-2xl mx-auto">
					We service all major HVAC brands. Contact us today to discuss your specific needs and schedule expert service for your system
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
