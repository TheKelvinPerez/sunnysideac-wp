<?php get_header(); ?>

<main class="min-h-screen bg-gray-50">
	<!-- Mobile constraint wrapper - applies 20px padding on mobile only -->
	<div class="px-5 lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<!-- Page Header -->
						<header class="py-16 bg-gradient-to-r from-[#fb9939] to-[#e5462f] text-white">
							<div class="container mx-auto px-4 text-center">
								<h1 class="text-4xl md:text-5xl font-bold mb-4">
									<?php the_title(); ?>
								</h1>
								<?php if ( has_excerpt() ) : ?>
									<p class="text-xl text-[#ffc549] max-w-3xl mx-auto">
										<?php echo get_the_excerpt(); ?>
									</p>
								<?php endif; ?>
							</div>
						</header>

						<!-- Page Content -->
						<div class="py-16">
							<div class="prose prose-lg max-w-none">
								<?php the_content(); ?>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			<?php else : ?>
				<section class="py-16">
					<div class="container mx-auto px-4 text-center">
						<h1 class="text-4xl font-bold text-gray-900 mb-4">Page Not Found</h1>
						<p class="text-xl text-gray-600 mb-8">The page you're looking for doesn't exist.</p>
						<a href="<?php echo home_url(); ?>" class="bg-[#e5462f] text-white px-6 py-3 rounded-lg font-semibold hover:bg-[#fb9939] transition-colors inline-block">
							Return Home
						</a>
					</div>
				</section>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php get_footer(); ?>