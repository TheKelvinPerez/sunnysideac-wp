<?php
/**
 * Blog Content Helpers
 *
 * Blog post categorization, FAQ extraction, and content utilities
 */

/**
 * Auto-categorize blog posts based on their content
 * Assigns appropriate categories to uncategorized posts
 */
function sunnysideac_auto_categorize_posts() {
	// Define category mapping based on keywords
	$category_keywords = array(
		'HVAC Maintenance' => array(
			'maintenance', 'clean', 'filter', 'check', 'inspection', 'annual', 'service', 'upkeep', 'tune-up'
		),
		'Emergency Repairs' => array(
			'emergency', 'repair', 'break', 'fail', 'broken', '24/7', 'urgent', 'immediate', 'fix', 'problem'
		),
		'Energy Efficiency' => array(
			'energy', 'efficiency', 'save', 'cost', 'bill', 'efficient', 'lower', 'reduce', 'consumption', 'money'
		),
		'AC Installation' => array(
			'installation', 'install', 'cost', 'price', 'guide', 'complete', 'replace'
		),
		'Troubleshooting' => array(
			'why', 'how', 'common', 'causes', 'explained', 'signs', 'symptoms'
		),
		'Technical Updates' => array(
			'rules', 'regulations', '2025', 'refrigerant', 'florida', 'compliance'
		)
	);

	// Get uncategorized posts
	$uncategorized_posts = get_posts(array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'numberposts' => -1,
		'category' => get_category_by_slug('uncategorized')->term_id
	));

	$categorized_count = 0;

	foreach ($uncategorized_posts as $post) {
		$post_title = strtolower($post->post_title);
		$post_content = strtolower(get_post_field('post_content', $post->ID));
		$full_text = $post_title . ' ' . $post_content;

		// Find matching categories
		$matched_categories = array();
		foreach ($category_keywords as $category => $keywords) {
			foreach ($keywords as $keyword) {
				if (strpos($full_text, $keyword) !== false) {
					$matched_categories[] = $category;
					break;
				}
			}
		}

		// Remove uncategorized and add matched categories
		if (!empty($matched_categories)) {
			wp_set_post_categories($post->ID, array(), false); // Clear existing categories

			$category_ids = array();
			foreach ($matched_categories as $category_name) {
				$cat = get_term_by('name', $category_name, 'category');
				if ($cat) {
					$category_ids[] = $cat->term_id;
				}
			}

			if (!empty($category_ids)) {
				wp_set_post_categories($post->ID, $category_ids, false);
				$categorized_count++;
			}
		}
	}

	return $categorized_count;
}

/**
 * Get category icon for blog posts
 * Returns inline SVG based on category
 */
function sunnysideac_get_category_icon($category_name) {
	$icons = array(
		'HVAC Maintenance' => '<svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>',
		'Emergency Repairs' => '<svg class="h-4 w-4 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>',
		'Energy Efficiency' => '<svg class="h-4 w-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>',
		'AC Installation' => '<svg class="h-4 w-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>',
		'Troubleshooting' => '<svg class="h-4 w-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>',
		'Technical Updates' => '<svg class="h-4 w-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>',
	);

	// Return the matching icon or default
	if (isset($icons[$category_name])) {
		return $icons[$category_name];
	}

	// Default icon (air conditioner)
	return '<svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path></svg>';
}