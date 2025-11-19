<?php
/**
 * Template Name: Submit Review
 *
 * @package Sunnyside AC
 */

get_header();
?>

<div class="container mx-auto px-4 py-16">
    <div class="max-w-2xl mx-auto">
        <header class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900 mb-4">Share Your Experience</h1>
            <p class="text-xl text-gray-600">
                We value your feedback and would love to hear about your experience with our services throughout South Florida.
            </p>
        </header>

        <?php get_template_part('template-parts/review-form'); ?>
    </div>
</div>

<?php
get_footer();