<?php
/**
 * Single Post Template
 * Template for displaying individual blog posts
 */

get_header();

// Get post data
$post_id            = get_the_ID();
$post_title         = get_the_title();
$post_date          = get_the_date();
$author_id          = get_the_author_meta('ID');
$author_name        = 'SunnySide247AC';
$author_description = 'Professional HVAC company serving South Florida with 24/7 emergency AC repair, installation, and maintenance services. Family-owned and operated since 2014.';
$author_avatar      = get_template_directory_uri() . '/assets/images/images/logos/sunny-side-logo.png';

// Get icons from theme assets
$blog_icon     = sunnysideac_asset_url( 'assets/images/home-page/blog/blog-title-icon.svg' );
$author_icon   = sunnysideac_asset_url( 'assets/images/home-page/blog/blog-auther-icon.svg' );
$category_icon = sunnysideac_asset_url( 'assets/images/home-page/blog/air-con-blog-icon.svg' );

// Page breadcrumbs
$breadcrumbs = array(
	array(
		'name' => 'Home',
		'url'  => home_url( '/' ),
	),
	array(
		'name' => 'Blog',
		'url'  => home_url( '/blog/' ),
	),
	array(
		'name' => $post_title,
		'url'  => '',
	),
);

// Get post categories and tags
$categories = get_the_category();
$tags       = get_the_tags();

// Get related posts
$related_posts = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 3,
		'post__not_in'   => array( $post_id ),
		'orderby'        => 'rand',
		'tax_query'      => array(
			'relation' => 'OR',
			array(
				'taxonomy' => 'category',
				'field'    => 'term_id',
				'terms'    => wp_list_pluck( $categories, 'term_id' ),
			),
		),
	)
);
?>

<!-- Page Header with Breadcrumbs -->
<?php
get_template_part(
	'template-parts/page-header',
	null,
	array(
		'breadcrumbs' => $breadcrumbs,
		'title'       => '',
		'description' => '',
		'show_ctas'   => false,
		'bg_color'    => 'white',
	)
);
?>

<main class="">
	<article class="py-12">
		<!-- Post Header -->
		<header class="mb-8">
			<!-- Post Title -->
			<h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6 leading-tight">
				<?php echo esc_html( $post_title ); ?>
			</h1>

			<!-- Post Meta -->
			<div class="flex flex-wrap items-center gap-6 text-sm text-gray-600 mb-8">
				<!-- Date -->
				<div class="flex items-center gap-2">
					<svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
					</svg>
					<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
						<?php echo esc_html( $post_date ); ?>
					</time>
				</div>

				<!-- Author -->
				<div class="flex items-center gap-2">
					<img src="<?php echo esc_url( $author_icon ); ?>" alt="Author" class="h-4 w-4">
					<span>By <?php echo esc_html( $author_name ); ?></span>
				</div>

				<!-- Categories -->
				<?php if ( ! empty( $categories ) ) : ?>
					<div class="flex items-center gap-2">
						<img src="<?php echo esc_url( $category_icon ); ?>" alt="Category" class="h-4 w-4">
						<div class="flex flex-wrap gap-2">
							<?php foreach ( $categories as $category ) : ?>
								<a href="<?php echo esc_url( get_category_link( $category ) ); ?>"
									class="text-[#F79E37] hover:text-[#E64B30] transition-colors duration-200">
									<?php echo esc_html( isset( $category->name ) ? $category->name : 'Uncategorized' ); ?>
								</a>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<!-- Featured Image -->
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="mb-8 rounded-2xl overflow-hidden shadow-lg">
					<?php
					the_post_thumbnail(
						'large',
						array(
							'class' => 'w-full h-[400px] object-cover',
							'alt'   => get_the_title(),
						)
					);
					?>
				</div>
			<?php endif; ?>
		</header>

		<!-- Post Content -->
		<div class="prose prose-lg prose-gray max-w-none mb-12">
			<?php
			the_content();

			// Link pages for paginated posts
			wp_link_pages(
				array(
					'before'      => '<div class="page-links">',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				)
			);
			?>
		</div>

		<!-- Post Tags -->
		<?php if ( $tags ) : ?>
			<footer class="border-t border-gray-200 pt-8 mb-12">
				<div class="flex flex-wrap gap-2">
					<span class="text-sm font-semibold text-gray-600 mr-2">Tags:</span>
					<?php foreach ( $tags as $tag ) : ?>
						<a href="<?php echo esc_url( get_tag_link( $tag ) ); ?>"
							class="inline-block bg-gray-100 hover:bg-[#F79E37] hover:text-white text-gray-700 px-3 py-1 rounded-full text-sm transition-colors duration-200">
							<?php echo esc_html( $tag->name ); ?>
						</a>
					<?php endforeach; ?>
				</div>
			</footer>
		<?php endif; ?>

		<!-- Author Bio Box -->
		<section class="bg-gray-50 rounded-2xl p-8 mb-12">
			<div class="flex flex-col md:flex-row gap-6 items-start md:items-center">
				<div class="flex-shrink-0">
					<img src="<?php echo esc_url( $author_avatar ); ?>"
						alt="<?php echo esc_attr( $author_name ); ?>"
						class="w-20 h-20 rounded-full object-cover">
				</div>
				<div class="flex-grow">
					<div class="text-xl font-semibold text-gray-900 mb-2" role="heading" aria-level="4">
						About <?php echo esc_html( $author_name ); ?>
					</div>
					<?php if ( $author_description ) : ?>
						<div class="text-gray-600 mb-3">
							<?php echo wp_kses_post( $author_description ); ?>
						</div>
					<?php endif; ?>
					<div class="text-sm text-gray-500">
						<?php
						$post_count = count_user_posts( $author_id, 'post' );
						printf(
							esc_html(
								_n(
									'%d post published',
									'%d posts published',
									$post_count,
									'sunnysideac'
								)
							),
							number_format_i18n( $post_count )
						);
						?>
					</div>
				</div>
			</div>
		</section>

		<!-- Navigation -->
		<nav class="flex justify-between items-center mb-12 border-t border-b border-gray-200 py-6">
			<?php
			$prev_post = get_previous_post();
			$next_post = get_next_post();
			?>

			<div class="w-1/2">
				<?php if ( $prev_post ) : ?>
					<a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>"
						class="flex items-center gap-2 text-gray-600 hover:text-[#F79E37] transition-colors duration-200">
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
						</svg>
						<div class="text-left">
							<div class="text-xs uppercase tracking-wide">Previous</div>
							<div class="font-medium"><?php echo esc_html( $prev_post->post_title ); ?></div>
						</div>
					</a>
				<?php endif; ?>
			</div>

			<div class="w-1/2 text-right">
				<?php if ( $next_post ) : ?>
					<a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>"
						class="flex items-center gap-2 justify-end text-gray-600 hover:text-[#F79E37] transition-colors duration-200">
						<div class="text-right">
							<div class="text-xs uppercase tracking-wide">Next</div>
							<div class="font-medium"><?php echo esc_html( $next_post->post_title ); ?></div>
						</div>
						<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
							<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
						</svg>
					</a>
				<?php endif; ?>
			</div>
		</nav>

		<!-- Related Posts -->
		<?php if ( $related_posts->have_posts() ) : ?>
			<section class="mb-12">
				<h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">Related Articles</h2>
				<div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
					<?php
					while ( $related_posts->have_posts() ) :
						$related_posts->the_post();
						?>
						<?php
						get_template_part(
							'template-parts/blog-card',
							null,
							array(
								'post'            => get_post(),
								'show_excerpt'    => true,
								'excerpt_length'  => 15,
								'image_size'      => 'medium',
								'show_author'     => false,  // Hide author for related posts
								'show_category'   => true,
								'show_date'       => false,  // Hide date for related posts
								'card_class'      => '',     // No max-width constraint for related posts
								'author_icon'     => $author_icon,
								'category_icon'   => $category_icon,
								'read_more_arrow' => sunnysideac_asset_url( 'assets/images/home-page/blog/read-more-arrow-up-right.svg' ),
								'fallback_image'  => sunnysideac_asset_url( 'assets/images/optimize/blog-post-image-1.webp' ),
							)
						);
						?>
					<?php endwhile; ?>
				</div>
			</section>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
	</article>
</main>

<!-- Back to Blog CTA -->
<section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-16">
	<div class="px-5 lg:px-0 max-w-4xl mx-auto text-center">
		<h2 class="text-3xl font-bold mb-4">Enjoyed this article?</h2>
		<p class="text-xl text-blue-100 mb-8">
			Explore more HVAC tips and insights on our blog.
		</p>
		<a href="<?php echo esc_url( home_url( '/blog/' ) ); ?>"
			class="inline-flex items-center gap-2 bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-50 transition-all duration-200 transform hover:scale-105">
			Back to Blog
			<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
				<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
			</svg>
		</a>
	</div>
</section>

<?php get_footer(); ?>
