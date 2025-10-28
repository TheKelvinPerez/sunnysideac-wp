<?php
/**
 * Template Name: Single Service
 * Template for displaying individual service posts
 * URL: /services/{service-slug}/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$service_id = get_the_ID();
	$service_title = get_the_title();

	// Get ACF fields
	$service_description = get_field( 'service_description', $service_id );
	$service_benefits = get_field( 'service_benefits', $service_id );
	$service_process = get_field( 'service_process', $service_id );
	$service_faqs = get_field( 'service_faqs', $service_id );

	// SEO Variables
	$page_title = $service_title . ' - Sunnyside AC';
	$meta_desc = $service_description ? wp_trim_words( $service_description, 20 ) : 'Expert ' . strtolower( $service_title ) . ' services in South Florida. Licensed technicians, same-day service, 24/7 emergency repairs.';
	$canonical_url = get_permalink();
	$og_image = has_post_thumbnail() ? get_the_post_thumbnail_url( $service_id, 'large' ) : get_template_directory_uri() . '/assets/images/default-og.jpg';

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
							"name": "<?php echo esc_js( $service_title ); ?>",
							"item": "<?php echo esc_url( $canonical_url ); ?>"
						}
					]
				},
				{
					"@type": "LocalBusiness",
					"name": "Sunnyside AC",
					"telephone": "<?php echo esc_js( SUNNYSIDE_PHONE_DISPLAY ); ?>",
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
					"serviceType": "<?php echo esc_js( $service_title ); ?>",
					"provider": {
						"@type": "LocalBusiness",
						"name": "Sunnyside AC"
					}
					<?php if ( $service_faqs ) : ?>
					,
					{
						"@type": "FAQPage",
						"mainEntity": [
							<?php foreach ( $service_faqs as $index => $faq ) : ?>
							{
								"@type": "Question",
								"name": "<?php echo esc_js( $faq['question'] ); ?>",
								"acceptedAnswer": {
									"@type": "Answer",
									"text": "<?php echo esc_js( wp_strip_all_tags( $faq['answer'] ) ); ?>"
								}
							}<?php echo $index < count( $service_faqs ) - 1 ? ',' : ''; ?>
							<?php endforeach; ?>
						]
					}
					<?php endif; ?>
				}
				<?php if ( $service_process ) : ?>
				,
				{
					"@type": "HowTo",
					"name": "Our <?php echo esc_js( $service_title ); ?> Process",
					"step": [
						<?php foreach ( $service_process as $index => $step ) : ?>
						{
							"@type": "HowToStep",
							"position": <?php echo $index + 1; ?>,
							"name": "<?php echo esc_js( $step['title'] ); ?>",
							"text": "<?php echo esc_js( $step['description'] ); ?>"
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

					<!-- Page Header - Breadcrumbs & Title -->
					<header class="entry-header bg-white rounded-[20px] p-6 md:p-10 mb-6">
						<!-- Breadcrumbs -->
						<nav aria-label="Breadcrumb" class="mb-6 flex justify-center" itemscope itemtype="https://schema.org/BreadcrumbList">
							<ol class="flex flex-wrap items-center gap-2 text-sm text-gray-600">
								<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
									<a itemprop="item" href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-orange-500">
										<span itemprop="name">Home</span>
									</a>
									<meta itemprop="position" content="1">
								</li>
								<li class="text-gray-400">/</li>
								<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
									<a itemprop="item" href="<?php echo esc_url( home_url( '/services/' ) ); ?>" class="hover:text-orange-500">
										<span itemprop="name">Services</span>
									</a>
									<meta itemprop="position" content="2">
								</li>
								<li class="text-gray-400">/</li>
								<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
									<span itemprop="name" class="font-semibold text-orange-500"><?php echo esc_html( $service_title ); ?></span>
									<meta itemprop="position" content="3">
								</li>
							</ol>
						</nav>

						<!-- Main Title with Gradient -->
						<div class="text-center mb-8">
							<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4" itemprop="name">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									<?php echo esc_html( $service_title ); ?>
								</span>
							</h1>

							<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
								Expert <?php echo esc_html( strtolower( $service_title ) ); ?> services throughout South Florida
							</p>
						</div>

						<!-- CTA Buttons -->
						<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
							<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none"
								aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
								<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
									Schedule Service Now
								</span>
							</a>

							<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
								<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
									Get a Free Quote
								</span>
							</a>
						</div>

						<!-- Featured Image (if exists) -->
						<?php if ( has_post_thumbnail() ) : ?>
							<figure class="mt-8" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
								<?php
								the_post_thumbnail(
									'large',
									[
										'class'    => 'w-full h-auto rounded-2xl shadow-lg',
										'itemprop' => 'url',
										'alt'      => esc_attr( $service_title . ' services in South Florida' ),
									]
								);
								?>
								<meta itemprop="width" content="1200">
								<meta itemprop="height" content="630">
							</figure>
						<?php endif; ?>
					</header>

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
										<h3 class="text-lg font-semibold text-gray-900 mb-2">
											<?php echo esc_html( $benefit['benefit'] ); ?>
										</h3>
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
											<h3 class="text-xl font-bold text-gray-900 mb-3" itemprop="name">
												<?php echo esc_html( $step['title'] ); ?>
											</h3>

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
						<section class="service-faqs bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="faq-heading">
							<header class="text-center mb-8">
								<h2 id="faq-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
									Frequently Asked Questions
								</h2>
								<p class="text-lg text-gray-600">
									Got questions? We've got answers.
								</p>
							</header>

							<div class="faq-list space-y-4 max-w-4xl mx-auto">
								<?php foreach ( $service_faqs as $faq ) : ?>
									<details class="faq-item bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:bg-orange-50 group" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
										<summary class="font-semibold text-lg cursor-pointer hover:text-orange-500 flex justify-between items-center" itemprop="name">
											<span><?php echo esc_html( $faq['question'] ); ?></span>
											<svg class="w-6 h-6 transform transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
											</svg>
										</summary>
										<div class="faq-answer mt-4 text-gray-600 leading-relaxed border-t border-gray-200 pt-4" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
											<div itemprop="text">
												<?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
											</div>
										</div>
									</details>
								<?php endforeach; ?>
							</div>
						</section>
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
							<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
								<?php foreach ( SUNNYSIDE_SERVICE_AREAS as $city ) : ?>
									<a href="<?php echo esc_url( home_url( '/' . sanitize_title( $city ) . '/' . get_post_field( 'post_name', $service_id ) . '/' ) ); ?>"
									   class="group block bg-gray-50 rounded-2xl p-4 text-center transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
										<h3 class="font-semibold text-gray-900 group-hover:text-orange-500">
											<?php echo esc_html( $city ); ?>
										</h3>
										<p class="text-sm text-gray-600 mt-1">
											<?php echo esc_html( $service_title ); ?>
										</p>
									</a>
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