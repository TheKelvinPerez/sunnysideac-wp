<?php
/**
 * Schema.org JSON-LD Structured Data
 *
 * Generates LocalBusiness and Organization schemas for SEO
 */

/**
 * Generate LocalBusiness JSON-LD Schema for Homepage
 *
 * @return string JSON-LD schema markup
 */
function sunnysideac_get_localbusiness_schema() {
	$schema = array(
		'@context'                  => 'https://schema.org',
		'@type'                     => 'LocalBusiness',
		'name'                      => 'SunnySide 24/7 AC',
		'alternateName'             => 'Sunnyside AC',
		'description'               => 'Professional HVAC services including AC repair, installation, and maintenance. Family-owned and operated since 2014, serving South Florida with 24/7 emergency service.',
		'url'                       => home_url( '/' ),
		'telephone'                 => SUNNYSIDE_PHONE_SCHEMA,
		'email'                     => SUNNYSIDE_EMAIL_ADDRESS,
		'image'                     => get_template_directory_uri() . '/assets/images/social/social-preview-hero.jpg',
		'address'                   => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => SUNNYSIDE_ADDRESS_STREET,
			'addressLocality' => SUNNYSIDE_ADDRESS_CITY,
			'addressRegion'   => SUNNYSIDE_ADDRESS_STATE,
			'postalCode'      => SUNNYSIDE_ADDRESS_ZIP,
			'addressCountry'  => 'US',
		),
		'geo'                       => array(
			'@type'     => 'GeoCoordinates',
			'latitude'  => SUNNYSIDE_LATITUDE,
			'longitude' => SUNNYSIDE_LONGITUDE,
		),
		'openingHoursSpecification' => array(),
		'areaServed'                => array(),
		'hasOfferCatalog'           => array(
			'@type'           => 'OfferCatalog',
			'name'            => 'HVAC Services',
			'itemListElement' => array(),
		),
		'sameAs'                    => array(
			SUNNYSIDE_FACEBOOK_URL,
			SUNNYSIDE_INSTAGRAM_URL,
			SUNNYSIDE_TWITTER_URL,
			SUNNYSIDE_YOUTUBE_URL,
			SUNNYSIDE_LINKEDIN_URL,
		),
		'foundingDate'              => SUNNYSIDE_FOUNDING_DATE . '-01-01',
		'priceRange'                => '$$',
		'paymentAccepted'           => array( 'Cash', 'Credit Card', 'Check' ),
		'aggregateRating'           => array(
			'@type'       => 'AggregateRating',
			'ratingValue' => '5.0',
			'reviewCount' => '127',
			'bestRating'  => '5',
			'worstRating' => '1',
		),
	);

	// Add opening hours
	foreach ( SUNNYSIDE_BUSINESS_HOURS as $day => $hours ) {
		$schema['openingHoursSpecification'][] = array(
			'@type'     => 'OpeningHoursSpecification',
			'dayOfWeek' => $day,
			'opens'     => substr( $hours, 0, 5 ),
			'closes'    => substr( $hours, -5 ),
		);
	}

	// Add service areas
	foreach ( SUNNYSIDE_SERVICE_AREAS as $area ) {
		$schema['areaServed'][] = array(
			'@type' => 'City',
			'name'  => $area,
		);
	}

	// Add services to catalog
	$services_by_category = SUNNYSIDE_SERVICES_BY_CATEGORY;
	$all_services         = array_merge( $services_by_category['cooling'], $services_by_category['heating'], $services_by_category['air_quality'] );

	foreach ( $all_services as $service ) {
		$schema['hasOfferCatalog']['itemListElement'][] = array(
			'@type'             => 'Offer',
			'itemOffered'       => array(
				'@type' => 'Service',
				'name'  => $service,
			),
			'areaServed'        => 'South Florida',
			'availableAtOrFrom' => array(
				'@type'   => 'Place',
				'address' => array(
					'@type'         => 'PostalAddress',
					'addressRegion' => 'FL',
				),
			),
		);
	}

	return json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS );
}

/**
 * Generate Organization JSON-LD Schema for Homepage
 *
 * @return string JSON-LD schema markup
 */
function sunnysideac_get_organization_schema() {
	$schema = array(
		'@context'      => 'https://schema.org',
		'@type'         => 'Organization',
		'name'          => 'SunnySide 24/7 AC',
		'alternateName' => 'Sunnyside AC',
		'url'           => home_url( '/' ),
		'logo'          => get_template_directory_uri() . '/assets/images/logos/sunny-side-logo.png',
		'description'   => 'Family-owned and operated HVAC company serving South Florida since 2014. Specializing in AC repair, installation, and maintenance with 24/7 emergency service.',
		'contactPoint'  => array(
			'@type'             => 'ContactPoint',
			'telephone'         => SUNNYSIDE_PHONE_SCHEMA,
			'contactType'       => 'customer service',
			'areaServed'        => 'South Florida',
			'availableLanguage' => array( 'English', 'Spanish' ),
		),
		'address'       => array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => SUNNYSIDE_ADDRESS_STREET,
			'addressLocality' => SUNNYSIDE_ADDRESS_CITY,
			'addressRegion'   => SUNNYSIDE_ADDRESS_STATE,
			'postalCode'      => SUNNYSIDE_ADDRESS_ZIP,
			'addressCountry'  => 'US',
		),
		'sameAs'        => array(
			SUNNYSIDE_FACEBOOK_URL,
			SUNNYSIDE_INSTAGRAM_URL,
			SUNNYSIDE_TWITTER_URL,
			SUNNYSIDE_YOUTUBE_URL,
			SUNNYSIDE_LINKEDIN_URL,
		),
		'foundingDate'  => SUNNYSIDE_FOUNDING_DATE . '-01-01',
		'areaServed'    => array(),
	);

	// Add service areas
	foreach ( SUNNYSIDE_SERVICE_AREAS as $area ) {
		$schema['areaServed'][] = array(
			'@type' => 'City',
			'name'  => $area,
		);
	}

	return json_encode( $schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_HEX_APOS );
}

/**
 * Output homepage schemas in head
 */
function sunnysideac_homepage_schemas() {
	// Only run on homepage
	if ( ! is_front_page() ) {
		return;
	}
	?>
	<!-- Local SEO Schema Markup -->
	<script type="application/ld+json">
	<?php echo sunnysideac_get_localbusiness_schema(); ?>
	</script>

	<script type="application/ld+json">
	<?php echo sunnysideac_get_organization_schema(); ?>
	</script>
	<?php
}
add_action( 'wp_head', 'sunnysideac_homepage_schemas', 5 );