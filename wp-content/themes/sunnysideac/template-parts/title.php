<?php
/**
 * Title Component with Icon
 *
 * @param string $icon Icon URL/path (optional)
 * @param string $title Title text (required)
 * @param string $mobileTitle Mobile-specific title text (optional)
 * @param string $id Optional ID attribute for the h2 element
 * @param string $align Alignment: 'left', 'center', or 'right' (default: 'center')
 */

$icon         = isset( $args['icon'] ) ? $args['icon'] : '';
$title        = isset( $args['title'] ) ? $args['title'] : '';
$mobile_title = isset( $args['mobileTitle'] ) ? $args['mobileTitle'] : '';
$id           = isset( $args['id'] ) ? $args['id'] : '';
$align        = isset( $args['align'] ) ? $args['align'] : 'center';

// Get justify class based on alignment
$justify_class = 'justify-center'; // default
if ( $align === 'left' ) {
	$justify_class = 'justify-start';
} elseif ( $align === 'right' ) {
	$justify_class = 'justify-end';
}
?>

<div class="mb-4 flex flex-col items-center gap-3 md:flex-row md:items-center md:gap-3 <?php echo esc_attr( $justify_class ); ?>">
	<?php if ( $icon ) : ?>
		<img
			class="h-8 w-8 md:h-10 md:w-10"
			alt=""
			src="<?php echo esc_url( $icon ); ?>"
			role="presentation"
		/>
	<?php endif; ?>

	<h2
		<?php if ( $id ) : ?>
			id="<?php echo esc_attr( $id ); ?>"
		<?php endif; ?>
		class="bg-gradient-to-r from-[#F79E37] to-[#915D20] bg-clip-text text-center text-2xl font-semibold text-transparent md:text-left md:text-3xl"
	>
		<?php if ( $mobile_title ) : ?>
			<span class="md:hidden"><?php echo esc_html( $mobile_title ); ?></span>
			<span class="hidden md:inline"><?php echo esc_html( $title ); ?></span>
		<?php else : ?>
			<?php echo esc_html( $title ); ?>
		<?php endif; ?>
	</h2>
</div>
