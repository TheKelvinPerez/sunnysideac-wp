<?php
/**
 * Mobile Navigation Links Template Part
 *
 * Renders the mobile navigation main links section (Projects, Blog, About, Contact).
 *
 * @package SunnysideAC
 *
 * Expected $args:
 * @var array $main_links Main navigation links (title, href)
 */

// Extract args
$main_links = $args['main_links'] ?? [];

// Safety check
if ( empty( $main_links ) ) {
	return;
}

?>
<div class="mb-6 space-y-1">
	<?php foreach ( $main_links as $link ) : ?>
		<button
			class="w-full border-b border-gray-200 py-2 text-left text-gray-700 hover:text-[#fb9939] mobile-nav-link"
			data-href="<?php echo esc_url( $link['href'] ?? '#' ); ?>"
		>
			<?php echo esc_html( $link['title'] ?? '' ); ?>
		</button>
	<?php endforeach; ?>
</div>
