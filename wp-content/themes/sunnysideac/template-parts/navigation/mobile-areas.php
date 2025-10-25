<?php
/**
 * Mobile Areas Section Template Part
 *
 * Renders the mobile navigation cities/service areas section.
 *
 * @package SunnysideAC
 *
 * Expected $args:
 * @var array  $priority_cities   Priority cities to display
 * @var string $current_city_name Currently active city name
 */

// Extract args
$priority_cities   = $args['priority_cities'] ?? [];
$current_city_name = $args['current_city_name'] ?? '';

// Safety check
if ( empty( $priority_cities ) ) {
	return;
}

?>
<div class="mb-6">
	<h3 class="mb-3 border-b border-gray-200 pb-2 text-lg font-medium text-gray-800">Areas</h3>
	<div class="space-y-1">
		<?php foreach ( $priority_cities as $city ) : ?>
			<?php
			$city_slug = sanitize_title( $city );
			$city_url  = home_url( sprintf( SUNNYSIDE_CITY_URL_PATTERN, $city_slug ) );
			$is_active = ( $current_city_name === $city );

			$link_classes = 'block w-full py-2 text-left transition-colors duration-200 mobile-area-link';
			if ( $is_active ) {
				$link_classes .= ' text-[#fb9939] font-medium';
			} else {
				$link_classes .= ' text-gray-700 hover:text-[#fb9939]';
			}
			?>
			<a
				href="<?php echo esc_url( $city_url ); ?>"
				class="<?php echo esc_attr( $link_classes ); ?>"
				<?php echo $is_active ? 'aria-current="page"' : ''; ?>
			>
				<?php echo esc_html( $city ); ?>
			</a>
		<?php endforeach; ?>

		<a
			href="<?php echo esc_url( home_url( '/cities' ) ); ?>"
			class="block w-full py-2 text-left font-medium text-[#fb9939] transition-colors duration-200 hover:text-[#e5462f]"
		>
			→ View All Cities
		</a>
	</div>
</div>
