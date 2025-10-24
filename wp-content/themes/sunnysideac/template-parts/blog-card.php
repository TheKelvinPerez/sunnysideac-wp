<?php
/**
 * Blog Card Template Part
 * Reusable blog card component for displaying blog posts
 *
 * Usage:
 * get_template_part('template-parts/blog-card', null, [
 *     'post' => $post_object,                    // WordPress post object (optional, defaults to global post)
 *     'show_excerpt' => true,                    // Whether to show excerpt
 *     'excerpt_length' => 20,                    // Excerpt length in words
 *     'image_size' => 'large',                   // Featured image size
 *     'show_author' => true,                     // Whether to show author
 *     'show_category' => true,                   // Whether to show category
 *     'show_date' => true,                       // Whether to show date badge
 *     'card_class' => 'max-w-[375px]',           // Additional CSS classes
 * ]);
 */

// Default arguments
$defaults = [
	'post'            => get_post(),
	'show_excerpt'    => true,
	'excerpt_length'  => 20,
	'image_size'      => 'large',
	'show_author'     => true,
	'show_category'   => true,
	'show_date'       => true,
	'card_class'      => 'max-w-[375px]',
	'author_icon'     => sunnysideac_asset_url( 'assets/images/home-page/blog/blog-auther-icon.svg' ),
	'category_icon'   => sunnysideac_asset_url( 'assets/images/home-page/blog/air-con-blog-icon.svg' ),
	'read_more_arrow' => sunnysideac_asset_url( 'assets/images/home-page/blog/read-more-arrow-up-right.svg' ),
	'fallback_image'  => sunnysideac_asset_url( 'assets/images/home-page/blog/blog-post-image-1.png' ),
];

$args = wp_parse_args( $args, $defaults );

// Extract post data
$post = $args['post'];
if ( ! $post ) {
	return;
}

$post_id    = $post->ID;
$post_title = get_the_title( $post );
$post_url   = get_permalink( $post );
$post_date  = get_the_date( '', $post );
$author     = get_the_author( $post );
$excerpt    = has_excerpt( $post ) ? get_the_excerpt( $post ) : wp_trim_words( get_the_content( '', false, $post ), $args['excerpt_length'], '...' );
$categories = get_the_category( $post );

// Get featured image
$image_url = '';
if ( has_post_thumbnail( $post ) ) {
	$image_atts = wp_get_attachment_image_src( get_post_thumbnail_id( $post ), $args['image_size'] );
	if ( $image_atts ) {
		$image_url = $image_atts[0];
	}
} else {
	$image_url = $args['fallback_image'];
}

// Date badge parts
$date_parts = explode( ' ', $post_date );
$day        = $date_parts[0] ?? '';
$month      = isset( $date_parts[1] ) ? substr( $date_parts[1], 0, 3 ) : '';
$year       = $date_parts[2] ?? '';
?>

<article class="group <?php echo esc_attr( $args['card_class'] ); ?> block overflow-hidden rounded-b-2xl bg-white shadow-lg transition-all duration-300 hover:shadow-xl">
	<a href="<?php echo esc_url( $post_url ); ?>"
		aria-label="<?php echo esc_attr( 'Read full article: ' . $post_title ); ?>"
		class="block focus:ring-2 focus:ring-[#F79E37] focus:ring-offset-2 focus:outline-none">
		<!-- Image Container with Date Badge -->
		<div class="relative h-[292px] w-full overflow-hidden rounded-[20px]">
			<?php if ( $image_url ) : ?>
				<img src="<?php echo esc_url( $image_url ); ?>"
					alt="<?php echo esc_attr( $post_title ); ?>"
					class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105">
			<?php endif; ?>

			<!-- Gradient Overlay -->
			<div class="absolute right-0 bottom-0 left-0 h-3/4 rounded-[20px]"
				style="background: linear-gradient(360deg, #000000 0%, rgba(102, 102, 102, 0) 100%)"></div>

			<!-- Date Badge -->
			<?php if ( $args['show_date'] && $day && $month && $year ) : ?>
				<div class="absolute right-4 bottom-4 z-10">
					<div class="rounded-lg bg-gradient-to-r from-[#FDC85F] to-[#E64B30] px-3 py-2 text-center shadow-lg">
						<div class="text-sm font-bold text-white"><?php echo esc_html( $day ); ?></div>
						<div class="text-xs font-thin text-white"><?php echo esc_html( $month ); ?></div>
						<div class="text-xs font-thin text-white"><?php echo esc_html( $year ); ?></div>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<!-- Card Content -->
		<div class="cursor-pointer p-6">
			<!-- Author and Category -->
			<div class="mb-4 flex items-center justify-between">
				<!-- Author -->
				<?php if ( $args['show_author'] ) : ?>
					<div class="flex items-center gap-2">
						<img src="<?php echo esc_url( $args['author_icon'] ); ?>"
							alt="Author"
							class="h-4 w-4">
						<span class="text-sm font-medium text-gray-600"><?php echo esc_html( $author ); ?></span>
					</div>
				<?php endif; ?>

				<!-- Category -->
				<?php if ( $args['show_category'] && ! empty( $categories ) ) : ?>
					<div class="flex items-center gap-2">
						<img src="<?php echo esc_url( $args['category_icon'] ); ?>"
							alt="Category"
							class="h-4 w-4">
						<span class="text-sm font-medium text-[#F79E37]">
							<?php echo esc_html( $categories[0]->name ); ?>
						</span>
					</div>
				<?php endif; ?>
			</div>

			<!-- Title -->
			<h3 class="mb-3 text-lg leading-tight font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#F79E37]">
				<?php echo esc_html( $post_title ); ?>
			</h3>

			<!-- Excerpt -->
			<?php if ( $args['show_excerpt'] ) : ?>
				<p class="mb-4 text-sm leading-relaxed text-gray-600">
					<?php echo esc_html( $excerpt ); ?>
				</p>
			<?php endif; ?>

			<!-- Read More Link -->
			<div class="flex cursor-pointer items-center gap-2">
				<span class="cursor-pointer text-sm font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#F79E37]">
					Read More
				</span>
				<img src="<?php echo esc_url( $args['read_more_arrow'] ); ?>"
					alt="Read more"
					class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1 group-hover:translate-y-[-2px]">
			</div>
		</div>
	</a>
</article>
