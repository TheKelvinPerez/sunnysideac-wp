<?php
/**
 * Global Constants
 * Define all theme constants here
 */

// Phone number constants
define( 'SUNNYSIDE_TEL_HREF', 'tel:+13059789382' );
define( 'SUNNYSIDE_PHONE_DISPLAY', '(305) 978-9382' );
define( 'SUNNYSIDE_PHONE_SCHEMA', '+13059789382' ); // Clean format for schema.org (no "tel:" prefix)

// Email constants
define( 'SUNNYSIDE_EMAIL_ADDRESS', 'support@sunnyside247ac.com' );
define( 'SUNNYSIDE_MAILTO_HREF', 'mailto:support@sunnyside247ac.com' );

// Address constants
define( 'SUNNYSIDE_ADDRESS_STREET', '6609 Emerald Lake Dr' );
define( 'SUNNYSIDE_ADDRESS_CITY', 'Miramar' );
define( 'SUNNYSIDE_ADDRESS_STATE', 'FL' );
define( 'SUNNYSIDE_ADDRESS_ZIP', '33023' );
define( 'SUNNYSIDE_ADDRESS_FULL', '6609 Emerald Lake Dr, Miramar, FL 33023' );
define( 'SUNNYSIDE_ADDRESS_GOOGLE_MAPS_URL', 'https://maps.google.com/?q=6609+Emerald+Lake+Dr,+Miramar,+FL+33023' );

// Service areas
define(
	'SUNNYSIDE_SERVICE_AREAS',
	array(
		'Miramar',
		'Pembroke Pines',
		'South West Ranches',
		'Miami Lakes',
		'West Park',
		'Pembroke Park',
		'Davie',
		'Hollywood',
		'Weston',
		'Hialeah Lakes',
		'Sunny Isles',
		'Plantation',
		'Sunrise',
		'Miami',
		'Fort Lauderdale',
		'Tamarac',
		'Coral Gables',
		'Coral Springs',
		'Key Biscayne',
		'Pompano Beach',
		'Palmetto Bay',
		'Light House Point',
		'Deerfield Beach',
		'Boca Raton',
		'Homestead',
		'Palm Springs',
		'Wellington',
		'Royal Palm Beach',
		'West Palm Beach',
		'Palm Beach',
	)
);

// Service categorization for menu organization
define(
	'SUNNYSIDE_SERVICES_BY_CATEGORY',
	array(
		'cooling'     => array(
			'AC Repair',
			'AC Installation',
			'AC Maintenance',
			'AC Replacement',
		),
		'heating'     => array(
			'Heating Repair',
			'Heating Installation',
			'Heat Pumps',
		),
		'air_quality' => array(
			'Ductless / Mini Split',
			'Indoor Air Quality',
			'Water Heaters',
		),
	)
);

// Priority cities for dropdown (top 16)
define(
	'SUNNYSIDE_PRIORITY_CITIES',
	array(
		'Pembroke Pines',
		'Miramar',
		'Weston',
		'Hollywood',
		'Fort Lauderdale',
		'Miami',
		'Boca Raton',
		'West Palm Beach',
		'Coral Springs',
		'Plantation',
		'Davie',
		'Sunrise',
		'Pompano Beach',
		'Hialeah Lakes',
		'Coral Gables',
		'Deerfield Beach',
	)
);

// Service URL patterns
define( 'SUNNYSIDE_SERVICE_URL_PATTERN', '/services/%s' );
define( 'SUNNYSIDE_CITY_URL_PATTERN', '/cities/%s' );

// Social media links
define( 'SUNNYSIDE_FACEBOOK_URL', 'https://facebook.com/sunnyside247ac' );
define( 'SUNNYSIDE_INSTAGRAM_URL', 'https://instagram.com/sunnyside247ac' );
define( 'SUNNYSIDE_TWITTER_URL', 'https://twitter.com/sunnyside247ac' );
define( 'SUNNYSIDE_YOUTUBE_URL', 'https://youtube.com/@sunnyside247ac' );
define( 'SUNNYSIDE_LINKEDIN_URL', 'https://linkedin.com/company/sunnyside247ac' );

// Business hours and coordinates for Local SEO
define(
	'SUNNYSIDE_BUSINESS_HOURS',
	array(
		'Monday'    => '07:00-22:00',
		'Tuesday'   => '07:00-22:00',
		'Wednesday' => '07:00-22:00',
		'Thursday'  => '07:00-22:00',
		'Friday'    => '07:00-22:00',
		'Saturday'  => '07:00-22:00',
		'Sunday'    => '07:00-22:00',
	)
);

// Geo-coordinates for Miramar, FL office
define( 'SUNNYSIDE_LATITUDE', '25.9763' );
define( 'SUNNYSIDE_LONGITUDE', '-80.2989' );

// Business identifiers
define( 'SUNNYSIDE_FOUNDING_DATE', '2014' );
define( 'SUNNYSIDE_BUSINESS_TYPE', 'HVACContractor' );
define( 'SUNNYSIDE_TAX_ID', '' ); // Add if needed for verification

// Brands we service
define(
	'SUNNYSIDE_BRANDS',
	array(
		'daikin'  => 'Daikin',
		'carrier' => 'Carrier',
		'trane'   => 'Trane',
		'goodman' => 'Goodman',
		'rheem'   => 'Rheem',
		'lennox'  => 'Lennox',
		'bryant'  => 'Bryant',
	)
);

// City videos
define(
	'SUNNYSIDE_CITY_VIDEOS',
	array(
		'Palm Beach'           => 'https://youtu.be/q7mF0BGn6iE',
		'West Palm Beach'      => 'https://youtu.be/5eTLTT7h3aU',
		'Royal Palm Beach'     => 'https://youtu.be/2tKWc5rx_g8',
		'Wellington'           => 'https://www.youtube.com/watch?v=eOnemeiOCRQ',
		'Palm Springs'         => 'https://www.youtube.com/watch?v=tUmzAMND8is',
		'Homestead'            => 'https://www.youtube.com/watch?v=KwmorRpJj00',
		'Boca Raton'           => 'https://www.youtube.com/watch?v=znTpJazXzdY',
		'Deerfield Beach'      => 'https://youtu.be/Ks2Vf5sNrRU',
		'Light House Point'    => 'https://www.youtube.com/watch?v=J21qK703wUQ',
		'Palmetto Bay'         => 'https://www.youtube.com/watch?v=qr0M52qjeiY',
		'Pompano Beach'        => 'https://www.youtube.com/watch?v=DZdG8ohRDSI',
		'Key Biscayne'         => 'https://www.youtube.com/watch?v=PJUhinVCzy0',
		'Coral Springs'        => 'https://www.youtube.com/watch?v=rwj6MjNB61M',
		'Coral Gables'         => 'https://www.youtube.com/watch?v=U8W-sc8wzjE',
		'Tamarac'              => 'https://www.youtube.com/watch?v=fxQW2DPTZP0',
		'Fort Lauderdale'      => 'https://www.youtube.com/watch?v=p_x5lKpViR8',
		'Sunrise'              => 'https://www.youtube.com/watch?v=9_81GJYDsQE',
		'Plantation'           => 'https://www.youtube.com/watch?v=01i8no3pAFw',
		'Sunny Isles'          => 'https://www.youtube.com/watch?v=1FU2wTzgvAU',
		'Hialeah Lakes'        => 'https://www.youtube.com/watch?v=LzDds3iZotI',
		'Weston'               => 'https://www.youtube.com/watch?v=d509pWeQfeY',
		'Hollywood'            => 'https://www.youtube.com/watch?v=n1Dyoj1w5tI',
		'Davie'                => 'https://www.youtube.com/watch?v=bntgoZi1FkM',
		'Pembroke Park'        => 'https://www.youtube.com/watch?v=674cMcVWCIk',
		'West Park'            => 'https://www.youtube.com/watch?v=fgjdG38yPp4',
		'Miami Lakes'          => 'https://www.youtube.com/watch?v=UFQbIQ20ko4',
		'South West Ranches'   => 'https://www.youtube.com/watch?v=y7_j9IjRWEM',
		'Pembroke Pines'       => 'https://www.youtube.com/watch?v=TvSi7lgSPk4',
		'Miramar'              => 'https://www.youtube.com/watch?v=mt-Y4R2LbnA',
	)
);

// Daikin product pages structure
define(
	'SUNNYSIDE_DAIKIN_PRODUCTS',
	array(
		array(
			'name'        => 'Daikin Inverter Air Conditioners',
			'slug'        => 'daikin-inverter-air-conditioners',
			'short_name'  => 'Inverter AC',
			'description' => 'Advanced adaptive technology and high-quality components for efficient cooling',
			'icon'        => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
		),
		array(
			'name'        => 'Daikin VRV',
			'slug'        => 'daikin-vrv',
			'short_name'  => 'VRV',
			'description' => 'Variable Refrigerant Volume system for complex indoor spaces',
			'icon'        => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
		),
		array(
			'name'        => 'Daikin Ductless',
			'slug'        => 'daikin-ductless',
			'short_name'  => 'Ductless',
			'description' => 'Mini-split systems for energy-efficient heating and cooling',
			'icon'        => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6',
		),
		array(
			'name'        => 'Daikin FIT',
			'slug'        => 'daikin-fit',
			'short_name'  => 'FIT',
			'description' => 'Compact, quiet, and energy-efficient system for ducted homes',
			'icon'        => 'M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z',
		),
		array(
			'name'        => 'Daikin Package Units',
			'slug'        => 'daikin-package-units',
			'short_name'  => 'Package Units',
			'description' => 'All-in-one heating and cooling system in a single package',
			'icon'        => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
		),
		array(
			'name'        => 'Daikin Heat Pumps',
			'slug'        => 'daikin-heat-pumps',
			'short_name'  => 'Heat Pumps',
			'description' => 'Energy-efficient heating and cooling with inverter technology',
			'icon'        => 'M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z M9.879 16.121A3 3 0 1012.015 11L11 14H9c0 .768.293 1.536.879 2.121z',
		),
		array(
			'name'        => 'Daikin Furnaces',
			'slug'        => 'daikin-furnaces',
			'short_name'  => 'Furnaces',
			'description' => 'High-efficiency furnaces with stainless steel heat exchangers',
			'icon'        => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7',
		),
	)
);
