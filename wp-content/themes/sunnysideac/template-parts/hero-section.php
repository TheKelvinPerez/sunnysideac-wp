<?php
/**
 * Hero Section Template Part
 * Vanilla PHP/WordPress template
 */

// Icons data
$icons = array(
	'best_refreshed'        => sunnysideac_asset_url( 'assets/icons/best-at-keeping-refreshed-icon.svg' ),
	'mobile_best_refreshed' => sunnysideac_asset_url( 'assets/icons/mobile-best-refreshed-icon.svg' ),
	'schedule_service'      => sunnysideac_asset_url( 'assets/icons/schedule-service-now-icon.svg' ),
	'call_us_now'           => sunnysideac_asset_url( 'assets/icons/call-us-now-icon.svg' ),
	'google'                => sunnysideac_asset_url( 'assets/icons/google-icon.svg' ),
	'star'                  => sunnysideac_asset_url( 'assets/icons/star-icon.svg' ),
	'hero_line_break'       => sunnysideac_asset_url( 'assets/icons/hero-line-break.svg' ),
);

// Images data
$images = array(
	'hero_right'    => sunnysideac_asset_url( 'assets/optimized/hero-right-image.webp' ),
	'mobile_hero'   => sunnysideac_asset_url( 'assets/optimized/mobile-hero-image.webp' ),
	'review_photos' => array(
		sunnysideac_asset_url( 'assets/images/images/hero/review_photo_1.png' ),
		sunnysideac_asset_url( 'assets/images/images/hero/review_photo_2.png' ),
		sunnysideac_asset_url( 'assets/images/images/hero/review_photo_3.png' ),
		sunnysideac_asset_url( 'assets/images/images/hero/review_photo_4.png' ),
	),
);

// Statistics data
$statistics = array(
	array(
		'number'      => '1.5K+',
		'description' => "Project Completed\nWith Excellence",
	),
	array(
		'number'      => '2014',
		'description' => "Family-Owned\n& Operated Since",
	),
	array(
		'number'      => '2.5K+',
		'description' => "Happy Customers\nServed",
	),
);

// Get WordPress data
$home_page_id  = get_option( 'page_on_front' );
$review_images = get_field( 'review_images', $home_page_id );
$tel_href      = SUNNYSIDE_TEL_HREF;
$phone_display = SUNNYSIDE_PHONE_DISPLAY;

/**
 * Render star rating
 *
 * @param int    $count Number of stars
 * @param string $star_icon_url URL to the star icon
 * @param string $class Additional CSS classes
 */
function sunnysideac_render_stars( $count = 5, $star_icon_url = '', $class = 'h-3 w-3 sm:h-4 sm:w-4 lg:h-5 lg:w-5' ) {
	for ( $i = 0; $i < $count; $i++ ) {
		echo '<img src="' . esc_url( $star_icon_url ) . '" alt="Star" class="' . esc_attr( $class ) . '" loading="lazy" decoding="async" />';
	}
}
?>

<section class="relative w-full overflow-hidden rounded-[20px] bg-white lg:bg-transparent">
	<!-- Mobile Background Image - Hidden on Desktop -->
	<div class="absolute inset-0 rounded-[20px] bg-cover bg-center bg-no-repeat lg:hidden"
		style="background-image: url('<?php echo esc_url( sunnysideac_asset_url( 'assets/optimized/mobile-hero-image.webp' ) ); ?>');">
	</div>

	<!-- Mobile Gradient Overlay - Hidden on Desktop -->
	<div
	class="absolute inset-0 rounded-[20px] bg-gradient-to-b from-orange-500 via-orange-600 to-black opacity-80 lg:hidden">
	</div>

	<!-- Desktop Grid Layout -->
	<div
	class="hidden min-h-[600px] gap-6 rounded-lg bg-white p-4 sm:p-6 lg:grid lg:min-h-[650px] lg:grid-cols-2 lg:gap-8 lg:rounded-[20px] lg:p-8">
	<!-- Desktop Left Column - Content -->
	<div class="flex flex-col justify-between space-y-4 md:max-w-[500px]">
		<!-- Header with Logo and Tagline -->
		<header class="flex items-center justify-center gap-2 lg:justify-start">
		<div class="h-6 w-6 flex-shrink-0 lg:h-7 lg:w-7">
			<img src="<?php echo esc_url( $icons['best_refreshed'] ); ?>" alt="Company logo" class="h-full w-full" loading="lazy"
			decoding="async" />
		</div>
		<p class="text-center text-xs leading-normal font-normal sm:text-sm lg:text-left lg:text-lg">
			<span class="text-[#4c4c4c]">The </span>
			<span class="text-[#d87711]">Best </span>
			<span class="text-[#4c4c4c]">At Keeping You </span>
			<span class="text-[#c23616]">Refreshed!</span>
		</p>
		</header>

		<!-- Main Content Area -->
		<div class="flex flex-1 flex-col justify-center">
		<!-- Main Headline - SEO Optimized -->
		<h1
			class="text-center text-xl border-red-500 leading-tight font-semibold text-black sm:text-2xl md:text-3xl lg:text-left lg:text-4xl">
			Premier AC Repair & Installation Services in South Florida | Fast, Reliable HVAC Service
		</h1>

		<!-- Decorative Line - Hidden on mobile for cleaner look -->
		<div class="hidden justify-start lg:flex">
			<img src="<?php echo esc_url( $icons['hero_line_break'] ); ?>" alt="" class="w-full max-w-md" role="presentation" loading="lazy"
			decoding="async" />
		</div>

		<!-- Subtitle -->
		<p
			class="text-center text-base leading-relaxed font-normal text-black italic sm:text-lg lg:text-left lg:text-[17px]">
			South Florida's most responsive AC pros. <br />
			Honest pricing. Same-day service. Zero stress.
		</p>

		<!-- Call to Action Buttons -->
		<div class="flex flex-col justify-center gap-3 pt-4 sm:flex-row sm:gap-4 lg:justify-start">
			<a href="<?php echo esc_attr( $tel_href ); ?>"
			class="order-1 inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-5 py-3 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none lg:px-6 lg:py-4"
			aria-label="Call to schedule AC service - <?php echo esc_attr( $phone_display ); ?>">
			<div class="h-4 w-4 flex-shrink-0 lg:h-5 lg:w-5">
				<img src="<?php echo esc_url( $icons['schedule_service'] ); ?>" alt="" class="h-full w-full" role="presentation" loading="lazy"
				decoding="async" />
			</div>
			<span class="text-base font-medium whitespace-nowrap text-white lg:text-lg">
				Schedule Service Now
			</span>
			</a>

			<a href="<?php echo esc_attr( $tel_href ); ?>"
			class="order-2 inline-flex w-full items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-5 py-3 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none lg:px-6 lg:py-4"
			aria-label="Call us now for AC service - <?php echo esc_attr( $phone_display ); ?>">
			<div class="h-4 w-4 flex-shrink-0 lg:h-5 lg:w-5">
				<img src="<?php echo esc_url( $icons['call_us_now'] ); ?>" alt="" class="h-full w-full" role="presentation" loading="lazy"
				decoding="async" />
			</div>
			<span class="text-base font-medium whitespace-nowrap text-white lg:text-lg">
				Call Us Now
			</span>
			</a>
		</div>
		</div>

		<!-- Bottom Section - Reviews and Stats -->
		<div class="space-y-4 lg:space-y-6">
		<!-- Reviews Section -->
		<section class="rounded-[20px] bg-[#eeeeee] p-4 lg:p-5" aria-label="Customer reviews">
			<div class="flex items-center justify-between gap-2 sm:gap-4 lg:gap-6">
			<!-- Review Photos -->
			<div class="flex w-[15vw] items-center gap-2">
				<?php if ( $review_images ) : ?>
					<?php foreach ( $review_images as $index => $review ) : ?>
						<?php if ( ! empty( $review['review_picture'] ) ) : ?>
							<?php
							$alt_text = ! empty( $review['review_picture']['alt'] )
							? $review['review_picture']['alt']
							: 'Customer review photo ' . ( $index + 1 );
							$ml_class = $index === 0 ? '' : '-ml-8';
							?>
					<img src="<?php echo esc_url( $review['review_picture']['url'] ); ?>"
						alt="<?php echo esc_attr( $alt_text ); ?>"
						class="<?php echo esc_attr( $ml_class ); ?> h-10 w-10 rounded-full object-cover sm:h-12 sm:w-12"
						loading="lazy" decoding="async" />
					<?php endif; ?>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<!-- Google Rating Section -->
			<div class="flex flex-shrink-0 items-center gap-2 sm:gap-3">
				<img src="<?php echo esc_url( $icons['google'] ); ?>" alt="Google" class="h-6 w-6 sm:h-8 sm:w-8 lg:h-10 lg:w-10" loading="lazy"
				decoding="async" />
				<div class="flex flex-col items-start">
				<div class="flex items-center gap-0.5">
					<?php sunnysideac_render_stars( 5, $icons['star'], 'h-3 w-3 sm:h-4 sm:w-4 lg:h-5 lg:w-5' ); ?>
				</div>
				<div class="mt-0.5 text-[10px] font-medium text-black sm:text-xs lg:text-sm">
					5.0 Rating
				</div>
				</div>
			</div>

			<!-- Reviews Text -->
			<div class="min-w-0 flex-grow text-right">
				<div class="text-base leading-tight font-bold text-black sm:text-lg lg:text-xl">
				Reviews
				</div>
				<p class="mt-0.5 text-[10px] font-normal text-black sm:text-xs lg:text-sm">
				Rated Best Over 100 Reviews
				</p>
			</div>
			</div>
		</section>

		<!-- Statistics Section -->
		<section class="flex min-w-96 items-center justify-between py-4" aria-label="Company statistics">
			<?php foreach ( $statistics as $index => $stat ) : ?>
			<div class="text-center">
				<div class="text-xl font-semibold text-black sm:text-2xl lg:text-3xl">
				<?php echo esc_html( $stat['number'] ); ?>
				</div>
				<div class="mt-1 text-center text-[10px] font-normal whitespace-pre-line text-black sm:text-xs">
				<?php echo esc_html( $stat['description'] ); ?>
				</div>
			</div>
				<?php if ( $index < count( $statistics ) - 1 ) : ?>
				<!-- Separator Line -->
				<div class="h-16 w-px bg-gray-300 lg:h-20" role="presentation"></div>
			<?php endif; ?>
			<?php endforeach; ?>
		</section>
		</div>
	</div>

	<!-- Desktop Right Column - Hero Image -->
	<div class="flex items-center justify-center lg:items-start lg:justify-end">
		<?php
		// Custom responsive image with AVIF and WebP support for optimal performance
		$webp_url = sunnysideac_asset_url( 'assets/optimized/hero-right-image.webp' );
		$avif_url = sunnysideac_asset_url( 'assets/optimized/hero-right-image.avif' );
		$fallback_url = sunnysideac_asset_url( 'assets/optimized/hero-right-image.png' );
		?>
		<picture>
			<?php if (file_exists(get_template_directory() . '/assets/optimized/hero-right-image.avif')): ?>
				<source srcset="<?php echo esc_url($avif_url); ?>" type="image/avif">
			<?php endif; ?>
			<?php if (file_exists(get_template_directory() . '/assets/optimized/hero-right-image.webp')): ?>
				<source srcset="<?php echo esc_url($webp_url); ?>" type="image/webp">
			<?php endif; ?>
			<img
				src="<?php echo esc_url($webp_url); ?>"
				alt="Professional AC technician working on air conditioning unit"
				loading="eager"
				decoding="sync"
				class="h-64 min-h-[300px] w-full max-w-md rounded-lg object-contain sm:h-80 lg:h-full lg:min-h-[554px] lg:w-full lg:max-w-none"
				width="554"
				height="554"
			>
		</picture>
	</div>
	</div>

	<!-- Mobile Content Container - Visible only on mobile -->
	<div class="relative z-10 min-h-[600px] p-4 sm:p-6 lg:hidden">
	<!-- Mobile Content -->
	<div class="flex flex-col justify-between space-y-4">
		<!-- Header with Logo and Tagline -->
		<header class="flex items-center justify-center gap-2">
		<div class="h-6 w-6 flex-shrink-0">
			<img src="<?php echo esc_url( $icons['mobile_best_refreshed'] ); ?>" alt="Company logo" class="h-full w-full" loading="lazy"
			decoding="async" />
		</div>
		<p class="text-center text-xs leading-normal font-normal sm:text-sm">
			<span class="text-white">The </span>
			<span class="text-yellow-300">Best</span>
			<span class="text-white"> At Keeping You </span>
			<span class="text-yellow-300">Refreshed!</span>
		</p>
		</header>

		<!-- Main Content Area -->
		<div class="flex flex-1 flex-col justify-center">
		<!-- Main Headline - SEO Optimized -->
		<h1 class="text-center text-xl leading-tight font-semibold text-white sm:text-2xl md:text-3xl">
			Premier AC Repair & Installation in South Florida | Fast HVAC Service
		</h1>

		<!-- Subtitle -->
		<p class="mt-4 text-center text-base leading-relaxed font-normal text-white italic sm:text-lg">
			South Florida's most responsive AC pros. <br />
			Honest pricing. Same-day service. Zero stress.
		</p>

		<!-- Call to Action Buttons -->
		<div class="flex flex-col justify-center gap-3 pt-4">
			<a href="<?php echo esc_attr( $tel_href ); ?>"
			class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-5 py-3 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none"
			aria-label="Call to schedule AC service - <?php echo esc_attr( $phone_display ); ?>">
			<div class="h-4 w-4 flex-shrink-0">
				<img src="<?php echo esc_url( $icons['schedule_service'] ); ?>" alt="" class="h-full w-full" role="presentation" loading="lazy"
				decoding="async" />
			</div>
			<span class="text-base font-medium whitespace-nowrap text-white">
				Schedule Service Now
			</span>
			</a>

			<a href="<?php echo esc_attr( $tel_href ); ?>"
			class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-5 py-3 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none"
			aria-label="Call us now for AC service - <?php echo esc_attr( $phone_display ); ?>">
			<div class="h-4 w-4 flex-shrink-0">
				<img src="<?php echo esc_url( $icons['call_us_now'] ); ?>" alt="" class="h-full w-full" role="presentation" loading="lazy"
				decoding="async" />
			</div>
			<span class="text-base font-medium whitespace-nowrap text-white">
				Call Us Now
			</span>
			</a>
		</div>
		</div>

		<!-- Bottom Section - Reviews and Stats -->
		<div class="space-y-4">
		<!-- Reviews Section -->
		<section class="rounded-[20px] bg-black/20 p-4 backdrop-blur-sm" aria-label="Customer reviews">
			<div class="flex items-center justify-between gap-2 sm:gap-4">
			<!-- Review Photos -->
			<div class="flex w-[40vw] items-center justify-center gap-2">
				<?php if ( $review_images ) : ?>
					<?php foreach ( $review_images as $index => $review ) : ?>
						<?php if ( ! empty( $review['review_picture'] ) ) : ?>
							<?php
							$alt_text = ! empty( $review['review_picture']['alt'] )
							? $review['review_picture']['alt']
							: 'Customer review photo ' . ( $index + 1 );
							$ml_class = $index === 0 ? 'ml-0' : '-ml-8';
							?>
					<img src="<?php echo esc_url( $review['review_picture']['url'] ); ?>"
						alt="<?php echo esc_attr( $alt_text ); ?>"
						class="h-10 w-10 rounded-full object-cover sm:h-12 sm:w-12 <?php echo esc_attr( $ml_class ); ?>"
						loading="lazy" decoding="async" />
					<?php endif; ?>
				<?php endforeach; ?>
				<?php endif; ?>
			</div>

			<!-- Google Rating Section -->
			<div class="flex flex-shrink-0 items-center gap-2 sm:gap-3">
				<img src="<?php echo esc_url( $icons['google'] ); ?>" alt="Google" class="h-6 w-6 sm:h-8 sm:w-8" loading="lazy"
				decoding="async" />
				<div class="flex flex-col items-start">
				<div class="flex items-center gap-0.5">
					<?php sunnysideac_render_stars( 5, $icons['star'], 'h-3 w-3 sm:h-4 sm:w-4' ); ?>
				</div>
				<div class="mt-0.5 text-[10px] font-medium text-white sm:text-xs">
					5.0 Rating
				</div>
				</div>
			</div>

			<!-- Reviews Text -->
			<div class="min-w-0 flex-grow text-right">
				<div class="text-sm leading-tight font-bold text-white sm:text-lg">
				Reviews
				</div>
				<p class="mt-0.5 text-[8px] font-normal text-white sm:text-xs">
				Rated Best Over 100 Reviews
				</p>
			</div>
			</div>
		</section>

		<!-- Statistics Section -->
		<section class="flex items-center justify-between py-4" aria-label="Company statistics">
			<?php foreach ( $statistics as $index => $stat ) : ?>
			<div class="text-center">
				<div class="text-xl font-semibold text-white sm:text-2xl">
				<?php echo esc_html( $stat['number'] ); ?>
				</div>
				<div class="mt-1 text-center text-[10px] font-normal whitespace-pre-line text-white sm:text-xs">
				<?php echo esc_html( $stat['description'] ); ?>
				</div>
			</div>
				<?php if ( $index < count( $statistics ) - 1 ) : ?>
				<!-- Separator Line -->
				<div class="h-16 w-px bg-white/30" role="presentation"></div>
			<?php endif; ?>
			<?php endforeach; ?>
		</section>
		</div>
	</div>
	</div>
</section>
