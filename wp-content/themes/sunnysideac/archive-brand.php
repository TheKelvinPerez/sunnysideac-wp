<?php
/**
 * Template for displaying Brand archive page
 * URL: /brands
 */

get_header();
?>

<main class="min-h-screen bg-gray-50">
	<!-- Hero Section -->
	<header class="py-16 bg-gradient-to-r from-[#fb9939] to-[#e5462f] text-white">
		<div class="container mx-auto px-4 text-center">
			<h1 class="text-4xl md:text-5xl font-bold mb-4 [font-family:'Inter-Bold',Helvetica]">
				Brands We Service
			</h1>
			<p class="text-xl text-white/90 max-w-3xl mx-auto [font-family:'Inter',Helvetica]">
				Expert service and repair for all major HVAC brands
			</p>
		</div>
	</header>

	<!-- Brands Grid -->
	<div class="py-16 px-4 max-w-7xl mx-auto">
		<?php
		$brands_query = new WP_Query( [
			'post_type'      => 'brand',
			'posts_per_page' => -1,
			'orderby'        => 'title',
			'order'          => 'ASC',
		] );

		if ( $brands_query->have_posts() ) :
		?>
			<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-16">
				<?php while ( $brands_query->have_posts() ) : $brands_query->the_post(); ?>
					<a href="<?php the_permalink(); ?>"
					   class="group bg-white rounded-[20px] p-8 shadow-md hover:shadow-xl transition-all duration-200 hover:scale-105 border-2 border-transparent hover:border-[#ffc549]">

						<?php if ( has_post_thumbnail() ) : ?>
							<div class="mb-4 h-24 flex items-center justify-center">
								<?php the_post_thumbnail( 'medium', [ 'class' => 'max-h-full w-auto object-contain' ] ); ?>
							</div>
						<?php endif; ?>

						<div class="text-center">
							<h3 class="text-2xl font-bold text-gray-900 group-hover:text-[#e5462f] transition-colors duration-200 mb-2 [font-family:'Inter-Bold',Helvetica]">
								<?php the_title(); ?>
							</h3>

							<?php if ( has_excerpt() ) : ?>
								<p class="text-sm text-gray-600 mb-4 [font-family:'Inter',Helvetica]">
									<?php echo get_the_excerpt(); ?>
								</p>
							<?php endif; ?>

							<div class="inline-flex items-center gap-2 text-[#fb9939] group-hover:text-[#e5462f] transition-colors duration-200 font-medium [font-family:'Inter-Medium',Helvetica]">
								Learn More
								<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
									<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
								</svg>
							</div>
						</div>
					</a>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>

		<?php else : ?>
			<div class="text-center py-12">
				<p class="text-xl text-gray-600">No brands found.</p>
			</div>
		<?php endif; ?>

		<!-- Featured Brands Section -->
		<section class="mb-16">
			<h2 class="text-3xl font-bold text-center mb-8 bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text [-webkit-background-clip:text] [-webkit-text-fill-color:transparent] [text-fill-color:transparent] [font-family:'Inter-Bold',Helvetica]">
				We Service All Major Brands
			</h2>

			<div class="bg-white rounded-[20px] p-8 shadow-lg">
				<div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-6 text-center">
					<?php
					$major_brands = [
						'Daikin',
						'Trane',
						'Lennox',
						'Carrier',
						'Mitsubishi',
						'Goodman',
						'Rheem',
						'York',
						'Bryant',
						'Ruud',
						'Amana',
						'American Standard',
					];

					foreach ( $major_brands as $brand ) :
					?>
						<div class="flex items-center justify-center p-4 text-gray-700 font-medium [font-family:'Inter-Medium',Helvetica]">
							<?php echo esc_html( $brand ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</section>

		<!-- CTA Section -->
		<section class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] rounded-[20px] p-8 text-center">
			<h2 class="text-3xl font-bold text-white mb-4 [font-family:'Inter-Bold',Helvetica]">
				Don't See Your Brand?
			</h2>
			<p class="text-white/90 text-lg mb-6 [font-family:'Inter',Helvetica]">
				We service all major HVAC brands. Contact us to discuss your specific needs.
			</p>
			<div class="flex flex-col sm:flex-row gap-4 justify-center">
				<a href="<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
				   class="inline-flex items-center justify-center gap-2 bg-white text-[#e5462f] px-8 py-3 rounded-full font-bold hover:bg-[#ffc549] transition-colors duration-200 [font-family:'Inter-Bold',Helvetica]">
					<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
					</svg>
					Call <?php echo esc_html( SUNNYSIDE_PHONE_DISPLAY ); ?>
				</a>
				<a href="<?php echo home_url( '/contact' ); ?>"
				   class="inline-flex items-center justify-center gap-2 bg-white/10 text-white border-2 border-white px-8 py-3 rounded-full font-bold hover:bg-white hover:text-[#e5462f] transition-colors duration-200 [font-family:'Inter-Bold',Helvetica]">
					Contact Us
					<svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
						<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
					</svg>
				</a>
			</div>
		</section>
	</div>
</main>

<?php get_footer(); ?>
