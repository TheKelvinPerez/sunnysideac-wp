<?php
/**
 * Content Cleanup
 *
 * Removes SEO bot hashes and competitor mentions from content
 */

/**
 * Clean SEO bot hashes from post content
 * Removes unwanted SEO bot generated hashes like sbb-itb-dd99a15, sbb-cls from blog posts
 */
function sunnysideac_clean_seo_bot_hashes( $content ) {
	if ( ! $content ) {
		return $content;
	}

	// Remove SEO bot hash patterns
	$patterns = array(
		'/<h6[^>]*>sbb-itb-[a-z0-9]*<\/h6>/i',  // Remove sbb-itb-xxxxx hash elements
		'/<h6[^>]*>sbb-cls<\/h6>/i',            // Remove sbb-cls hash elements
		'/<h6[^>]*>sbb-[a-z0-9-]*<\/h6>/i',      // Remove other sbb- hash elements
		'/<!-- sbb-[a-z0-9-]* -->/i',            // Remove sbb HTML comments
		'/\[sbb-[a-z0-9-]*\]/i',                 // Remove sbb shortcodes
		'/\bsbb-itb-[a-z0-9]*\b/',               // Remove standalone sbb-itb-xxxxx hashes
		'/\bsbb-cls\b/',                         // Remove standalone sbb-cls hashes
		'/class="[^"]*\bsbb-cls[^"]*"/i',        // Remove sbb-cls from class attributes
		'/class="[^"]*\bsb[^"]*"/i',             // Remove other sb class attributes
	);

	$content = preg_replace( $patterns, '', $content );

	return $content;
}

// Apply the hash cleanup to post content when displaying
add_filter( 'the_content', 'sunnysideac_clean_seo_bot_hashes', 10 );

// Apply the hash cleanup to post excerpts as well
add_filter( 'the_excerpt', 'sunnysideac_clean_seo_bot_hashes', 10 );

/**
 * Remove competitor links and mentions from post content
 * Helps prevent traffic leakage to competitors and maintains brand focus
 */
function sunnysideac_remove_competitor_content($content) {
	if (empty($content)) {
		return $content;
	}

	// List of competitor domains to remove links from
	$competitor_domains = array(
		'mountainbreezehvac.com',
		'veteransac.com',
		'aircomfortky.com',
		'mccarthyair.com',
		'brodypennell.com',
		'advancedairsystems.com'
	);

	// Remove links to competitor websites
	foreach ($competitor_domains as $domain) {
		// Pattern to match links with competitor domains
		$pattern = '/<a[^>]*href=["\'][^"\']*' . preg_quote($domain) . '[^"\']*["\'][^>]*>.*?<\/a>/i';
		$content = preg_replace($pattern, '', $content);
	}

	// Remove competitor brand mentions from headings and content
	$competitor_brands = array(
		'Mountain Breeze HVAC',
		'Veterans AC & Heat',
		'Air Comfort of Kentucky',
		'McCarthy Air Conditioning',
		'Brody Pennell',
		'Advanced Air Systems'
	);

	foreach ($competitor_brands as $brand) {
		// Remove brand mentions from headings and titles
		$content = preg_replace('/\|?\s*' . preg_quote($brand) . '\s*\|?/i', '', $content);
		// Only replace standalone brand mentions that are not in quotes
		$content = preg_replace('/\b' . preg_quote($brand) . '\b(?!.*?["\'])/i', 'SunnySide AC', $content);
	}

	return $content;
}

// Apply competitor content removal to post content and excerpts
add_filter('the_content', 'sunnysideac_remove_competitor_content', 15);
add_filter('the_excerpt', 'sunnysideac_remove_competitor_content', 15);

/**
 * Clean up post titles with competitor mentions
 */
function sunnysideac_clean_competitor_titles($title) {
	// Remove competitor brand mentions from post titles
	$competitor_brands = array(
		'Mountain Breeze HVAC',
		'Veterans AC & Heat',
		'Air Comfort of Kentucky',
		'McCarthy Air Conditioning',
		'Brody Pennell',
		'Advanced Air Systems'
	);

	foreach ($competitor_brands as $brand) {
		$title = preg_replace('/\|?\s*' . preg_quote($brand) . '\s*\|?/i', '', $title);
		$title = preg_replace('/\b' . preg_quote($brand) . '\b/i', 'SunnySide AC', $title);
	}

	return trim($title);
}

add_filter('the_title', 'sunnysideac_clean_competitor_titles', 15);