<?php
/**
 * Desktop Navigation Item Template Part
 *
 * Renders a single desktop navigation menu item.
 * Handles both regular links and mega menu triggers.
 *
 * @package SunnysideAC
 *
 * Expected $args:
 * @var array  $item          Navigation item data (title, href, type, mega_menu_type)
 * @var string $chevron_icon  Path to chevron icon SVG
 */

// Extract args for cleaner code
$item         = $args['item'] ?? [];
$chevron_icon = $args['chevron_icon'] ?? '';

// Safety check
if ( empty( $item ) ) {
	return;
}

// Determine if this item is active
$is_active = sunnysideac_is_menu_item_active( $item['title'] ?? '' );

// Build CSS classes for the menu item
$menu_item_classes = 'inline-flex cursor-pointer items-center gap-1 rounded-full px-6 py-3 transition-colors duration-200 focus:ring-2 focus:ring-[#fbbf24] focus:ring-offset-2 focus:outline-none nav-item';
if ( $is_active ) {
	$menu_item_classes .= ' bg-[#fbbf24]';
} else {
	$menu_item_classes .= ' hover:bg-[#fbbf24] bg-[#fef3c7]';
}

$text_classes = ' text-lg font-medium whitespace-nowrap transition-colors duration-200 ' . ( $is_active ? 'text-[#9a3412]' : 'text-black hover:text-black focus:text-black' );

?>
<li role="none">
<?php if ( ( $item['type'] ?? '' ) === 'mega_menu' ) : ?>
	<?php
	// Mega menu item (Services or Cities)
	$container_id = ( $item['mega_menu_type'] ?? '' ) === 'services' ? 'services-dropdown-container' : 'service-areas-dropdown-container';
	$btn_class    = ( $item['mega_menu_type'] ?? '' ) === 'services' ? 'services-dropdown-btn' : 'service-areas-dropdown-btn';
	?>
	<div class="relative" id="<?php echo esc_attr( $container_id ); ?>">
		<div class="<?php echo esc_attr( $menu_item_classes ); ?>" data-item="<?php echo esc_attr( $item['title'] ?? '' ); ?>" role="menuitem" aria-haspopup="true" aria-expanded="false" aria-label="<?php echo esc_attr( $item['title'] ?? '' ); ?> menu" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
			<a href="<?php echo esc_url( $item['href'] ?? '#' ); ?>" class="<?php echo esc_attr( $text_classes ); ?>">
				<?php echo esc_html( $item['title'] ?? '' ); ?>
			</a>
			<button class="ml-1 border-none bg-transparent p-0 focus:outline-none <?php echo esc_attr( $btn_class ); ?>" aria-label="Toggle <?php echo esc_attr( strtolower( $item['title'] ?? '' ) ); ?> dropdown">
				<img src="<?php echo esc_url( $chevron_icon ); ?>" alt="" class="h-4 w-4 text-current transition-transform duration-200 chevron-icon" role="presentation" loading="lazy" decoding="async" />
			</button>
		</div>
		<?php
		// Render mega menu dropdown
		if ( ( $item['mega_menu_type'] ?? '' ) === 'services' ) {
			sunnysideac_render_services_mega_menu();
		} else {
			sunnysideac_render_service_areas_mega_menu();
		}
		?>
	</div>
<?php else : ?>
	<?php
	// Regular link button
	$button_classes = 'cursor-pointer rounded-full px-6 py-3 transition-colors duration-200 focus:ring-2 focus:ring-[#fbbf24] focus:ring-offset-2 focus:outline-none nav-item';
	if ( $is_active ) {
		$button_classes .= ' bg-[#fbbf24]';
	} else {
		$button_classes .= ' hover:bg-[#fbbf24] bg-[#fef3c7]';
	}

	$button_text_classes = ' text-lg font-medium whitespace-nowrap transition-colors duration-200 ' . ( $is_active ? 'text-[#9a3412]' : 'text-black' );
	?>
	<button class="<?php echo esc_attr( $button_classes ); ?>" data-item="<?php echo esc_attr( $item['title'] ?? '' ); ?>" data-href="<?php echo esc_url( $item['href'] ?? '#' ); ?>" role="menuitem" aria-label="Navigate to <?php echo esc_attr( $item['title'] ?? '' ); ?>" tabindex="0" <?php echo $is_active ? 'aria-current="page"' : ''; ?>>
		<span class="<?php echo esc_attr( $button_text_classes ); ?>">
			<?php echo esc_html( $item['title'] ?? '' ); ?>
		</span>
	</button>
<?php endif; ?>
</li>
