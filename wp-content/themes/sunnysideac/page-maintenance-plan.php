<?php
/**
 * Template Name: Maintenance Plan
 * Template for displaying maintenance plan page
 * URL: /maintenance-plan/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$page_id    = get_the_ID();
	$page_title = get_the_title();

	// Maintenance Plan Data
	$maintenance_benefits = array(
		array(
			'title'       => 'Priority Service',
			'description' => 'Get priority scheduling and faster response times for all your HVAC needs.',
		),
		array(
			'title'       => 'Extended Equipment Life',
			'description' => 'Regular maintenance extends the lifespan of your HVAC system by years.',
		),
		array(
			'title'       => 'Lower Energy Bills',
			'description' => 'Well-maintained systems run more efficiently, reducing monthly energy costs.',
		),
		array(
			'title'       => 'Fewer Breakdowns',
			'description' => 'Preventive maintenance catches small issues before they become major problems.',
		),
		array(
			'title'       => 'Peace of Mind',
			'description' => 'Know your system is running safely and efficiently all year round.',
		),
		array(
			'title'       => 'Warranty Protection',
			'description' => 'Maintain manufacturer warranties with regular professional service.',
		),
	);

	$maintenance_process = array(
		array(
			'title'       => 'System Inspection',
			'description' => 'Comprehensive evaluation of your entire HVAC system components.',
		),
		array(
			'title'       => 'Cleaning & Tune-Up',
			'description' => 'Professional cleaning and calibration of all system components.',
		),
		array(
			'title'       => 'Performance Testing',
			'description' => 'Test system performance, efficiency, and safety controls.',
		),
		array(
			'title'       => 'Detailed Report',
			'description' => 'Receive a complete report on system condition and recommendations.',
		),
	);

	$maintenance_faqs = array(
		array(
			'question' => 'How often should I schedule HVAC maintenance?',
			'answer'   => 'We recommend bi-annual maintenance - once in spring for your cooling system and once in fall for your heating system. Regular maintenance ensures optimal performance and extends equipment life.',
		),
		array(
			'question' => 'What does a maintenance plan include?',
			'answer'   => 'Our maintenance plan includes comprehensive system inspection, cleaning of all components, performance testing, filter replacement, lubrication of moving parts, thermostat calibration, and a detailed service report with recommendations.',
		),
		array(
			'question' => 'How much can I save with a maintenance plan?',
			'answer'   => 'Customers with maintenance plans typically save 15-20% on energy bills, avoid expensive emergency repairs, and extend their equipment lifespan by 3-5 years. Plus, plan members receive discounts on repairs and priority service.',
		),
		array(
			'question' => 'Is a maintenance plan worth the cost?',
			'answer'   => 'Absolutely. The cost of one emergency repair often exceeds a full year of maintenance plan coverage. Plus, you\'ll benefit from improved efficiency, lower energy bills, and the peace of mind that comes with a well-maintained system.',
		),
		array(
			'question' => 'Do you offer maintenance plans for both residential and commercial systems?',
			'answer'   => 'Yes, we offer customized maintenance plans for both residential and commercial HVAC systems. Our commercial plans are tailored to your specific equipment and business needs.',
		),
		array(
			'question' => 'What happens if you find issues during maintenance?',
			'answer'   => 'If we discover any issues during maintenance, we\'ll provide you with a detailed report and transparent quote for repairs. As a maintenance plan member, you\'ll receive priority scheduling and a discount on any necessary repairs.',
		),
	);

	// SEO Variables
	$page_title    = 'HVAC Maintenance Plans - Sunnyside AC';
	$meta_desc     = 'Protect your investment with our comprehensive HVAC maintenance plans. Regular tune-ups, priority service, and peace of mind for homeowners in South Florida.';
	$canonical_url = get_permalink();
	$og_image      = has_post_thumbnail() ? get_the_post_thumbnail_url( $page_id, 'large' ) : sunnysideac_asset_url( 'assets/images/default-og.jpg' );

	// Service slug for city links
	$service_slug = 'maintenance-plan';
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
						"name": "Maintenance Plan",
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
				"serviceType": "HVAC Maintenance Plan",
				"provider": {
					"@type": "LocalBusiness",
					"name": "Sunnyside AC"
				},
				"description": "Comprehensive HVAC maintenance plans including regular inspections, priority service, and extended equipment protection."
			}
			<?php if ( $maintenance_process ) : ?>
			,
			{
				"@type": "HowTo",
				"name": "Our HVAC Maintenance Process",
				"step": [
					<?php foreach ( $maintenance_process as $index => $step ) : ?>
					{
						"@type": "HowToStep",
						"position": <?php echo $index + 1; ?>,
						"name": "<?php echo esc_js( $step['title'] ); ?>",
						"text": "<?php echo esc_js( $step['description'] ); ?>"
					}<?php echo $index < count( $maintenance_process ) - 1 ? ',' : ''; ?>
					<?php endforeach; ?>
				]
			}
			<?php endif; ?>
			<?php if ( $maintenance_faqs ) : ?>
			,
			{
				"@type": "FAQPage",
				"mainEntity": [
					<?php
					$faq_count = count( $maintenance_faqs );
					foreach ( $maintenance_faqs as $index => $faq ) :
						?>
					{
						"@type": "Question",
						"name": "<?php echo esc_js( $faq['question'] ); ?>",
						"acceptedAnswer": {
							"@type": "Answer",
							"text": "<?php echo esc_js( wp_strip_all_tags( $faq['answer'] ) ); ?>"
						}
					}<?php echo $index < $faq_count - 1 ? ',' : ''; ?>
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
		<section>

			<!-- Hero Section with Maintenance Plan Title & CTA -->
			<article class="flex gap-10 flex-col" id="post-<?php the_ID(); ?>" <?php post_class( 'maintenance-plan-page' ); ?>>

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
								<span itemprop="name" class="font-semibold text-orange-500">Maintenance Plan</span>
								<meta itemprop="position" content="2">
							</li>
						</ol>
					</nav>

					<!-- Main Title with Gradient -->
					<div class="text-center mb-8">
						<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4" itemprop="name">
							<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
								HVAC Maintenance Plans
							</span>
						</h1>

						<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
							Protect your investment and ensure year-round comfort with our comprehensive HVAC maintenance plans
						</p>
					</div>

					<!-- Featured Image -->
					<?php if ( has_post_thumbnail() ) : ?>
						<figure class="mt-8 mb-8" itemprop="image" itemscope itemtype="https://schema.org/ImageObject">
							<?php
							the_post_thumbnail(
								'large',
								array(
									'class'    => 'w-full h-auto rounded-2xl shadow-lg',
									'itemprop' => 'url',
									'alt'      => esc_attr( 'HVAC maintenance plan services in South Florida' ),
								)
							);
							?>
							<meta itemprop="width" content="1200">
							<meta itemprop="height" content="630">
						</figure>
					<?php endif; ?>

					<!-- CTA Buttons -->
					<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
						<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none"
							aria-label="Call to schedule maintenance plan - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
							<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
								Schedule Maintenance Now
							</span>
						</a>

						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
							<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
								Get a Free Quote
							</span>
						</a>
					</div>
				</header>

				<!-- Benefits Section -->
				<section class="benefits bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="benefits-heading">
					<header class="text-center mb-8">
						<h2 id="benefits-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
							Why Choose Our Maintenance Plans
						</h2>
						<p class="text-lg text-gray-600">
							Expert Service, Long-Lasting Protection
						</p>
					</header>

					<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
						<?php foreach ( $maintenance_benefits as $index => $benefit ) : ?>
							<article class="group bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
								<!-- Number Badge -->
								<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300 mb-4">
									<span class="text-xl font-bold text-orange-500">
										<?php echo $index + 1; ?>
									</span>
								</div>

								<!-- Benefit Text -->
								<div class="text-lg font-semibold text-gray-900 mb-2" role="heading" aria-level="4">
									<?php echo esc_html( $benefit['title'] ); ?>
								</div>
								<p class="text-gray-600">
									<?php echo esc_html( $benefit['description'] ); ?>
								</p>
							</article>
						<?php endforeach; ?>
					</div>
				</section>

				<!-- Process Section -->
				<section class="process bg-white rounded-[20px] p-6 md:p-10 mb-6"
						aria-labelledby="process-heading"
						itemscope
						itemtype="https://schema.org/HowTo">
					<header class="text-center mb-12">
						<h2 id="process-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4" itemprop="name">
							Our Maintenance Process
						</h2>
						<p class="text-lg text-gray-600">
							Comprehensive Care for Your System
						</p>
					</header>

					<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
						<?php foreach ( $maintenance_process as $index => $step ) : ?>
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

				<!-- FAQ Section -->
				<?php
				// Transform FAQ data to match component format
				$formatted_faqs = array_map(
					function ( $faq ) {
						return array(
							'question' => $faq['question'],
							'answer'   => wp_strip_all_tags( $faq['answer'] ),
						);
					},
					$maintenance_faqs
				);

				get_template_part(
					'template-parts/faq-component',
					null,
					array(
						'faq_data'     => $formatted_faqs,
						'title'        => 'Frequently Asked Questions',
						'mobile_title' => 'FAQ',
						'subheading'   => 'Got Questions About Maintenance Plans?',
						'description'  => 'Find answers to common questions about our HVAC maintenance plans and services.',
						'show_schema'  => false, // Schema already added in <head>
						'section_id'   => 'maintenance-plan-faq-section',
					)
				);
				?>

				<!-- Cities Served Section - COMMENTED OUT FOR NOW -->
				<!-- Uncomment this section when ready to target location-based keywords
				<section class="cities-served bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="cities-heading">
					<header class="text-center mb-8">
						<h2 id="cities-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
							<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
								Maintenance Plans in Your Area
							</span>
						</h2>
						<p class="text-lg text-gray-600">
							Expert maintenance services across South Florida
						</p>
					</header>

					<nav aria-label="Service areas">
						<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
							<?php foreach ( SUNNYSIDE_SERVICE_AREAS as $city ) : ?>
								<a href="<?php echo esc_url( home_url( sprintf( '/%s/%s/', sanitize_title( $city ), $service_slug ) ) ); ?>"
									class="group block bg-gray-50 rounded-2xl p-4 text-center transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
									<div class="font-semibold text-gray-900 group-hover:text-orange-500" role="heading" aria-level="4">
										<?php echo esc_html( $city ); ?>
									</div>
									<p class="text-sm text-gray-600 mt-1">
										Maintenance Plans Available
									</p>
								</a>
							<?php endforeach; ?>
						</div>
					</nav>
				</section>
				-->

				<!-- Final CTA Section -->
				<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center" aria-labelledby="cta-heading">
					<h2 id="cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
						Protect Your HVAC Investment Today
					</h2>
					<p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
						Join our maintenance plan and enjoy priority service, lower energy bills, and peace of mind
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
				<h1 class="text-4xl font-bold text-gray-900 mb-4">Page Not Found</h1>
				<p class="text-lg text-gray-600 mb-8">The maintenance plan page you're looking for doesn't exist or has been removed.</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-white font-medium hover:opacity-90">
					Return to Home
				</a>
			</div>
		</section>
	</div>

<?php endif; ?>
