<?php
/**
 * FAQ Helpers
 *
 * FAQ extraction from blog posts and utility functions
 */

/**
 * Extract FAQs from blog post content
 * Extracts FAQ data structured for the faq-component.php
 */
function sunnysideac_extract_faqs_from_post($post_content) {
	$faqs = array();

	// Look for FAQ section with data-faq-q pattern
	if (preg_match_all('/<h3[^>]*data-faq-q[^>]*>(.*?)<\/h3>(.*?)(?=<h3|<h2|<h4|$)/s', $post_content, $matches)) {
		foreach ($matches[1] as $i => $question) {
			$answer_text = $matches[2][$i];

			// Clean up the question - remove all HTML tags
			$question = trim(strip_tags($question));

			// Clean up the answer - remove ALL HTML tags including <strong>, <em>, <p>, etc.
			$answer = trim(strip_tags($answer_text));

			// Replace multiple spaces, newlines, and HTML entities with single spaces
			$answer = preg_replace('/\s+/', ' ', $answer);

			// Convert HTML entities to characters if needed
			$answer = html_entity_decode($answer, ENT_QUOTES, 'UTF-8');

			// Remove leading/trailing whitespace
			$answer = trim($answer);

			// Remove any remaining HTML-like patterns
			$answer = preg_replace('/&[a-zA-Z0-9#]+;/', '', $answer);

			if (!empty($question) && !empty($answer)) {
				$faqs[] = array(
					'question' => $question,
					'answer' => $answer,
					'id' => 'faq-' . ($i + 1)
				);
			}
		}
	}

	return $faqs;
}

/**
 * Get FAQs from multiple posts
 * Returns structured FAQ data from recent blog posts
 */
function sunnysideac_get_recent_faqs($limit = 6) {
	$all_faqs = array();

	$recent_posts = get_posts(array(
		'post_type' => 'post',
		'post_status' => 'publish',
		'numberposts' => 10, // Check more posts to find FAQs
		'orderby' => 'date',
		'order' => 'DESC'
	));

	foreach ($recent_posts as $post) {
		$post_faqs = sunnysideac_extract_faqs_from_post($post->post_content);
		foreach ($post_faqs as $faq) {
			$all_faqs[] = array(
				'id' => $faq['id'] . '-post-' . $post->ID,
				'question' => $faq['question'],
				'answer' => $faq['answer'],
				'isOpen' => false,
				'category' => 'General HVAC',
				'postTitle' => $post->post_title,
				'postUrl' => get_permalink($post->ID),
				'postDate' => get_the_date('', $post),
				'postCategory' => ''
			);

			// Get the first category for context
			$categories = get_the_category($post->ID);
			if (!empty($categories)) {
				$all_faqs[count($all_faqs) - 1]['postCategory'] = $categories[0]->name;
			}
		}

		if (count($all_faqs) >= $limit) {
			break;
		}
	}

	return array_slice($all_faqs, 0, $limit);
}