<?php
/**
 * Social Icons Component
 *
 * @param string $size Size of icons: 'sm', 'md', or 'lg' (default: 'md')
 * @param string $direction Layout direction: 'horizontal' or 'vertical' (default: 'horizontal')
 * @param string $class Custom className for the container
 * @param bool $show_labels Whether to show labels for accessibility (default: false)
 * @param string $gap Custom gap between icons
 */

// Get component arguments
$size         = isset( $args['size'] ) ? $args['size'] : 'md';
$direction    = isset( $args['direction'] ) ? $args['direction'] : 'horizontal';
$custom_class = isset( $args['class'] ) ? $args['class'] : '';
$show_labels  = isset( $args['show_labels'] ) ? $args['show_labels'] : false;
$gap          = isset( $args['gap'] ) ? $args['gap'] : '';

// Size-based styling configurations
$size_config = array(
	'sm' => array(
		'container' => 'h-8 w-8',
		'icon'      => 'h-4 w-4',
		'shape'     => 'rounded-lg',
	),
	'md' => array(
		'container' => 'h-10 w-10',
		'icon'      => 'h-full w-full p-2',
		'shape'     => 'rounded-full',
	),
	'lg' => array(
		'container' => 'h-12 w-12',
		'icon'      => 'h-full w-full p-2',
		'shape'     => 'rounded-full',
	),
);

$config = isset( $size_config[ $size ] ) ? $size_config[ $size ] : $size_config['md'];

// Direction-based layout
$direction_class   = $direction === 'horizontal' ? 'flex-row' : 'flex-col';
$gap_class         = $gap ? "gap-{$gap}" : ( $direction === 'horizontal' ? 'gap-3' : 'gap-2' );
$container_classes = "flex {$direction_class} {$gap_class} {$custom_class}";

// Define social links
$social_links = array();

if ( defined( 'SUNNYSIDE_FACEBOOK_URL' ) && SUNNYSIDE_FACEBOOK_URL ) {
	$social_links[] = array(
		'name'      => 'facebook',
		'href'      => SUNNYSIDE_FACEBOOK_URL,
		'icon_path' => get_template_directory_uri() . '/assets/images/images/logos/FacebookIcon.svg',
		'label'     => 'Visit our Facebook page',
	);
}

if ( defined( 'SUNNYSIDE_INSTAGRAM_URL' ) && SUNNYSIDE_INSTAGRAM_URL ) {
	$social_links[] = array(
		'name'      => 'instagram',
		'href'      => SUNNYSIDE_INSTAGRAM_URL,
		'icon_path' => get_template_directory_uri() . '/assets/images/images/logos/InstagramIcon.svg',
		'label'     => 'Visit our Instagram page',
	);
}

if ( defined( 'SUNNYSIDE_YOUTUBE_URL' ) && SUNNYSIDE_YOUTUBE_URL ) {
	$social_links[] = array(
		'name'      => 'youtube',
		'href'      => SUNNYSIDE_YOUTUBE_URL,
		'icon_path' => get_template_directory_uri() . '/assets/images/images/logos/YouTubeIcon.svg',
		'label'     => 'Visit our YouTube channel',
	);
}

if ( defined( 'SUNNYSIDE_LINKEDIN_URL' ) && SUNNYSIDE_LINKEDIN_URL ) {
	$social_links[] = array(
		'name'      => 'linkedin',
		'href'      => SUNNYSIDE_LINKEDIN_URL,
		'icon_path' => get_template_directory_uri() . '/assets/images/images/logos/LinkedInIcon.svg',
		'label'     => 'Visit our LinkedIn page',
	);
}
?>

<div class="<?php echo esc_attr( $container_classes ); ?>">
	<?php foreach ( $social_links as $social ) : ?>
		<div class="<?php echo $show_labels ? 'flex items-center gap-2' : ''; ?>">
			<a
				href="<?php echo esc_url( $social['href'] ); ?>"
				class="flex items-center justify-center <?php echo esc_attr( $config['container'] ); ?> <?php echo esc_attr( $config['shape'] ); ?> group bg-white shadow-md transition-all duration-200 hover:scale-110 hover:bg-[#FEBD3B] hover:shadow-lg focus:ring-2 focus:ring-[#FEBD3B] focus:ring-offset-2 focus:outline-none <?php echo $size === 'sm' ? 'text-gray-700' : ''; ?>"
				aria-label="<?php echo esc_attr( $social['label'] ); ?>"
				target="_blank"
				rel="noopener noreferrer"
			>
				<img
					src="<?php echo esc_url( $social['icon_path'] ); ?>"
					alt=""
					class="<?php echo esc_attr( $config['icon'] ); ?> <?php echo $size === 'sm' ? 'text-current group-hover:text-white' : 'group-hover:text-white'; ?>"
				/>
			</a>
			<?php if ( $show_labels ) : ?>
				<span class="text-sm font-medium text-gray-700">
					<?php echo esc_html( ucfirst( $social['name'] ) ); ?>
				</span>
			<?php endif; ?>
		</div>
	<?php endforeach; ?>
</div>
