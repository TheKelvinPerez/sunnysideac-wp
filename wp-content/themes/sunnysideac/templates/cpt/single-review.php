<?php
/**
 * Single Review Template
 *
 * Displays a single customer review with full details.
 * Shows reviewer information, rating, service details, and full review content.
 *
 * @package Sunnyside AC
 */

get_header();

// Get review data from ACF fields
$reviewer_name = get_field('reviewer_name');
$reviewer_email = get_field('reviewer_email');
$rating = get_field('rating');
$service_relationship = get_field('service_relationship');
$city_relationship = get_field('city_relationship');
$submission_date = get_field('submission_date');

// Get service and city post objects
$service = $service_relationship ? get_post($service_relationship) : null;
$city = $city_relationship ? get_post($city_relationship) : null;

// Format submission date
$display_date = $submission_date ? date('F j, Y', strtotime($submission_date)) : get_the_date('F j, Y');
$display_datetime = $submission_date ? date('c', strtotime($submission_date)) : get_the_date('c');

// Get related reviews (same service or city)
$related_reviews = [];
if ($service_relationship) {
    $related_reviews = array_merge($related_reviews, sunnysideac_get_reviews_by_service($service_relationship, ['posts_per_page' => 3]));
}
if ($city_relationship) {
    $related_reviews = array_merge($related_reviews, sunnysideac_get_reviews_by_city($city_relationship, ['posts_per_page' => 3]));
}

// Remove current review from related and limit to 3 unique reviews
$related_reviews = array_filter($related_reviews, function($r) {
    return $r->ID !== get_the_ID();
});
$related_reviews = array_slice($related_reviews, 0, 3);

// Breadcrumbs
$breadcrumbs = array(
    array(
        'name' => 'Home',
        'url'  => home_url('/'),
    ),
    array(
        'name' => 'Customer Reviews',
        'url'  => get_post_type_archive_link('review'),
    ),
    array(
        'name' => 'Review by ' . esc_html($reviewer_name),
        'url'  => '',
    ),
);
?>

<!-- Page Header with Breadcrumbs -->
<?php
get_template_part(
    'template-parts/header/page-header',
    null,
    array(
        'breadcrumbs' => $breadcrumbs,
        'title'       => 'Customer Review',
        'description' => 'Detailed review from one of our valued customers throughout South Florida.',
        'show_ctas'   => false,
        'bg_color'    => 'white',
    )
);
?>

<main class="px-5 lg:px-0 max-w-4xl mx-auto">
    <div class="flex gap-10 flex-col py-12">
        <!-- Review Details -->
        <article class="bg-white rounded-[20px] p-6 md:p-8 lg:p-10">
            <!-- Review Header -->
            <header class="border-b border-gray-200 pb-6 mb-6">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 mb-2">
                            Review by <?php echo esc_html($reviewer_name); ?>
                        </h1>

                        <!-- Rating and Date -->
                        <div class="flex flex-wrap items-center gap-4 text-gray-600">
                            <div class="flex items-center gap-2">
                                <?php echo sunnysideac_get_star_rating_html($rating); ?>
                                <span class="font-semibold"><?php echo esc_html($rating); ?>/5 stars</span>
                            </div>

                            <time datetime="<?php echo esc_attr($display_datetime); ?>" class="text-sm">
                                <?php echo esc_html($display_date); ?>
                            </time>
                        </div>
                    </div>
                </div>

                <!-- Service and City Information -->
                <?php if ($service || $city) : ?>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <?php if ($service) : ?>
                            <div class="flex items-center gap-2 px-4 py-2 bg-blue-50 text-blue-700 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <span class="font-medium"><?php echo esc_html($service->post_title); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($city) : ?>
                            <div class="flex items-center gap-2 px-4 py-2 bg-green-50 text-green-700 rounded-lg">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="font-medium"><?php echo esc_html($city->post_title); ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </header>

            <!-- Review Content -->
            <div class="prose prose-lg max-w-none">
                <?php
                the_content();
                ?>
            </div>

            <!-- Review Footer -->
            <footer class="mt-8 pt-6 border-t border-gray-200">
                <div class="flex flex-wrap justify-between items-center gap-4">
                    <div class="text-sm text-gray-500">
                        <span class="inline-flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Verified Review
                        </span>
                    </div>

                    <div class="flex gap-4">
                        <a href="<?php echo esc_url(get_post_type_archive_link('review')); ?>"
                           class="inline-flex items-center text-orange-500 hover:text-orange-600 font-medium text-sm transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                            Back to All Reviews
                        </a>
                    </div>
                </div>
            </footer>
        </article>

        <!-- Related Reviews -->
        <?php if (!empty($related_reviews)) : ?>
            <section class="bg-white rounded-[20px] p-6 md:p-8 lg:p-10">
                <?php
                get_template_part(
                    'template-parts/title',
                    null,
                    array(
                        'title' => 'Related Reviews',
                        'align' => 'center',
                    )
                );
                ?>

                <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6 mt-8">
                    <?php foreach ($related_reviews as $related_review) : ?>
                        <?php get_template_part('template-parts/cards/review-card', null, ['review' => $related_review]); ?>
                    <?php endforeach; ?>
                </div>

                <div class="text-center mt-8">
                    <a href="<?php echo esc_url(get_post_type_archive_link('review')); ?>"
                       class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-[#F79E37] to-[#E5462F] px-8 py-4 font-medium text-white transition-opacity hover:opacity-90 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none">
                        View All Reviews
                    </a>
                </div>
            </section>
        <?php endif; ?>

        <!-- Submit Review CTA -->
        <section class="bg-gradient-to-r from-[#F79E37] to-[#E5462F] rounded-[20px] p-8 text-center text-white">
            <h2 class="text-2xl md:text-3xl font-bold mb-4">
                Share Your Experience
            </h2>
            <p class="text-lg mb-6 opacity-90">
                Had a great experience with Sunnyside AC? We'd love to hear from you!
            </p>
            <a href="<?php echo esc_url(home_url('/review/')); ?>"
               class="inline-flex items-center justify-center gap-2 rounded-full bg-white px-8 py-4 font-medium text-orange-500 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none">
                Submit Your Review
            </a>
        </section>
    </div>
</main>

<!-- Schema.org structured data for review -->
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Review",
    "itemReviewed": {
        "@type": "Service",
        "name": "<?php echo $service ? esc_js($service->post_title) : 'HVAC Service'; ?>",
        "provider": {
            "@type": "LocalBusiness",
            "name": "Sunnyside AC",
            "address": "<?php echo esc_js(SUNNYSIDE_ADDRESS_FULL); ?>"
        }
    },
    "reviewRating": {
        "@type": "Rating",
        "ratingValue": "<?php echo esc_js($rating); ?>",
        "bestRating": "5"
    },
    "author": {
        "@type": "Person",
        "name": "<?php echo esc_js($reviewer_name); ?>"
    },
    "datePublished": "<?php echo esc_js($display_datetime); ?>",
    "reviewBody": <?php echo wp_json_encode(get_the_content()); ?>,
    <?php if ($city) : ?>
    "locationCreated": {
        "@type": "Place",
        "name": "<?php echo esc_js($city->post_title); ?>"
    }
    <?php endif; ?>
}
</script>

<?php
get_footer();