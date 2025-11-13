<?php
/**
 * SEO Meta Tags
 *
 * Handles Open Graph, Twitter Cards, and other meta tags for social media and SEO
 */

/**
 * Add homepage meta tags
 */
function sunnysideac_homepage_meta_tags() {
	// Only run on homepage
	if ( ! is_front_page() ) {
		return;
	}

	$site_title       = get_bloginfo( 'name' );
	$site_description = get_bloginfo( 'description' );
	$home_url         = home_url( '/' );
	$logo_url         = get_template_directory_uri() . '/assets/images/logos/sunny-side-logo.png';

	?>
	<!-- Enhanced Meta Tags for Local SEO -->
	<meta name="description" content="<?php echo esc_attr( $site_description . ' | 24/7 Emergency AC Repair, Installation & Maintenance in South Florida. Family-owned since 2014. Call ' . SUNNYSIDE_PHONE_DISPLAY . ' for fast service!' ); ?>">

	<!-- Open Graph Meta Tags -->
	<meta property="og:title" content="<?php echo esc_attr( $site_title ); ?> | 24/7 AC Repair South Florida">
	<meta property="og:description" content="<?php echo esc_attr( $site_description . ' | Professional HVAC services with 24/7 emergency response. Serving all of South Florida.' ); ?>">
	<meta property="og:type" content="website">
	<meta property="og:url" content="<?php echo esc_url( $home_url ); ?>">
	<meta property="og:image" content="<?php echo esc_url( $logo_url ); ?>">
	<meta property="og:image:width" content="1200">
	<meta property="og:image:height" content="630">
	<meta property="og:site_name" content="<?php echo esc_attr( $site_title ); ?>">
	<meta property="og:locale" content="en_US">

	<!-- Twitter Card Meta Tags -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php echo esc_attr( $site_title ); ?> | 24/7 AC Repair">
	<meta name="twitter:description" content="<?php echo esc_attr( $site_description . ' | Fast, professional HVAC service available 24/7.' ); ?>">
	<meta name="twitter:image" content="<?php echo esc_url( $logo_url ); ?>">
	<meta name="twitter:site" content="@sunnyside247ac">

	<!-- Additional Local SEO Meta Tags -->
	<meta name="geo.region" content="US-FL">
	<meta name="geo.placename" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_CITY ); ?>">
	<meta name="geo.position" content="<?php echo esc_attr( SUNNYSIDE_LATITUDE . ';' . SUNNYSIDE_LONGITUDE ); ?>">
	<meta name="ICBM" content="<?php echo esc_attr( SUNNYSIDE_LATITUDE . ',' . SUNNYSIDE_LONGITUDE ); ?>">

	<!-- Business Information -->
	<meta name="business:contact_data:street_address" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_STREET ); ?>">
	<meta name="business:contact_data:locality" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_CITY ); ?>">
	<meta name="business:contact_data:region" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_STATE ); ?>">
	<meta name="business:contact_data:postal_code" content="<?php echo esc_attr( SUNNYSIDE_ADDRESS_ZIP ); ?>">
	<meta name="business:contact_data:country_name" content="USA">
	<meta name="business:contact_data:phone_number" content="<?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
	<meta name="business:contact_data:email" content="<?php echo esc_attr( SUNNYSIDE_EMAIL_ADDRESS ); ?>">
	<?php
}
add_action( 'wp_head', 'sunnysideac_homepage_meta_tags', 1 );