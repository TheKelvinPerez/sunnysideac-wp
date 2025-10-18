<?php
/**
 * Video Embed Component
 * Self-contained video player with full responsive support and lazy loading
 *
 * Expected variables:
 * @var string $video_url - YouTube or Vimeo URL
 * @var string $video_title - Video title for accessibility and schema
 * @var string $video_description - Optional video description
 * @var string $video_thumbnail - Optional custom thumbnail URL
 * @var string $video_duration - ISO 8601 duration format (e.g., PT2M45S)
 * @var string $service_title - Service name for fallback title
 * @var string $city_name - City name for fallback title
 * @var object $city_post - City post object for schema
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Validate required variables
if ( empty( $video_url ) ) {
	return;
}

// Set defaults for optional variables
$video_title       = $video_title ?? ( $service_title . ' in ' . $city_name . ', Florida' );
$video_description = $video_description ?? '';
$video_thumbnail   = $video_thumbnail ?? '';
$video_duration    = $video_duration ?? '';

// Parse video data
$video_data = sunnysideac_parse_video_url( $video_url );
if ( ! $video_data ) {
	return;
}

// Determine thumbnail URL (custom or auto-generated)
$thumbnail_url = ! empty( $video_thumbnail ) ? $video_thumbnail : ( $video_data['thumbnail_url'] ?? '' );
?>

<section class="city-video mb-12 bg-gradient-to-r from-blue-600 to-blue-800 p-4 sm:p-6 md:p-8 rounded-lg"
         aria-labelledby="video-heading"
         itemscope
         itemtype="https://schema.org/VideoObject">

	<div class="container mx-auto max-w-6xl">
		<!-- Video Title -->
		<h2 id="video-heading"
		    class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-4 sm:mb-6 text-center px-2"
		    itemprop="name">
			<?php echo esc_html( $video_title ); ?>
		</h2>

		<!-- Video Description (if provided) -->
		<?php if ( ! empty( $video_description ) ) : ?>
			<p class="text-white text-center mb-4 sm:mb-6 text-base sm:text-lg max-w-3xl mx-auto px-4"
			   itemprop="description">
				<?php echo esc_html( $video_description ); ?>
			</p>
		<?php endif; ?>

		<!-- Responsive Video Container -->
		<div class="video-wrapper max-w-4xl mx-auto">
			<!--
				aspect-video = 16:9 aspect ratio
				relative = positioning context for absolute iframe
				rounded-lg = rounded corners
				overflow-hidden = clips iframe to rounded corners
				shadow-2xl = prominent shadow
			-->
			<div class="video-container relative aspect-video bg-black rounded-lg overflow-hidden shadow-2xl">
				<?php
				// Generate video embed with optimized settings
				echo sunnysideac_get_video_embed(
					$video_url,
					[
						'title' => $video_title,
						'class' => 'absolute top-0 left-0 w-full h-full border-0',
					]
				);
				?>

				<!-- Schema.org Microdata -->
				<meta itemprop="embedUrl" content="<?php echo esc_url( $video_data['embed_url'] ); ?>">
				<meta itemprop="contentUrl" content="<?php echo esc_url( $video_data['watch_url'] ); ?>">

				<?php if ( isset( $city_post ) && $city_post ) : ?>
					<meta itemprop="uploadDate" content="<?php echo esc_attr( get_the_date( 'c', $city_post ) ); ?>">
				<?php endif; ?>

				<?php if ( ! empty( $thumbnail_url ) ) : ?>
					<link itemprop="thumbnailUrl" href="<?php echo esc_url( $thumbnail_url ); ?>">
				<?php endif; ?>

				<?php if ( ! empty( $video_duration ) ) : ?>
					<meta itemprop="duration" content="<?php echo esc_attr( $video_duration ); ?>">
				<?php endif; ?>
			</div>
		</div>

		<!-- Context Text Below Video -->
		<p class="text-white text-center mt-4 sm:mt-6 text-sm sm:text-base px-4">
			<?php if ( ! empty( $city_name ) && ! empty( $service_title ) ) : ?>
				Watch how we serve customers in <?php echo esc_html( $city_name ); ?> with professional <?php echo strtolower( $service_title ); ?> services
			<?php else : ?>
				Watch our professional HVAC services in action
			<?php endif; ?>
		</p>
	</div>
</section>
