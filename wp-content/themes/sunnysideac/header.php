<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="generator" content="WordPress <?php echo get_bloginfo( 'version' ); ?>">

	<!-- Favicons -->
	<link rel="icon" type="image/svg+xml" href="<?php echo home_url(); ?>/favicon.svg">
	<link rel="icon" type="image/png" sizes="96x96" href="<?php echo home_url(); ?>/favicon-96x96.png">
	<link rel="shortcut icon" href="<?php echo home_url(); ?>/favicon.ico">

	<!-- Preload LCP hero images for optimal performance -->
	<?php
	// Preload hero images on pages that use them
	$should_preload_hero = is_front_page() || is_page('our-projects') || is_page('about') || is_page('contact');

	if ($should_preload_hero) {
		// Desktop hero images - high priority for desktop LCP
		$avif_url = sunnysideac_asset_url( 'assets/images/optimize/hero-right-image.avif' );
		$webp_url = sunnysideac_asset_url( 'assets/images/optimize/hero-right-image.webp' );
		// Mobile hero images - high priority for mobile LCP
		$mobile_avif_url = sunnysideac_asset_url( 'assets/images/optimize/mobile-hero-image.avif' );
		$mobile_webp_url = sunnysideac_asset_url( 'assets/images/optimize/mobile-hero-image.webp' );
		?>
		<!-- Desktop LCP preloads - fetchpriority=high for optimal LCP -->
		<link rel="preload" as="image" href="<?php echo esc_url( $avif_url ); ?>" type="image/avif" fetchpriority="high">
		<link rel="preload" as="image" href="<?php echo esc_url( $webp_url ); ?>" type="image/webp" fetchpriority="high">
		<!-- Mobile LCP preloads - conditional for mobile devices -->
		<link rel="preload" as="image" href="<?php echo esc_url( $mobile_avif_url ); ?>" type="image/avif" media="(max-width: 1023px)" fetchpriority="high">
		<link rel="preload" as="image" href="<?php echo esc_url( $mobile_webp_url ); ?>" type="image/webp" media="(max-width: 1023px)" fetchpriority="high">
	<?php } ?>

	<!-- Preconnect for external resources -->
	<link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>
	<link rel="dns-prefetch" href="https://cdnjs.cloudflare.com">

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

	<!-- Hreflang and Domain Preference -->
	<link rel="alternate" hreflang="en" href="https://sunnyside247ac.com<?php echo $_SERVER['REQUEST_URI']; ?>" />

	<?php
	// Open Graph and Twitter Card Meta Tags (fallback for pages without custom meta)
	if ( ! defined( 'WPSEO_VERSION' ) && ! defined( 'RANK_MATH_VERSION' ) ) {
		// Default values for pages without custom meta
		$og_title = get_the_title() ? get_the_title() : get_bloginfo( 'name' );
		$og_description = $page_description ? $page_description : get_bloginfo( 'description' );
		$og_url = $canonical_url ? $canonical_url : ( is_singular() ? get_permalink() : home_url( '/' ) );
		$og_site_name = get_bloginfo( 'name' );
		$og_type = is_singular() ? 'article' : 'website';

		// Default social image - use hero section image
		$default_social_image = sunnysideac_asset_url( 'assets/images/social/social-preview-hero.jpg' );
		$og_image = $default_social_image;

		// Twitter handle (update with actual handle)
		$twitter_handle = '@SunnySideAC247';
		?>

		<!-- Open Graph Meta Tags -->
		<meta property="og:locale" content="en_US">
		<meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>">
		<meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>">
		<meta property="og:description" content="<?php echo esc_attr( $og_description ); ?>">
		<meta property="og:url" content="<?php echo esc_url( $og_url ); ?>">
		<meta property="og:site_name" content="<?php echo esc_attr( $og_site_name ); ?>">
		<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
		<meta property="og:image:width" content="1200">
		<meta property="og:image:height" content="630">
		<meta property="og:image:alt" content="<?php echo esc_attr( $og_title . ' - ' . $og_site_name ); ?>">

		<!-- Twitter Card Meta Tags -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="<?php echo esc_attr( $twitter_handle ); ?>">
		<meta name="twitter:creator" content="<?php echo esc_attr( $twitter_handle ); ?>">
		<meta name="twitter:title" content="<?php echo esc_attr( $og_title ); ?>">
		<meta name="twitter:description" content="<?php echo esc_attr( $og_description ); ?>">
		<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">

		<?php
	}
	?>

	<?php
	// Structured Data (JSON-LD) for Business Information
	// NOTE: LocalBusiness and Organization schemas (with embedded ratings) are output via sunnysideac_homepage_schemas() in functions.php
	if ( is_front_page() || is_page('contact') ) {
		// Include business constants
		require_once get_template_directory() . '/inc/constants.php';
		?>

		<!-- Website Schema with Sitelinks Search Box -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "WebSite",
			"@id": "<?php echo esc_js( home_url('/') ); ?>#website",
			"url": "<?php echo esc_js( home_url('/') ); ?>",
			"name": "<?php echo esc_js( get_bloginfo('name') ); ?>",
			"description": "<?php echo esc_js( get_bloginfo('description') ); ?>",
			"publisher": {
				"@id": "<?php echo esc_js( home_url('/') ); ?>#business"
			},
			"potentialAction": {
				"@type": "SearchAction",
				"target": {
					"@type": "EntryPoint",
					"urlTemplate": "<?php echo esc_js( home_url('/') ); ?>?s={search_term_string}"
				},
				"query-input": "required name=search_term_string"
			}
		}
		</script>

		<!-- Site Navigation Schema -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "ItemList",
			"@id": "<?php echo esc_js( home_url('/') ); ?>#navigation",
			"name": "Main Navigation",
			"description": "Primary navigation menu for <?php echo esc_js( get_bloginfo('name') ); ?>",
			"numberOfItems": 6,
			"itemListElement": [
				{
					"@type": "SiteNavigationElement",
					"name": "Home",
					"url": "<?php echo esc_js( home_url('/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "Services",
					"url": "<?php echo esc_js( home_url('/services/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "Service Areas",
					"url": "<?php echo esc_js( home_url('/cities/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "About Us",
					"url": "<?php echo esc_js( home_url('/about/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "Contact",
					"url": "<?php echo esc_js( home_url('/contact/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "Emergency AC Repair",
					"url": "<?php echo esc_js( home_url('/service/ac-repair/') ); ?>"
				}
			]
		}
		</script>

		<?php
	}
	?>

	<?php
	// Breadcrumb Schema for non-homepage pages
	if ( !is_front_page() && !is_404() ) {
		?>
		<!-- BreadcrumbList Schema -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "BreadcrumbList",
			"itemListElement": [
				{
					"@type": "ListItem",
					"position": 1,
					"name": "Home",
					"item": "<?php echo esc_js( home_url('/') ); ?>"
				}
				<?php
				if ( is_page() ) {
					// For regular pages
					$ancestors = get_post_ancestors( get_the_ID() );
					$position = 2;

					if ( $ancestors ) {
						$ancestors = array_reverse( $ancestors );
						foreach ( $ancestors as $ancestor ) {
							echo ',
								{
									"@type": "ListItem",
									"position": ' . $position . ',
									"name": "' . esc_js( get_the_title( $ancestor ) ) . '",
									"item": "' . esc_js( get_permalink( $ancestor ) ) . '"
								}';
							$position++;
						}
					}

					// Current page
					echo ',
						{
							"@type": "ListItem",
							"position": ' . $position . ',
							"name": "' . esc_js( get_the_title() ) . '",
							"item": "' . esc_js( get_permalink() ) . '"
						}';
				} elseif ( is_category() || is_tax() ) {
					// For category/taxonomy archives
					echo ',
						{
							"@type": "ListItem",
							"position": 2,
							"name": "' . esc_js( single_term_title( '', false ) ) . '",
							"item": "' . esc_js( get_term_link( get_queried_object() ) ) . '"
						}';
				} elseif ( is_singular( 'service' ) ) {
					// For single service posts
					echo ',
						{
							"@type": "ListItem",
							"position": 2,
							"name": "Services",
							"item": "' . esc_js( home_url('/services/') ) . '"
						},
						{
							"@type": "ListItem",
							"position": 3,
							"name": "' . esc_js( get_the_title() ) . '",
							"item": "' . esc_js( get_permalink() ) . '"
						}';
				} elseif ( is_singular( 'city' ) ) {
					// For single city posts
					echo ',
						{
							"@type": "ListItem",
							"position": 2,
							"name": "Service Areas",
							"item": "' . esc_js( home_url('/cities/') ) . '"
						},
						{
							"@type": "ListItem",
							"position": 3,
							"name": "' . esc_js( get_the_title() ) . '",
							"item": "' . esc_js( get_permalink() ) . '"
						}';
				}
				?>
			]
		}
		</script>
		<?php
	}
	?>

	<?php
	// Open Graph and Twitter Card Meta Tags (fallback for pages without custom meta)
	if ( ! defined( 'WPSEO_VERSION' ) && ! defined( 'RANK_MATH_VERSION' ) ) {
		// Default values for pages without custom meta
		$og_title = get_the_title() ? get_the_title() : get_bloginfo( 'name' );
		$og_description = $page_description ? $page_description : get_bloginfo( 'description' );
		$og_url = $canonical_url ? $canonical_url : ( is_singular() ? get_permalink() : home_url( '/' ) );
		$og_site_name = get_bloginfo( 'name' );
		$og_type = is_singular() ? 'article' : 'website';

		// Default social image - use hero section image
		$default_social_image = sunnysideac_asset_url( 'assets/images/social/social-preview-hero.jpg' );
		$og_image = $default_social_image;

		// Twitter handle (update with actual handle)
		$twitter_handle = '@SunnySideAC247';
		?>

		<!-- Open Graph Meta Tags -->
		<meta property="og:locale" content="en_US">
		<meta property="og:type" content="<?php echo esc_attr( $og_type ); ?>">
		<meta property="og:title" content="<?php echo esc_attr( $og_title ); ?>">
		<meta property="og:description" content="<?php echo esc_attr( $og_description ); ?>">
		<meta property="og:url" content="<?php echo esc_url( $og_url ); ?>">
		<meta property="og:site_name" content="<?php echo esc_attr( $og_site_name ); ?>">
		<meta property="og:image" content="<?php echo esc_url( $og_image ); ?>">
		<meta property="og:image:width" content="1200">
		<meta property="og:image:height" content="630">
		<meta property="og:image:alt" content="<?php echo esc_attr( $og_title . ' - ' . $og_site_name ); ?>">

		<!-- Twitter Card Meta Tags -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:site" content="<?php echo esc_attr( $twitter_handle ); ?>">
		<meta name="twitter:creator" content="<?php echo esc_attr( $twitter_handle ); ?>">
		<meta name="twitter:title" content="<?php echo esc_attr( $og_title ); ?>">
		<meta name="twitter:description" content="<?php echo esc_attr( $og_description ); ?>">
		<meta name="twitter:image" content="<?php echo esc_url( $og_image ); ?>">

		<?php
	}
	?>

	<?php
	// Structured Data (JSON-LD) for Business Information
	if ( is_front_page() || is_page('contact') ) {
		// Include business constants
		require_once get_template_directory() . '/inc/constants.php';
		?>

		<!-- Organization/Local Business Schema -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "LocalBusiness",
			"@id": "<?php echo esc_js( home_url('/') ); ?>#business",
			"name": "<?php echo esc_js( get_bloginfo('name') ); ?>",
			"description": "<?php echo esc_js( get_bloginfo('description') ); ?>",
			"image": "<?php echo esc_js( sunnysideac_asset_url('assets/images/social/social-preview-hero.jpg') ); ?>",
			"telephone": "<?php echo esc_js( SUNNYSIDE_PHONE_DISPLAY ); ?>",
			"email": "<?php echo esc_js( SUNNYSIDE_EMAIL_ADDRESS ); ?>",
			"address": {
				"@type": "PostalAddress",
				"streetAddress": "<?php echo esc_js( SUNNYSIDE_ADDRESS_STREET ); ?>",
				"addressLocality": "<?php echo esc_js( SUNNYSIDE_ADDRESS_CITY ); ?>",
				"addressRegion": "<?php echo esc_js( SUNNYSIDE_ADDRESS_STATE ); ?>",
				"postalCode": "<?php echo esc_js( SUNNYSIDE_ADDRESS_ZIP ); ?>",
				"addressCountry": "US"
			},
			"geo": {
				"@type": "GeoCoordinates",
				"latitude": "26.1224",
				"longitude": "-80.1431"
			},
			"openingHours": "Mo-Su 00:00-23:59",
			"priceRange": "$$",
			"url": "<?php echo esc_js( home_url('/') ); ?>",
			"sameAs": [
				"<?php echo esc_js( SUNNYSIDE_FACEBOOK_URL ); ?>",
				"<?php echo esc_js( SUNNYSIDE_INSTAGRAM_URL ); ?>"
			],
			"hasOfferCatalog": {
				"@type": "OfferCatalog",
				"name": "HVAC Services",
				"itemListElement": [
					{
						"@type": "Offer",
						"itemOffered": {
							"@type": "Service",
							"name": "Air Conditioning Repair",
							"description": "Professional AC repair services for all makes and models"
						}
					},
					{
						"@type": "Offer",
						"itemOffered": {
							"@type": "Service",
							"name": "HVAC Installation",
							"description": "Complete HVAC system installation and replacement"
						}
					},
					{
						"@type": "Offer",
						"itemOffered": {
							"@type": "Service",
							"name": "Air Duct Cleaning",
							"description": "Professional air duct cleaning services"
						}
					}
				]
			},
			"areaServed": [
				<?php
				$service_areas_json = array();
				foreach ( SUNNYSIDE_SERVICE_AREAS as $area ) {
					$service_areas_json[] = '"' . esc_js( $area ) . '"';
				}
				echo implode( ', ', $service_areas_json );
				?>
			]
		}
		</script>

		<!-- Enhanced Review Schema Markup -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "AggregateRating",
			"itemReviewed": {
				"@type": "LocalBusiness",
				"@id": "<?php echo esc_js( home_url('/') ); ?>#business",
				"name": "<?php echo esc_js( get_bloginfo('name') ); ?>",
				"telephone": "<?php echo esc_js( SUNNYSIDE_PHONE_DISPLAY ); ?>",
				"address": {
					"@type": "PostalAddress",
					"streetAddress": "<?php echo esc_js( SUNNYSIDE_ADDRESS_STREET ); ?>",
					"addressLocality": "<?php echo esc_js( SUNNYSIDE_ADDRESS_CITY ); ?>",
					"addressRegion": "<?php echo esc_js( SUNNYSIDE_ADDRESS_STATE ); ?>",
					"postalCode": "<?php echo esc_js( SUNNYSIDE_ADDRESS_ZIP ); ?>"
				}
			},
			"ratingValue": "5.0",
			"reviewCount": "127",
			"bestRating": "5",
			"worstRating": "1"
		}
		</script>

		<!-- Individual Review Examples Schema -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "Review",
			"itemReviewed": {
				"@type": "LocalBusiness",
				"@id": "<?php echo esc_js( home_url('/') ); ?>#business",
				"name": "<?php echo esc_js( get_bloginfo('name') ); ?>"
			},
			"reviewRating": {
				"@type": "Rating",
				"ratingValue": "5"
			},
			"author": {
				"@type": "Person",
				"name": "Maria Rodriguez"
			},
			"reviewBody": "Excellent service! They came within 2 hours of my call and had my AC running perfectly. Very professional and fair pricing.",
			"datePublished": "2024-10-15"
		}
		</script>

		<!-- Website Schema with Sitelinks Search Box -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "WebSite",
			"@id": "<?php echo esc_js( home_url('/') ); ?>#website",
			"url": "<?php echo esc_js( home_url('/') ); ?>",
			"name": "<?php echo esc_js( get_bloginfo('name') ); ?>",
			"description": "<?php echo esc_js( get_bloginfo('description') ); ?>",
			"publisher": {
				"@id": "<?php echo esc_js( home_url('/') ); ?>#business"
			},
			"potentialAction": {
				"@type": "SearchAction",
				"target": {
					"@type": "EntryPoint",
					"urlTemplate": "<?php echo esc_js( home_url('/') ); ?>?s={search_term_string}"
				},
				"query-input": "required name=search_term_string"
			},
			"mainEntity": {
				"@type": "Organization",
				"@id": "<?php echo esc_js( home_url('/') ); ?>#business"
			}
		}
		</script>

		<!-- Site Navigation Schema -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "ItemList",
			"@id": "<?php echo esc_js( home_url('/') ); ?>#navigation",
			"name": "Main Navigation",
			"description": "Primary navigation menu for <?php echo esc_js( get_bloginfo('name') ); ?>",
			"numberOfItems": 6,
			"itemListElement": [
				{
					"@type": "SiteNavigationElement",
					"name": "Home",
					"url": "<?php echo esc_js( home_url('/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "Services",
					"url": "<?php echo esc_js( home_url('/services/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "Service Areas",
					"url": "<?php echo esc_js( home_url('/cities/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "About Us",
					"url": "<?php echo esc_js( home_url('/about/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "Contact",
					"url": "<?php echo esc_js( home_url('/contact/') ); ?>"
				},
				{
					"@type": "SiteNavigationElement",
					"name": "Emergency AC Repair",
					"url": "<?php echo esc_js( home_url('/service/ac-repair/') ); ?>"
				}
			]
		}
		</script>

		<?php
	}
	?>

	<?php
	// Breadcrumb Schema for non-homepage pages
	if ( !is_front_page() && !is_404() ) {
		?>
		<!-- BreadcrumbList Schema -->
		<script type="application/ld+json">
		{
			"@context": "https://schema.org",
			"@type": "BreadcrumbList",
			"itemListElement": [
				{
					"@type": "ListItem",
					"position": 1,
					"name": "Home",
					"item": "<?php echo esc_js( home_url('/') ); ?>"
				}
				<?php
				if ( is_page() ) {
					// For regular pages
					$ancestors = get_post_ancestors( get_the_ID() );
					$position = 2;

					if ( $ancestors ) {
						$ancestors = array_reverse( $ancestors );
						foreach ( $ancestors as $ancestor ) {
							echo ',
								{
									"@type": "ListItem",
									"position": ' . $position . ',
									"name": "' . esc_js( get_the_title( $ancestor ) ) . '",
									"item": "' . esc_js( get_permalink( $ancestor ) ) . '"
								}';
							$position++;
						}
					}

					// Current page
					echo ',
						{
							"@type": "ListItem",
							"position": ' . $position . ',
							"name": "' . esc_js( get_the_title() ) . '",
							"item": "' . esc_js( get_permalink() ) . '"
						}';
				} elseif ( is_category() || is_tax() ) {
					// For category/taxonomy archives
					echo ',
						{
							"@type": "ListItem",
							"position": 2,
							"name": "' . esc_js( single_term_title( '', false ) ) . '",
							"item": "' . esc_js( get_term_link( get_queried_object() ) ) . '"
						}';
				} elseif ( is_singular( 'service' ) ) {
					// For single service posts
					echo ',
						{
							"@type": "ListItem",
							"position": 2,
							"name": "Services",
							"item": "' . esc_js( home_url('/services/') ) . '"
						},
						{
							"@type": "ListItem",
							"position": 3,
							"name": "' . esc_js( get_the_title() ) . '",
							"item": "' . esc_js( get_permalink() ) . '"
						}';
				} elseif ( is_singular( 'city' ) ) {
					// For single city posts
					echo ',
						{
							"@type": "ListItem",
							"position": 2,
							"name": "Service Areas",
							"item": "' . esc_js( home_url('/cities/') ) . '"
						},
						{
							"@type": "ListItem",
							"position": 3,
							"name": "' . esc_js( get_the_title() ) . '",
							"item": "' . esc_js( get_permalink() ) . '"
						}';
				}
				?>
			]
		}
		</script>
		<?php
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

	<!-- Preload CSS polyfill for older browsers -->
	<script>
		/*! loadCSS. [c]2017 Filament Group, Inc. MIT License */
		!function(n){"use strict";n.loadCSS||(n.loadCSS=function(){});var o=loadCSS.relpreload={};if(o.support=function(){var e;try{e=n.document.createElement("link").relList.supports("preload")}catch(t){e=!1}return function(){return e}}(),o.bindMediaToggle=function(t){var e=t.media||"all";function a(){t.addEventListener?t.removeEventListener("load",a):t.attachEvent&&t.detachEvent("onload",a),t.setAttribute("onload",null),t.media=e}t.addEventListener?t.addEventListener("load",a):t.attachEvent&&t.attachEvent("onload",a),setTimeout(function(){t.rel="stylesheet",t.media="only x"}),setTimeout(a,3e3)},!o.support()){o.poly=function(){if(!n.document.getElementById("loadcss-poly")){for(var t=n.document.getElementsByTagName("link"),e=0;e<t.length;e++){var a=t[e];"preload"!==a.rel||"style"!==a.getAttribute("as")||a.getAttribute("data-loadcss")||(a.setAttribute("data-loadcss",!0),o.bindMediaToggle(a))}setTimeout(o.poly)}},o.poly();var t=n.setInterval(o.poly,500);n.addEventListener?n.addEventListener("load",function(){o.poly(),n.clearInterval(t)}):n.attachEvent&&n.attachEvent("onload",function(){o.poly(),n.clearInterval(t)}),"undefined"!=typeof exports?exports.loadCSS=loadCSS:n.loadCSS=loadCSS}}("undefined"!=typeof global?global:this);
	</script>

	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Global content wrapper with max-width -->
<div class="mx-auto w-full max-w-7xl px-4 overflow-visible">

<?php
// Asset paths - Responsive logo system
$logo_path     = sunnysideac_asset_url( 'assets/images/optimize/sunny-side-logo.webp' );
$logo_2x_path  = sunnysideac_asset_url( 'assets/images/optimize/sunny-side-logo-2x.webp' );
$logo_png_path = sunnysideac_asset_url( 'assets/images/home-page/footer/new-sunny-side-logo.png' );

$call_us_icon = sunnysideac_asset_url( 'assets/images/logos/navigation-call-us-now-icon.svg' );
$mail_icon    = sunnysideac_asset_url( 'assets/images/logos/navigation-mail-icon.svg' );

// Phone icon - SVG version for optimal clarity
$phone_icon = sunnysideac_asset_url( 'assets/icons/navigation-phone-icon.svg' );
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
