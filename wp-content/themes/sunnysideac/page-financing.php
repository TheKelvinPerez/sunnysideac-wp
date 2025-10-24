<?php
/**
 * Template Name: Financing Page
 * Template for displaying the Financing Options page
 * URL: /financing/
 */

get_header();

// SEO Variables
$page_title    = 'HVAC Financing Options | Flexible Payment Plans - Sunnyside AC';
$meta_desc     = 'Flexible HVAC financing options with quick approval and competitive rates. Make your home comfort affordable with our financing plans. 0% APR available for qualified buyers.';
$canonical_url = home_url( '/financing/' );
$og_image      = sunnysideac_asset_url( 'assets/images/home-page/hero/hero-image.png' );

// Financing benefits
$financing_benefits = array(
	array(
		'title'       => 'Flexible Payment Plans',
		'description' => 'Choose from various payment options that fit your budget and financial situation.',
	),
	array(
		'title'       => 'Quick Approval',
		'description' => 'Get approved in minutes with our streamlined financing application process.',
	),
	array(
		'title'       => 'Same-Day Installation',
		'description' => 'Once approved, we can often install your new HVAC system the same day.',
	),
	array(
		'title'       => 'Competitive Rates',
		'description' => 'Enjoy competitive interest rates and special promotional offers on select plans.',
	),
	array(
		'title'       => 'No Hidden Fees',
		'description' => 'Transparent pricing with no hidden fees or surprises in your financing agreement.',
	),
	array(
		'title'       => 'Dedicated Support',
		'description' => 'Our financing specialists are here to help you every step of the way.',
	),
);

// How it works steps
$process_steps = array(
	array(
		'number'      => '1',
		'title'       => 'Apply Online or In-Person',
		'description' => 'Fill out our simple financing application online or speak with one of our specialists during your service visit.',
	),
	array(
		'number'      => '2',
		'title'       => 'Get Instant Approval',
		'description' => 'Receive a financing decision in minutes. Most applications are approved on the spot.',
	),
	array(
		'number'      => '3',
		'title'       => 'Choose Your Plan',
		'description' => 'Select the payment plan that works best for your budget and financial goals.',
	),
	array(
		'number'      => '4',
		'title'       => 'Schedule Installation',
		'description' => 'Once approved, we\'ll schedule your HVAC installation at a time that\'s convenient for you.',
	),
);

// FAQs
$faqs = array(
	array(
		'question' => 'What credit score do I need to qualify for financing?',
		'answer'   => 'We work with multiple financing partners to offer options for a wide range of credit scores. Even if you have less-than-perfect credit, we likely have a financing solution that can work for you. Apply today to see what options are available.',
	),
	array(
		'question' => 'How long does the approval process take?',
		'answer'   => 'Most financing applications are reviewed and approved within minutes. You can apply online before your appointment or during your service visit, and our team will help you through the process.',
	),
	array(
		'question' => 'Are there any special promotional offers available?',
		'answer'   => 'Yes! We regularly offer special financing promotions, including 0% interest for qualified buyers. Contact us to learn about current offers and find the best financing option for your needs.',
	),
	array(
		'question' => 'Can I pay off my financing early without penalties?',
		'answer'   => 'Most of our financing plans allow for early payoff without any prepayment penalties. Check your specific financing agreement for details, or ask our financing specialists about prepayment terms.',
	),
	array(
		'question' => 'What can I finance with your program?',
		'answer'   => 'Our financing options cover all HVAC services including new system installations, replacements, repairs, and maintenance plans. Whether you need a new air conditioner, heating system, or emergency repair, we have financing available.',
	),
	array(
		'question' => 'Do I need a down payment?',
		'answer'   => 'Down payment requirements vary depending on the financing plan you choose. Many of our options require little to no down payment. Our financing specialists will help you understand the requirements for each available plan.',
	),
);
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
	<meta property="og:type" content="website">
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
						"name": "Financing",
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
				"@type": "FinancialProduct",
				"name": "HVAC Financing",
				"description": "Flexible financing options for HVAC installations, replacements, and repairs with quick approval and competitive rates",
				"provider": {
					"@type": "LocalBusiness",
					"name": "Sunnyside AC"
				},
				"featureList": [
					"Flexible Payment Plans",
					"Quick Approval Process",
					"Competitive Interest Rates",
					"No Hidden Fees",
					"Same-Day Installation Available",
					"Dedicated Financing Support"
				]
			},
			{
				"@type": "HowTo",
				"name": "How to Apply for HVAC Financing",
				"step": [
					<?php foreach ( $process_steps as $index => $step ) : ?>
					{
						"@type": "HowToStep",
						"position": <?php echo $index + 1; ?>,
						"name": "<?php echo esc_js( $step['title'] ); ?>",
						"text": "<?php echo esc_js( $step['description'] ); ?>"
					}<?php echo $index < count( $process_steps ) - 1 ? ',' : ''; ?>
					<?php endforeach; ?>
				]
			},
			{
				"@type": "FAQPage",
				"mainEntity": [
					<?php
					$faq_count = count( $faqs );
					foreach ( $faqs as $index => $faq ) :
						?>
					{
						"@type": "Question",
						"name": "<?php echo esc_js( $faq['question'] ); ?>",
						"acceptedAnswer": {
							"@type": "Answer",
							"text": "<?php echo esc_js( $faq['answer'] ); ?>"
						}
					}<?php echo $index < $faq_count - 1 ? ',' : ''; ?>
					<?php endforeach; ?>
				]
			}
		]
	}
	</script>
</head>

<body <?php body_class(); ?>>

<?php get_header(); ?>

<main class="min-h-screen bg-gray-50" role="main" itemscope itemtype="https://schema.org/WebPage">

	<!-- Container matching single service/city style -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">

			<!-- Page Article -->
			<article id="page-financing" class="financing-page">

				<!-- Page Header - Breadcrumbs & Title -->
				<header class="entry-header bg-white rounded-[20px] p-6 md:p-10 mb-6">
					<!-- Breadcrumbs with Schema.org microdata -->
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
								<span itemprop="name" class="font-semibold text-orange-500">Financing</span>
								<meta itemprop="position" content="2">
							</li>
						</ol>
					</nav>

					<!-- Main Title with Gradient -->
					<div class="text-center mb-8">
						<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4" itemprop="name">
							<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
								Flexible HVAC Financing
							</span>
						</h1>

						<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
							Make your home comfort affordable with quick approval and competitive rates
						</p>
					</div>

					<!-- CTA Buttons -->
					<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
							<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
								Apply for Financing
							</span>
						</a>

						<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
							aria-label="Call to discuss financing - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
							<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
								Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
							</span>
						</a>
					</div>
				</header>

				<!-- Introduction Section -->
				<section class="bg-white rounded-[20px] p-6 md:p-10 mb-6" itemprop="description">
					<div class="max-w-4xl mx-auto text-center">
						<h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
							Don't Let Budget Hold You Back from Comfort
						</h2>
						<div class="prose prose-lg max-w-none">
							<p class="text-lg text-gray-700 mb-4">
								At Sunny Side 24/7 AC, we understand that HVAC installations and repairs can be a significant investment. That's why we've partnered with leading financing companies to offer flexible payment options that make it easy to get the comfort you need without breaking the bank.
							</p>
							<p class="text-lg text-gray-700">
								Whether you need a new air conditioning system, heating replacement, or emergency repair, our financing solutions help you spread the cost over time with affordable monthly payments.
							</p>
						</div>
					</div>
				</section>

				<!-- Financing Benefits -->
				<section class="financing-benefits bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="benefits-heading">
					<header class="text-center mb-8">
						<h2 id="benefits-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
							Why Choose Our Financing?
						</h2>
						<p class="text-lg text-gray-600">
							We've made it simple and stress-free to finance your HVAC needs
						</p>
					</header>

					<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
						<?php foreach ( $financing_benefits as $index => $benefit ) : ?>
							<article class="group bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
								<!-- Number Badge -->
								<div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gradient-to-br from-orange-200 to-orange-300 mb-4">
									<span class="text-xl font-bold text-orange-500">
										<?php echo $index + 1; ?>
									</span>
								</div>

								<!-- Benefit Text -->
								<h3 class="text-lg font-semibold text-gray-900 mb-2">
									<?php echo esc_html( $benefit['title'] ); ?>
								</h3>
								<p class="text-gray-600">
									<?php echo esc_html( $benefit['description'] ); ?>
								</p>
							</article>
						<?php endforeach; ?>
					</div>
				</section>

				<!-- How It Works Process -->
				<section class="financing-process bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="process-heading" itemscope itemtype="https://schema.org/HowTo">
					<header class="text-center mb-12">
						<h2 id="process-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4" itemprop="name">
							How Our Financing Works
						</h2>
						<p class="text-lg text-gray-600">
							Getting approved is quick and easy
						</p>
					</header>

					<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">
						<?php foreach ( $process_steps as $index => $step ) : ?>
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

				<!-- Apply Now CTA -->
				<section class="bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] rounded-[20px] p-8 md:p-12 text-center mb-6" aria-labelledby="apply-cta-heading">
					<h2 id="apply-cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
						Ready to Get Started?
					</h2>
					<p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
						Apply for financing today and get instant approval
					</p>

					<div class="flex flex-col sm:flex-row justify-center gap-4">
						<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-500 focus:outline-none">
							<span class="text-lg font-bold text-blue-600">
								Apply Now
							</span>
						</a>

						<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-blue-500 focus:outline-none">
							<span class="text-lg font-bold text-white">
								Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
							</span>
						</a>
					</div>
				</section>

				<!-- FAQs -->
				<?php
				// Transform FAQ data to match component format
				$formatted_faqs = array_map(
					function ( $faq ) {
						return array(
							'question' => $faq['question'],
							'answer'   => $faq['answer'],
						);
					},
					$faqs
				);

				get_template_part(
					'template-parts/faq-component',
					null,
					array(
						'faq_data'     => $formatted_faqs,
						'title'        => 'Frequently Asked Questions',
						'mobile_title' => 'FAQ',
						'subheading'   => 'Got Questions About Financing? We\'ve Got Answers!',
						'description'  => 'Find answers to common questions about our HVAC financing options.',
						'show_schema'  => false, // Schema already added in <head>
						'section_id'   => 'financing-faq-section',
					)
				);
				?>

				<!-- Final CTA Section -->
				<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 text-center" aria-labelledby="final-cta-heading">
					<h2 id="final-cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
						Questions About Financing?
					</h2>
					<p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
						Our financing specialists are here to help you understand your options
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
