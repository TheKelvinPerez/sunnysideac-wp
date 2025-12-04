<?php
/**
 * Template Name: About Page
 * Template for displaying the About Us page
 */

get_header();

// Page breadcrumbs
$breadcrumbs = array(
	array(
		'name' => 'Home',
		'url'  => home_url( '/' ),
	),
	array(
		'name' => 'About Us',
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
		'title'       => 'About Us',
		'description' => 'Meet the family behind Sunny Side 24/7 AC',
		'show_ctas'   => true,
		'bg_color'    => 'white',
	)
);
?>

<main class="px-5 lg:px-0 max-w-7xl mx-auto">
	<div class="flex gap-10 flex-col py-12">
		<!-- Family Owned Legacy Section -->
		<?php get_template_part( 'template-parts/family-owned-section' ); ?>

		<!-- Call to Action Section -->
		<section
			class="w-full bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px]"
			role="contentinfo"
			aria-labelledby="cta-heading"
		>
			<div class="p-6 md:p-8 lg:p-10 text-center">
				<h2 id="cta-heading" class="text-3xl md:text-4xl font-bold text-white mb-4">
					Ready to Experience the Sunny Side Difference?
				</h2>
				<p class="text-lg md:text-xl text-white/90 mb-8 max-w-3xl mx-auto">
					Let our family take care of yours with reliable HVAC solutions backed by decades of experience and unwavering dedication.
				</p>
				<div class="flex flex-col sm:flex-row justify-center gap-4">
					<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
						class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none shadow-lg"
						aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
						<span class="text-base lg:text-lg font-bold text-orange-500 whitespace-nowrap">
							Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
						</span>
					</a>

					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
						class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none shadow-lg">
						<span class="text-base lg:text-lg font-bold text-white whitespace-nowrap">
							Get a Free Quote
						</span>
					</a>
				</div>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
