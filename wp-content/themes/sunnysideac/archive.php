<?php
/**
 * Archive Template
 * Template for displaying all archive pages (categories, tags, dates, authors, and blog posts)
 */

get_header();

// Get icons from theme assets
$archive_icon = sunnysideac_asset_url( 'assets/images/home-page/blog/blog-title-icon.svg' );

// Page breadcrumbs
$breadcrumbs = [
	[
		'name' => 'Home',
		'url'  => home_url( '/' ),
	],
];

// Determine archive type and set appropriate title
$archive_type = '';
$archive_title = 'Blog Archives';
$archive_description = '';

if ( is_category() ) {
	$archive_type = 'category';
	$archive_title = single_cat_title( 'Category: ', false );
	$archive_description = category_description();
	$breadcrumbs[] = [
		'name' => 'Categories',
		'url'  => home_url( '/category/' ),
	];
	$breadcrumbs[] = [
		'name' => single_cat_title( '', false ),
		'url'  => '',
	];
} elseif ( is_tag() ) {
	$archive_type = 'tag';
	$archive_title = single_tag_title( 'Tag: ', false );
	$archive_description = tag_description();
	$breadcrumbs[] = [
		'name' => 'Tags',
		'url'  => home_url( '/tag/' ),
	];
	$breadcrumbs[] = [
		'name' => single_tag_title( '', false ),
		'url'  => '',
	];
} elseif ( is_author() ) {
	$archive_type = 'author';
	$archive_title = 'Author: ' . get_the_author();
	$archive_description = get_the_author_meta( 'description' );
	$breadcrumbs[] = [
		'name' => 'Authors',
		'url'  => home_url( '/author/' ),
	];
	$breadcrumbs[] = [
		'name' => get_the_author(),
		'url'  => '',
	];
} elseif ( is_date() ) {
	$archive_type = 'date';
	if ( is_day() ) {
		$archive_title = 'Daily Archives: ' . get_the_date();
	} elseif ( is_month() ) {
		$archive_title = 'Monthly Archives: ' . get_the_date( 'F Y' );
	} elseif ( is_year() ) {
		$archive_title = 'Yearly Archives: ' . get_the_date( 'Y' );
	}
	$breadcrumbs[] = [
		'name' => 'Archives',
		'url'  => home_url( '/archives/' ),
	];
	$breadcrumbs[] = [
		'name' => $archive_title,
		'url'  => '',
	];
} elseif ( is_home() && ! is_front_page() ) {
	// This is the blog posts page
	$archive_type = 'blog';
	$archive_title = 'Our Blog';
	$archive_description = 'Stay Cool, Stay Warm, Stay Informed';
	$breadcrumbs[] = [
		'name' => 'Blog',
		'url'  => '',
	];
}

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
		'title'       => $archive_title,
		'description' => $archive_description,
		'show_ctas'   => false,
		'bg_color'    => 'white',
	]
);
?>

<main class="px-5 lg:px-0 max-w-7xl mx-auto">
	<div class="flex gap-10 flex-col py-12">
		<?php if ( $archive_type === 'blog' ) : ?>
			<!-- Blog Overview Section (only for main blog page) -->
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
							'icon'  => $archive_icon,
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
							'text' => $archive_description,
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
		<?php elseif ( ! empty( $archive_description ) ) : ?>
			<!-- Archive Description Section (for category, tag, author descriptions) -->
			<section class="w-full bg-white rounded-[20px]">
				<div class="mx-auto max-w-4xl p-6 md:p-8">
					<div class="prose prose-gray max-w-none">
						<?php
						// Get the raw description without WordPress processing
						if (is_category()) {
							$cat_id = get_queried_object_id();
							$raw_desc = get_term_field('description', $cat_id, 'category', 'raw');
							echo wpautop($raw_desc);
						} else {
							echo wpautop($archive_description);
						}
						?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<?php if ( $archive_type === 'category' || $archive_type === 'tag' ) : ?>
			<!-- Archive Stats Section -->
			<section class="w-full bg-white rounded-[20px]">
				<div class="mx-auto max-w-4xl p-6 md:p-8">
					<div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-center">
						<div class="bg-gray-50 rounded-lg p-4">
							<div class="text-2xl font-bold text-[#F79E37]">
								<?php
								global $wp_query;
								echo number_format_i18n( $wp_query->found_posts );
								?>
							</div>
							<div class="text-sm text-gray-600 uppercase tracking-wide">
								<?php echo esc_html( $archive_type === 'category' ? 'Posts in Category' : 'Posts with Tag' ); ?>
							</div>
						</div>
						<div class="bg-gray-50 rounded-lg p-4">
							<div class="text-2xl font-bold text-[#F79E37]">
								<?php
								if ( $archive_type === 'category' ) {
									$category = get_queried_object();
									echo count( get_categories( [ 'child_of' => $category->term_id ] ) );
								} else {
									// Related tags logic could go here
									echo 'Related';
								}
								?>
							</div>
							<div class="text-sm text-gray-600 uppercase tracking-wide">
								<?php echo esc_html( $archive_type === 'category' ? 'Sub-categories' : 'Topics' ); ?>
							</div>
						</div>
						<div class="bg-gray-50 rounded-lg p-4">
							<div class="text-2xl font-bold text-[#F79E37]">
								<?php
								$latest_post = get_posts( [
									'numberposts' => 1,
									'post_type'   => 'post',
									$post_type    => $archive_type,
									'tax_query'   => [
										[
											'taxonomy' => $archive_type,
											'field'    => 'slug',
											'terms'    => get_queried_object()->slug,
										],
									],
								] );
								echo $latest_post ? human_time_diff( strtotime( $latest_post[0]->post_date ), current_time( 'timestamp' ) ) : 'No';
								?>
							</div>
							<div class="text-sm text-gray-600 uppercase tracking-wide">
								Days Since Last Post
							</div>
						</div>
					</div>
				</div>
			</section>

			<!-- Related Categories/Tags Section -->
			<section class="w-full bg-white rounded-[20px]">
				<div class="mx-auto max-w-4xl p-6 md:p-8">
					<h3 class="text-xl font-semibold text-gray-900 mb-4">
						Related <?php echo esc_html( $archive_type === 'category' ? 'Categories' : 'Tags' ); ?>
					</h3>
					<div class="flex flex-wrap gap-2">
						<?php
						if ( $archive_type === 'category' ) {
							$current_cat = get_queried_object();
							$related_cats = get_categories( [
								'exclude'   => $current_cat->term_id,
								'number'    => 8,
								'orderby'   => 'count',
								'order'     => 'DESC',
							] );
							foreach ( $related_cats as $cat ) :
								// Skip categories with numeric names or empty names
								if (empty($cat->name) || is_numeric($cat->name)) continue;
								?>
								<a href="<?php echo esc_url( get_category_link( $cat ) ); ?>"
								   class="inline-block bg-gray-100 hover:bg-[#F79E37] hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors duration-200">
									<?php echo esc_html( $cat->name ); ?>
									<span class="text-xs opacity-75">(<?php echo $cat->count; ?>)</span>
								</a>
							<?php endforeach; ?>
						<?php } else { // Tags ?>
							<?php
							$current_tag = get_queried_object();
							$related_tags = get_tags( [
								'exclude'   => $current_tag->term_id,
								'number'    => 8,
								'orderby'   => 'count',
								'order'     => 'DESC',
							] );
							foreach ( $related_tags as $tag ) :
								// Skip tags with numeric names or empty names
								if (empty($tag->name) || is_numeric($tag->name)) continue;
								?>
								<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"
								   class="inline-block bg-gray-100 hover:bg-[#F79E37] hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors duration-200">
									<?php echo esc_html( $tag->name ); ?>
									<span class="text-xs opacity-75">(<?php echo $tag->count; ?>)</span>
								</a>
							<?php endforeach; ?>
						<?php } ?>
					</div>
				</div>
			</section>
		<?php endif; ?>

		<!-- Blog Posts Grid Section -->
		<section
			class="w-full bg-gray-50 rounded-[20px]"
			role="contentinfo"
			aria-labelledby="archive-posts-heading"
		>
			<div class="p-6 md:p-8 lg:p-10">
				<h2 id="archive-posts-heading" class="sr-only">
					<?php echo esc_html( $archive_title ); ?>
				</h2>

				<?php if ( have_posts() ) : ?>
					<!-- Posts Count -->
					<div class="mb-8 text-center">
						<p class="text-sm text-gray-600">
							<?php
							global $wp_query;
							$found_posts = $wp_query->found_posts;
							$paged = max( 1, get_query_var( 'paged' ) );
							$posts_per_page = get_query_var( 'posts_per_page' );
							$from = ( $paged - 1 ) * $posts_per_page + 1;
							$to = min( $paged * $posts_per_page, $found_posts );

							printf(
								esc_html(
									_n(
										'Showing %1$d–%2$d of %3$d post',
										'Showing %1$d–%2$d of %3$d posts',
										$found_posts,
										'sunnysideac'
									)
								),
								number_format_i18n( $from ),
								number_format_i18n( $to ),
								number_format_i18n( $found_posts )
							);
							?>
						</p>
					</div>

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
					<nav class="mt-12 flex justify-center" aria-label="<?php echo esc_attr( $archive_title ); ?> pagination">
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
						<p class="text-lg text-gray-600">
							<?php
							if ( is_category() || is_tag() || is_author() ) {
								echo 'No posts found in this ' . esc_html( $archive_type ) . '.';
							} else {
								echo 'No blog posts found. Check back soon for HVAC tips and insights!';
							}
							?>
						</p>
					</div>
				<?php endif; ?>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>