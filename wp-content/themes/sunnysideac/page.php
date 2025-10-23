<?php get_header(); ?>

<!-- Standard Page Template -->
<main class="min-h-screen bg-gray-50" role="main">
	<!-- Container matching front-page style -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
						<!-- Page Header -->
						<header class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-6 md:p-10 text-white text-center">
							<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
								<span class="bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent">
									<?php the_title(); ?>
								</span>
							</h1>
							<?php if ( has_excerpt() ) : ?>
								<p class="text-lg md:text-xl text-white/90 max-w-4xl mx-auto leading-relaxed">
									<?php echo get_the_excerpt(); ?>
								</p>
							<?php endif; ?>
						</header>

						<!-- Page Content -->
						<div class="page-content bg-white rounded-[20px] p-6 md:p-10">
							<div class="prose prose-lg max-w-none">
								<?php the_content(); ?>
							</div>
						</div>
					</article>
				<?php endwhile; ?>
			<?php else : ?>
				<section class="error-section bg-white rounded-[20px] p-6 md:p-10 text-center">
					<h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Page Not Found</h1>
					<p class="text-xl text-gray-600 mb-8">The page you're looking for doesn't exist.</p>
					<a href="<?php echo home_url(); ?>" class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-8 py-4 text-white font-medium hover:opacity-90 transition-opacity">
						Return Home
					</a>
				</section>
			<?php endif; ?>
		</section>
	</div>
</main>

<?php get_footer(); ?>