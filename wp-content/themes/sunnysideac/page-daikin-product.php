<?php
/**
 * Template Name: Daikin Product Page
 * Template for displaying individual Daikin product pages
 * URL: /daikin/{product-slug}/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$page_id    = get_the_ID();
	$page_title = get_the_title();
	$page_slug  = get_post_field( 'post_name', $page_id );

	// Get ACF fields (will be added later if needed)
	$hero_image        = get_field( 'hero_image', $page_id );
	$subtitle          = get_field( 'subtitle', $page_id );
	$short_description = get_field( 'short_description', $page_id );
	$key_features      = get_field( 'key_features', $page_id );
	$product_benefits  = get_field( 'product_benefits', $page_id );
	$technical_specs   = get_field( 'technical_specs', $page_id );

	// Manually build key_features array if ACF doesn't return it
	if ( ! $key_features || ! is_array( $key_features ) ) {
		$feature_count = get_post_meta( $page_id, 'key_features', true );
		if ( $feature_count && is_numeric( $feature_count ) ) {
			$key_features = array();
			for ( $i = 0; $i < intval( $feature_count ); $i++ ) {
				$feature_title       = get_post_meta( $page_id, 'key_features_' . $i . '_title', true );
				$feature_description = get_post_meta( $page_id, 'key_features_' . $i . '_description', true );
				$feature_icon        = get_post_meta( $page_id, 'key_features_' . $i . '_icon', true );

				if ( $feature_title ) {
					$key_features[] = array(
						'title'       => $feature_title,
						'description' => $feature_description,
						'icon'        => $feature_icon,
					);
				}
			}
		}
	}

	// Fallback for subtitle and short_description if ACF doesn't return them
	if ( ! $subtitle ) {
		$subtitle = get_post_meta( $page_id, 'subtitle', true );
	}
	if ( ! $short_description ) {
		$short_description = get_post_meta( $page_id, 'short_description', true );
	}

	// Find product data from constants
	$product_data = null;
	if ( defined( 'SUNNYSIDE_DAIKIN_PRODUCTS' ) ) {
		foreach ( SUNNYSIDE_DAIKIN_PRODUCTS as $product ) {
			if ( $product['slug'] === $page_slug ) {
				$product_data = $product;
				break;
			}
		}
	}

	// Fallback subtitle and short description
	if ( ! $subtitle && $product_data ) {
		$subtitle = 'Reliable and Efficient HVAC Solutions for Your Home';
	}
	if ( ! $short_description && $product_data ) {
		$short_description = $product_data['description'];
	}

	// SEO Variables
	$seo_title       = $page_title . ' | Expert Service & Installation | Sunnyside AC';
	$seo_description = 'Professional ' . $page_title . ' service, installation, and repair in South Florida. Factory-trained technicians, genuine parts, and 24/7 emergency support. ' . $short_description;
	$page_url        = get_permalink();
	$page_image      = has_post_thumbnail() ? get_the_post_thumbnail_url( $page_id, 'large' ) : ( $hero_image ?: sunnysideac_asset_url( 'assets/images/home-page/hero/hero-image.png' ) );

	// Page breadcrumbs
	$breadcrumbs = array(
		array(
			'name' => 'Home',
			'url'  => home_url( '/' ),
		),
		array(
			'name' => 'Brands',
			'url'  => home_url( '/brands/' ),
		),
		array(
			'name' => 'Daikin',
			'url'  => home_url( '/brands/daikin/' ),
		),
		array(
			'name' => $page_title,
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
				"name": "Brands",
				"item": "<?php echo esc_url( home_url( '/brands/' ) ); ?>"
			},
			{
				"@type": "ListItem",
				"position": 3,
				"name": "Daikin",
				"item": "<?php echo esc_url( home_url( '/brands/daikin/' ) ); ?>"
			},
			{
				"@type": "ListItem",
				"position": 4,
				"name": <?php echo wp_json_encode( $page_title, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
				"item": "<?php echo esc_url( $page_url ); ?>"
			}
		]
	}
	</script>

	<!-- JSON-LD Structured Data: Product -->
	<script type="application/ld+json">
	{
		"@context": "https://schema.org",
		"@type": "Product",
		"name": <?php echo wp_json_encode( $page_title, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
		"brand": {
			"@type": "Brand",
			"name": "Daikin"
		},
		"description": <?php echo wp_json_encode( $seo_description, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
		"image": "<?php echo esc_url( $page_image ); ?>",
		"url": "<?php echo esc_url( $page_url ); ?>",
		"aggregateRating": {
			"@type": "AggregateRating",
			"ratingValue": "5.0",
			"reviewCount": "127",
			"bestRating": "5",
			"worstRating": "1"
		},
		"offers": {
			"@type": "AggregateOffer",
			"availability": "https://schema.org/InStock",
			"priceCurrency": "USD",
			"lowPrice": "2500",
			"highPrice": "12000",
			"offerCount": "15"
		}
	}
	</script>

	<main class="min-h-screen bg-gray-50" role="main">
		<div class="lg:px-0 max-w-7xl mx-auto">
			<section class="">
				<article class="flex gap-10 flex-col" id="post-<?php the_ID(); ?>" <?php post_class( 'daikin-product-page' ); ?>>

					<!-- Page Header with Breadcrumbs -->
					<?php
					get_template_part(
						'template-parts/page-header',
						null,
						array(
							'breadcrumbs' => $breadcrumbs,
							'title'       => $page_title,
							'description' => $subtitle ?: 'Expert service, installation, and repair in South Florida',
							'show_ctas'   => true,
							'bg_color'    => 'white',
						)
					);
					?>

					<!-- Daikin Product Submenu -->
					<?php get_template_part( 'template-parts/daikin-submenu' ); ?>

					<!-- Hero Section with Image -->
					<?php if ( $hero_image || $short_description ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10">
							<div class="max-w-6xl mx-auto">
								<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
									<!-- Text Content -->
									<div class="order-2 lg:order-1">
										<?php if ( $subtitle ) : ?>
											<h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-6">
												<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
													<?php echo esc_html( $subtitle ); ?>
												</span>
											</h2>
										<?php endif; ?>

										<?php if ( $short_description ) : ?>
											<p class="text-lg text-gray-700 mb-6 leading-relaxed">
												<?php echo wp_kses_post( $short_description ); ?>
											</p>
										<?php endif; ?>

										<!-- CTA Buttons -->
										<div class="flex flex-col sm:flex-row gap-4">
											<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
												class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#e5462f] to-[#fb9939] px-8 py-4 text-white font-bold transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
												<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
												</svg>
												Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
											</a>

											<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
												class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-8 py-4 text-white font-bold transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:outline-none">
												Schedule Service
											</a>
										</div>
									</div>

									<!-- Product Image -->
									<?php if ( $hero_image ) : ?>
										<div class="order-1 lg:order-2">
											<img src="<?php echo esc_url( $hero_image ); ?>"
												alt="<?php echo esc_attr( $page_title ); ?>"
												class="w-full h-auto rounded-2xl shadow-lg"
												loading="lazy">
										</div>
									<?php endif; ?>
								</div>
							</div>
						</section>
					<?php endif; ?>

					<!-- Page Content -->
					<?php if ( get_the_content() ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10">
							<div class="max-w-4xl mx-auto prose prose-lg max-w-none">
								<?php
								$content = get_the_content();
								// Convert markdown headers to HTML
								$content = preg_replace('/^# (.+)$/m', '<h1>$1</h1>', $content);
								$content = preg_replace('/^## (.+)$/m', '<h2>$1</h2>', $content);
								$content = preg_replace('/^### (.+)$/m', '<h3>$1</h3>', $content);
								// Convert bold text
								$content = preg_replace('/\*\*(.+?)\*\*/', '<strong>$1</strong>', $content);
								// Convert double newlines to paragraphs
								$content = wpautop($content);
								echo $content;
								?>
							</div>
						</section>
					<?php endif; ?>

					<!-- Key Features Section (if defined) -->
					<?php if ( $key_features && is_array( $key_features ) ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10">
							<div class="max-w-6xl mx-auto">
								<div class="text-center mb-12">
									<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
										<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
											Why Choose <?php echo esc_html( $page_title ); ?>?
										</span>
									</h2>
									<p class="text-lg text-gray-600">
										<?php
										$product_name_parts = explode( ' ', $page_title );
										$short_name         = end( $product_name_parts );
										echo 'Daikin ' . esc_html( $short_name ) . ' systems are known for their innovative features, energy efficiency, and superior comfort!';
										?>
									</p>
								</div>

								<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
									<?php foreach ( $key_features as $feature ) : ?>
										<div class="bg-gray-50 rounded-2xl p-8">
											<?php if ( ! empty( $feature['icon'] ) ) : ?>
												<div class="mb-4">
													<div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
														<svg class="h-8 w-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
															<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?php echo esc_attr( $feature['icon'] ); ?>" />
														</svg>
													</div>
												</div>
											<?php endif; ?>

											<?php if ( ! empty( $feature['title'] ) ) : ?>
												<h3 class="text-xl font-bold text-gray-900 mb-3">
													<?php echo esc_html( $feature['title'] ); ?>
												</h3>
											<?php endif; ?>

											<?php if ( ! empty( $feature['description'] ) ) : ?>
												<p class="text-gray-600 leading-relaxed">
													<?php echo wp_kses_post( $feature['description'] ); ?>
												</p>
											<?php endif; ?>
										</div>
									<?php endforeach; ?>
								</div>
							</div>
						</section>
					<?php endif; ?>

					<!-- Technical Specifications (if defined) -->
					<?php if ( $technical_specs ) : ?>
						<section class="bg-white rounded-[20px] p-6 md:p-10">
							<div class="max-w-4xl mx-auto">
								<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-8 text-center">
									<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
										Technical Specifications
									</span>
								</h2>
								<div class="prose prose-lg max-w-none">
									<?php echo wp_kses_post( $technical_specs ); ?>
								</div>
							</div>
						</section>
					<?php endif; ?>

					<!-- Services Available -->
					<section class="bg-white rounded-[20px] p-6 md:p-10">
						<div class="text-center mb-12">
							<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									Services We Offer
								</span>
							</h2>
							<p class="text-lg text-gray-600">Complete HVAC solutions for your Daikin system</p>
						</div>

						<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
							<?php
							$daikin_services = array(
								'ac-installation'   => 'AC Installation',
								'ac-repair'        => 'AC Repair',
								'emergency-service' => 'Emergency Service',
								'ac-replacement'   => 'AC Replacement',
								'warranty-service' => 'Warranty Service',
								'ac-maintenance'   => 'AC Maintenance',
							);

							foreach ( $daikin_services as $service_slug => $service_name ) :
								// Get service post ID for featured image
								$service_post = get_page_by_path( $service_slug, OBJECT, 'service' );
								$service_url = $service_post ? get_permalink( $service_post->ID ) : home_url( sprintf( '/services/%s/', $service_slug ) );
								$service_post_id = $service_post ? $service_post->ID : null;

								get_template_part(
									'template-parts/service-card',
									null,
									array(
										'service_name'    => $service_name,
										'service_slug'    => $service_slug,
										'service_url'     => $service_url,
										'service_post_id' => $service_post_id,
										'card_size'       => 'featured',
										'show_button'     => true,
										'button_text'     => 'Learn More',
										'description'     => 'Expert ' . strtolower( esc_html( $service_name ) ) . ' for Daikin systems',
									)
								);
							endforeach;
							?>
						</div>
					</section>

					<!-- FAQ Section -->
					<?php
					// Product-specific FAQs
					$product_faqs = array(
						array(
							'question' => 'What is ' . $page_title . '?',
							'answer'   => $short_description ?: 'This Daikin product offers superior performance, energy efficiency, and reliable comfort for your home.',
						),
						array(
							'question' => 'Do you install ' . $page_title . '?',
							'answer'   => 'Yes! We are certified to install ' . $page_title . ' throughout South Florida. Our factory-trained technicians ensure proper installation according to Daikin specifications for optimal performance and warranty coverage.',
						),
						array(
							'question' => 'How energy efficient is this system?',
							'answer'   => 'Daikin systems are among the most energy-efficient HVAC solutions available. They use advanced inverter technology to reduce energy consumption while maintaining consistent comfort. Many models qualify for utility rebates and tax credits.',
						),
						array(
							'question' => 'What warranty does Daikin provide?',
							'answer'   => 'Daikin offers comprehensive warranty coverage on their systems. Warranty terms vary by product line, but typically include extended coverage on major components. We handle all warranty claims and work directly with Daikin on your behalf.',
						),
						array(
							'question' => 'How often should this system be serviced?',
							'answer'   => 'We recommend professional maintenance at least twice per year - once before the cooling season and once before winter. Regular maintenance by certified technicians helps prevent breakdowns, maintains efficiency, and keeps your warranty valid.',
						),
						array(
							'question' => 'Do you service all Daikin products?',
							'answer'   => 'Yes, we service all Daikin HVAC systems including inverter air conditioners, VRV systems, ductless mini-splits, heat pumps, furnaces, and package units. Our technicians are factory-trained on all Daikin product lines.',
						),
					);

					get_template_part(
						'template-parts/faq-component',
						null,
						array(
							'faq_data'     => $product_faqs,
							'title'        => 'Frequently Asked Questions',
							'mobile_title' => 'FAQ',
							'subheading'   => 'Common Questions About ' . $page_title,
							'description'  => 'Get answers to frequently asked questions about ' . $page_title . ' service and installation.',
							'show_schema'  => true,
							'section_id'   => 'daikin-product-faq-section',
						)
					);
					?>

					<!-- CTA Section -->
					<section class="bg-gradient-to-r from-[#e5462f] to-[#fb9939] text-white rounded-[20px] p-6 md:p-10">
						<div class="text-center">
							<h2 class="text-3xl md:text-4xl font-bold mb-6">
								Need <?php echo esc_html( $page_title ); ?> Service?
							</h2>
							<p class="text-xl mb-8 max-w-2xl mx-auto">
								Call us now for expert <?php echo esc_html( $page_title ); ?> service, installation, or repair. Factory-trained technicians ready 24/7.
							</p>
							<div class="flex flex-col sm:flex-row gap-4 justify-center">
								<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
									class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none shadow-lg">
									<svg class="h-5 w-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
									</svg>
									<span class="text-lg font-bold text-orange-500 whitespace-nowrap">
										Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
									</span>
								</a>

								<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
									class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none shadow-lg">
									<svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
										<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
									</svg>
									<span class="text-lg font-bold text-white whitespace-nowrap">
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
					<h1 class="text-4xl font-bold text-gray-900 mb-4">Page Not Found</h1>
					<p class="text-xl text-gray-600 mb-8">The product you're looking for doesn't exist.</p>
					<a href="<?php echo esc_url( home_url( '/brands/daikin/' ) ); ?>" class="bg-[#e5462f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#fb9939] transition-colors inline-block">
						View All Daikin Products
					</a>
				</div>
			</section>
		</div>
	</main>
<?php endif; ?>

<?php get_footer(); ?>
