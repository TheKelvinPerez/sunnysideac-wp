<?php
/**
 * Template Name: Blog Page
 * Template for displaying the Blog archive page
 */

get_header();

// Get icons from theme assets
$blog_icon = sunnysideac_asset_url( 'assets/images/home-page/blog/blog-title-icon.svg' );

// Page breadcrumbs
$breadcrumbs = array(
	array(
		'name' => 'Home',
		'url'  => home_url( '/' ),
	),
	array(
		'name' => 'Blog',
		'url'  => '',
	),
);

// Blog post icons
$images = array(
	'air_con_blog_icon' => sunnysideac_asset_url( 'assets/images/home-page/blog/air-con-blog-icon.svg' ),
	'blog_auther_icon'  => sunnysideac_asset_url( 'assets/images/home-page/blog/blog-auther-icon.svg' ),
	'read_more_arrow'   => sunnysideac_asset_url( 'assets/images/home-page/blog/read-more-arrow-up-right.svg' ),
);

// Query blog posts
$paged      = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
$blog_query = new WP_Query(
	array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => 9,
		'paged'          => $paged,
		'orderby'        => 'date',
		'order'          => 'DESC',
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
		'title'       => 'Our Blog',
		'description' => '',
		'show_ctas'   => false,
		'bg_color'    => 'white',
	)
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
					array(
						'icon'  => $blog_icon,
						'title' => 'HVAC Tips & Insights',
						'id'    => 'blog-overview-heading',
						'align' => 'center',
					)
				);
				?>

				<?php
				get_template_part(
					'template-parts/subheading',
					null,
					array(
						'text' => 'Stay Cool, Stay Warm, Stay Informed',
					)
				);
				?>

				<div class="mt-8 text-left space-y-6">
					<p class="text-sm md:text-base font-normal leading-snug text-gray-700">
						Welcome to the SunnySide 24/7 AC blog, your trusted resource for HVAC tips, maintenance advice, and industry insights. Whether you're looking to improve your home's energy efficiency, troubleshoot common AC problems, or learn about the latest HVAC technologies, we've got you covered.
					</p>
					<p class="text-sm md:text-base font-normal leading-snug text-gray-700">
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

				<?php if ( $blog_query->have_posts() ) : ?>
					<!-- Blog Cards Grid -->
					<div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
						<?php
						while ( $blog_query->have_posts() ) :
							$blog_query->the_post();
							?>
							<article class="group mx-auto block max-w-[375px] overflow-hidden rounded-b-2xl bg-white shadow-lg transition-all duration-300 hover:shadow-xl">
								<a
									href="<?php the_permalink(); ?>"
									class="block focus:ring-2 focus:ring-[#F79E37] focus:ring-offset-2 focus:outline-none"
								>
									<!-- Image Container with Date Badge -->
									<div class="relative h-[292px] w-full overflow-hidden rounded-[20px]">
										<?php if ( has_post_thumbnail() ) : ?>
											<?php
											the_post_thumbnail(
												'large',
												array(
													'class' => 'h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105',
													'alt' => get_the_title(),
												)
											);
											?>
										<?php else : ?>
											<img
												src="<?php echo esc_url( sunnysideac_asset_url( 'assets/images/optimize/blog-post-image-1.webp' ) ); ?>"
												alt="<?php echo esc_attr( get_the_title() ); ?>"
												class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
											/>
										<?php endif; ?>

										<!-- Gradient Overlay -->
										<div
											class="absolute right-0 bottom-0 left-0 h-3/4 rounded-[20px]"
											style="background: linear-gradient(360deg, #000000 0%, rgba(102, 102, 102, 0) 100%)"
										></div>

										<!-- Date Badge -->
										<div class="absolute right-4 bottom-4 z-10">
											<div class="rounded-lg bg-gradient-to-r from-[#FDC85F] to-[#E64B30] px-3 py-2 text-center shadow-lg">
												<div class="text-sm font-bold text-white">
													<?php echo get_the_date( 'd' ); ?>
												</div>
												<div class="text-xs font-thin text-white">
													<?php echo get_the_date( 'M' ); ?>
												</div>
												<div class="text-xs font-thin text-white">
													<?php echo get_the_date( 'Y' ); ?>
												</div>
											</div>
										</div>
									</div>

									<!-- Card Content -->
									<div class="cursor-pointer p-6">
										<!-- Author and Category -->
										<div class="mb-4 flex items-center justify-between">
											<!-- Author -->
											<div class="flex items-center gap-2">
												<img
													src="<?php echo esc_url( $images['blog_auther_icon'] ); ?>"
													alt="Author"
													class="h-4 w-4"
												/>
												<span class="text-sm font-medium text-gray-600">
													<?php echo esc_html( get_the_author() ); ?>
												</span>
											</div>

											<!-- Category -->
											<?php
											$categories = get_the_category();
											if ( ! empty( $categories ) ) :
												?>
												<div class="flex items-center gap-2">
													<img
														src="<?php echo esc_url( $images['air_con_blog_icon'] ); ?>"
														alt="Category"
														class="h-4 w-4"
													/>
													<span class="text-sm font-medium text-[#F79E37]">
														<?php
														$category_name = isset( $categories[0]->name ) ? $categories[0]->name : 'Uncategorized';
														echo esc_html( $category_name );
														?>
													</span>
												</div>
											<?php endif; ?>
										</div>

										<!-- Title -->
										<div class="mb-3 text-lg leading-tight font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#F79E37]" role="heading" aria-level="4">
											<?php the_title(); ?>
										</div>

										<!-- Excerpt -->
										<p class="mb-4 text-sm leading-relaxed text-gray-600">
											<?php
											if ( has_excerpt() ) {
												echo esc_html( get_the_excerpt() );
											} else {
												echo esc_html( wp_trim_words( get_the_content(), 20, '...' ) );
											}
											?>
										</p>

										<!-- Read More Link -->
										<div class="flex cursor-pointer items-center gap-2">
											<span class="cursor-pointer text-sm font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#F79E37]">
												Read More
											</span>
											<img
												src="<?php echo esc_url( $images['read_more_arrow'] ); ?>"
												alt="Read more"
												class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1 group-hover:translate-y-[-2px]"
											/>
										</div>
									</div>
								</a>
							</article>
						<?php endwhile; ?>
					</div>

					<!-- Pagination -->
					<?php if ( $blog_query->max_num_pages > 1 ) : ?>
						<nav class="mt-12 flex justify-center" aria-label="Blog pagination">
							<div class="flex gap-2">
								<?php
								echo paginate_links(
									array(
										'total'     => $blog_query->max_num_pages,
										'current'   => max( 1, $paged ),
										'prev_text' => '&laquo; Previous',
										'next_text' => 'Next &raquo;',
										'type'      => 'list',
										'class'     => 'pagination',
									)
								);
								?>
							</div>
						</nav>
					<?php endif; ?>

				<?php else : ?>
					<!-- No Posts Found -->
					<div class="text-center py-12">
						<p class="text-lg text-gray-600">No blog posts found. Check back soon for HVAC tips and insights!</p>
					</div>
				<?php endif; ?>

				<?php wp_reset_postdata(); ?>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
