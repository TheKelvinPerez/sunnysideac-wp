<?php
/**
 * Template: single-service-city.php
 * Displays a service in the context of a specific city
 * URL structure: /{city-slug}/{service-slug}/
 *
 * SEO Optimized: JSON-LD Schema, Meta Tags, Semantic HTML, Internal Linking
 */

// Service post is the main loop
if ( have_posts() ) :
	the_post();

	$service_id = get_the_ID();
	$city_slug  = get_query_var( 'city_slug' );
	$city_post  = $city_slug ? get_page_by_path( $city_slug, OBJECT, 'city' ) : null;

	// Get ACF fields
	$service_description = get_field( 'service_description', $service_id );
	$service_benefits    = get_field( 'service_benefits', $service_id );
	$service_process     = get_field( 'service_process', $service_id );
	$service_faqs        = get_field( 'service_faqs', $service_id );

	if ( $city_post ) {
		$city_neighborhoods     = get_field( 'neighborhoods', $city_post->ID );
		$city_zip_codes         = get_field( 'zip_codes', $city_post->ID );
		$city_climate_note      = get_field( 'climate_note', $city_post->ID );
		$city_service_area_note = get_field( 'service_area_note', $city_post->ID );
		$city_video_url         = get_field( 'city_video_url', $city_post->ID );
		$city_video_title       = get_field( 'city_video_title', $city_post->ID );
		$city_video_description = get_field( 'city_video_description', $city_post->ID );
		$city_video_thumbnail   = get_field( 'city_video_thumbnail', $city_post->ID );
		$city_video_duration    = get_field( 'city_video_duration', $city_post->ID );
		$city_faqs              = get_field( 'city_faqs', $city_post->ID );
	}

	// Merge city-specific FAQs with service FAQs for comprehensive coverage
	$all_faqs = [];
	if ( ! empty( $city_faqs ) && is_array( $city_faqs ) ) {
		// Add city-specific FAQs first (they're more locally relevant)
		$all_faqs = array_merge( $all_faqs, $city_faqs );
	}
	if ( ! empty( $service_faqs ) && is_array( $service_faqs ) ) {
		// Add service FAQs after city FAQs
		$all_faqs = array_merge( $all_faqs, $service_faqs );
	}

	// SEO Variables
	$service_title = get_the_title();
	$city_name     = $city_post ? get_the_title( $city_post ) : '';
	$page_title    = $city_post ? $service_title . ' in ' . $city_name . ', FL - Sunnyside AC' : $service_title;
	$meta_desc     = $city_post
		? 'Expert ' . strtolower( $service_title ) . ' services in ' . $city_name . ', Florida. Licensed technicians, same-day service, 24/7 emergency repairs. Call ' . SUNNYSIDE_PHONE_DISPLAY . ' for fast service.'
		: wp_trim_words( get_the_excerpt(), 20 );

	$canonical_url = home_url( '/' . ( $city_post ? $city_slug . '/' : '' ) . get_post_field( 'post_name', $service_id ) . '/' );
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

	<!-- Local Business Meta -->
	<?php if ( $city_post ) : ?>
	<meta name="geo.region" content="US-FL">
	<meta name="geo.placename" content="<?php echo esc_attr( $city_name ); ?>">
	<?php endif; ?>

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
					}
					<?php
					if ( $city_post ) :
						?>
						,
					{
						"@type": "ListItem",
						"position": 2,
						"name": "<?php echo esc_js( $city_name ); ?>",
						"item": "<?php echo esc_url( home_url( '/' . $city_slug . '/' ) ); ?>"
					}
					<?php endif; ?>,
					{
						"@type": "ListItem",
						"position": <?php echo $city_post ? '3' : '2'; ?>,
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
				<?php
				if ( $city_post ) :
					?>
					,
				"areaServed": {
					"@type": "City",
					"name": "<?php echo esc_js( $city_name ); ?>"
				}
				<?php endif; ?>
			}
			<?php
			if ( $service_faqs ) :
				?>
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
			<?php
			if ( $service_process ) :
				?>
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
			<?php if ( ! empty( $city_video_url ) ) : ?>
				<?php
				$video_data = sunnysideac_parse_video_url( $city_video_url );
				if ( $video_data ) :
					?>
			,{
				"@type": "VideoObject",
				"name": "<?php echo ! empty( $city_video_title ) ? esc_js( $city_video_title ) : esc_js( $service_title . ' in ' . $city_name . ', Florida' ); ?>",
				"description": "<?php echo ! empty( $city_video_description ) ? esc_js( $city_video_description ) : esc_js( 'Video about ' . $service_title . ' services in ' . $city_name ); ?>",
				"thumbnailUrl": "<?php echo ! empty( $city_video_thumbnail ) ? esc_url( $city_video_thumbnail ) : esc_url( $video_data['thumbnail_url'] ); ?>",
				"uploadDate": "<?php echo esc_attr( get_the_date( 'c', $city_post ) ); ?>",
				"contentUrl": "<?php echo esc_url( $video_data['watch_url'] ); ?>",
				"embedUrl": "<?php echo esc_url( $video_data['embed_url'] ); ?>"
					<?php if ( ! empty( $city_video_duration ) ) : ?>
				,"duration": "<?php echo esc_js( $city_video_duration ); ?>"
				<?php endif; ?>
			}
				<?php endif; ?>
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
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'service-city-page' ); ?>>

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
							<?php if ( $city_post ) : ?>
								<li class="text-gray-400">/</li>
								<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
									<a itemprop="item" href="<?php echo esc_url( home_url( '/' . $city_slug . '/' ) ); ?>" class="hover:text-orange-500">
										<span itemprop="name"><?php echo esc_html( $city_name ); ?></span>
									</a>
									<meta itemprop="position" content="2">
								</li>
							<?php endif; ?>
							<li class="text-gray-400">/</li>
							<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
								<span itemprop="name" class="font-semibold text-orange-500"><?php echo esc_html( $service_title ); ?></span>
								<meta itemprop="position" content="<?php echo $city_post ? '3' : '2'; ?>">
							</li>
						</ol>
					</nav>

					<!-- Main Title with Gradient -->
					<div class="text-center mb-8">
						<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4" itemprop="name">
							<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
								<?php echo esc_html( $service_title ); ?>
							</span>
							<?php if ( $city_post ) : ?>
								<span class="block text-gray-800 mt-2">
									in <?php echo esc_html( $city_name ); ?>, Florida
								</span>
							<?php endif; ?>
						</h1>

						<?php if ( $city_post && $city_climate_note ) : ?>
							<p class="text-lg md:text-xl text-gray-600 max-w-4xl mx-auto leading-relaxed italic">
								<?php echo esc_html( $city_climate_note ); ?>
							</p>
						<?php endif; ?>
					</div>

					<!-- CTA Buttons - matching front page style -->
					<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
						<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none"
							aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
							<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
								Schedule Service Now
							</span>
						</a>

						<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
							class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
							aria-label="Call us now - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
							<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
								Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
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
									'alt'      => esc_attr( $service_title . ' services in ' . $city_name . ', Florida' ),
								]
							);
							?>
							<meta itemprop="width" content="1200">
							<meta itemprop="height" content="630">
						</figure>
					<?php endif; ?>
				</header>

				<!-- City Video Section (High Local SEO Value) -->
				<?php
				if ( $city_post && ! empty( $city_video_url ) ) :
					// Set template part variables for video component
					set_query_var( 'video_url', $city_video_url );
					set_query_var( 'video_title', $city_video_title );
					set_query_var( 'video_description', $city_video_description );
					set_query_var( 'video_thumbnail', $city_video_thumbnail );
					set_query_var( 'video_duration', $city_video_duration );
					set_query_var( 'service_title', $service_title );
					set_query_var( 'city_name', $city_name );
					set_query_var( 'city_post', $city_post );

					// Include responsive video component
					get_template_part( 'template-parts/video-embed' );
				endif;
				?>

				<!-- Main Service Description - Redesigned -->
				<?php if ( $service_description ) : ?>
					<div class="service-description bg-white rounded-[20px] p-6 md:p-10 mb-6" itemprop="description">
						<div class="prose prose-lg max-w-none">
							<?php echo wp_kses_post( $service_description ); ?>
						</div>
					</div>
				<?php endif; ?>

				<!-- Service Benefits - Redesigned as Cards -->
				<?php if ( $service_benefits ) : ?>
					<section class="service-benefits bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="benefits-heading">
						<header class="text-center mb-8">
							<h2 id="benefits-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Why Choose Our <?php echo esc_html( $service_title ); ?> Services
								<?php echo $city_post ? 'in ' . esc_html( $city_name ) : ''; ?>?
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

				<!-- Service Process - Matching work-process.php style -->
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

				<!-- Service Area Information (City-Specific) -->
				<?php if ( $city_post && $city_service_area_note ) : ?>
					<section class="service-area-info bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="service-area-heading">
						<header class="mb-6">
							<h2 id="service-area-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Serving <?php echo esc_html( $city_name ); ?>
							</h2>
						</header>

						<div class="prose prose-lg max-w-none">
							<?php echo wp_kses_post( $city_service_area_note ); ?>
						</div>

						<!-- Neighborhoods Grid -->
						<?php if ( $city_neighborhoods ) : ?>
							<?php
							$neighborhoods_array = array_filter( array_map( 'trim', explode( "\n", $city_neighborhoods ) ) );
							if ( ! empty( $neighborhoods_array ) ) :
								?>
								<div class="mt-8">
									<h3 class="text-xl font-semibold text-gray-900 mb-4">
										Areas We Serve in <?php echo esc_html( $city_name ); ?>:
									</h3>
									<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
										<?php foreach ( $neighborhoods_array as $neighborhood ) : ?>
											<div class="bg-gray-50 rounded-lg px-4 py-2 text-center text-sm text-gray-700 hover:bg-orange-50 transition-colors">
												<?php echo esc_html( $neighborhood ); ?>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						<?php endif; ?>

						<!-- Zip Codes Grid -->
						<?php if ( $city_zip_codes ) : ?>
							<?php
							$zip_codes_array = array_filter( array_map( 'trim', explode( "\n", $city_zip_codes ) ) );
							if ( ! empty( $zip_codes_array ) ) :
								?>
								<div class="mt-8">
									<h3 class="text-xl font-semibold text-gray-900 mb-4">
										Zip Codes:
									</h3>
									<div class="grid grid-cols-3 md:grid-cols-5 lg:grid-cols-6 gap-3">
										<?php foreach ( $zip_codes_array as $zip_code ) : ?>
											<div class="bg-orange-50 rounded-lg px-4 py-2 text-center text-sm font-medium text-orange-700 hover:bg-orange-100 transition-colors">
												<?php echo esc_html( $zip_code ); ?>
											</div>
										<?php endforeach; ?>
									</div>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</section>
				<?php endif; ?>

				<!-- FAQs - Matching Homepage Style -->
				<?php if ( ! empty( $all_faqs ) ) : ?>
					<section class="w-full rounded-2xl bg-white px-4 py-12 md:px-10 md:py-16 lg:py-20 mb-6" id="faq-section" role="region" aria-labelledby="faq-heading">
						<div class="mx-auto max-w-7xl">
							<!-- Header -->
							<header class="mb-12 text-center md:mb-16">
								<h2 id="faq-heading" class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4">
									Frequently Asked Questions
								</h2>
								<p class="text-xl md:text-2xl font-medium text-gray-700 mb-4">
									Got Questions? We've Got Answers!
								</p>
								<p class="text-base font-light text-gray-700 md:text-lg">
									Find answers to common questions about <?php echo esc_html( $service_title ); ?> in <?php echo esc_html( $city_name ); ?>.
								</p>
							</header>

							<!-- FAQ Grid -->
							<div class="grid gap-4 md:gap-6">
								<?php foreach ( $all_faqs as $index => $faq ) : ?>
									<div class="faq-item w-full">
										<input type="checkbox" id="faq-<?php echo esc_attr( $index + 1 ); ?>" class="faq-toggle hidden" />

										<label for="faq-<?php echo esc_attr( $index + 1 ); ?>" class="block w-full cursor-pointer">
											<div class="faq-container relative w-full rounded-[20px] border-2 border-transparent bg-[#f6f6f6] transition-all duration-300 ease-in-out hover:shadow-md" itemscope itemprop="mainEntity" itemtype="https://schema.org/Question">
												<div class="flex items-start justify-between p-6">
													<h3 class="pr-4 text-lg leading-relaxed font-semibold text-black md:text-xl" itemprop="name">
														<?php echo esc_html( $faq['question'] ); ?>
													</h3>

													<div class="faq-chevron h-[35px] w-[35px] flex-shrink-0 rounded-full shadow-md transition-all duration-300 ease-in-out hover:scale-110">
														<img class="chevron-icon h-full w-full transition-transform duration-300 ease-in-out"
															 alt="Toggle FAQ"
															 src="<?php echo esc_url( sunnysideac_asset_url('assets/images/home-page/faq-chevron-down-circle.svg') ); ?>"
															 loading="lazy"
															 decoding="async" />
													</div>
												</div>

												<div class="faq-content max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out" itemscope itemprop="acceptedAnswer" itemtype="https://schema.org/Answer">
													<div class="px-6 pb-6 text-base leading-relaxed font-light text-gray-700 md:text-lg" itemprop="text">
														<?php echo wp_kses_post( wpautop( $faq['answer'] ) ); ?>
													</div>
												</div>
											</div>
										</label>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</section>

					<style>
					/* CSS-only accordion functionality - matching homepage */
					.faq-toggle:checked + label .faq-container {
						background-color: #ffeac0;
						border-color: #fed7aa;
					}

					.faq-toggle:checked + label .faq-content {
						max-height: 1000px;
						opacity: 1;
					}

					.faq-toggle:checked + label .chevron-icon {
						transform: rotate(180deg);
					}

					.faq-toggle:checked + label .faq-chevron {
						box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
					}

					/* Enhanced hover effects */
					.faq-item:hover .faq-chevron {
						transform: scale(1.1);
					}
					</style>
				<?php endif; ?>

				<!-- Internal Links Section - Redesigned as Grid Cards -->
				<?php
				// Get related services for internal linking
				$all_services = get_posts(
					[
						'post_type'      => 'service',
						'posts_per_page' => 6,
						'post__not_in'   => [ $service_id ],
						'orderby'        => 'rand',
					]
				);

				if ( $all_services && $city_post ) :
					?>
					<section class="related-services bg-white rounded-[20px] p-6 md:p-10 mb-6" aria-labelledby="related-services-heading">
						<header class="text-center mb-8">
							<h2 id="related-services-heading" class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
								Other HVAC Services in <?php echo esc_html( $city_name ); ?>
							</h2>
							<p class="text-lg text-gray-600">
								Comprehensive HVAC solutions for your home or business
							</p>
						</header>

						<nav aria-label="Related services">
							<div class="grid md:grid-cols-2 lg:grid-cols-3 gap-4">
								<?php foreach ( array_slice( $all_services, 0, 6 ) as $related_service ) : ?>
									<a href="<?php echo esc_url( home_url( '/' . $city_slug . '/' . $related_service->post_name . '/' ) ); ?>"
										class="group block bg-gray-50 rounded-2xl p-6 transition-all duration-300 hover:scale-105 hover:bg-gradient-to-br hover:from-orange-50 hover:to-orange-100 hover:shadow-lg">
										<h3 class="font-bold text-lg text-gray-900 mb-2 group-hover:text-orange-500">
											<?php echo esc_html( get_the_title( $related_service ) ); ?>
										</h3>
										<p class="text-gray-600 text-sm">
											Professional service in <?php echo esc_html( $city_name ); ?>
										</p>
										<span class="inline-flex items-center mt-3 text-orange-500 font-medium text-sm">
											Learn More
											<svg class="w-4 h-4 ml-1 transform transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
											</svg>
										</span>
									</a>
								<?php endforeach; ?>
							</div>
						</nav>
					</section>
				<?php endif; ?>

				<!-- Call to Action - Matching hero gradient style -->
				<section class="cta-section bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 md:p-12 mb-6 text-center" aria-labelledby="cta-heading">
					<h2 id="cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
						Ready to Get Started?
					</h2>
					<p class="text-xl text-white mb-8 max-w-2xl mx-auto">
						Contact us today for expert <?php echo strtolower( $service_title ); ?>
						<?php echo $city_post ? 'in ' . esc_html( $city_name ) : ''; ?>
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
<?php endif; ?>
