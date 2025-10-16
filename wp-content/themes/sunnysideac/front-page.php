<?php
/**
 * Front Page Template
 * This template is used when you set a static homepage in Settings > Reading
 */

get_header(); ?>

<main class="min-h-screen bg-gray-50">
	<!-- Mobile constraint wrapper - applies 20px padding on mobile only -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">
			<!-- Hero Section -->
			<?php get_template_part( 'template-parts/hero-section' ); ?>

			<!-- Logo Marquee Section -->
			<?php get_template_part( 'template-parts/logo-marquee' ); ?>

			<!-- Work Process Section -->
			<?php get_template_part( 'template-parts/work-process' ); ?>

			<!-- Why Choose Us Section -->
			<?php get_template_part( 'template-parts/why-choose-us' ); ?>

			<!-- Family Owned Section -->
		<?php get_template_part( 'template-parts/family-owned-section' ); ?>

			<!-- Customer Reviews Section -->
		<?php get_template_part( 'template-parts/customer-reviews' ); ?>

			<!-- Our Projects Section -->
			<?php get_template_part( 'template-parts/our-projects' ); ?>

			<!-- FAQ Section -->
			<?php get_template_part( 'template-parts/faq-section' ); ?>
		</section>
	</div>

</main>

<?php get_footer(); ?>
