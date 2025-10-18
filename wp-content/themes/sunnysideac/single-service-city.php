<?php
/**
 * Template: single-service-city.php
 * Displays a service in the context of a specific city
 * URL structure: /{city-slug}/{service-slug}/
 */

get_header();

// Service post is the main loop
if ( have_posts() ) :
	the_post();

	$service_id = get_the_ID();
	$city_slug  = get_query_var( 'city_slug' );
	$city_post  = $city_slug ? get_page_by_path( $city_slug, OBJECT, 'city' ) : null;
	?>

	<main class="service-city-content">
		<?php if ( $city_post ) : ?>
			<nav class="breadcrumbs container mx-auto px-4 py-4">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Home</a>
				<span class="separator"> / </span>
				<a href="<?php echo esc_url( get_permalink( $city_post->ID ) ); ?>">
					<?php echo esc_html( get_the_title( $city_post ) ); ?>
				</a>
				<span class="separator"> / </span>
				<span class="current"><?php the_title(); ?></span>
			</nav>
		<?php endif; ?>

		<article id="post-<?php echo esc_attr( $service_id ); ?>" <?php post_class( 'container mx-auto px-4 py-8' ); ?>>
			<header class="entry-header mb-8">
				<h1 class="text-4xl font-bold mb-4"><?php the_title(); ?></h1>

				<?php if ( $city_post ) : ?>
					<p class="service-city text-xl text-gray-600">
						Serving: <strong><?php echo esc_html( get_the_title( $city_post ) ); ?></strong>
					</p>
				<?php endif; ?>

				<?php if ( has_post_thumbnail() ) : ?>
					<div class="featured-image my-6">
						<?php the_post_thumbnail( 'large', [ 'class' => 'w-full h-auto rounded-lg' ] ); ?>
					</div>
				<?php endif; ?>
			</header>

			<div class="entry-content prose max-w-none">
				<?php the_content(); ?>
			</div>

			<?php if ( $city_post ) : ?>
				<aside class="city-info mt-12 p-6 bg-gray-100 rounded-lg">
					<h2 class="text-2xl font-semibold mb-4">
						About <?php echo esc_html( get_the_title( $city_post ) ); ?>
					</h2>
					<div class="city-description">
						<?php echo wpautop( get_the_content( null, false, $city_post ) ); ?>
					</div>
					<a href="<?php echo esc_url( get_permalink( $city_post->ID ) ); ?>"
					   class="inline-block mt-4 text-blue-600 hover:text-blue-800">
						View all services in <?php echo esc_html( get_the_title( $city_post ) ); ?> &rarr;
					</a>
				</aside>
			<?php endif; ?>
		</article>
	</main>

	<?php
endif;

get_footer();
