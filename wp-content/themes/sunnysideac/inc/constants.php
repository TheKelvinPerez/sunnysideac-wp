<?php
/**
 * Global Constants
 * Define all theme constants here
 */

// Phone number constants
define('SUNNYSIDE_TEL_HREF', 'tel:+13059789382');
define('SUNNYSIDE_PHONE_DISPLAY', '(305) 978-9382');

// Email constants
define('SUNNYSIDE_EMAIL_ADDRESS', 'support@sunnyside247ac.com');
define('SUNNYSIDE_MAILTO_HREF', 'mailto:support@sunnyside247ac.com');

// Address constants
define('SUNNYSIDE_ADDRESS_STREET', '6609 Emerald Lake Dr');
define('SUNNYSIDE_ADDRESS_CITY', 'Miramar');
define('SUNNYSIDE_ADDRESS_STATE', 'FL');
define('SUNNYSIDE_ADDRESS_ZIP', '33023');
define('SUNNYSIDE_ADDRESS_FULL', '6609 Emerald Lake Dr, Miramar, FL 33023');
define('SUNNYSIDE_ADDRESS_GOOGLE_MAPS_URL', 'https://maps.google.com/?q=6609+Emerald+Lake+Dr,+Miramar,+FL+33023');

// Service areas
define('SUNNYSIDE_SERVICE_AREAS', [
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
]);

// Service categorization for menu organization
define('SUNNYSIDE_SERVICES_BY_CATEGORY', [
	'cooling' => [
		'AC Repair',
		'AC Installation',
		'AC Maintenance',
		'AC Replacement'
	],
	'heating' => [
		'Heating Repair',
		'Heating Installation',
		'Heat Pumps'
	],
	'air_quality' => [
		'Ductless / Mini Split',
		'Indoor Air Quality',
		'Water Heaters'
	]
]);

// Priority cities for dropdown (top 16)
define('SUNNYSIDE_PRIORITY_CITIES', [
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
	'Deerfield Beach'
]);

// Service URL patterns
define('SUNNYSIDE_SERVICE_URL_PATTERN', '/services/%s');
define('SUNNYSIDE_CITY_URL_PATTERN', '/areas/%s');

// Social media links
define('SUNNYSIDE_FACEBOOK_URL', 'https://facebook.com/sunnyside247ac');
define('SUNNYSIDE_INSTAGRAM_URL', 'https://instagram.com/sunnyside247ac');
define('SUNNYSIDE_TWITTER_URL', 'https://twitter.com/sunnyside247ac');
define('SUNNYSIDE_YOUTUBE_URL', 'https://youtube.com/@sunnyside247ac');
define('SUNNYSIDE_LINKEDIN_URL', 'https://linkedin.com/company/sunnyside247ac');

