<?php
/**
 * Template Name: Careers
 * Template for displaying careers page
 * URL: /careers/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$page_id    = get_the_ID();
	$page_title = get_the_title();

	// Careers Data - Job Openings
	$job_openings = array(
		array(
			'title'       => 'HVAC Sales Representative',
			'type'        => 'Full-Time',
			'location'    => 'South Florida',
			'description' => 'Drive business growth by selling HVAC systems, maintenance plans, and services to residential and commercial customers. Great earning potential with base salary plus commission.',
			'requirements' => array(
				'Previous sales experience required (HVAC industry preferred)',
				'Excellent communication and negotiation skills',
				'Self-motivated with strong work ethic',
				'Valid driver\'s license and reliable transportation',
				'Bilingual (English/Spanish) strongly preferred',
				'Comfortable with technology and CRM systems'
			)
		),
		array(
			'title'       => 'Inside Sales Representative',
			'type'        => 'Full-Time',
			'location'    => 'Miramar, FL',
			'description' => 'Convert inbound leads and follow up with prospects to schedule consultations and close HVAC sales opportunities. Base salary plus generous commission structure.',
			'requirements' => array(
				'Previous inside sales or customer service experience',
				'Excellent phone and email communication skills',
				'Experience with CRM software preferred',
				'Goal-oriented with ability to handle objections',
				'Bilingual (English/Spanish) a plus',
				'Strong organizational and follow-up skills'
			)
		),
		array(
			'title'       => 'HVAC Technician',
			'type'        => 'Full-Time',
			'location'    => 'South Florida',
			'description' => 'Looking for skilled HVAC technicians to join our growing team. Must have EPA certification and minimum 2 years experience.',
			'requirements' => array(
				'EPA 608 Certification required',
				'Minimum 2 years residential/commercial HVAC experience',
				'Valid driver\'s license and clean driving record',
				'Ability to lift 50+ pounds',
				'Excellent customer service skills'
			)
		),
		array(
			'title'       => 'Service Dispatcher',
			'type'        => 'Full-Time',
			'location'    => 'Miramar, FL',
			'description' => 'We need a detail-oriented dispatcher to coordinate service calls and manage technician schedules.',
			'requirements' => array(
				'Previous dispatch or customer service experience preferred',
				'Excellent communication and organizational skills',
				'Proficient with computer systems',
				'Ability to multitask in fast-paced environment',
				'Bilingual (English/Spanish) a plus'
			)
		),
		array(
			'title'       => 'HVAC Installer',
			'type'        => 'Full-Time',
			'location'    => 'South Florida',
			'description' => 'Join our installation team for new HVAC system installations and replacements.',
			'requirements' => array(
				'Previous HVAC installation experience required',
				'EPA 608 Certification required',
				'Physical ability to perform installation work',
				'Team player with attention to detail',
				'Valid driver\'s license'
			)
		),
		array(
			'title'       => 'Customer Service Representative',
			'type'        => 'Full-Time',
			'location'    => 'Miramar, FL',
			'description' => 'Front-line customer service role handling inquiries, scheduling, and customer relations.',
			'requirements' => array(
				'Previous customer service experience required',
				'Excellent phone and communication skills',
				'Proficient with CRM systems',
				'Bilingual (English/Spanish) strongly preferred',
				'Positive attitude and problem-solving skills'
			)
		)
	);

	// Company Benefits
	$company_benefits = array(
		array(
			'title'       => 'Competitive Pay',
			'description' => 'Industry-leading compensation with base salary plus commission for sales roles',
		),
		array(
			'title'       => 'Uncapped Commission',
			'description' => 'No limits on your earning potential - the more you sell, the more you earn',
		),
		array(
			'title'       => 'Health Insurance',
			'description' => 'Comprehensive medical, dental, and vision coverage for you and your family',
		),
		array(
			'title'       => '401(k) Retirement',
			'description' => 'Company-matched retirement savings plan to secure your future',
		),
		array(
			'title'       => 'Paid Time Off',
			'description' => 'Generous vacation, sick days, and holiday pay',
		),
		array(
			'title'       => 'Sales Training',
			'description' => 'Comprehensive sales training and HVAC product education',
		),
		array(
			'title'       => 'Company Vehicle',
			'description' => 'Take-home company vehicle for qualified outside sales representatives',
		),
		array(
			'title'       => 'Career Growth',
			'description' => 'Advancement opportunities to sales management and leadership roles',
		)
	);

	// Company Culture Values
	$culture_values = array(
		array(
			'title'       => 'Family First',
			'description' => 'We treat our team like family and support work-life balance',
		),
		array(
			'title'       => 'Excellence in Service',
			'description' => 'We deliver exceptional quality and take pride in our work',
		),
		array(
			'title'       => 'Team Collaboration',
			'description' => 'We work together, support each other, and celebrate success',
		),
		array(
			'title'       => 'Continuous Learning',
			'description' => 'We invest in training and embrace new technologies',
		)
	);

	// Careers FAQs
	$careers_faqs = array(
		array(
			'question' => 'What benefits do you offer employees?',
			'answer'   => 'We offer comprehensive benefits including competitive pay with uncapped commission for sales roles, health insurance (medical, dental, vision), 401(k) with company match, paid time off, company vehicles for qualified reps, and ongoing sales training.',
		),
		array(
			'question' => 'What is the earning potential for sales positions?',
			'answer'   => 'Our sales positions offer uncapped earning potential with base salary plus generous commission. Top performers can earn six-figure incomes. Your earnings directly reflect your effort and success - no limits on how much you can make.',
		),
		array(
			'question' => 'Do I need HVAC experience for sales roles?',
			'answer'   => 'While HVAC industry experience is preferred, we welcome motivated sales professionals from other industries. We provide comprehensive product training and ongoing education to help you succeed. Strong communication skills and sales experience are most important.',
		),
		array(
			'question' => 'What kind of sales training do you provide?',
			'answer'   => 'We offer extensive sales training including HVAC product education, sales techniques, CRM software training, and mentorship from experienced team members. We believe in continuous learning and development to help you reach your full potential.',
		),
		array(
			'question' => 'Are there opportunities for advancement in sales?',
			'answer'   => 'Yes! We offer clear career advancement paths from Sales Representative to Senior Sales, Sales Manager, and beyond. We prefer to promote from within and provide leadership training for those looking to grow into management roles.',
		),
		array(
			'question' => 'What experience do I need for technical positions?',
			'answer'   => 'Requirements vary by position. For technician roles, we typically require EPA certification and some experience. However, we also offer entry-level positions and are willing to train the right candidates who show initiative and strong work ethic.',
		),
		array(
			'question' => 'How do I apply for a position?',
			'answer'   => 'You can apply through the form on this page, email your resume to careers@sunnysideac.com, or call our office at (954) 588-4877. We review all applications and will contact you within 2-3 business days if your qualifications match our needs.',
		),
		array(
			'question' => 'Do you offer relocation assistance?',
			'answer'   => 'For certain hard-to-fill positions and experienced candidates, we may offer relocation assistance. This is evaluated on a case-by-case basis and would be discussed during the interview process.',
		),
	);

	// SEO Variables
	$page_title    = 'HVAC Careers - Join Our Team at Sunnyside AC';
	$meta_desc     = 'Build your HVAC career with Sunnyside AC. Competitive pay, great benefits, and opportunities for growth. Join our South Florida HVAC team today.';
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
							"name": "Careers",
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
					"@type": "Organization",
					"name": "Sunnyside AC",
					"url": "<?php echo esc_url( home_url( '/' ) ); ?>",
					"description": "HVAC company serving South Florida with career opportunities in installation, maintenance, and customer service"
				}
			]
		}
		</script>
	</head>

	<body <?php body_class(); ?>>

	<?php get_header(); ?>

	<main class="min-h-screen bg-gray-50" role="main" itemscope itemtype="https://schema.org/Organization">

		<!-- Container matching front-page style -->
		<div class="lg:px-0 max-w-7xl mx-auto">
			<section>

				<!-- Hero Section with Careers Title & CTA -->
				<article class="flex gap-10 flex-col" id="post-<?php the_ID(); ?>" <?php post_class( 'careers-page' ); ?>>

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
									<span itemprop="name" class="font-semibold text-orange-500">Careers</span>
									<meta itemprop="position" content="2">
								</li>
							</ol>
						</nav>

						<!-- Main Title with Gradient -->
						<div class="text-center mb-8">
							<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4" itemprop="name">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									Join Our Team
								</span>
							</h1>

							<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
								Build a rewarding HVAC career with Sunnyside AC. Competitive pay, excellent benefits, and opportunities for growth await you.
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
										'alt'      => esc_attr( 'Join our HVAC team at Sunnyside AC' ),
									)
								);
								?>
								<meta itemprop="width" content="1200">
								<meta itemprop="height" content="630">
							</figure>
						<?php endif; ?>

						<!-- CTA Buttons -->
						<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
							<a href="#job-openings"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
								<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
									View Open Positions
								</span>
							</a>

							<a href="#application-form"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
								<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
									Apply Now
								</span>
							</a>
						</div>
					</header>

					<!-- Job Openings Section -->
					<section id="job-openings" class="job-openings bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="openings-heading">
						<header class="text-center mb-12">
							<h2 id="openings-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Current Job Openings
							</h2>
							<p class="text-lg text-gray-600">
								Join our growing team of HVAC professionals
							</p>
						</header>

						<div class="grid gap-8">
							<?php foreach ( $job_openings as $index => $job ) : ?>
								<article class="group bg-gray-50 rounded-2xl p-6 md:p-8 transition-all duration-300 hover:scale-[1.02] hover:bg-orange-50 hover:shadow-lg">
									<div class="flex flex-col md:flex-row md:items-center md:justify-between mb-4">
										<div>
											<div class="text-2xl font-bold text-gray-900 mb-2" role="heading" aria-level="4">
												<?php echo esc_html( $job['title'] ); ?>
											</div>
											<div class="flex flex-wrap items-center gap-4 text-sm">
												<span class="inline-flex items-center px-3 py-1 rounded-full bg-orange-100 text-orange-700 font-medium">
													<?php echo esc_html( $job['type'] ); ?>
												</span>
												<span class="inline-flex items-center text-gray-600">
													<svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
													</svg>
													<?php echo esc_html( $job['location'] ); ?>
												</span>
											</div>
										</div>
										<a href="#application-form"
											class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-3 mt-4 md:mt-0 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
											<span class="text-base font-medium text-white whitespace-nowrap">
												Apply for This Position
											</span>
										</a>
									</div>

									<p class="text-gray-600 mb-6">
										<?php echo esc_html( $job['description'] ); ?>
									</p>

									<div>
										<h4 class="text-lg font-semibold text-gray-900 mb-3">Requirements:</h4>
										<ul class="space-y-2">
											<?php foreach ( $job['requirements'] as $requirement ) : ?>
												<li class="flex items-start">
													<svg class="w-5 h-5 text-orange-500 mr-2 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
														<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
													</svg>
													<span class="text-gray-600"><?php echo esc_html( $requirement ); ?></span>
												</li>
											<?php endforeach; ?>
										</ul>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					</section>

					<!-- Benefits Section -->
					<section class="benefits bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="benefits-heading">
						<header class="text-center mb-8">
							<h2 id="benefits-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Why Work With Us
							</h2>
							<p class="text-lg text-gray-600">
								We invest in our team with competitive benefits and support
							</p>
						</header>

						<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
							<?php foreach ( $company_benefits as $index => $benefit ) : ?>
								<article class="group bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
									<!-- Icon Circle -->
									<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300 mb-4">
										<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<?php if ( $index === 0 ) : ?>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
											<?php elseif ( $index === 1 ) : ?>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
											<?php elseif ( $index === 2 ) : ?>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
											<?php elseif ( $index === 3 ) : ?>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
											<?php else : ?>
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
											<?php endif; ?>
										</svg>
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

					<!-- Company Culture Section -->
					<section class="culture bg-gradient-to-br from-blue-50 to-orange-50 rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="culture-heading">
						<header class="text-center mb-12">
							<h2 id="culture-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Our Company Culture
							</h2>
							<p class="text-lg text-gray-600">
								We're more than a company - we're a family
							</p>
						</header>

						<div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
							<?php foreach ( $culture_values as $index => $value ) : ?>
								<article class="text-center">
									<div class="bg-white rounded-2xl p-8 h-full transition-all duration-300 hover:scale-105 hover:shadow-lg">
										<!-- Value Icon -->
										<div class="mb-6">
											<div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-orange-200 to-orange-300">
												<span class="text-3xl font-bold text-orange-500">
													<?php echo $index + 1; ?>
												</span>
											</div>
										</div>

										<!-- Value Content -->
										<div class="text-xl font-bold text-gray-900 mb-3" role="heading" aria-level="4">
											<?php echo esc_html( $value['title'] ); ?>
										</div>
										<p class="text-base text-gray-600 leading-relaxed">
											<?php echo esc_html( $value['description'] ); ?>
										</p>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					</section>

					<!-- Application Form Section -->
					<section id="application-form" class="application-form bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="application-heading">
						<header class="text-center mb-12">
							<h2 id="application-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Apply Today
							</h2>
							<p class="text-lg text-gray-600">
								Take the first step toward your new career
							</p>
						</header>

						<div class="max-w-4xl mx-auto">
							<div class="bg-gray-50 rounded-2xl p-6 md:p-8">
								<!-- Application Success Message (hidden by default) -->
								<div id="application-success" class="hidden mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
									<div class="flex items-start">
										<svg class="w-6 h-6 text-green-500 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
										</svg>
										<div>
											<h4 class="text-green-800 font-semibold">Application Submitted Successfully!</h4>
											<p class="text-green-700 mt-1">Thank you for your interest in joining our team. We'll review your application and contact you within 2-3 business days.</p>
										</div>
									</div>
								</div>

								<!-- Application Form -->
								<form id="careers-form" class="space-y-6" method="POST" enctype="multipart/form-data">
									<!-- Personal Information Section -->
									<div class="bg-white rounded-xl p-6 border border-gray-200">
										<div class="text-lg font-semibold text-gray-900 mb-4 flex items-center" role="heading" aria-level="4">
											<svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
											</svg>
											Personal Information
										</div>

										<div class="grid md:grid-cols-2 gap-6">
											<div>
												<label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
												<input type="text" id="first_name" name="first_name" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="John">
											</div>

											<div>
												<label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
												<input type="text" id="last_name" name="last_name" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="Doe">
											</div>

											<div>
												<label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
												<input type="email" id="email" name="email" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="john.doe@example.com">
											</div>

											<div>
												<label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number *</label>
												<input type="tel" id="phone" name="phone" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="(954) 555-0123">
											</div>
										</div>
									</div>

									<!-- Position Information Section -->
									<div class="bg-white rounded-xl p-6 border border-gray-200">
										<div class="text-lg font-semibold text-gray-900 mb-4 flex items-center" role="heading" aria-level="4">
											<svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
											</svg>
											Position Information
										</div>

										<div class="space-y-4">
											<div>
												<label for="position" class="block text-sm font-medium text-gray-700 mb-2">Position You're Applying For *</label>
												<select id="position" name="position" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
													<option value="">Select a position</option>
													<?php foreach ( $job_openings as $job ) : ?>
														<option value="<?php echo esc_attr( $job['title'] ); ?>"><?php echo esc_html( $job['title'] ); ?> - <?php echo esc_html( $job['type'] ); ?></option>
													<?php endforeach; ?>
													<option value="Other">Other Position</option>
												</select>
											</div>

											<div id="other_position_div" class="hidden">
												<label for="other_position" class="block text-sm font-medium text-gray-700 mb-2">Specify Position</label>
												<input type="text" id="other_position" name="other_position"
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="Enter the position you're applying for">
											</div>

											<div>
												<label for="experience" class="block text-sm font-medium text-gray-700 mb-2">Years of Experience *</label>
												<input type="number" id="experience" name="experience" min="0" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="2">
											</div>
										</div>
									</div>

									<!-- Additional Information Section -->
									<div class="bg-white rounded-xl p-6 border border-gray-200">
										<div class="text-lg font-semibold text-gray-900 mb-4 flex items-center" role="heading" aria-level="4">
											<svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
											</svg>
											Additional Information
										</div>

										<div class="space-y-4">
											<div>
												<label for="availability" class="block text-sm font-medium text-gray-700 mb-2">Availability to Start *</label>
												<select id="availability" name="availability" required
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
													<option value="">Select availability</option>
													<option value="Immediately">Immediately</option>
													<option value="1-2 weeks">1-2 weeks</option>
													<option value="2-4 weeks">2-4 weeks</option>
													<option value="1 month or more">1 month or more</option>
												</select>
											</div>

											<div>
												<label for="message" class="block text-sm font-medium text-gray-700 mb-2">Tell us why you'd be a great addition to our team</label>
												<textarea id="message" name="message" rows="4"
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
													placeholder="Share any additional information about yourself, your experience, or why you're interested in joining Sunnyside AC..."></textarea>
											</div>

											<div>
												<label for="resume" class="block text-sm font-medium text-gray-700 mb-2">Resume/CV (PDF, DOC, DOCX - Max 5MB)</label>
												<input type="file" id="resume" name="resume" accept=".pdf,.doc,.docx"
													class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent">
											</div>
										</div>
									</div>

									<!-- Form Submit Section -->
									<div class="text-center">
										<button type="submit" id="careers-submit"
											class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-lg font-semibold text-white transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
											<span id="submit-text">Submit Application</span>
											<svg id="submit-loading" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
												<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
												<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
											</svg>
										</button>

										<p class="text-sm text-gray-600 mt-4">
											By submitting this application, you agree to be contacted by Sunnyside AC regarding your application and future job opportunities.
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
						$careers_faqs
					);

					get_template_part(
						'template-parts/faq-component',
						null,
						array(
							'faq_data'     => $formatted_faqs,
							'title'        => 'Frequently Asked Questions',
							'mobile_title' => 'FAQ',
							'subheading'   => 'Got Questions About Working With Us?',
							'description'  => 'Find answers to common questions about careers and working at Sunnyside AC.',
							'show_schema'  => true,
							'section_id'   => 'careers-faq-section',
						)
					);
					?>

					<!-- Final CTA Section -->
					<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center" aria-labelledby="cta-heading">
						<h2 id="cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
							Ready to Join Our Team?
						</h2>
						<p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
							Take the next step in your HVAC career with a company that values your skills and invests in your growth
						</p>

						<div class="flex flex-col sm:flex-row justify-center gap-4">
							<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
								<span class="text-lg font-bold text-orange-500">
									Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
								</span>
							</a>

							<a href="mailto:<?php echo esc_attr( SUNNYSIDE_EMAIL_ADDRESS ); ?>"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
								<span class="text-lg font-bold text-white">
									Email Resume
								</span>
							</a>
						</div>
					</section>

				</article>

			</section>
		</div>

	</main>

	<!-- Careers Form JavaScript -->
	<script>
		(function() {
			'use strict';

			const careersForm = document.getElementById('careers-form');
			const positionSelect = document.getElementById('position');
			const otherPositionDiv = document.getElementById('other_position_div');
			const submitBtn = document.getElementById('careers-submit');
			const submitText = document.getElementById('submit-text');
			const submitLoading = document.getElementById('submit-loading');
			const successMessage = document.getElementById('application-success');

			// Show/hide "Other position" field
			positionSelect.addEventListener('change', function() {
				if (this.value === 'Other') {
					otherPositionDiv.classList.remove('hidden');
					document.getElementById('other_position').required = true;
				} else {
					otherPositionDiv.classList.add('hidden');
					document.getElementById('other_position').required = false;
					document.getElementById('other_position').value = '';
				}
			});

			// Form validation
			function validateForm() {
				let isValid = true;
				const requiredFields = careersForm.querySelectorAll('[required]');

				requiredFields.forEach(field => {
					if (!field.value.trim()) {
						isValid = false;
						field.classList.add('border-red-300');
					} else {
						field.classList.remove('border-red-300');
					}
				});

				// Email validation
				const email = document.getElementById('email');
				const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
				if (email.value && !emailRegex.test(email.value)) {
					isValid = false;
					email.classList.add('border-red-300');
				}

				// Phone validation
				const phone = document.getElementById('phone');
				const phoneRegex = /^[\d\s\-\(\)\+]+$/;
				if (phone.value && !phoneRegex.test(phone.value)) {
					isValid = false;
					phone.classList.add('border-red-300');
				}

				// Resume file validation
				const resume = document.getElementById('resume');
				if (resume.files && resume.files[0]) {
					const file = resume.files[0];
					const maxSize = 5 * 1024 * 1024; // 5MB
					const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];

					if (file.size > maxSize) {
						isValid = false;
						alert('Resume file must be smaller than 5MB');
						resume.classList.add('border-red-300');
					}

					if (!allowedTypes.includes(file.type)) {
						isValid = false;
						alert('Resume must be a PDF, DOC, or DOCX file');
						resume.classList.add('border-red-300');
					}
				}

				return isValid;
			}

			// Handle form submission
			careersForm.addEventListener('submit', async function(e) {
				e.preventDefault();

				if (!validateForm()) {
					alert('Please fill in all required fields correctly.');
					return;
				}

				// Show loading state
				submitBtn.disabled = true;
				submitText.classList.add('hidden');
				submitLoading.classList.remove('hidden');

				const formData = new FormData(careersForm);

				try {
					const response = await fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
						method: 'POST',
						body: formData
					});

					const result = await response.json();

					if (result.success) {
						// Show success message
						successMessage.classList.remove('hidden');
						careersForm.reset();

						// Scroll to success message
						successMessage.scrollIntoView({ behavior: 'smooth', block: 'center' });

						// Hide success message after 10 seconds
						setTimeout(() => {
							successMessage.classList.add('hidden');
						}, 10000);
					} else {
						alert('There was an error submitting your application. Please try again or call us directly.');
					}
				} catch (error) {
					console.error('Error submitting careers form:', error);
					alert('There was an error submitting your application. Please try again or call us directly.');
				} finally {
					// Reset button state
					submitBtn.disabled = false;
					submitText.classList.remove('hidden');
					submitLoading.classList.add('hidden');
				}
			});

			// Real-time validation
			const requiredFields = careersForm.querySelectorAll('[required]');
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
				<p class="text-lg text-gray-600 mb-8">The careers page you're looking for doesn't exist or has been removed.</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-white font-medium hover:opacity-90">
					Return to Home
				</a>
			</div>
		</section>
	</div>

<?php endif; ?>