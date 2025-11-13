<?php
/**
 * Template Name: Terms & Conditions
 * Template for displaying terms and conditions page
 * URL: /terms-conditions/
 */

get_header();

if ( have_posts() ) :
	the_post();

	$page_id    = get_the_ID();
	$page_title = get_the_title();

	// Terms and Conditions Sections
	$terms_sections = array(
		array(
			'id'          => 'acceptance',
			'title'       => '1. Acceptance of Terms',
			'content'     => 'By accessing and using Sunnyside AC\'s website and services, you acknowledge that you have read, understood, and agree to be bound by these Terms and Conditions. If you do not agree to these terms, please do not use our services or website.'
		),
		array(
			'id'          => 'services',
			'title'       => '2. Services Description',
			'content'     => 'Sunnyside AC provides professional HVAC services including installation, repair, maintenance, and emergency services for residential and commercial customers in South Florida. We reserve the right to modify, suspend, or discontinue any service at any time without notice.'
		),
		array(
			'id'          => 'user-responsibilities',
			'title'       => '3. User Responsibilities',
			'content'     => 'As a user of our services, you agree to: (a) provide accurate and complete information; (b) maintain the security of your account credentials; (c) notify us immediately of any unauthorized use; (d) use our services for lawful purposes only; (e) not interfere with or disrupt our services or servers.'
		),
		array(
			'id'          => 'payment-terms',
			'title'       => '4. Payment Terms',
			'content'     => 'Payment for services is due upon completion unless otherwise agreed in writing. We accept cash, check, credit cards, and financing options. Late payments may be subject to interest charges of 1.5% per month. Returned checks are subject to a $35 fee. All prices are subject to change without notice.'
		),
		array(
			'id'          => 'cancellation-policy',
			'title'       => '5. Cancellation Policy',
			'content'     => 'Customers may cancel scheduled appointments with at least 24 hours\' notice without penalty. Cancellations made less than 24 hours before the scheduled appointment may be subject to a $75 cancellation fee. Emergency service cancellations must be made at least 2 hours before the scheduled time.'
		),
		array(
			'id'          => 'warranty-terms',
			'title'       => '6. Warranty Terms',
			'content'     => 'All warranties are subject to the terms and conditions set forth by the manufacturer and Sunnyside AC. Warranty coverage may be voided by improper installation, lack of maintenance, unauthorized repairs, or damage from misuse. Extended warranty options are available for purchase.'
		),
		array(
			'id'          => 'liability-limitation',
			'title'       => '7. Limitation of Liability',
			'content'     => 'Sunnyside AC shall not be liable for any indirect, incidental, special, or consequential damages arising from the use of our services. Our total liability for any claim shall not exceed the amount paid for the specific service that gave rise to the claim.'
		),
		array(
			'id'          => 'indemnification',
			'title'       => '8. Indemnification',
			'content'     => 'You agree to indemnify and hold Sunnyside AC, its employees, and contractors harmless from any claims, damages, or expenses arising from your use of our services, violation of these terms, or infringement of any third-party rights.'
		),
		array(
			'id'          => 'intellectual-property',
			'title'       => '9. Intellectual Property',
			'content'     => 'All content on this website, including text, graphics, logos, images, and software, is the property of Sunnyside AC and protected by copyright and other intellectual property laws. You may not use, reproduce, or distribute our content without our prior written consent.'
		),
		array(
			'id'          => 'privacy-policy',
			'title'       => '10. Privacy Policy',
			'content'     => 'Your privacy is important to us. Our collection, use, and protection of your personal information is governed by our Privacy Policy, which is incorporated into these Terms by reference. By using our services, you consent to the collection and use of information as described in our Privacy Policy.'
		),
		array(
			'id'          => 'force-majeure',
			'title'       => '11. Force Majeure',
			'content'     => 'Sunnyside AC shall not be liable for any failure or delay in performance under these terms due to circumstances beyond our reasonable control, including but not limited to acts of God, war, terrorism, natural disasters, or government actions.'
		),
		array(
			'id'          => 'dispute-resolution',
			'title'       => '12. Dispute Resolution',
			'content'     => 'Any disputes arising from these terms or our services shall be resolved through good faith negotiations. If a resolution cannot be reached, the dispute shall be resolved through binding arbitration in Broward County, Florida, in accordance with the rules of the American Arbitration Association.'
		),
		array(
			'id'          => 'termination',
			'title'       => '13. Termination',
			'content'     => 'We may terminate or suspend your access to our services immediately, without prior notice or liability, for any reason, including if you breach the Terms. Upon termination, your right to use the services will cease immediately.'
		),
		array(
			'id'          => 'governing-law',
			'title'       => '14. Governing Law',
			'content'     => 'These Terms and Conditions shall be governed by and construed in accordance with the laws of the State of Florida, without regard to its conflict of law provisions. Any legal action or proceeding arising under these terms will be brought exclusively in the federal or state courts located in Broward County, Florida.'
		),
		array(
			'id'          => 'modifications',
			'title'       => '15. Modifications to Terms',
			'content'     => 'Sunnyside AC reserves the right to modify these Terms and Conditions at any time. We will notify you of any changes by posting the new terms on this page and updating the "Last Updated" date. Your continued use of our services after any such changes constitutes your acceptance of the new terms.'
		),
		array(
			'id'          => 'severability',
			'title'       => '16. Severability',
			'content'     => 'If any provision of these Terms is found to be unenforceable or invalid, that provision will be limited or eliminated to the minimum extent necessary so that the remaining terms will remain in full force and effect.'
		),
		array(
			'id'          => 'entire-agreement',
			'title'       => '17. Entire Agreement',
			'content'     => 'These Terms and Conditions, along with our Privacy Policy, constitute the entire agreement between you and Sunnyside AC regarding the use of our services and website. They supersede all prior agreements, communications, and understandings.'
		),
		array(
			'id'          => 'contact-information',
			'title'       => '18. Contact Information',
			'content'     => 'If you have any questions about these Terms and Conditions, please contact us at:<br><br>
			<strong>Sunnyside AC</strong><br>
			6609 Emerald Lake Dr<br>
			Miramar, FL 33023<br>
			Phone: ' . SUNNYSIDE_PHONE_DISPLAY . '<br>
			Email: ' . SUNNYSIDE_EMAIL_ADDRESS . '<br>
			Website: ' . home_url( '/' )
		)
	);

	// SEO Variables
	$page_title    = 'Terms & Conditions - Sunnyside AC';
	$meta_desc     = 'Read Sunnyside AC\'s complete terms and conditions. Learn about our service policies, payment terms, warranties, and customer agreements for HVAC services in South Florida.';
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
							"name": "Terms & Conditions",
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
								<span itemprop="name" class="font-semibold text-orange-500">Terms & Conditions</span>
								<meta itemprop="position" content="2">
							</li>
						</ol>
					</nav>

					<!-- Page Title -->
					<div class="text-center mb-8">
						<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
							<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
								Terms & Conditions
							</span>
						</h1>

						<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
							Please read these terms and conditions carefully before using our HVAC services and website.
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

					<nav aria-label="Terms sections navigation" class="grid md:grid-cols-2 lg:grid-cols-3 gap-3">
						<?php foreach ( $terms_sections as $index => $section ) : ?>
							<a href="#<?php echo esc_attr( $section['id'] ); ?>"
							   class="block p-3 text-sm text-gray-600 hover:text-orange-500 hover:bg-orange-50 rounded-lg transition-all duration-200">
								<?php echo esc_html( $section['title'] ); ?>
							</a>
						<?php endforeach; ?>
					</nav>
				</aside>

				<!-- Terms Content -->
				<article class="terms-content bg-white rounded-[20px] p-6 md:p-10">

					<?php foreach ( $terms_sections as $section ) : ?>
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
						Questions About Our Terms?
					</h2>
					<p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
						If you have any questions about our terms and conditions, please don\'t hesitate to contact us.
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
								Contact Us
							</span>
						</a>
					</div>
				</section>

			</section>
		</div>

	</main>

	<!-- Terms Page JavaScript -->
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
				printButton.innerHTML = 'Print Terms';
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
				<p class="text-lg text-gray-600 mb-8">The terms and conditions page you're looking for doesn't exist or has been removed.</p>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-white font-medium hover:opacity-90">
					Return to Home
				</a>
			</div>
		</section>
	</div>

<?php endif; ?>