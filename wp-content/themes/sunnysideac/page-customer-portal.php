<?php
/**
 * Template Name: Customer Portal
 * Template for displaying customer portal page
 * URL: /customer-portal/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$page_id    = get_the_ID();
	$page_title = get_the_title();

	// Customer Portal Features
	$portal_features = array(
		array(
			'title'       => 'Service History',
			'description' => 'View complete history of all HVAC services, repairs, and maintenance performed on your equipment.',
			'icon'        => 'history',
			'benefits'    => array(
				'Detailed service reports',
				'Work order documentation',
				'Technician notes and recommendations',
				'Parts and labor records'
			)
		),
		array(
			'title'       => 'Maintenance Scheduling',
			'description' => 'Schedule routine maintenance, annual tune-ups, and seasonal check-ups with preferred time slots.',
			'icon'        => 'calendar',
			'benefits'    => array(
				'Online appointment booking',
				'Automated reminders',
				'Flexible scheduling options',
				'Priority service for plan members'
			)
		),
		array(
			'title'       => 'Account Management',
			'description' => 'Manage your contact information, service addresses, payment methods, and communication preferences.',
			'icon'        => 'user',
			'benefits'    => array(
				'Update contact details',
				'Manage multiple properties',
				'Secure payment processing',
				'Communication preferences'
			)
		),
		array(
			'title'       => 'Warranty Tracking',
			'description' => 'Monitor your equipment warranties, coverage details, and upcoming expiration dates.',
			'icon'        => 'shield',
			'benefits'    => array(
				'Warranty status monitoring',
				'Coverage documentation',
				'Expiration alerts',
				'Claims tracking'
			)
		),
		array(
			'title'       => 'Digital Invoices',
			'description' => 'Access, download, and pay invoices online with secure payment processing and payment history.',
			'icon'        => 'document',
			'benefits'    => array(
				'Secure online payments',
				'Invoice history tracking',
				'Automated payment options',
				'Tax documentation'
			)
		),
		array(
			'title'       => 'Equipment Information',
			'description' => 'Detailed records of your HVAC equipment including models, serial numbers, and installation dates.',
			'icon'        => 'home',
			'benefits'    => array(
				'Equipment specifications',
				'Maintenance schedules',
				'Performance tracking',
				'Replacement recommendations'
			)
		)
	);

	// Portal Benefits
	$portal_benefits = array(
		array(
			'title'       => '24/7 Access',
			'description' => 'Access your account information and schedule service anytime, anywhere.',
		),
		array(
			'title'       => 'Paperless Records',
			'description' => 'Go digital with electronic invoices, service records, and warranty documentation.',
		),
		array(
			'title'       => 'Priority Support',
			'description' => 'Portal members receive priority scheduling and faster response times.',
		),
		array(
			'title'       => 'Mobile Friendly',
			'description' => 'Full functionality on smartphones, tablets, and desktop computers.',
		),
		array(
			'title'       => 'Secure Platform',
			'description' => 'Bank-level security protects your personal and payment information.',
		),
		array(
			'title'       => 'Family Access',
			'description' => 'Add family members to your account for shared property management.',
		)
	);

	// How It Works Steps
	$portal_steps = array(
		array(
			'title'       => 'Create Your Account',
			'description' => 'Sign up in seconds with your email and service address to get started.',
		),
		array(
			'title'       => 'Verify Your Information',
			'description' => 'We\'ll link your account to your service history and equipment records.',
		),
		array(
			'title'       => 'Explore Your Dashboard',
			'description' => 'Access your service history, schedule maintenance, and manage your account.',
		),
		array(
			'title'       => 'Enjoy Full Control',
			'description' => 'Take advantage of all portal features for complete HVAC management.',
		)
	);

	// Customer Portal FAQs
	$portal_faqs = array(
		array(
			'question' => 'Is the customer portal free to use?',
			'answer'   => 'Yes! The customer portal is completely free for all Sunnyside AC customers. There are no monthly fees or hidden charges to access your account information, schedule service, or use any portal features.',
		),
		array(
			'question' => 'How do I create a customer portal account?',
			'answer'   => 'Creating an account is easy! Click the "Sign Up" button on this page, enter your email address and service address, and create a secure password. We\'ll verify your information and link your account to your service history automatically.',
		),
		array(
			'question' => 'What information can I access in the portal?',
			'answer'   => 'You can access your complete service history, schedule maintenance appointments, view and pay invoices, track warranty coverage, manage equipment information, update contact details, and much more - all from one secure dashboard.',
		),
		array(
			'question' => 'Is my personal and payment information secure?',
			'answer'   => 'Absolutely! We use bank-level encryption and security protocols to protect your data. All payment processing is PCI compliant, and we never share your information with third parties without your explicit consent.',
		),
		array(
			'question' => 'Can I manage multiple properties?',
			'answer'   => 'Yes! The portal allows you to manage multiple service addresses under one account. You can view separate service histories, schedule maintenance for different properties, and manage equipment for each location individually.',
		),
		array(
			'question' => 'Can I pay my bills online through the portal?',
			'answer'   => 'Yes! You can view current and past invoices, make secure online payments using credit cards or bank transfers, set up automatic payments, and download receipts for your records. All payment processing is secure and encrypted.',
		),
		array(
			'question' => 'How do I schedule service through the portal?',
			'answer'   => 'Scheduling is simple! Log into your dashboard, click "Schedule Service," select your preferred date and time, describe the service needed, and confirm your appointment. You\'ll receive immediate confirmation and automated reminders.',
		),
		array(
			'question' => 'What if I need help using the portal?',
			'answer'   => 'Our customer support team is here to help! You can call us at ' . SUNNYSIDE_PHONE_DISPLAY . ' during business hours, or use the live chat feature in the portal. We also offer video tutorials and detailed guides for common tasks.',
		),
		array(
			'question' => 'Can family members access my account?',
			'answer'   => 'Yes, you can authorize family members or property managers to access your account. Each user gets their own login credentials, and you can control what information and features they can access for security and privacy.',
		),
	);

	// SEO Variables
	$page_title    = 'Customer Portal - Sunnyside AC';
	$meta_desc     = 'Access your Sunnyside AC customer portal for 24/7 account management, service scheduling, online payments, and complete HVAC service history. Manage your HVAC systems online.';
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
							"name": "Customer Portal",
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
					"serviceType": "Customer Portal Access",
					"provider": {
						"@type": "LocalBusiness",
						"name": "Sunnyside AC",
						"address": {
							"@type": "PostalAddress",
							"streetAddress": "<?php echo esc_js( SUNNYSIDE_ADDRESS_STREET ); ?>",
							"addressLocality": "<?php echo esc_js( SUNNYSIDE_ADDRESS_CITY ); ?>",
							"addressRegion": "<?php echo esc_js( SUNNYSIDE_ADDRESS_STATE ); ?>",
							"postalCode": "<?php echo esc_js( SUNNYSIDE_ADDRESS_ZIP ); ?>",
							"addressCountry": "US"
						}
					},
					"description": "Online customer portal for HVAC service management, scheduling, payments, and account access."
				}
				<?php if ( $portal_faqs ) : ?>
				,
				{
					"@type": "FAQPage",
					"mainEntity": [
						<?php
						$faq_count = count( $portal_faqs );
						foreach ( $portal_faqs as $index => $faq ) :
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

				<!-- Hero Section with Customer Portal Title & CTA -->
				<article class="flex gap-10 flex-col" id="post-<?php the_ID(); ?>" <?php post_class( 'customer-portal-page' ); ?>>

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
									<span itemprop="name" class="font-semibold text-orange-500">Customer Portal</span>
									<meta itemprop="position" content="2">
								</li>
							</ol>
						</nav>

						<!-- Main Title with Gradient -->
						<div class="text-center mb-8">
							<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4" itemprop="name">
								<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
									Customer Portal
								</span>
							</h1>

							<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
								Manage your HVAC services, schedule maintenance, pay bills, and access your complete service history - all in one secure online portal.
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
										'alt'      => esc_attr( 'Sunnyside AC customer portal for online account management' ),
									)
								);
								?>
								<meta itemprop="width" content="1200">
								<meta itemprop="height" content="630">
							</figure>
						<?php endif; ?>

						<!-- CTA Buttons -->
						<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
							<a href="#login-section"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
								<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
									Sign In to Portal
								</span>
							</a>

							<a href="#signup-section"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
								<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
									Create Account
								</span>
							</a>
						</div>
					</header>

					<!-- Portal Features Section -->
					<section class="portal-features bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="features-heading">
						<header class="text-center mb-12">
							<h2 id="features-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Portal Features
							</h2>
							<p class="text-lg text-gray-600">
								Everything you need to manage your HVAC services online
							</p>
						</header>

						<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
							<?php foreach ( $portal_features as $index => $feature ) : ?>
								<article class="group bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-[1.02] hover:bg-orange-50 hover:shadow-lg">
									<div class="flex items-center mb-4">
										<!-- Feature Icon -->
										<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300 mr-6">
											<?php if ( $feature['icon'] === 'history' ) : ?>
												<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
												</svg>
											<?php elseif ( $feature['icon'] === 'calendar' ) : ?>
												<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
												</svg>
											<?php elseif ( $feature['icon'] === 'user' ) : ?>
												<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
												</svg>
											<?php elseif ( $feature['icon'] === 'shield' ) : ?>
												<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
												</svg>
											<?php elseif ( $feature['icon'] === 'document' ) : ?>
												<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
												</svg>
											<?php elseif ( $feature['icon'] === 'home' ) : ?>
												<svg class="h-6 w-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
												</svg>
											<?php endif; ?>
										</div>
										<div class="text-xl font-bold text-gray-900" role="heading" aria-level="4">
											<?php echo esc_html( $feature['title'] ); ?>
										</div>
									</div>

									<p class="text-gray-600 mb-4">
										<?php echo esc_html( $feature['description'] ); ?>
									</p>

									<ul class="space-y-1">
										<?php foreach ( $feature['benefits'] as $benefit ) : ?>
											<li class="flex items-center text-sm text-gray-600">
												<svg class="w-4 h-4 text-orange-500 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
												</svg>
												<?php echo esc_html( $benefit ); ?>
											</li>
										<?php endforeach; ?>
									</ul>
								</article>
							<?php endforeach; ?>
						</div>
					</section>

					<!-- Portal Benefits Section -->
					<section class="benefits bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="benefits-heading">
						<header class="text-center mb-8">
							<h2 id="benefits-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Why Use Our Customer Portal
							</h2>
							<p class="text-lg text-gray-600">
								Convenience, control, and peace of mind for HVAC management
							</p>
						</header>

						<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
							<?php foreach ( $portal_benefits as $index => $benefit ) : ?>
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

					<!-- How It Works Section -->
					<section class="process bg-white rounded-[20px] p-6 md:p-10 mb-6"
							 aria-labelledby="process-heading"
							 itemscope
							 itemtype="https://schema.org/HowTo">
						<header class="text-center mb-12">
							<h2 id="process-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4" itemprop="name">
								How to Get Started
							</h2>
							<p class="text-lg text-gray-600">
								Simple steps to access your customer portal
							</p>
						</header>

						<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
							<?php foreach ( $portal_steps as $index => $step ) : ?>
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

					<!-- Login/Signup Section -->
					<section class="portal-access bg-gradient-to-br from-blue-50 to-orange-50 rounded-[20px] p-6 md:p-10 mb-6">
						<!-- Construction Notice -->
						<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 text-center">
							<div class="flex items-center justify-center mb-2">
								<svg class="w-6 h-6 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
								</svg>
								<span class="text-yellow-800 font-semibold">Coming Soon!</span>
							</div>
							<p class="text-yellow-700 text-sm">We're currently building our Customer Portal. Sign up below to be notified when it launches!</p>
						</div>

						<div class="grid md:grid-cols-2 gap-8">
							<!-- Login Section -->
							<div id="login-section" class="bg-white rounded-2xl p-6 md:p-8">
								<header class="text-center mb-6">
									<div class="text-2xl font-bold text-gray-900 mb-2" role="heading" aria-level="4">Sign In to Your Portal</div>
									<p class="text-gray-600">Access your account and manage your services</p>
								</header>

								<form id="portal-login-form" class="space-y-4">
									<div>
										<label for="login_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
										<input type="email" id="login_email" name="login_email" required
											class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
											placeholder="your@email.com">
									</div>

									<div>
										<label for="login_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
										<input type="password" id="login_password" name="login_password" required
											class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
											placeholder="••••••••">
									</div>

									<div class="flex items-center justify-between">
										<label class="flex items-center">
											<input type="checkbox" name="remember_me" class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
											<span class="ml-2 text-sm text-gray-700">Remember me</span>
										</label>
										<a href="#" class="text-sm text-orange-500 hover:text-orange-600">Forgot password?</a>
									</div>

									<button type="submit"
										class="w-full inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-3 text-base font-semibold text-white transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
										<span>Sign In</span>
										<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
										</svg>
									</button>
								</form>
							</div>

							<!-- Signup Section -->
							<div id="signup-section" class="bg-white rounded-2xl p-6 md:p-8">
								<header class="text-center mb-6">
									<div class="text-2xl font-bold text-gray-900 mb-2" role="heading" aria-level="4">Create Your Account</div>
									<p class="text-gray-600">Join thousands of satisfied customers</p>
								</header>

								<form id="portal-signup-form" class="space-y-4">
									<div class="grid grid-cols-2 gap-4">
										<div>
											<label for="signup_first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name</label>
											<input type="text" id="signup_first_name" name="signup_first_name" required
												class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
												placeholder="John">
										</div>
										<div>
											<label for="signup_last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name</label>
											<input type="text" id="signup_last_name" name="signup_last_name" required
												class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
												placeholder="Doe">
										</div>
									</div>

									<div>
										<label for="signup_email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
										<input type="email" id="signup_email" name="signup_email" required
											class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
											placeholder="your@email.com">
									</div>

									<div>
										<label for="signup_phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
										<input type="tel" id="signup_phone" name="signup_phone" required
											class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
											placeholder="(954) 555-0123">
									</div>

									<div>
										<label for="signup_address" class="block text-sm font-medium text-gray-700 mb-2">Service Address</label>
										<input type="text" id="signup_address" name="signup_address" required
											class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
											placeholder="123 Main St, Miami, FL 33101">
									</div>

									<div>
										<label for="signup_password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
										<input type="password" id="signup_password" name="signup_password" required
											class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-orange-500 focus:border-transparent"
											placeholder="Create a secure password">
									</div>

									<div>
										<label class="flex items-center">
											<input type="checkbox" name="agree_terms" required class="w-4 h-4 text-orange-500 border-gray-300 rounded focus:ring-orange-500">
											<span class="ml-2 text-sm text-gray-700">I agree to the <a href="#" class="text-orange-500 hover:text-orange-600">Terms of Service</a> and <a href="#" class="text-orange-500 hover:text-orange-600">Privacy Policy</a></span>
										</label>
									</div>

									<button type="submit"
										class="w-full inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-3 text-base font-semibold text-white transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
										<span>Create Account</span>
										<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
										</svg>
									</button>
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
						$portal_faqs
					);

					get_template_part(
						'template-parts/faq-component',
						null,
						array(
							'faq_data'     => $formatted_faqs,
							'title'        => 'Customer Portal Frequently Asked Questions',
							'mobile_title' => 'Portal FAQ',
							'subheading'   => 'Got Questions About the Customer Portal?',
							'description'  => 'Find answers to common questions about using our customer portal for HVAC service management.',
							'show_schema'  => false, // Schema already added in <head>
							'section_id'   => 'customer-portal-faq-section',
						)
					);
					?>

					<!-- Final CTA Section -->
					<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center" aria-labelledby="cta-heading">
						<h2 id="cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
							Take Control of Your HVAC Services
						</h2>
						<p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
							Join thousands of satisfied customers managing their HVAC services online with our secure customer portal.
						</p>

						<div class="flex flex-col sm:flex-row justify-center gap-4">
							<a href="#signup-section"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
								<span class="text-lg font-bold text-orange-500">
									Get Started Now
								</span>
							</a>

							<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
								class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
								<span class="text-lg font-bold text-white">
									Call for Support
								</span>
							</a>
						</div>
					</section>

				</article>

			</section>
		</div>

	</main>

	<!-- Customer Portal JavaScript -->
	<script>
		(function() {
			'use strict';

			const loginForm = document.getElementById('portal-login-form');
			const signupForm = document.getElementById('portal-signup-form');

			// Login form validation
			if (loginForm) {
				loginForm.addEventListener('submit', function(e) {
					e.preventDefault();

					const email = document.getElementById('login_email').value;
					const password = document.getElementById('login_password').value;

					// Basic validation
					if (!email || !password) {
						alert('Please fill in all required fields.');
						return;
					}

					// Email validation
					const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
					if (!emailRegex.test(email)) {
						alert('Please enter a valid email address.');
						return;
					}

					// Show success message about portal coming soon
					alert('Thank you for your interest in our Customer Portal! We\'re currently building this exciting new feature to help you manage your HVAC services online. We\'ll notify you as soon as it\'s ready for launch.\n\nIn the meantime, please call us at ' + <?php echo json_encode(SUNNYSIDE_PHONE_DISPLAY); ?> . ' for all your service needs.');

					// Reset form
					loginForm.reset();
				});
			}

			// Signup form validation
			if (signupForm) {
				signupForm.addEventListener('submit', function(e) {
					e.preventDefault();

					const firstName = document.getElementById('signup_first_name').value;
					const lastName = document.getElementById('signup_last_name').value;
					const email = document.getElementById('signup_email').value;
					const phone = document.getElementById('signup_phone').value;
					const address = document.getElementById('signup_address').value;
					const password = document.getElementById('signup_password').value;
					const agreeTerms = document.querySelector('input[name="agree_terms"]').checked;

					// Basic validation
					if (!firstName || !lastName || !email || !phone || !address || !password) {
						alert('Please fill in all required fields.');
						return;
					}

					if (!agreeTerms) {
						alert('Please agree to the Terms of Service and Privacy Policy.');
						return;
					}

					// Email validation
					const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
					if (!emailRegex.test(email)) {
						alert('Please enter a valid email address.');
						return;
					}

					// Password validation
					if (password.length < 8) {
						alert('Password must be at least 8 characters long.');
						return;
					}

					// Show success message about portal coming soon
					alert('Thank you for your interest in our Customer Portal, ' + firstName + '! We\'re excited to be building this new online service to help you manage your HVAC services. We\'ll notify you as soon as it\'s ready for launch.\n\nIn the meantime, please call us at ' + <?php echo json_encode(SUNNYSIDE_PHONE_DISPLAY); ?> . ' for all your service needs.');

					// Reset form
					signupForm.reset();
				});
			}

			// Smooth scroll to sections
			document.querySelectorAll('a[href^="#"]').forEach(anchor => {
				anchor.addEventListener('click', function (e) {
					e.preventDefault();
					const target = document.querySelector(this.getAttribute('href'));
					if (target) {
						target.scrollIntoView({
							behavior: 'smooth',
							block: 'start'
						});
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
				<p class="text-lg text-gray-600 mb-8">The customer portal page you're looking for doesn't exist or has been removed.</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-white font-medium hover:opacity-90">
					Return to Home
				</a>
			</div>
		</section>
	</div>

<?php endif; ?>