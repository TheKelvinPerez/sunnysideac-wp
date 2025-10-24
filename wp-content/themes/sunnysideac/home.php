<?php
/**
 * Home Template
 * Template for displaying the blog posts archive (when a static page is set as front page)
 */

get_header();

// Get icons from theme assets
$blog_icon = sunnysideac_asset_url( 'assets/images/home-page/blog/blog-title-icon.svg' );

// Page breadcrumbs
$breadcrumbs = [
	[
		'name' => 'Home',
		'url'  => home_url( '/' ),
	],
	[
		'name' => 'Blog',
		'url'  => '',
	],
];

// Blog post icons
$images = [
	'air_con_blog_icon' => sunnysideac_asset_url( 'assets/images/home-page/blog/air-con-blog-icon.svg' ),
	'blog_auther_icon'  => sunnysideac_asset_url( 'assets/images/home-page/blog/blog-auther-icon.svg' ),
	'read_more_arrow'   => sunnysideac_asset_url( 'assets/images/home-page/blog/read-more-arrow-up-right.svg' ),
];
?>

<!-- Page Header with Breadcrumbs -->
<?php
get_template_part(
	'template-parts/page-header',
	null,
	[
		'breadcrumbs' => $breadcrumbs,
		'title'       => 'Our Blog',
		'description' => '',
		'show_ctas'   => false,
		'bg_color'    => 'white',
	]
);
?>

<main class="px-5 lg:px-0 max-w-7xl mx-auto">
	<div class="flex gap-10 flex-col py-12">
		<!-- Blog Overview Section -->
		<section
			class="w-full bg-white rounded-[20px]"
			role="main"
			aria-labelledby="blog-overview-heading"
		>
			<div class="mx-auto text-center max-w-4xl p-6 md:p-8 lg:p-10">
				<?php
				get_template_part(
					'template-parts/title',
					null,
					[
						'icon'  => $blog_icon,
						'title' => 'HVAC Tips & Insights',
						'id'    => 'blog-overview-heading',
						'align' => 'center',
					]
				);
				?>

				<?php
				get_template_part(
					'template-parts/subheading',
					null,
					[
						'text' => 'Stay Cool, Stay Warm, Stay Informed',
					]
				);
				?>

				<div class="mt-8 text-left space-y-6">
					<p class="text-sm md:text-base font-light leading-snug text-gray-700">
						Welcome to the SunnySide 24/7 AC blog, your trusted resource for HVAC tips, maintenance advice, and industry insights. Whether you're looking to improve your home's energy efficiency, troubleshoot common AC problems, or learn about the latest HVAC technologies, we've got you covered.
					</p>
					<p class="text-sm md:text-base font-light leading-snug text-gray-700">
						Our team of certified technicians shares their expertise to help you make informed decisions about your heating and cooling systems. Browse our articles below to stay informed and keep your home comfortable year-round.
					</p>
				</div>
			</div>
		</section>

		<!-- Blog Posts Grid Section -->
		<section
			class="w-full bg-gray-50 rounded-[20px]"
			role="contentinfo"
			aria-labelledby="blog-posts-heading"
		>
			<div class="p-6 md:p-8 lg:p-10">
				<h2 id="blog-posts-heading" class="sr-only">Blog Posts</h2>

				<?php if ( have_posts() ) : ?>
					<!-- Blog Cards Grid -->
					<div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
						<?php while ( have_posts() ) : the_post(); ?>
							<?php
							get_template_part('template-parts/blog-card', null, [
								'post'          => get_post(),
								'show_excerpt'  => true,
								'excerpt_length'=> 20,
								'image_size'    => 'large',
								'show_author'   => true,
								'show_category' => true,
								'show_date'     => true,
								'card_class'    => 'max-w-[375px]',
								'author_icon'   => $images['blog_auther_icon'],
								'category_icon' => $images['air_con_blog_icon'],
								'read_more_arrow' => $images['read_more_arrow'],
								'fallback_image'  => sunnysideac_asset_url('assets/images/home-page/blog/blog-post-image-1.png'),
							]);
							?>
						<?php endwhile; ?>
					</div>

					<!-- Pagination -->
					<nav class="mt-12 flex justify-center" aria-label="Blog pagination">
						<div class="flex gap-2">
							<?php
							the_posts_pagination(
								[
									'mid_size'  => 2,
									'prev_text' => '&laquo; Previous',
									'next_text' => 'Next &raquo;',
									'type'      => 'list',
									'class'     => 'pagination',
								]
							);
							?>
						</div>
					</nav>

				<?php else : ?>
					<!-- No Posts Found -->
					<div class="text-center py-12">
						<p class="text-lg text-gray-600">No blog posts found. Check back soon for HVAC tips and insights!</p>
					</div>
				<?php endif; ?>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>