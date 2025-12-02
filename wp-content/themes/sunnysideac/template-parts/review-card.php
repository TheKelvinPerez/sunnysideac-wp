<?php
/**
 * Review Card Component
 *
 * Displays a single review in a card format for archive pages.
 * Used in review archive and other listing contexts.
 *
 * Usage:
 * get_template_part('template-parts/review-card', null, ['review' => $review_post]);
 *
 * @package Sunnyside AC
 */

// Get review data from ACF fields
$review = $args['review'] ?? null;

if (!$review) {
    return;
}

$reviewer_name = get_field('reviewer_name', $review->ID);
$reviewer_email = get_field('reviewer_email', $review->ID); // This won't be displayed
$rating = get_field('rating', $review->ID);
$service_relationship = get_field('service_relationship', $review->ID);
$city_relationship = get_field('city_relationship', $review->ID);
$submission_date = get_field('submission_date', $review->ID);

// Get service and city post objects
// Handle relationship fields - they might be WP_Post objects or IDs
$service_id = null;
$city_id = null;

if ($service_relationship) {
    if (is_object($service_relationship) && isset($service_relationship->ID)) {
        $service_id = $service_relationship->ID;
        $service = $service_relationship;
    } elseif (is_numeric($service_relationship)) {
        $service_id = $service_relationship;
        $service = get_post($service_relationship);
    }
}

if ($city_relationship) {
    if (is_object($city_relationship) && isset($city_relationship->ID)) {
        $city_id = $city_relationship->ID;
        $city = $city_relationship;
    } elseif (is_numeric($city_relationship)) {
        $city_id = $city_relationship;
        $city = get_post($city_relationship);
    }
}

// Format submission date
$display_date = $submission_date ? date('F j, Y', strtotime($submission_date)) : get_the_date('F j, Y', $review->ID);

// Truncate review content for card display
$review_content = wp_trim_words($review->post_content, 30, '...');
$review_link = get_permalink($review->ID);
?>

<article class="review-card bg-white border border-gray-200 rounded-lg p-6 hover:shadow-lg transition-shadow duration-300"
         data-service-id="<?php echo esc_attr($service_id ? (string)$service_id : ''); ?>"
         data-city-id="<?php echo esc_attr($city_id ? (string)$city_id : ''); ?>">

    <!-- Header with Rating -->
    <div class="flex justify-between items-start mb-4">
        <div class="flex-1">
            <h3 class="font-semibold text-lg text-gray-900 mb-1">
                <?php echo esc_html($reviewer_name); ?>
            </h3>
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <?php echo sunnysideac_get_star_rating_html($rating); ?>
                <span><?php echo esc_html($rating); ?> stars</span>
            </div>
        </div>
        <div class="text-sm text-gray-500">
            <?php echo esc_html($display_date); ?>
        </div>
    </div>

    <!-- Review Content -->
    <div class="text-gray-700 mb-4 leading-relaxed">
        <?php echo wp_kses_post($review_content); ?>
    </div>

    <!-- Service and City Info -->
    <?php if ($service || $city) : ?>
        <div class="flex flex-wrap gap-2 mb-4">
            <?php if ($service) : ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    <?php echo esc_html($service->post_title); ?>
                </span>
            <?php endif; ?>
            <?php if ($city) : ?>
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    📍 <?php echo esc_html($city->post_title); ?>
                </span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <!-- Read More Link -->
    <div class="text-right">
        <a href="<?php echo esc_url($review_link); ?>"
           class="inline-flex items-center text-orange-500 hover:text-orange-600 font-medium text-sm transition-colors">
            Read Full Review
            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
        </a>
    </div>
</article>