<?php
/**
 * Template Name: Privacy Policy
 * Template for displaying privacy policy page
 * URL: /privacy-policy/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$page_id    = get_the_ID();
	$page_title = get_the_title();

	// Privacy Policy Sections
	$privacy_sections = array(
		array(
			'id'          => 'introduction',
			'title'       => '1. Introduction',
			'content'     => 'At Sunnyside AC, we are committed to protecting your privacy and the security of your personal information. This Privacy Policy explains how we collect, use, disclose, and safeguard your information when you visit our website, use our services, or interact with our company.'
		),
		array(
			'id'          => 'information-collect',
			'title'       => '2. Information We Collect',
			'content'     => 'We collect several types of information from and about users of our website and services, including:<br><br>
			<strong>Personal Information:</strong><br>
			• Name, address, phone number, and email address<br>
			• Service address and property details<br>
			• Payment information (processed securely through third-party providers)<br>
			• Equipment details and service history<br><br>
			<strong>Automatically Collected Information:</strong><br>
			• IP address and browser type<br>
			• Operating system and device information<br>
			• Pages visited and time spent on our website<br>
			• Referral source and website usage patterns<br><br>
			<strong>Cookies and Tracking Technologies:</strong><br>
			• Session cookies for website functionality<br>
			• Analytics cookies to understand usage patterns<br>
			• Advertising cookies for personalized marketing'
		),
		array(
			'id'          => 'information-use',
			'title'       => '3. How We Use Your Information',
			'content'     => 'We use your information for the following purposes:<br><br>
			<strong>Service Provision:</strong><br>
			• To provide HVAC installation, repair, and maintenance services<br>
			• To schedule appointments and dispatch technicians<br>
			• To process payments and manage billing<br>
			• To maintain equipment and service records<br><br>
			<strong>Communication:</strong><br>
			• To respond to inquiries and service requests<br>
			• To send appointment reminders and service updates<br>
			• To provide maintenance plan information and renewal notices<br>
			• To share important safety and service information<br><br>
			<strong>Business Operations:</strong><br>
			• To improve our website and services<br>
			• To analyze usage patterns and optimize user experience<br>
			• To conduct market research and customer surveys<br>
			• To comply with legal and regulatory requirements'
		),
		array(
			'id'          => 'information-sharing',
			'title'       => '4. Information Sharing',
			'content'     => 'We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy:<br><br>
			<strong>Service Providers:</strong><br>
			• Payment processors for secure transaction handling<br>
			• HVAC equipment manufacturers for warranty registration<br>
			• Insurance providers for claims processing<br>
			• Marketing platforms for email communications<br><br>
			<strong>Legal Requirements:</strong><br>
			• When required by law, subpoena, or court order<br>
			• To protect our rights, property, or safety<br>
			• To protect our customers or the public from harm<br>
			• In connection with a business transaction (merger, acquisition, etc.)<br><br>
			<strong>With Your Consent:</strong><br>
			• When you explicitly authorize us to share specific information<br>
		• For joint marketing programs with trusted partners'
		),
		array(
			'id'          => 'data-security',
			'title'       => '5. Data Security',
			'content'     => 'We implement appropriate security measures to protect your personal information:<br><br>
			<strong>Technical Safeguards:</strong><br>
			• SSL encryption for all data transmissions<br>
			• Secure servers with firewall protection<br>
			• Regular security audits and vulnerability assessments<br>
			• Limited employee access to customer data<br><br>
			<strong>Administrative Safeguards:</strong><br>
			• Employee training on privacy and security practices<br>
			• Confidentiality agreements with all staff<br>
			• Strict access controls and authentication protocols<br>
			• Regular security awareness training<br><br>
			<strong>Physical Safeguards:</strong><br>
			• Secure facilities with controlled access<br>
			• Secure document storage and disposal procedures<br>
			• Limited physical access to data centers'
		),
		array(
			'id'          => 'data-retention',
			'title'       => '6. Data Retention',
			'content'     => 'We retain your personal information only as long as necessary to fulfill the purposes for which it was collected, unless a longer retention period is required or permitted by law:<br><br>
			<strong>Customer Records:</strong> 7 years after last service date<br>
			<strong>Financial Records:</strong> 7 years as required by tax law<br>
			<strong>Warranty Information:</strong> Duration of warranty plus 2 years<br>
			<strong>Marketing Communications:</strong> Until you unsubscribe or request removal<br>
			<strong>Website Analytics:</strong> 26 months aggregated data<br><br>
			When your information is no longer needed, we securely delete or anonymize it using appropriate methods.'
		),
		array(
			'id'          => 'your-rights',
			'title'       => '7. Your Privacy Rights',
			'content'     => 'You have the following rights regarding your personal information:<br><br>
			<strong>Access:</strong> Request a copy of the personal information we hold about you<br>
			<strong>Correction:</strong> Request correction of inaccurate or incomplete information<br>
			<strong>Deletion:</strong> Request deletion of your personal information (subject to legal requirements)<br>
			<strong>Portability:</strong> Request transfer of your data to another service provider<br>
			<strong>Restriction:</strong> Request limitation of how we use your information<br>
			<strong>Objection:</strong> Object to certain uses of your information<br><br>
			To exercise these rights, please contact us using the information provided in Section 13.'
		),
		array(
			'id'          => 'california-privacy',
			'title'       => '8. California Privacy Rights (CCPA)',
			'content'     => 'If you are a California resident, you have additional rights under the California Consumer Privacy Act (CCPA):<br><br>
			<strong>Right to Know:</strong> Access specific categories of personal information we collect<br>
			<strong>Right to Delete:</strong> Request deletion of your personal information<br>
			<strong>Right to Opt-Out:</strong> Opt-out of sale or sharing of personal information<br>
			<strong>Right to Non-Discrimination:</strong> Not receive discriminatory treatment for exercising privacy rights<br><br>
			We do not sell personal information as defined by the CCPA. To exercise your CCPA rights, please contact us using the information in Section 13.'
		),
		array(
			'id'          => 'children-privacy',
			'title'       => '9. Children\'s Privacy',
			'content'     => 'Our website and services are not intended for children under 13 years of age. We do not knowingly collect personal information from children under 13. If you believe we have collected information from a child under 13, please contact us immediately so we can delete such information promptly.'
		),
		array(
			'id'          => 'website-analytics',
			'title'       => '10. Website Analytics and Advertising',
			'content'     => 'We use third-party analytics and advertising services to understand how our website is used and to deliver relevant advertisements:<br><br>
			<strong>Google Analytics:</strong> Provides website traffic and usage insights<br>
			<strong>Facebook Pixel:</strong> For ad campaign measurement and optimization<br>
			<strong>Google Ads:</strong> For search and display advertising campaigns<br><br>
			These services may collect information about your online activities across different websites and devices. You can control advertising preferences through:<br>
			• <a href="https://adssettings.google.com" target="_blank" rel="noopener">Google Ads Settings</a><br>
			• <a href="https://www.facebook.com/ads/preferences" target="_blank" rel="noopener">Facebook Ad Preferences</a><br>
			• <a href="https://www.aboutads.info/choices/" target="_blank" rel="noopener">Digital Advertising Alliance</a>'
		),
		array(
			'id'          => 'third-party-websites',
			'title'       => '11. Third-Party Websites',
			'content'     => 'Our website may contain links to third-party websites, including HVAC manufacturers, payment processors, and industry resources. We are not responsible for the privacy practices of these third-party websites. We encourage you to review the privacy policies of any third-party websites you visit.'
		),
		array(
			'id'          => 'policy-changes',
			'title'       => '12. Changes to This Policy',
			'content'     => 'We may update this Privacy Policy from time to time to reflect changes in our practices, applicable laws, or operational requirements. We will notify you of any material changes by:<br><br>
			• Posting the updated policy on our website<br>
			• Updating the "Last Updated" date at the top of this policy<br>
			• Sending email notifications for significant changes<br>
			• Displaying prominent notices on our website<br><br>
			Your continued use of our website and services after any changes indicates your acceptance of the updated policy.'
		),
		array(
			'id'          => 'contact-information',
			'title'       => '13. Contact Information',
			'content'     => 'If you have any questions about this Privacy Policy or want to exercise your privacy rights, please contact us:<br><br>
			<strong>Sunnyside AC</strong><br>
			Attn: Privacy Officer<br>
			6609 Emerald Lake Dr<br>
			Miramar, FL 33023<br>
			Phone: ' . SUNNYSIDE_PHONE_DISPLAY . '<br>
			Email: ' . SUNNYSIDE_EMAIL_ADDRESS . '<br>
			Website: ' . home_url( '/' ) . '<br><br>
			For privacy-related inquiries, you can also email us directly at: privacy@sunnysideac.com'
		)
	);

	// SEO Variables
	$page_title    = 'Privacy Policy - Sunnyside AC';
	$meta_desc     = 'Read Sunnyside AC\'s complete privacy policy. Learn how we collect, use, and protect your personal information when you use our HVAC services and website in South Florida.';
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
							"name": "Privacy Policy",
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
				}
			]
		}
		</script>
	</head>

	<body <?php body_class(); ?>>

	<?php get_header(); ?>

	<main class="min-h-screen bg-gray-50" role="main">

		<!-- Container matching front-page style -->
		<div class="lg:px-0 max-w-7xl mx-auto">
			<section class="flex gap-10 flex-col">

				<!-- Page Header -->
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
								<span itemprop="name" class="font-semibold text-orange-500">Privacy Policy</span>
								<meta itemprop="position" content="2">
							</li>
						</ol>
					</nav>

					<!-- Page Title -->
					<div class="text-center mb-8">
						<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
							<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
								Privacy Policy
							</span>
						</h1>

						<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
							Your privacy is important to us. Learn how we collect, use, and protect your personal information.
						</p>
					</div>

					<!-- Last Updated Date -->
					<div class="text-center">
						<p class="text-sm text-gray-500">
							Last updated: <?php echo date( 'F j, Y' ); ?>
						</p>
					</div>
				</header>

				<!-- Table of Contents -->
				<aside class="table-of-contents bg-white rounded-[20px] p-6 md:p-8 mb-6" aria-labelledby="toc-heading">
					<header class="mb-6">
						<h2 id="toc-heading" class="text-2xl font-bold text-gray-900 mb-2">Table of Contents</h2>
						<p class="text-gray-600">Quick navigation to all sections</p>
					</header>

					<nav aria-label="Privacy policy sections navigation" class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
						<?php foreach ( $privacy_sections as $index => $section ) : ?>
							<a href="#<?php echo esc_attr( $section['id'] ); ?>"
							   class="block p-3 text-sm text-gray-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all duration-200">
								<?php echo esc_html( $section['title'] ); ?>
							</a>
						<?php endforeach; ?>
					</nav>
				</aside>

				<!-- Privacy Content -->
				<article class="privacy-content bg-white rounded-[20px] p-6 md:p-10">

					<?php foreach ( $privacy_sections as $section ) : ?>
						<section id="<?php echo esc_attr( $section['id'] ); ?>" class="mb-12 scroll-mt-6">
							<h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-4">
								<?php echo esc_html( $section['title'] ); ?>
							</h2>
							<div class="prose prose-lg max-w-none text-gray-600">
								<?php echo wpautop( $section['content'] ); ?>
							</div>
						</section>
					<?php endforeach; ?>

					<!-- Back to Top Button -->
					<div class="text-center mt-16">
						<a href="#top"
						   class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-3 text-base font-medium text-white transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
							<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
							</svg>
							Back to Top
						</a>
					</div>

				</article>

				<!-- Contact CTA Section -->
				<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center" aria-labelledby="contact-cta-heading">
					<h2 id="contact-cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
						Questions About Your Privacy?
					</h2>
					<p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
						If you have any questions about our privacy practices or want to exercise your privacy rights, please contact us.
					</p>

					<div class="flex flex-col sm:flex-row justify-center gap-4">
						<a href="mailto:privacy@sunnysideac.com"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
							<span class="text-lg font-bold text-orange-500">
								Email Privacy Officer
							</span>
						</a>

						<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
							<span class="text-lg font-bold text-white">
								Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
							</span>
						</a>
					</div>
				</section>

			</section>
		</div>

	</main>

	<!-- Privacy Policy JavaScript -->
	<script>
		(function() {
			'use strict';

			// Smooth scrolling for anchor links
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

			// Highlight active section in table of contents
			const sections = document.querySelectorAll('section[id]');
			const tocLinks = document.querySelectorAll('.table-of-contents a[href^="#"]');

			function highlightTocLink() {
				let currentSection = '';

				sections.forEach(section => {
					const sectionTop = section.offsetTop;
					const sectionHeight = section.offsetHeight;

					if (window.pageYOffset >= (sectionTop - 200)) {
						currentSection = section.getAttribute('id');
					}
				});

				tocLinks.forEach(link => {
					link.classList.remove('text-orange-500', 'bg-orange-50');
					link.classList.add('text-gray-600');

					if (link.getAttribute('href') === '#' + currentSection) {
						link.classList.remove('text-gray-600');
						link.classList.add('text-orange-500', 'bg-orange-50');
					}
				});
			}

			// Listen for scroll events
			window.addEventListener('scroll', highlightTocLink);

			// Initial highlight on page load
			highlightTocLink();

			// Print functionality
			function addPrintButton() {
				const printButton = document.createElement('button');
				printButton.innerHTML = 'Print Policy';
				printButton.className = 'fixed bottom-6 right-6 bg-gradient-to-r from-[#fb9939] to-[#e5462f] text-white px-4 py-2 rounded-lg shadow-lg hover:scale-105 transition-all z-50';
				printButton.addEventListener('click', () => {
					window.print();
				});
				document.body.appendChild(printButton);
			}

			// Add print button after page load
			if (window.addEventListener) {
				window.addEventListener('load', addPrintButton);
			} else if (window.attachEvent) {
				window.attachEvent('onload', addPrintButton);
			}
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
				<p class="text-lg text-gray-600 mb-8">The privacy policy page you're looking for doesn't exist or has been removed.</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-white font-medium hover:opacity-90">
					Return to Home
				</a>
			</div>
		</section>
	</div>

<?php endif; ?>