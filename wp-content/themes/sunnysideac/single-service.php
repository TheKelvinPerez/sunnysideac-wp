<?php
/**
 * Template Name: Single Service
 * Template for displaying individual service posts
 * URL: /services/{service-slug}/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$service_id    = get_the_ID();
	$service_title = get_the_title();

	// Get ACF fields
	$service_description = get_field( 'service_description', $service_id );
	$service_benefits    = get_field( 'service_benefits', $service_id );
	$service_process     = get_field( 'service_process', $service_id );
	$service_faqs        = get_field( 'service_faqs', $service_id );

	// SEO Variables
	$page_title    = $service_title . ' - Sunnyside AC';
	$meta_desc     = $service_description ? wp_trim_words( $service_description, 20 ) : 'Expert ' . strtolower( $service_title ) . ' services in South Florida. Licensed technicians, same-day service, 24/7 emergency repairs.';
	$canonical_url = get_permalink();
	$og_image      = has_post_thumbnail() ? get_the_post_thumbnail_url( $service_id, 'large' ) : get_template_directory_uri() . '/assets/images/default-og.jpg';

	?>

	<!DOCTYPE html>
	<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- SEO Meta Tags -->
		<title><?php echo esc_html( $page_title ); ?></title>
		<meta name="description" content="<?php echo esc_attr( $meta_desc ); ?>">
		<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">
		<link rel="canonical" href="<?php echo esc_url( $canonical_url ); ?>">

		<!-- Open Graph Meta Tags -->
		<meta property="og:locale" content="en_US">
		<meta property="og:type" content="article">
		<meta property="og:title" content="<?php echo esc_attr( $page_title ); ?>">
		<meta property="og:description" content="<?php echo esc_attr( $meta_desc ); ?>">
		<meta property="og:url" content="<?php echo esc_url( $canonical_url ); ?>">
		<meta property="og:site_name" content="Sunnyside AC">
		<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
		<meta property="og:image:width" content="1200">
		<meta property="og:image:height" content="630">

		<!-- Twitter Card Meta Tags -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:title" content="<?php echo esc_attr( $page_title ); ?>">
		<meta name="twitter:description" content="<?php echo esc_attr( $meta_desc ); ?>">
		<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">

		<?php wp_head(); ?>

		<!-- JSON-LD Structured Data -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@graph": [
				{
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
							"item": "<?php echo esc_url( home_url( '/services/' ) ); ?>"
						},
						{
							"@type": "ListItem",
							"position": 3,
							"name": <?php echo wp_json_encode( $service_title, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"item": "<?php echo esc_url( $canonical_url ); ?>"
						}
					]
				},
				{
					"@type": "LocalBusiness",
					"name": "Sunnyside AC",
					"image": "<?php echo esc_url( get_template_directory_uri() . '/assets/images/social/social-preview-hero.jpg' ); ?>",
					"telephone": "<?php echo esc_js( SUNNYSIDE_PHONE_SCHEMA ); ?>",
					"address": {
						"@type": "PostalAddress",
						"streetAddress": "6609 Emerald Lake Dr",
						"addressLocality": "Miramar",
						"addressRegion": "FL",
						"postalCode": "33023",
						"addressCountry": "US"
					},
					"url": "<?php echo esc_url( home_url( '/' ) ); ?>",
					"priceRange": "$$",
					"openingHours": "Mo-Su 00:00-23:59",
					"areaServed": "Florida"
				},
				{
					"@type": "Service",
					"serviceType": <?php echo wp_json_encode( $service_title, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
					"provider": {
						"@type": "LocalBusiness",
						"name": "Sunnyside AC",
						"image": "<?php echo esc_url( get_template_directory_uri() . '/assets/images/social/social-preview-hero.jpg' ); ?>",
						"telephone": "<?php echo esc_js( SUNNYSIDE_PHONE_SCHEMA ); ?>",
						"priceRange": "$$",
						"address": {
							"@type": "PostalAddress",
							"streetAddress": <?php echo wp_json_encode( SUNNYSIDE_ADDRESS_STREET, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"addressLocality": <?php echo wp_json_encode( SUNNYSIDE_ADDRESS_CITY, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"addressRegion": <?php echo wp_json_encode( SUNNYSIDE_ADDRESS_STATE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"postalCode": <?php echo wp_json_encode( SUNNYSIDE_ADDRESS_ZIP, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"addressCountry": "US"
						}
					}
				}
				<?php if ( $service_faqs ) : ?>
				,
				{
					"@type": "FAQPage",
					"mainEntity": [
						<?php foreach ( $service_faqs as $index => $faq ) : ?>
						{
							"@type": "Question",
							"name": <?php echo wp_json_encode( $faq['question'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"acceptedAnswer": {
								"@type": "Answer",
								"text": <?php echo wp_json_encode( wp_strip_all_tags( $faq['answer'] ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>
							}
						}<?php echo $index < count( $service_faqs ) - 1 ? ',' : ''; ?>
						<?php endforeach; ?>
					]
				}
				<?php endif; ?>
				<?php if ( $service_process ) : ?>
				,
				{
					"@type": "HowTo",
					"name": <?php echo wp_json_encode( 'Our ' . $service_title . ' Process', JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
					"step": [
						<?php foreach ( $service_process as $index => $step ) : ?>
						{
							"@type": "HowToStep",
							"position": <?php echo $index + 1; ?>,
							"name": <?php echo wp_json_encode( $step['title'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"text": <?php echo wp_json_encode( $step['description'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>
						}<?php echo $index < count( $service_process ) - 1 ? ',' : ''; ?>
						<?php endforeach; ?>
					]
				}
				<?php endif; ?>
			]
		}
		</script>
	</head>

	<body <?php body_class(); ?>>

	<?php get_header(); ?>

	<main class="min-h-screen bg-gray-50" role="main" itemscope itemtype="https://schema.org/Service">

		<!-- Container matching front-page style -->
		<div class="lg:px-0 max-w-7xl mx-auto">
			<section class="flex gap-10 flex-col">

				<!-- Hero Section with Service Title & CTA -->
				<article id="post-<?php the_ID(); ?>" <?php post_class( 'service-page' ); ?>>

					<!-- Page Header with Featured Image Background -->
					<?php
					// Page breadcrumbs
					$breadcrumbs = array(
						array(
							'name' => 'Home',
							'url'  => home_url( '/' ),
						),
						array(
							'name' => 'Services',
							'url'  => home_url( '/services/' ),
						),
						array(
							'name' => $service_title,
							'url'  => '',
						),
					);

					get_template_part(
						'template-parts/page-header',
						null,
						array(
							'breadcrumbs'      => $breadcrumbs,
							'title'            => $service_title,
							'description'      => 'Expert ' . strtolower( $service_title ) . ' services throughout South Florida',
							'show_ctas'        => true,
							'bg_color'         => 'white', // This is fallback if no featured image
							'featured_image_id' => $service_id, // Pass service post ID for featured image
						)
					);
					?>

					<!-- Main Service Description -->
					<?php if ( $service_description ) : ?>
						<div class="service-description bg-white rounded-[20px] p-6 md:p-10 mb-6" itemprop="description">
							<div class="prose prose-lg max-w-none">
								<?php echo wp_kses_post( $service_description ); ?>
							</div>
						</div>
					<?php endif; ?>

					<!-- Service Benefits -->
					<?php if ( $service_benefits ) : ?>
						<section class="service-benefits bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="benefits-heading">
							<header class="text-center mb-8">
								<h2 id="benefits-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
									Why Choose Our <?php echo esc_html( $service_title ); ?> Services
								</h2>
								<p class="text-lg text-gray-600">
									Service You Can Trust, Comfort You Deserve
								</p>
							</header>

							<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
								<?php foreach ( $service_benefits as $index => $benefit ) : ?>
									<article class="group bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
										<!-- Number Badge -->
										<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300 mb-4">
											<span class="text-xl font-bold text-orange-500">
												<?php echo $index + 1; ?>
											</span>
										</div>

										<!-- Benefit Text -->
										<div class="text-lg font-semibold text-gray-900 mb-2" role="heading" aria-level="4">
											<?php echo esc_html( $benefit['benefit'] ); ?>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- Service Process -->
					<?php if ( $service_process ) : ?>
						<section class="service-process bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="process-heading" itemscope itemtype="https://schema.org/HowTo">
							<header class="text-center mb-12">
								<h2 id="process-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4" itemprop="name">
									Our <?php echo esc_html( $service_title ); ?> Process
								</h2>
								<p class="text-lg text-gray-600">
									Your Comfort, Our Process
								</p>
							</header>

							<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-<?php echo min( count( $service_process ), 4 ); ?> gap-6">
								<?php foreach ( $service_process as $index => $step ) : ?>
									<article class="group" itemprop="step" itemscope itemtype="https://schema.org/HowToStep">
										<meta itemprop="position" content="<?php echo $index + 1; ?>">

										<div class="bg-gray-50 rounded-2xl p-8 text-center transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg h-full flex flex-col items-center">
											<!-- Step Number in Circle -->
											<div class="mb-6">
												<div class="relative inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
													<span class="text-3xl font-bold text-orange-500">
														<?php echo $index + 1; ?>
													</span>
												</div>
											</div>

											<!-- Step Content -->
											<div class="text-xl font-bold text-gray-900 mb-3" itemprop="name" role="heading" aria-level="4">
												<?php echo esc_html( $step['title'] ); ?>
											</div>

											<p class="text-base text-gray-600 leading-relaxed" itemprop="text">
												<?php echo esc_html( $step['description'] ); ?>
											</p>
										</div>
									</article>
								<?php endforeach; ?>
							</div>
						</section>
					<?php endif; ?>

					<!-- FAQs -->
					<?php if ( $service_faqs ) : ?>
						<?php
						// Transform ACF FAQ data to match component format
						$formatted_faqs = array_map(
							function ( $faq ) {
								return array(
									'question' => $faq['question'],
									'answer'   => wp_strip_all_tags( $faq['answer'] ),
								);
							},
							$service_faqs
						);

						get_template_part(
							'template-parts/faq-component',
							null,
							array(
								'faq_data'     => $formatted_faqs,
								'title'        => 'Frequently Asked Questions',
								'mobile_title' => 'FAQ',
								"subheading"   => "Got Questions About " . $service_title . "? We've Got Answers!",
								'description'  => 'Find answers to common questions about our ' . strtolower( $service_title ) . ' services.',
								'show_schema'  => false, // Schema already added in <head>
								'section_id'   => 'service-faq-section',
							)
						);
						?>
					<?php endif; ?>

					<!-- Cities We Serve This Service -->
					<section class="cities-served bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="cities-heading">
						<header class="text-center mb-8">
							<h2 id="cities-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Cities We Serve
							</h2>
							<p class="text-lg text-gray-600">
								Expert <?php echo esc_html( strtolower( $service_title ) ); ?> services across South Florida
							</p>
						</header>

						<nav aria-label="Service areas">
							<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
								<?php foreach ( SUNNYSIDE_SERVICE_AREAS as $city ) : ?>
									<?php
									// Get city post for featured image
									$city_post = get_page_by_path( sanitize_title( $city ), OBJECT, 'city' );
									$service_slug = get_post_field( 'post_name', $service_id );
									$city_service_url = home_url( '/' . sanitize_title( $city ) . '/' . $service_slug . '/' );

									get_template_part(
										'template-parts/city-card',
										null,
										array(
											'city_name'    => $city,
											'city_slug'    => sanitize_title( $city ),
											'city_url'     => $city_service_url,
											'card_size'    => 'archive',
											'city_post_id' => $city_post ? $city_post->ID : null,
											'description'  => $service_title,
										)
									);
									?>
								<?php endforeach; ?>
							</div>
						</nav>
					</section>

					<!-- Call to Action -->
					<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center" aria-labelledby="cta-heading">
						<h2 id="cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
							Ready to Get Started?
						</h2>
						<p class="text-xl text-white mb-8 max-w-2xl mx-auto">
							Contact us today for expert <?php echo strtolower( $service_title ); ?> services
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

				</article>

			</section>
		</div>

	</main>

	<?php get_footer(); ?>

	</body>
	</html>

<?php else : ?>

	<!-- 404 if no post found -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">
			<div class="bg-white rounded-[20px] p-10 text-center">
				<h1 class="text-4xl font-bold text-gray-900 mb-4">Service Not Found</h1>
				<p class="text-lg text-gray-600 mb-8">The service you're looking for doesn't exist or has been removed.</p>
				<a href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-white font-medium hover:opacity-90">
					View All Services
				</a>
			</div>
		</section>
	</div>

<?php endif; ?>