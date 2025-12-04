<?php
/**
 * Template Name: Warranty
 * Template for displaying warranty page
 * URL: /warranty/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$page_id    = get_the_ID();
	$page_title = get_the_title();

	// Warranty Coverage Data
	$warranty_coverage = array(
		array(
			'title'       => 'New Installation Warranty',
			'duration'    => '10 Years',
			'description' => 'Complete parts and labor warranty on all new HVAC system installations we perform.',
			'features'    => array(
				'Full parts replacement coverage',
				'Labor costs included',
				'Compressor protection',
				'24/7 emergency service covered'
			)
		),
		array(
			'title'       => 'Equipment Warranty',
			'duration'    => '5-10 Years',
			'description' => 'Manufacturer warranty on all HVAC equipment we install and service.',
			'features'    => array(
				'Brand-specific coverage',
				'Extended options available',
				'Transferable to new homeowners',
				'Priority service scheduling'
			)
		),
		array(
			'title'       => 'Workmanship Warranty',
			'duration'    => '2 Years',
			'description' => 'Our guarantee on the quality of our installation and repair work.',
			'features'    => array(
				'Installation quality guarantee',
				'Repair work coverage',
				'No-cost corrections',
				'Customer satisfaction promise'
			)
		),
		array(
			'title'       => 'Maintenance Plan Warranty',
			'duration'    => 'Ongoing',
			'description' => 'Enhanced coverage for customers on our maintenance plans.',
			'features'    => array(
				'Priority dispatch',
				'Discounted repairs',
				'Annual inspections',
				'Extended equipment life'
			)
		)
	);

	// Warranty Benefits
	$warranty_benefits = array(
		array(
			'title'       => 'Complete Peace of Mind',
			'description' => 'Rest easy knowing your investment is protected by comprehensive coverage.',
		),
		array(
			'title'       => 'No Hidden Costs',
			'description' => 'What\'s covered is what\'s covered - no surprise charges or hidden fees.',
		),
		array(
			'title'       => 'Priority Service',
			'description' => 'Warranty customers get priority scheduling for faster service when you need it.',
		),
		array(
			'title'       => 'Factory Authorized',
			'description' => 'We\'re authorized by major manufacturers to honor and process warranty claims.',
		),
		array(
			'title'       => 'Extended Coverage Options',
			'description' => 'Options to extend your warranty beyond standard manufacturer coverage.',
		),
		array(
			'title'       => 'Transferable Coverage',
			'description' => 'Many warranties can be transferred to new homeowners, adding value to your property.',
		)
	);

	// Warranty Process Steps
	$warranty_process = array(
		array(
			'title'       => 'Register Your Warranty',
			'description' => 'We handle all warranty registration paperwork for you when we install your system.',
		),
		array(
			'title'       => 'Regular Maintenance',
			'description' => 'Keep up with regular maintenance to maintain warranty coverage validity.',
		),
		array(
			'title'       => 'Document Everything',
			'description' => 'Keep all service records and documentation for warranty claims.',
		),
		array(
			'title'       => 'Quick Claims Processing',
			'description' => 'Contact us immediately for warranty issues - we handle the entire claims process.',
		)
	);

	// Warranty FAQs
	$warranty_faqs = array(
		array(
			'question' => 'What does a typical HVAC warranty cover?',
			'answer'   => 'HVAC warranties typically cover replacement parts for defective components and sometimes labor costs. Coverage varies by manufacturer and warranty type. Our installation warranty covers both parts and labor for 10 years.',
		),
		array(
			'question' => 'How long do HVAC warranties last?',
			'answer'   => 'Manufacturer warranties typically range from 5-10 years for parts, with some components like compressors having longer coverage. Our workmanship warranty lasts 2 years, and our installation warranty covers 10 years.',
		),
		array(
			'question' => 'What can void my HVAC warranty?',
			'answer'   => 'Common reasons for voided warranties include: lack of professional installation, improper maintenance, using non-approved parts, unregistered equipment, or damage from improper use. Regular professional maintenance helps maintain warranty validity.',
		),
		array(
			'question' => 'Do I need to register my warranty?',
			'answer'   => 'Yes, most manufacturers require warranty registration within a specific timeframe after installation. Don\'t worry - we handle all warranty registration for our customers to ensure proper coverage.',
		),
		array(
			'question' => 'Are warranty transfers available if I sell my home?',
			'answer'   => 'Many manufacturer warranties are transferable to new homeowners, which can add value to your property. There may be a small transfer fee required. We can help facilitate warranty transfers during real estate transactions.',
		),
		array(
			'question' => 'What\'s the difference between manufacturer and installation warranties?',
			'answer'   => 'Manufacturer warranties cover equipment defects and typically only cover parts. Installation warranties (like our 10-year coverage) cover both parts AND labor, and protect against installation errors. Having both provides complete protection.',
		),
		array(
			'question' => 'How do I file a warranty claim?',
			'answer'   => 'Simply call us at ' . SUNNYSIDE_PHONE_DISPLAY . ' if you experience issues with covered equipment. We\'ll diagnose the problem, determine warranty coverage, and handle the entire claims process with the manufacturer on your behalf.',
		),
		array(
			'question' => 'Does maintenance affect my warranty coverage?',
			'answer'   => 'Yes, most warranties require regular professional maintenance to remain valid. This is one reason our maintenance plans are so valuable - they ensure your equipment stays under warranty coverage while protecting your investment.',
		),
	);

	// SEO Variables
	$page_title    = 'HVAC Warranty Information - Sunnyside AC';
	$meta_desc     = 'Complete HVAC warranty coverage information from Sunnyside AC. Learn about our 10-year installation warranty, manufacturer warranties, and protection plans for South Florida homeowners.';
	$canonical_url = get_permalink();
	$og_image      = has_post_thumbnail() ? get_the_post_thumbnail_url( $page_id, 'large' ) : sunnysideac_asset_url( 'assets/images/default-og.jpg' );
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
							"name": "Warranty",
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
					"priceRange": "$",
					"openingHours": "Mo-Su 00:00-23:59",
					"areaServed": "Florida"
				},
				{
					"@type": "Service",
					"serviceType": "HVAC Warranty Services",
					"provider": {
						"@type": "LocalBusiness",
						"name": "Sunnyside AC",
						"image": "<?php echo esc_url( get_template_directory_uri() . '/assets/images/social/social-preview-hero.jpg' ); ?>",
						"telephone": "<?php echo esc_js( SUNNYSIDE_PHONE_SCHEMA ); ?>",
						"priceRange": "$",
						"address": {
							"@type": "PostalAddress",
							"streetAddress": <?php echo wp_json_encode( SUNNYSIDE_ADDRESS_STREET, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"addressLocality": <?php echo wp_json_encode( SUNNYSIDE_ADDRESS_CITY, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"addressRegion": <?php echo wp_json_encode( SUNNYSIDE_ADDRESS_STATE, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"postalCode": <?php echo wp_json_encode( SUNNYSIDE_ADDRESS_ZIP, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
							"addressCountry": "US"
						}
					},
					"description": "Comprehensive HVAC warranty coverage including installation warranty, manufacturer warranties, and maintenance plan protection."
				}
				<?php if ( $warranty_faqs ) : ?>
				,
				{
					"@type": "FAQPage",
					"mainEntity": [
						<?php
						$faq_count = count( $warranty_faqs );
						foreach ( $warranty_faqs as $index => $faq ) :
							?>
							{
								"@type": "Question",
								"name": <?php echo wp_json_encode( $faq['question'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>,
								"acceptedAnswer": {
									"@type": "Answer",
									"text": <?php echo wp_json_encode( wp_strip_all_tags( $faq['answer'] ), JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?>
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

				<!-- Hero Section with Warranty Title & CTA -->
				<article class="flex gap-10 flex-col" id="post-<?php the_ID(); ?>" <?php post_class( 'warranty-page' ); ?>>

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
									<span itemprop="name" class="font-semibold text-orange-500">Warranty</span>
									<meta itemprop="position" content="2">
								</li>
							</ol>
						</nav>

						<!-- Main Title with Gradient -->
						<div class="text-center mb-8">
							<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4" itemprop="name">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									HVAC Warranty Protection
								</span>
							</h1>

							<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
								Comprehensive warranty coverage for your peace of mind. From installation to equipment protection, we've got you covered.
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
										'alt'      => esc_attr( 'HVAC warranty protection services in South Florida' ),
									)
								);
								?>
								<meta itemprop="width" content="1200">
								<meta itemprop="height" content="630">
							</figure>
						<?php endif; ?>

						<!-- CTA Buttons -->
						<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
							<a href="#warranty-coverage"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
								<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
									View Coverage Options
								</span>
							</a>

							<a href="#warranty-claim"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
								<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
									File Warranty Claim
								</span>
							</a>
						</div>
					</header>

					<!-- Warranty Coverage Section -->
					<section id="warranty-coverage" class="warranty-coverage bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="coverage-heading">
						<header class="text-center mb-12">
							<h2 id="coverage-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Our Warranty Coverage
							</h2>
							<p class="text-lg text-gray-600">
								Comprehensive protection plans for your HVAC investment
							</p>
						</header>

						<div class="grid md:grid-cols-2 gap-8">
							<?php foreach ( $warranty_coverage as $index => $coverage ) : ?>
								<article class="group bg-gray-50 rounded-2xl p-6 md:p-8 transition-all duration-300 hover:scale-[1.02] hover:bg-orange-50 hover:shadow-lg">
									<div class="flex items-center justify-between mb-4">
										<div class="text-2xl font-bold text-gray-900" role="heading" aria-level="4">
											<?php echo esc_html( $coverage['title'] ); ?>
										</div>
										<span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-orange-700 font-semibold text-lg">
											<?php echo esc_html( $coverage['duration'] ); ?>
										</span>
									</div>

									<p class="text-gray-600 mb-6">
										<?php echo esc_html( $coverage['description'] ); ?>
									</p>

									<div>
										<h4 class="text-lg font-semibold text-gray-900 mb-3">Coverage Includes:</h4>
										<ul class="space-y-2">
											<?php foreach ( $coverage['features'] as $feature ) : ?>
												<li class="flex items-start">
													<svg class="w-5 h-5 text-orange-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
													</svg>
													<span class="text-gray-600"><?php echo esc_html( $feature ); ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					</section>

					<!-- Warranty Benefits Section -->
					<section class="benefits bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="benefits-heading">
						<header class="text-center mb-8">
							<h2 id="benefits-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Why Choose Our Warranty Protection
							</h2>
							<p class="text-lg text-gray-600">
								Comprehensive coverage that protects your investment and provides peace of mind
							</p>
						</header>

						<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
							<?php foreach ( $warranty_benefits as $index => $benefit ) : ?>
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

					<!-- Warranty Process Section -->
					<section class="process bg-white rounded-[20px] p-6 md:p-10 mb-6"
							 aria-labelledby="process-heading"
							 itemscope
							 itemtype="https://schema.org/HowTo">
						<header class="text-center mb-12">
							<h2 id="process-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4" itemprop="name">
								How to Maintain Your Warranty Coverage
							</h2>
							<p class="text-lg text-gray-600">
								Simple steps to ensure your warranty remains active and valid
							</p>
						</header>

						<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
							<?php foreach ( $warranty_process as $index => $step ) : ?>
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

					<!-- Warranty Claim Form Section -->
					<section id="warranty-claim" class="warranty-claim bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="claim-heading">
						<header class="text-center mb-12">
							<h2 id="claim-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								File a Warranty Claim
							</h2>
							<p class="text-lg text-gray-600">
								Experiencing issues with your covered equipment? Let us help you process your warranty claim quickly.
							</p>
						</header>

						<div class="max-w-4xl mx-auto">
							<div class="bg-gray-50 rounded-2xl p-6 md:p-8">
								<!-- Claim Success Message (hidden by default) -->
								<div id="claim-success" class="hidden mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
									<div class="flex items-start">
										<svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
										</svg>
										<div>
											<h4 class="text-green-800 font-semibold">Warranty Claim Submitted Successfully!</h4>
											<p class="text-green-700 mt-1">Thank you for contacting us. Our warranty team will review your claim and contact you within 24 hours to schedule service.</p>
										</div>
									</div>
								</div>

								<!-- Warranty Claim Form -->
								<form id="warranty-claim-form" class="space-y-6" method="POST" enctype="multipart/form-data">
									<!-- Customer Information Section -->
									<div class="bg-white rounded-xl p-6 border border-gray-200">
										<div class="text-lg font-semibold text-gray-900 mb-4 flex items-center" role="heading" aria-level="4">
											<svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
											</svg>
											Customer Information
										</div>

										<div class="grid md:grid-cols-2 gap-6">
											<div>
												<label for="claim_first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
												<input type="text" id="claim_first_name" name="claim_first_name" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="John">
											</div>

											<div>
												<label for="claim_last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
												<input type="text" id="claim_last_name" name="claim_last_name" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="Doe">
											</div>

											<div>
												<label for="claim_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
												<input type="email" id="claim_email" name="claim_email" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="john.doe@example.com">
											</div>

											<div>
												<label for="claim_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
												<input type="tel" id="claim_phone" name="claim_phone" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="(954) 555-0123">
											</div>

											<div class="md:col-span-2">
												<label for="claim_address" class="block text-sm font-medium text-gray-700 mb-2">Service Address *</label>
												<input type="text" id="claim_address" name="claim_address" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="123 Main St, Miami, FL 33101">
											</div>
										</div>
									</div>

									<!-- Equipment Information Section -->
									<div class="bg-white rounded-xl p-6 border border-gray-200">
										<div class="text-lg font-semibold text-gray-900 mb-4 flex items-center" role="heading" aria-level="4">
											<svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
											</svg>
											Equipment Information
										</div>

										<div class="grid md:grid-cols-2 gap-6">
											<div>
												<label for="equipment_type" class="block text-sm font-medium text-gray-700 mb-2">Equipment Type *</label>
												<select id="equipment_type" name="equipment_type" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
													<option value="">Select equipment type</option>
													<option value="Air Conditioner">Air Conditioner</option>
													<option value="Heat Pump">Heat Pump</option>
													<option value="Furnace">Furnace</option>
													<option value="Air Handler">Air Handler</option>
													<option value="Thermostat">Thermostat</option>
													<option value="Complete System">Complete System</option>
													<option value="Other">Other</option>
												</select>
											</div>

											<div>
												<label for="equipment_brand" class="block text-sm font-medium text-gray-700 mb-2">Equipment Brand *</label>
												<input type="text" id="equipment_brand" name="equipment_brand" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="Carrier, Trane, Lennox, etc.">
											</div>

											<div>
												<label for="install_date" class="block text-sm font-medium text-gray-700 mb-2">Installation Date *</label>
												<input type="date" id="install_date" name="install_date" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
											</div>

											<div>
												<label for="warranty_type" class="block text-sm font-medium text-gray-700 mb-2">Warranty Type *</label>
												<select id="warranty_type" name="warranty_type" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
													<option value="">Select warranty type</option>
													<option value="Manufacturer Warranty">Manufacturer Warranty</option>
													<option value="Installation Warranty">Installation Warranty (Sunnyside AC)</option>
													<option value="Extended Warranty">Extended Warranty</option>
													<option value="Maintenance Plan">Maintenance Plan Coverage</option>
													<option value="Not Sure">Not Sure / Need Help</option>
												</select>
											</div>
										</div>
									</div>

									<!-- Issue Description Section -->
									<div class="bg-white rounded-xl p-6 border border-gray-200">
										<div class="text-lg font-semibold text-gray-900 mb-4 flex items-center" role="heading" aria-level="4">
											<svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
											</svg>
											Issue Description
										</div>

										<div class="space-y-4">
											<div>
												<label for="issue_description" class="block text-sm font-medium text-gray-700 mb-2">Please describe the issue you're experiencing *</label>
												<textarea id="issue_description" name="issue_description" rows="4" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="Please describe what problems you're experiencing with your equipment, when the issues started, and any troubleshooting you've already tried..."></textarea>
											</div>

											<div>
												<label for="urgent_service" class="flex items-center">
													<input type="checkbox" id="urgent_service" name="urgent_service" value="yes"
														class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
													<span class="ml-2 text-sm font-medium text-gray-700">This is an urgent issue requiring immediate attention</span>
												</label>
											</div>

											<div>
												<label for="warranty_documents" class="block text-sm font-medium text-gray-700 mb-2">Warranty Documents (Optional)</label>
												<input type="file" id="warranty_documents" name="warranty_documents" accept=".pdf,.jpg,.jpeg,.png"
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
												<p class="text-xs text-gray-600 mt-1">Upload warranty documents, receipts, or photos (PDF, JPG, PNG - Max 5MB)</p>
											</div>
										</div>
									</div>

									<!-- Form Submit Section -->
									<div class="text-center">
										<button type="submit" id="claim-submit"
											class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-lg font-semibold text-white transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
											<span id="claim-submit-text">Submit Warranty Claim</span>
											<svg id="claim-submit-loading" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
												<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
												<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
											</svg>
										</button>

										<p class="text-sm text-gray-600 mt-4">
											For immediate assistance, call us at <a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>" class="text-orange-500 font-medium"><?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?></a>
										</p>
									</div>
								</form>
							</div>
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
						$warranty_faqs
					);

					get_template_part(
						'template-parts/faq-component',
						null,
						array(
							'faq_data'     => $formatted_faqs,
							'title'        => 'Warranty Frequently Asked Questions',
							'mobile_title' => 'Warranty FAQ',
							'subheading'   => 'Got Questions About Warranty Coverage?',
							'description'  => 'Find answers to common questions about HVAC warranties, coverage, and claims.',
							'show_schema'  => false, // Schema already added in <head>
							'section_id'   => 'warranty-faq-section',
						)
					);
					?>

					<!-- Final CTA Section -->
					<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center" aria-labelledby="cta-heading">
						<h2 id="cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
							Protect Your HVAC Investment Today
						</h2>
						<p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
							Don't wait until you need warranty coverage. Ensure your system is protected with comprehensive warranty service.
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
									Contact Support
								</span>
							</a>
						</div>
					</section>

				</article>

			</section>
		</div>

	</main>

	<!-- Warranty Claim Form JavaScript -->
	<script>
		(function() {
			'use strict';

			const warrantyForm = document.getElementById('warranty-claim-form');
			const submitBtn = document.getElementById('claim-submit');
			const submitText = document.getElementById('claim-submit-text');
			const submitLoading = document.getElementById('claim-submit-loading');
			const successMessage = document.getElementById('claim-success');

			// Form validation
			function validateWarrantyForm() {
				let isValid = true;
				const requiredFields = warrantyForm.querySelectorAll('[required]');

				requiredFields.forEach(field => {
					if (!field.value.trim()) {
						isValid = false;
						field.classList.add('border-red-300');
					} else {
						field.classList.remove('border-red-300');
					}
				});

				// Email validation
				const email = document.getElementById('claim_email');
				const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if (email.value && !emailRegex.test(email.value)) {
					isValid = false;
					email.classList.add('border-red-300');
				}

				// Phone validation
				const phone = document.getElementById('claim_phone');
				const phoneRegex = /^[\d\s\-\(\)\+]+$/;
				if (phone.value && !phoneRegex.test(phone.value)) {
					isValid = false;
					phone.classList.add('border-red-300');
				}

				// File validation
				const documents = document.getElementById('warranty_documents');
				if (documents.files && documents.files[0]) {
					const file = documents.files[0];
					const maxSize = 5 * 1024 * 1024; // 5MB
					const allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];

					if (file.size > maxSize) {
						isValid = false;
						alert('Document file must be smaller than 5MB');
						documents.classList.add('border-red-300');
					}

					if (!allowedTypes.includes(file.type)) {
						isValid = false;
						alert('Document must be a PDF, JPG, or PNG file');
						documents.classList.add('border-red-300');
					}
				}

				return isValid;
			}

			// Handle form submission
			warrantyForm.addEventListener('submit', async function(e) {
				e.preventDefault();

				if (!validateWarrantyForm()) {
					alert('Please fill in all required fields correctly.');
					return;
				}

				// Show loading state
				submitBtn.disabled = true;
				submitText.classList.add('hidden');
				submitLoading.classList.remove('hidden');

				const formData = new FormData(warrantyForm);

				try {
					const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
						method: 'POST',
						body: formData
					});

					const result = await response.json();

					if (result.success) {
						// Show success message
						successMessage.classList.remove('hidden');
						warrantyForm.reset();

						// Scroll to success message
						successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });

						// Hide success message after 10 seconds
						setTimeout(() => {
							successMessage.classList.add('hidden');
						}, 10000);
					} else {
						alert('There was an error submitting your warranty claim. Please try again or call us directly.');
					}
				} catch (error) {
					console.error('Error submitting warranty claim:', error);
					alert('There was an error submitting your warranty claim. Please try again or call us directly.');
				} finally {
					// Reset button state
					submitBtn.disabled = false;
					submitText.classList.remove('hidden');
					submitLoading.classList.add('hidden');
				}
			});

			// Real-time validation
			const requiredFields = warrantyForm.querySelectorAll('[required]');
			requiredFields.forEach(field => {
				field.addEventListener('blur', function() {
					if (this.hasAttribute('required') && !this.value.trim()) {
						this.classList.add('border-red-300');
					} else {
						this.classList.remove('border-red-300');
					}
				});
			});
		})();
	</script>

	<?php get_footer(); ?>

	</body>
	</html>

<?php else : ?>

	<!-- 404 if no post found -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">
			<div class="bg-white rounded-[20px] p-10 text-center">
				<h1 class="text-4xl font-bold text-gray-900 mb-4">Page Not Found</h1>
				<p class="text-lg text-gray-600 mb-8">The warranty page you're looking for doesn't exist or has been removed.</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-white font-medium hover:opacity-90">
					Return to Home
				</a>
			</div>
		</section>
	</div>

<?php endif; ?>