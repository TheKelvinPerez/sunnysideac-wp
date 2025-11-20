<?php
/**
 * Template Name: Submit Review
 *
 * @package Sunnyside AC
 */

get_header();

// Page breadcrumbs
$breadcrumbs = array(
    array(
        'name' => 'Home',
        'url'  => home_url('/'),
    ),
    array(
        'name' => 'Submit Review',
        'url'  => '',
    ),
);
?>

<!-- Page Header with Breadcrumbs -->
<?php
get_template_part(
    'template-parts/page-header',
    null,
    array(
        'breadcrumbs' => $breadcrumbs,
        'title'       => 'Share Your Review',
        'description' => 'We value your feedback and would love to hear about your experience with our services throughout South Florida. Your reviews help us improve and help other customers make informed decisions.',
        'show_ctas'   => false,
        'bg_color'    => 'white',
    )
);
?>

<main class="px-5 lg:px-0 max-w-7xl mx-auto">
    <div class="flex gap-10 flex-col py-12">
        <!-- Review Form Section -->
        <section
            class="w-full bg-white rounded-[20px]"
            role="main"
            aria-labelledby="review-form-heading"
        >
            <div class="mx-auto max-w-2xl p-6 md:p-8 lg:p-10">
                <?php
                get_template_part(
                    'template-parts/title',
                    null,
                    array(
                        'icon'  => sunnysideac_asset_url('assets/icons/review-star-icon-filled.svg'),
                        'title' => 'Submit Your Review',
                        'id'    => 'review-form-heading',
                        'align' => 'center',
                    )
                );
                ?>

                <?php
                get_template_part(
                    'template-parts/subheading',
                    null,
                    array(
                        'text' => 'Your feedback helps us improve our services and assists other customers in choosing the right HVAC solutions for their needs.',
                    'align' => 'center',
                    'max_width' => 'max-w-2xl',
                    'margin_bottom' => 'mb-8'
                    )
                );
                ?>

                <?php get_template_part('template-parts/review-form'); ?>
            </div>
        </section>
    </div>
</main>

<?php
get_footer();