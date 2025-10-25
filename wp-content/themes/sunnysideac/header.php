<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="generator" content="WordPress <?php echo get_bloginfo( 'version' ); ?>">

	<!-- Favicons -->
	<link rel="icon" type="image/svg+xml" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon.svg">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon-96x96.png">
	<link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/assets/icons/favicon.ico">

	<!-- Preload LCP hero image for optimal performance -->
	<?php
	$avif_url = get_template_directory_uri() . '/assets/optimized/hero-right-image.avif';
	$webp_url = get_template_directory_uri() . '/assets/optimized/hero-right-image.webp';
	?>
	<link rel="preload" as="image" href="<?php echo esc_url($avif_url); ?>" type="image/avif">
	<link rel="preload" as="image" href="<?php echo esc_url($webp_url); ?>" type="image/webp">

	<?php
	// SEO Meta Tags
	$page_description = get_post_meta( get_the_ID(), '_seo_description', true );
	$page_keywords    = get_post_meta( get_the_ID(), '_seo_keywords', true );
	$canonical_url    = get_post_meta( get_the_ID(), '_seo_canonical', true );

	// Use Yoast/RankMath if available, otherwise use custom fields
	if ( ! defined( 'WPSEO_VERSION' ) && ! defined( 'RANK_MATH_VERSION' ) ) {
		if ( $page_description ) {
			echo '<meta name="description" content="' . esc_attr( $page_description ) . '">' . "\n";
		}
		if ( $page_keywords ) {
			echo '<meta name="keywords" content="' . esc_attr( $page_keywords ) . '">' . "\n";
		}
		if ( $canonical_url ) {
			echo '<link rel="canonical" href="' . esc_url( $canonical_url ) . '">' . "\n";
		} elseif ( is_singular() ) {
			echo '<link rel="canonical" href="' . esc_url( get_permalink() ) . '">' . "\n";
		}
	}
	?>

	<?php
	// Inline critical CSS to prevent FOUC in development
	$is_dev = function_exists( 'sunnysideac_is_vite_dev_server_running' ) && sunnysideac_is_vite_dev_server_running();
	if ( $is_dev ) :
		?>
	<style>
		/* Prevent flash of unstyled content while Vite injects CSS */
		body {
			visibility: hidden;
			opacity: 0;
		}
		body.vite-ready {
			visibility: visible;
			opacity: 1;
			transition: opacity 0.1s ease-in;
		}
	</style>
	<script>
		// Mark body as ready once CSS is injected
		if (document.readyState === 'loading') {
			document.addEventListener('DOMContentLoaded', function() {
				setTimeout(function() {
					document.body.classList.add('vite-ready');
				}, 50);
			});
		} else {
			setTimeout(function() {
				document.body.classList.add('vite-ready');
			}, 50);
		}
	</script>
	<?php endif; ?>

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Global content wrapper with max-width -->
<div class="mx-auto w-full max-w-7xl px-4 overflow-visible">

<?php
// Asset paths - Responsive logo system
$logo_path    = get_template_directory_uri() . '/assets/optimized/sunny-side-logo.webp';
$logo_2x_path = get_template_directory_uri() . '/assets/optimized/sunny-side-logo-2x.webp';
$logo_png_path = get_template_directory_uri() . '/assets/images/home-page/footer/new-sunny-side-logo.png';

$call_us_icon = get_template_directory_uri() . '/assets/images/images/logos/navigation-call-us-now-icon.svg';
$mail_icon    = get_template_directory_uri() . '/assets/images/images/logos/navigation-mail-icon.svg';

// Phone icon - SVG version for optimal clarity
$phone_icon     = get_template_directory_uri() . '/assets/icons/navigation-phone-icon.svg';
?>

<div class="my-6 flex w-full justify-center lg:mt-8" id="main-navigation" data-tel-href="<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>" data-cities-base-url="<?php echo esc_url( home_url( '/cities/' ) ); ?>">
	<header class="w-full overflow-visible rounded-[20px] bg-[#ffead5]" role="banner">

		<!-- Desktop Top contact bar - hidden on mobile -->
		<div class="hidden w-full rounded-t-[20px] border-b-2 border-white bg-[#ffead5] py-2 lg:block">
			<div class="flex items-center justify-between px-4">
				<!-- Contact info -->
				<div class="flex items-center gap-6 text-sm">
					<a
						href="<?php echo esc_attr( SUNNYSIDE_MAILTO_HREF ); ?>"
						class="flex items-center gap-2 text-gray-700 transition-colors duration-200 hover:text-[#fb9939]"
						aria-label="Email us for support at <?php echo esc_attr( SUNNYSIDE_EMAIL_ADDRESS ); ?>"
					>
						<img src="<?php echo esc_url( $mail_icon ); ?>" alt="" class="icon-nav-mail" loading="lazy" decoding="async" />
						<span><?php echo esc_html( SUNNYSIDE_EMAIL_ADDRESS ); ?></span>
					</a>
					<a
						href="<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
						class="flex items-center gap-2 text-gray-700 transition-colors duration-200 hover:text-[#fb9939]"
						aria-label="Call <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?> for AC services"
					>
						<img
							src="<?php echo esc_url( $phone_icon ); ?>"
							alt=""
							class="icon-nav-phone"
							loading="lazy"
							decoding="async"
						/>
						<span><?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?></span>
					</a>
				</div>

				<!-- Social icons -->
				<?php get_template_part( 'template-parts/social-icons', null, array( 'size' => 'md' ) ); ?>
			</div>
		</div>

		<!-- Main navigation -->
		<nav class="w-full overflow-visible rounded-[20px] bg-[#ffead5] py-4" role="navigation" aria-label="Main navigation">

			<!-- Mobile Layout -->
			<div class="flex items-center justify-between px-4 lg:hidden">
				<!-- Hamburger Menu -->
				<button
					id="mobile-menu-toggle"
					class="flex flex-col gap-2 p-2 transition-opacity hover:opacity-80"
					aria-label="Toggle mobile menu"
					aria-expanded="false"
				>
					<div class="h-1 w-10 rounded-full bg-gradient-to-r from-[#fb9939] to-[#e5462f]"></div>
					<div class="h-1 w-10 rounded-full bg-gradient-to-r from-[#fb9939] to-[#e5462f]"></div>
					<div class="h-1 w-10 rounded-full bg-gradient-to-r from-[#fb9939] to-[#e5462f]"></div>
				</button>

				<!-- Centered Logo -->
				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="flex items-center gap-2 transition-opacity hover:opacity-80"
					aria-label="SunnySide 24/7 AC - Go to homepage"
				>
					<img
						class="h-12 w-20 object-contain sm:h-16 sm:w-28"
						alt="SunnySide 24/7 AC company logo"
						src="<?php echo esc_url( $logo_path ); ?>"
						srcset="<?php echo esc_url( $logo_path ); ?> 123w, <?php echo esc_url( $logo_path ); ?> 246w"
						sizes="(max-width: 640px) 80px, 112px"
						decoding="sync"
						fetchpriority="high"
					/>
					<div class="flex flex-col text-center">
						<div class="bg-[linear-gradient(90deg,rgba(255,193,59,1)_0%,rgba(229,70,47,1)_100%)] bg-clip-text text-lg leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
							SunnySide
						</div>
						<div class="bg-[linear-gradient(90deg,rgba(229,70,47,1)_0%,rgba(255,193,59,1)_100%)] bg-clip-text text-xl leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
							24/7 AC
						</div>
					</div>
				</a>

				<!-- Phone CTA -->
				<button
					id="mobile-call-btn"
					class="flex h-12 w-12 items-center justify-center rounded-2xl bg-gradient-to-r from-[#fb9939] to-[#e5462f] shadow-md transition-transform hover:scale-105"
					aria-label="Call us now"
				>
					<img src="<?php echo esc_url( $call_us_icon ); ?>" alt="" class="h-6 w-6" loading="lazy" decoding="async" />
				</button>
			</div>

			<!-- Desktop Layout -->
			<div class="hidden items-center justify-between px-4 lg:flex">
				<!-- Logo section -->
				<a
					href="<?php echo esc_url( home_url( '/' ) ); ?>"
					class="flex items-center gap-3 transition-opacity hover:opacity-80"
					aria-label="SunnySide 24/7 AC - Go to homepage"
				>
					<img
						class="h-12 w-20 object-contain sm:h-16 sm:w-28"
						alt="SunnySide 24/7 AC company logo"
						src="<?php echo esc_url( $logo_path ); ?>"
						srcset="<?php echo esc_url( $logo_path ); ?> 123w, <?php echo esc_url( $logo_path ); ?> 246w"
						sizes="(max-width: 640px) 80px, 112px"
						decoding="sync"
						fetchpriority="high"
					/>
					<div class="flex flex-col">
						<div class="bg-[linear-gradient(90deg,rgba(255,193,59,1)_0%,rgba(229,70,47,1)_100%)] bg-clip-text text-2xl leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
							SunnySide
						</div>
						<div class="bg-[linear-gradient(90deg,rgba(229,70,47,1)_0%,rgba(255,193,59,1)_100%)] bg-clip-text text-3xl leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
							24/7 AC
						</div>
					</div>
				</a>

				<!-- Navigation menu -->
			<?php sunnysideac_desktop_nav_menu(); ?>

			<!-- Call us button -->
				<button
					id="desktop-call-btn"
					class="inline-flex cursor-pointer items-center gap-2 rounded-full bg-[linear-gradient(90deg,rgba(251,176,57,1)_0%,rgba(229,70,47,1)_100%)] px-6 py-3 transition-transform duration-200 hover:scale-105 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:outline-none"
					aria-label="Call us now for AC services"
				>
					<img
						src="<?php echo esc_url( $call_us_icon ); ?>"
						alt=""
						class="h-5 w-5"
						role="presentation"
						loading="lazy"
						decoding="async"
					/>
					<span class="text-lg font-medium whitespace-nowrap text-white">
						Call Us Now
					</span>
				</button>
			</div>

			<!-- Mobile Menu Dropdown -->
			<div id="mobile-menu" class="fixed inset-0 z-[9999] bg-gradient-to-b from-[#fb9939]/40 to-black/80 backdrop-blur-md lg:hidden hidden">
				<div class="absolute inset-x-0 top-0 h-[100dvh] w-full p-5">
					<div class="mx-auto h-full w-full max-w-sm overflow-hidden overscroll-contain rounded-[20px] bg-white shadow-xl" id="mobile-menu-content">
						<div class="flex h-full flex-col">
							<!-- Header with close button -->
							<div class="flex items-center justify-between p-4">
								<button
									id="mobile-menu-close"
									class="flex h-8 w-8 items-center justify-center rounded-lg border-2 border-gray-300 hover:bg-gray-100"
									aria-label="Close mobile menu"
								>
									✕
								</button>
								<a
									href="<?php echo esc_url( home_url( '/' ) ); ?>"
									class="flex items-center gap-2 transition-opacity hover:opacity-80"
									aria-label="SunnySide 24/7 AC - Go to homepage"
								>
									<img
										class="h-11 w-16 object-contain"
										alt="SunnySide 24/7 AC company logo"
										src="<?php echo esc_url( $logo_path ); ?>"
										srcset="<?php echo esc_url( $logo_path ); ?> 123w, <?php echo esc_url( $logo_path ); ?> 246w"
										sizes="64px"
										decoding="sync"
										fetchpriority="high"
									/>
									<div class="flex flex-col">
										<div class="bg-[linear-gradient(90deg,rgba(255,193,59,1)_0%,rgba(229,70,47,1)_100%)] bg-clip-text text-sm leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
											SunnySide
										</div>
										<div class="bg-[linear-gradient(90deg,rgba(229,70,47,1)_0%,rgba(255,193,59,1)_100%)] bg-clip-text text-sm leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
											24/7 AC
										</div>
									</div>
								</a>
								<button
									id="mobile-call-btn-header"
									class="flex h-8 w-8 items-center justify-center rounded-lg bg-gradient-to-r from-[#fb9939] to-[#e5462f] transition-transform duration-200 hover:scale-105"
									aria-label="Call us now"
								>
									<img src="<?php echo esc_url( $call_us_icon ); ?>" alt="" class="h-4 w-4" loading="lazy" decoding="async" />
								</button>
							</div>

							<!-- Content -->
							<div class="flex-1 overflow-y-auto overscroll-contain px-4 pt-5 pb-4">
								<div class="mb-4 text-center text-sm text-gray-600">
									The <span class="font-bold text-[#fb9939]">Best</span> At Keeping You <span class="font-bold text-[#e5462f]">Refreshed!</span>
								</div>

								<!-- Action Buttons -->
								<div class="mb-6 space-y-3">
									<div class="relative">
										<button
											id="location-select-btn"
											class="flex w-full items-center justify-between rounded-lg bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-4 py-3 text-white"
											aria-label="Select a location"
										>
											<div class="flex items-center gap-2">
												<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
													<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
												</svg>
												<span class="font-medium" id="selected-location-text">SELECT A LOCATION</span>
											</div>
											<svg class="h-4 w-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
											</svg>
										</button>
										<select
											id="location-select"
											class="absolute inset-0 h-full w-full cursor-pointer opacity-0"
											aria-hidden="true"
											tabindex="-1"
										>
											<option value="">SELECT A LOCATION</option>
											<?php foreach ( SUNNYSIDE_SERVICE_AREAS as $area ) : ?>
												<option value="<?php echo esc_attr( $area ); ?>"><?php echo esc_html( $area ); ?></option>
											<?php endforeach; ?>
										</select>
									</div>

									<a
										href="<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
										class="flex w-full items-center justify-between rounded-lg bg-gradient-to-r from-[#e5462f] to-[#fb9939] px-4 py-3 text-white"
										aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>"
									>
										<div class="flex items-center gap-2">
											<span class="text-sm">
												SunnySide 24/7 AC Is Open And Available Schedule A Service Now
											</span>
										</div>
										<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
										</svg>
									</a>
								</div>

								<!-- Mobile Navigation -->
								<?php sunnysideac_mobile_nav_menu(); ?>

								<!-- Contact Info -->
								<div class="space-y-4">
									<div class="flex items-start gap-3">
										<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#ffc549]">
											<img src="<?php echo esc_url( $mail_icon ); ?>" alt="" class="h-4 w-4" loading="lazy" decoding="async" />
										</div>
										<div>
											<div class="text-sm font-medium text-gray-800">Email</div>
											<a
												href="<?php echo esc_attr( SUNNYSIDE_MAILTO_HREF ); ?>"
												class="text-sm text-gray-600 transition-colors duration-200 hover:text-[#fb9939]"
												aria-label="Email us at <?php echo esc_attr( SUNNYSIDE_EMAIL_ADDRESS ); ?>"
											>
												<?php echo esc_html( SUNNYSIDE_EMAIL_ADDRESS ); ?>
											</a>
										</div>
									</div>

									<div class="flex items-start gap-3">
										<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#ffc549]">
											<svg class="h-4 w-4 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
												<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
											</svg>
										</div>
										<div>
											<div class="text-sm font-medium text-gray-800">Locations</div>
											<div class="text-sm text-gray-600"><?php echo esc_html( SUNNYSIDE_ADDRESS_STREET ); ?></div>
											<div class="text-sm text-gray-600">
												<?php echo esc_html( SUNNYSIDE_ADDRESS_CITY ); ?>, <?php echo esc_html( SUNNYSIDE_ADDRESS_STATE ); ?> <?php echo esc_html( SUNNYSIDE_ADDRESS_ZIP ); ?>
											</div>
										</div>
									</div>

									<div class="flex items-start gap-3">
										<div class="flex h-8 w-8 items-center justify-center rounded-lg bg-[#ffc549]">
											<img src="<?php echo esc_url( $phone_icon ); ?>" alt="" class="icon-nav-phone" loading="lazy" decoding="async" />
										</div>
										<div>
											<div class="text-sm font-medium text-gray-800">Phone</div>
											<div class="text-sm text-gray-600"><?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?></div>
										</div>
									</div>
								</div>

								<!-- Follow Us -->
								<div class="mt-6 text-center">
									<div class="mb-3 text-sm font-medium text-gray-800">Follow Us:</div>
									<div class="flex justify-center">
										<?php get_template_part( 'template-parts/social-icons', null, array( 'size' => 'sm' ) ); ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</nav>
	</header>
</div>

<?php
/**
 * Navigation JavaScript is now loaded as a Vite module
 * See: src/js/navigation.js
 * Imported via: src/main.js
 */
?>
