<?php
/**
 * Footer Menu Section Component
 * Reusable footer menu section - works like a React component
 *
 * @param array $props Component properties
 *   - 'title' (string) Section title
 *   - 'links' (array) Array of links with 'name' and 'href'
 *   - 'class' (string) Additional CSS classes
 */

$props = wp_parse_args(
	$args,
	array(
		'title' => '',
		'links' => array(),
		'class' => '',
	)
);

if ( empty( $props['title'] ) || empty( $props['links'] ) ) {
	return;
}
?>

<section class="footer-menu-section <?php echo esc_attr( $props['class'] ); ?>" aria-labelledby="<?php echo esc_attr( sanitize_title( $props['title'] ) . '-heading' ); ?>">
	<h3 id="<?php echo esc_attr( sanitize_title( $props['title'] ) . '-heading' ); ?>" class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl">
		<?php echo esc_html( $props['title'] ); ?>
	</h3>

	<ul class="space-y-2">
		<?php foreach ( $props['links'] as $link ) : ?>
			<li>
				<a
					href="<?php echo esc_url( $link['href'] ); ?>"
					class="font-normal text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500"
					<?php
					if ( ! empty( $link['target'] ) ) :
						?>
						target="<?php echo esc_attr( $link['target'] ); ?>"<?php endif; ?>
					<?php
					if ( ! empty( $link['rel'] ) ) :
						?>
						rel="<?php echo esc_attr( $link['rel'] ); ?>"<?php endif; ?>
				>
					<?php echo esc_html( $link['name'] ); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</section>