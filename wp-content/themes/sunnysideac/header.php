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
// Asset paths
$logo_path    = get_template_directory_uri() . '/assets/images/images/logos/sunny-side-logo.png';
$call_us_icon = get_template_directory_uri() . '/assets/images/images/logos/navigation-call-us-now-icon.svg';
$mail_icon    = get_template_directory_uri() . '/assets/images/images/logos/navigation-mail-icon.svg';
$phone_icon   = get_template_directory_uri() . '/assets/images/images/logos/navigation-phone-icon.png';
?>

<div class="my-6 flex w-full justify-center lg:mt-8" id="main-navigation" data-tel-href="<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>">
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
						<img src="<?php echo esc_url( $mail_icon ); ?>" alt="" class="h-4 w-4" loading="lazy" decoding="async" />
						<span><?php echo esc_html( SUNNYSIDE_EMAIL_ADDRESS ); ?></span>
					</a>
					<a
						href="<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
						class="flex items-center gap-2 text-gray-700 transition-colors duration-200 hover:text-[#fb9939]"
						aria-label="Call <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?> for AC services"
					>
						<img src="<?php echo esc_url( $phone_icon ); ?>" alt="" class="h-4 w-4" loading="lazy" decoding="async" />
						<span><?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?></span>
					</a>
				</div>

				<!-- Social icons -->
				<?php get_template_part( 'template-parts/social-icons', null, [ 'size' => 'md' ] ); ?>
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
						class="h-12 w-20 object-contain"
						alt="SunnySide 24/7 AC company logo"
						src="<?php echo esc_url( $logo_path ); ?>"
						loading="lazy"
						decoding="async"
					/>
					<div class="flex flex-col text-center">
						<div class="bg-[linear-gradient(90deg,rgba(255,193,59,1)_0%,rgba(229,70,47,1)_100%)] bg-clip-text [font-family:'Inter-Bold',Helvetica] text-lg leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
							SunnySide
						</div>
						<div class="bg-[linear-gradient(90deg,rgba(229,70,47,1)_0%,rgba(255,193,59,1)_100%)] bg-clip-text [font-family:'Inter-Bold',Helvetica] text-xl leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
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
						class="h-auto w-full"
						alt="SunnySide 24/7 AC company logo"
						src="<?php echo esc_url( $logo_path ); ?>"
						loading="lazy"
						decoding="async"
					/>
					<div class="flex flex-col">
						<div class="bg-[linear-gradient(90deg,rgba(255,193,59,1)_0%,rgba(229,70,47,1)_100%)] bg-clip-text [font-family:'Inter-Bold',Helvetica] text-2xl leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
							SunnySide
						</div>
						<div class="bg-[linear-gradient(90deg,rgba(229,70,47,1)_0%,rgba(255,193,59,1)_100%)] bg-clip-text [font-family:'Inter-Bold',Helvetica] text-3xl leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
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
					<span class="[font-family:'Inter-Medium',Helvetica] text-lg font-medium whitespace-nowrap text-white">
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
										class="h-11 object-contain"
										alt="SunnySide 24/7 AC company logo"
										src="<?php echo esc_url( $logo_path ); ?>"
										loading="lazy"
										decoding="async"
									/>
									<div class="flex flex-col">
										<div class="bg-[linear-gradient(90deg,rgba(255,193,59,1)_0%,rgba(229,70,47,1)_100%)] bg-clip-text [font-family:'Inter-Bold',Helvetica] text-sm leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
											SunnySide
										</div>
										<div class="bg-[linear-gradient(90deg,rgba(229,70,47,1)_0%,rgba(255,193,59,1)_100%)] bg-clip-text [font-family:'Inter-Bold',Helvetica] text-sm leading-tight font-bold [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent]">
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
											<img src="<?php echo esc_url( $phone_icon ); ?>" alt="" class="h-4 w-4" loading="lazy" decoding="async" />
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
										<?php get_template_part( 'template-parts/social-icons', null, [ 'size' => 'sm' ] ); ?>
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

<script>
/**
 * Navigation JavaScript
 * Handles all interactive functionality for the main navigation
 */
document.addEventListener('DOMContentLoaded', function() {
	// Get constants from PHP (via data attributes or inline script)
	const TEL_HREF = document.querySelector('[data-tel-href]')?.dataset.telHref || 'tel:+13059789382';

	// State variables
	let activeMenuItem = 'Home';
	let isServicesDropdownOpen = false;
let isServiceAreasDropdownOpen = false;
	let isMobileMenuOpen = false;
	let selectedLocation = '';
	let hoverTimeout = null;
	let debugMode = false; // When true, disables hover/mouse events for manual control

	// DOM elements
	const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
	const mobileMenu = document.getElementById('mobile-menu');
	const mobileMenuClose = document.getElementById('mobile-menu-close');
	const mobileMenuContent = document.getElementById('mobile-menu-content');
	const servicesDropdownContainer = document.getElementById('services-dropdown-container');
	const servicesDropdown = document.querySelector('.services-dropdown');
	const servicesDropdownBtn = document.querySelector('.services-dropdown-btn');
	const chevronIcon = document.querySelector('.chevron-icon');
	const locationSelect = document.getElementById('location-select');
	const locationSelectBtn = document.getElementById('location-select-btn');
	const selectedLocationText = document.getElementById('selected-location-text');

	// Service Areas dropdown elements
	const serviceAreasDropdownContainer = document.getElementById('service-areas-dropdown-container');
	const serviceAreasDropdown = document.querySelector('.service-areas-dropdown');
	const serviceAreasDropdownBtn = document.querySelector('.service-areas-dropdown-btn');

	// Utility functions
	function updateActiveMenuItem(itemName) {
		activeMenuItem = itemName;
		document.querySelectorAll('.nav-item').forEach(item => {
			const itemData = item.dataset.item;
			if (itemData === itemName) {
				item.classList.remove('bg-[#fde0a0]');
				item.classList.add('bg-[#ffc549]');
			} else {
				item.classList.remove('bg-[#ffc549]');
				item.classList.add('bg-[#fde0a0]');
			}
		});
	}

	function handleCallUs() {
		window.location.href = TEL_HREF;
	}

	function handleMenuItemClick(itemName, href) {
		updateActiveMenuItem(itemName);
		if (itemName === 'Services') {
			// Toggle dropdown
			toggleServicesDropdown();
		} else if (itemName === 'Areas') {
			// Toggle dropdown
			toggleServiceAreasDropdown();
		} else {
			closeServicesDropdown();
			closeServiceAreasDropdown();
			// Navigate to the page
			if (href && href !== '#') {
				window.location.href = href;
			}
		}
	}

	function toggleServicesDropdown() {
		isServicesDropdownOpen = !isServicesDropdownOpen;
		updateServicesDropdown();
	}

	function openServicesDropdown() {
		// In debug mode, ignore hover events
		if (debugMode) return;

		if (hoverTimeout) {
			clearTimeout(hoverTimeout);
			hoverTimeout = null;
		}
		// Close Service Areas dropdown when opening Services
		isServiceAreasDropdownOpen = false;
		updateServiceAreasDropdown();

		isServicesDropdownOpen = true;
		updateServicesDropdown();
	}

	function closeServicesDropdown() {
		// In debug mode, ignore hover events
		if (debugMode) return;

		isServicesDropdownOpen = false;
		updateServicesDropdown();
	}

	function delayedCloseServicesDropdown() {
		// In debug mode, ignore hover events
		if (debugMode) return;

		hoverTimeout = setTimeout(() => {
			closeServicesDropdown();
		}, 150);
	}

	function updateServicesDropdown() {
		if (servicesDropdown) {
			if (isServicesDropdownOpen) {
				servicesDropdown.classList.remove('hidden');
				if (chevronIcon) {
					chevronIcon.style.transform = 'rotate(180deg)';
				}
				if (servicesDropdownContainer) {
					servicesDropdownContainer.setAttribute('aria-expanded', 'true');
				}
			} else {
				servicesDropdown.classList.add('hidden');
				if (chevronIcon) {
					chevronIcon.style.transform = 'rotate(0deg)';
				}
				if (servicesDropdownContainer) {
					servicesDropdownContainer.setAttribute('aria-expanded', 'false');
				}
			}
		}
	}

	function toggleServiceAreasDropdown() {
		isServiceAreasDropdownOpen = !isServiceAreasDropdownOpen;
		updateServiceAreasDropdown();
	}

	function openServiceAreasDropdown() {
		// In debug mode, ignore hover events
		if (debugMode) return;

		if (hoverTimeout) {
			clearTimeout(hoverTimeout);
			hoverTimeout = null;
		}
		// Close Services dropdown when opening Service Areas
		isServicesDropdownOpen = false;
		updateServicesDropdown();

		isServiceAreasDropdownOpen = true;
		updateServiceAreasDropdown();
	}

	function closeServiceAreasDropdown() {
		// In debug mode, ignore hover events
		if (debugMode) return;

		isServiceAreasDropdownOpen = false;
		updateServiceAreasDropdown();
	}

	function delayedCloseServiceAreasDropdown() {
		// In debug mode, ignore hover events
		if (debugMode) return;

		hoverTimeout = setTimeout(() => {
			closeServiceAreasDropdown();
		}, 150);
	}

	function updateServiceAreasDropdown() {
		if (serviceAreasDropdown) {
			if (isServiceAreasDropdownOpen) {
				serviceAreasDropdown.classList.remove('hidden');
				if (serviceAreasDropdownContainer) {
					serviceAreasDropdownContainer.setAttribute('aria-expanded', 'true');
				}
			} else {
				serviceAreasDropdown.classList.add('hidden');
				if (serviceAreasDropdownContainer) {
					serviceAreasDropdownContainer.setAttribute('aria-expanded', 'false');
				}
			}
		}
	}

	function toggleMobileMenu() {
		isMobileMenuOpen = !isMobileMenuOpen;
		updateMobileMenu();
	}

	function closeMobileMenu() {
		isMobileMenuOpen = false;
		updateMobileMenu();
	}

	function updateMobileMenu() {
		if (mobileMenu && mobileMenuToggle) {
			if (isMobileMenuOpen) {
				mobileMenu.classList.remove('hidden');
				document.body.style.overflow = 'hidden';
				mobileMenuToggle.setAttribute('aria-expanded', 'true');
			} else {
				mobileMenu.classList.add('hidden');
				document.body.style.overflow = '';
				mobileMenuToggle.setAttribute('aria-expanded', 'false');
			}
		}
	}

	function handleLocationSelect(value) {
		selectedLocation = value;
		if (selectedLocationText) {
			selectedLocationText.textContent = value || 'SELECT A LOCATION';
		}
		// Navigate to city page if a location was selected
		if (value) {
			const citySlug = value.toLowerCase().replace(/\s+/g, '-');
			const cityUrl = '<?php echo home_url( '/areas/' ); ?>' + citySlug;
			window.location.href = cityUrl;
		}
	}

	// Event listeners for desktop navigation
	document.querySelectorAll('.nav-item').forEach(item => {
		const itemName = item.dataset.item;
		const href = item.dataset.href;

		if (itemName === 'Services') {
			// Services dropdown toggle
			if (servicesDropdownBtn) {
				servicesDropdownBtn.addEventListener('click', (e) => {
					e.preventDefault();
					e.stopPropagation();
					handleMenuItemClick(itemName, href);
				});
			}

			// Hover events for services dropdown
			if (servicesDropdownContainer) {
				servicesDropdownContainer.addEventListener('mouseenter', openServicesDropdown);
				servicesDropdownContainer.addEventListener('mouseleave', delayedCloseServicesDropdown);
			}

			if (servicesDropdown) {
				servicesDropdown.addEventListener('mouseenter', () => {
					if (hoverTimeout) {
						clearTimeout(hoverTimeout);
						hoverTimeout = null;
					}
				});
				servicesDropdown.addEventListener('mouseleave', delayedCloseServicesDropdown);
			}
		} else if (itemName === 'Areas') {
			// Areas dropdown toggle
			if (serviceAreasDropdownBtn) {
				serviceAreasDropdownBtn.addEventListener('click', (e) => {
					e.preventDefault();
					e.stopPropagation();
					handleMenuItemClick(itemName, href);
				});
			}

			// Hover events for areas dropdown
			if (serviceAreasDropdownContainer) {
				serviceAreasDropdownContainer.addEventListener('mouseenter', openServiceAreasDropdown);
				serviceAreasDropdownContainer.addEventListener('mouseleave', delayedCloseServiceAreasDropdown);
			}

			if (serviceAreasDropdown) {
				serviceAreasDropdown.addEventListener('mouseenter', () => {
					if (hoverTimeout) {
						clearTimeout(hoverTimeout);
						hoverTimeout = null;
					}
				});
				serviceAreasDropdown.addEventListener('mouseleave', delayedCloseServiceAreasDropdown);
			}
		} else {
			// Regular navigation items
			item.addEventListener('click', () => {
				handleMenuItemClick(itemName, href);
			});
		}
	});

	// Call buttons
	const mobileCallBtn = document.getElementById('mobile-call-btn');
	const mobileCallBtnHeader = document.getElementById('mobile-call-btn-header');
	const desktopCallBtn = document.getElementById('desktop-call-btn');

	if (mobileCallBtn) {
		mobileCallBtn.addEventListener('click', handleCallUs);
	}
	if (mobileCallBtnHeader) {
		mobileCallBtnHeader.addEventListener('click', handleCallUs);
	}
	if (desktopCallBtn) {
		desktopCallBtn.addEventListener('click', handleCallUs);
	}

	// Mobile menu toggle
	if (mobileMenuToggle) {
		mobileMenuToggle.addEventListener('click', toggleMobileMenu);
	}
	if (mobileMenuClose) {
		mobileMenuClose.addEventListener('click', closeMobileMenu);
	}

	// Close mobile menu when clicking backdrop
	if (mobileMenu) {
		mobileMenu.addEventListener('click', closeMobileMenu);
	}
	if (mobileMenuContent) {
		mobileMenuContent.addEventListener('click', (e) => e.stopPropagation());
	}

	// Mobile navigation links
	document.querySelectorAll('.mobile-nav-link').forEach(link => {
		link.addEventListener('click', () => {
			const href = link.dataset.href;
			closeMobileMenu();
			if (href && href !== '#') {
				window.location.href = href;
			}
		});
	});

	// Mobile service links
	document.querySelectorAll('.mobile-service-link').forEach(link => {
		link.addEventListener('click', closeMobileMenu);
	});

	// Mobile service areas links
	document.querySelectorAll('.mobile-area-link').forEach(link => {
		link.addEventListener('click', closeMobileMenu);
	});

	// Location select
	if (locationSelectBtn && locationSelect) {
		locationSelectBtn.addEventListener('click', () => {
			locationSelect.focus();
			locationSelect.click();
		});

		locationSelect.addEventListener('change', (e) => {
			handleLocationSelect(e.target.value);
		});
	}

	// Close dropdown when clicking outside
	document.addEventListener('mousedown', (event) => {
		if (servicesDropdownContainer && !servicesDropdownContainer.contains(event.target)) {
			closeServicesDropdown();
		}
		if (serviceAreasDropdownContainer && !serviceAreasDropdownContainer.contains(event.target)) {
			closeServiceAreasDropdown();
		}
	});

	// Handle escape key
	document.addEventListener('keydown', (event) => {
		if (event.key === 'Escape') {
			if (isMobileMenuOpen) {
				closeMobileMenu();
			}
			if (isServicesDropdownOpen) {
				closeServicesDropdown();
			}
			if (isServiceAreasDropdownOpen) {
				closeServiceAreasDropdown();
			}
		}
	});

	// ===== DEBUGGING UTILITIES =====
	// Expose navigation state control to window object for debugging
	window.navDebug = {
		// State getters
		getState: () => ({
			servicesDropdownOpen: isServicesDropdownOpen,
			serviceAreasDropdownOpen: isServiceAreasDropdownOpen,
			mobileMenuOpen: isMobileMenuOpen,
			selectedLocation: selectedLocation,
			debugMode: debugMode
		}),

		// Debug mode control
		enableDebugMode: () => {
			debugMode = true;
			console.log('🔴 DEBUG MODE ENABLED - Hover events are now DISABLED');
			console.log('   Only manual commands will open/close dropdowns');
			console.log('   Use navDebug.disableDebugMode() to restore normal behavior');
		},
		disableDebugMode: () => {
			debugMode = false;
			console.log('🟢 DEBUG MODE DISABLED - Hover events restored to normal');
		},

		// Manual state control for Services dropdown (bypasses debug mode check)
		openServices: () => {
			console.log('🔵 Debug: Opening Services dropdown (manual control)');
			debugMode = true; // Enable debug mode automatically
			isServiceAreasDropdownOpen = false; // Close other dropdown
			updateServiceAreasDropdown();
			isServicesDropdownOpen = true;
			updateServicesDropdown();
		},
		closeServices: () => {
			console.log('🔵 Debug: Closing Services dropdown (manual control)');
			const wasDebugMode = debugMode;
			debugMode = false; // Temporarily disable to allow close
			isServicesDropdownOpen = false;
			updateServicesDropdown();
			debugMode = wasDebugMode; // Restore debug mode state
		},
		toggleServices: () => {
			console.log('🔵 Debug: Toggling Services dropdown (manual control)');
			debugMode = true; // Enable debug mode automatically
			if (!isServicesDropdownOpen) {
				isServiceAreasDropdownOpen = false; // Close other dropdown
				updateServiceAreasDropdown();
			}
			isServicesDropdownOpen = !isServicesDropdownOpen;
			updateServicesDropdown();
		},

		// Manual state control for Service Areas dropdown (bypasses debug mode check)
		openServiceAreas: () => {
			console.log('🟢 Debug: Opening Service Areas dropdown (manual control)');
			debugMode = true; // Enable debug mode automatically
			isServicesDropdownOpen = false; // Close other dropdown
			updateServicesDropdown();
			isServiceAreasDropdownOpen = true;
			updateServiceAreasDropdown();
		},
		closeServiceAreas: () => {
			console.log('🟢 Debug: Closing Service Areas dropdown (manual control)');
			const wasDebugMode = debugMode;
			debugMode = false; // Temporarily disable to allow close
			isServiceAreasDropdownOpen = false;
			updateServiceAreasDropdown();
			debugMode = wasDebugMode; // Restore debug mode state
		},
		toggleServiceAreas: () => {
			console.log('🟢 Debug: Toggling Service Areas dropdown (manual control)');
			debugMode = true; // Enable debug mode automatically
			if (!isServiceAreasDropdownOpen) {
				isServicesDropdownOpen = false; // Close other dropdown
				updateServicesDropdown();
			}
			isServiceAreasDropdownOpen = !isServiceAreasDropdownOpen;
			updateServiceAreasDropdown();
		},

		// Force both open (bypassing mutual exclusivity for debugging)
		forceOpenBoth: () => {
			console.log('🔴 Debug: FORCING both dropdowns open (bypassing mutual exclusivity)');
			debugMode = true; // Enable debug mode automatically
			isServicesDropdownOpen = true;
			isServiceAreasDropdownOpen = true;
			updateServicesDropdown();
			updateServiceAreasDropdown();
		},

		// Close all
		closeAll: () => {
			console.log('⭕ Debug: Closing all dropdowns');
			const wasDebugMode = debugMode;
			debugMode = false; // Temporarily disable to allow close
			isServicesDropdownOpen = false;
			isServiceAreasDropdownOpen = false;
			isMobileMenuOpen = false;
			updateServicesDropdown();
			updateServiceAreasDropdown();
			updateMobileMenu();
			debugMode = wasDebugMode; // Restore debug mode state
		},

		// Get DOM elements for inspection
		getElements: () => ({
			servicesDropdown,
			serviceAreasDropdown,
			servicesDropdownContainer,
			serviceAreasDropdownContainer,
			mobileMenu
		}),

		// Log current state
		logState: () => {
			const state = window.navDebug.getState();
			console.log('📊 Navigation State:', state);
			return state;
		},

		// Help message
		help: () => {
			console.log(`
🛠️  Navigation Debug Utilities Available:

📊 State Inspection:
	navDebug.getState()            - Get current state object
	navDebug.logState()            - Log current state to console
	navDebug.getElements()         - Get DOM element references

🔴 Debug Mode Control:
	navDebug.enableDebugMode()     - Disable hover events (manual control only)
	navDebug.disableDebugMode()    - Re-enable hover events (normal behavior)

🔵 Services Dropdown:
	navDebug.openServices()        - Open Services dropdown (auto-enables debug mode)
	navDebug.closeServices()       - Close Services dropdown
	navDebug.toggleServices()      - Toggle Services dropdown (auto-enables debug mode)

🟢 Service Areas Dropdown:
	navDebug.openServiceAreas()    - Open Service Areas dropdown (auto-enables debug mode)
	navDebug.closeServiceAreas()   - Close Service Areas dropdown
	navDebug.toggleServiceAreas()  - Toggle Service Areas dropdown (auto-enables debug mode)

🔴 Advanced:
	navDebug.forceOpenBoth()       - Force both dropdowns open (auto-enables debug mode)
	navDebug.closeAll()            - Close all dropdowns
	navDebug.help()                - Show this help message

Example usage for CSS debugging:
	navDebug.openServices()        // Opens Services & enables debug mode (stays open)
	// Now hover won't close it - only navDebug commands work
	navDebug.logState()            // Check current state
	navDebug.closeAll()            // Close everything
	navDebug.disableDebugMode()    // Restore normal hover behavior

Note: Opening any dropdown automatically enables debug mode to keep it open.
Use navDebug.disableDebugMode() when done debugging to restore normal behavior.
			`);
		}
	};

	// Log availability on page load
	console.log('🛠️  Navigation debug utilities loaded. Type navDebug.help() for available commands.');

	// Cleanup on page unload
	window.addEventListener('beforeunload', () => {
		document.body.style.overflow = '';
		if (hoverTimeout) {
			clearTimeout(hoverTimeout);
		}
	});

	// Initialize - detect current page and set active menu item
	const currentPath = window.location.pathname;
	let detectedMenuItem = 'Home';

	if (currentPath.includes('/about')) {
		detectedMenuItem = 'About';
	} else if (currentPath.includes('/services')) {
		detectedMenuItem = 'Services';
	} else if (currentPath.includes('/projects')) {
		detectedMenuItem = 'Projects';
	} else if (currentPath.includes('/blog')) {
		detectedMenuItem = 'Blog';
	} else if (currentPath.includes('/contact')) {
		detectedMenuItem = 'Contact Us';
	}

	updateActiveMenuItem(detectedMenuItem);
});
</script>
