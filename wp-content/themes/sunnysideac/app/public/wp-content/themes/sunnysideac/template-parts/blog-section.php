<?php
/**
 * Blog Section Component
 * Self-contained component with blog posts display
 */

// Get latest blog posts
$blog_posts_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

$images = array(
	'blog_title_icon'   => sunnysideac_asset_url( 'assets/images/home-page/blog/blog-title-icon.svg' ),
	'air_con_blog_icon' => sunnysideac_asset_url( 'assets/images/home-page/blog/air-con-blog-icon.svg' ),
	'blog_auther_icon'  => sunnysideac_asset_url( 'assets/images/home-page/blog/blog-auther-icon.svg' ),
	'read_more_arrow'   => sunnysideac_asset_url( 'assets/images/home-page/blog/read-more-arrow-up-right.svg' ),
);
?>

<section
	class="w-full rounded-2xl bg-white px-4 py-12 sm:px-6 lg:px-8"
	aria-labelledby="our-blog-heading"
>
	<div class="mx-auto max-w-7xl">
		<!-- Header Section -->
		<header class="mb-12 text-center">
			<?php
			get_template_part(
				'template-parts/title',
				null,
				array(
					'icon'  => $images['blog_title_icon'],
					'title' => 'Our Blog',
					'id'    => 'our-blog',
				)
			);
			?>
			<?php
			get_template_part(
				'template-parts/subheading',
				null,
				array(
					'text'  => 'Stay Cool, Stay Warm, Stay Informed',
					'class' => 'mt-4 text-center',
				)
			);
			?>
		</header>

		<!-- Blog Cards Grid -->
		<div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
			<?php if ( $blog_posts_query->have_posts() ) : ?>
				<?php
				while ( $blog_posts_query->have_posts() ) :
					$blog_posts_query->the_post();
					?>
					<?php
					get_template_part(
						'template-parts/blog-card',
						null,
						array(
							'post'            => get_post(),
							'show_excerpt'    => true,
							'excerpt_length'  => 20,
							'image_size'      => 'large',
							'show_author'     => true,
							'show_category'   => true,
							'show_date'       => true,
							'card_class'      => 'max-w-[375px]',
							'author_icon'     => $images['blog_auther_icon'],
							'category_icon'   => $images['air_con_blog_icon'],
							'read_more_arrow' => $images['read_more_arrow'],
							'fallback_image'  => sunnysideac_asset_url( 'assets/images/optimize/blog-post-image-1.webp' ),
						)
					);
					?>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php endif; ?>
		</div>
	</div>
</section>