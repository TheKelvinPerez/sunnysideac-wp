<?php
/**
 * SubHeading Component
 * Displays a subheading text with optional custom classes
 *
 * @param string $text Subheading text (optional, falls back to slot content)
 * @param string $class Additional CSS classes (optional)
 */

$text  = isset( $args['text'] ) ? $args['text'] : '';
$class = isset( $args['class'] ) ? $args['class'] : '';
?>

<div class="text-xl font-semibold text-gray-900 sm:text-4xl md:text-3xl <?php echo esc_attr( $class ); ?>">
	<?php if ( $text ) : ?>
		<?php echo esc_html( $text ); ?>
	<?php else : ?>
		<?php // Slot content would be rendered here in WordPress templates ?>
	<?php endif; ?>
</div>