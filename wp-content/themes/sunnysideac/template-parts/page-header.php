<?php
/**
 * Page Header Component
 * Reusable header with breadcrumbs, title, description, and CTA buttons
 *
 * Usage:
 * get_template_part('template-parts/page-header', null, [
 *     'breadcrumbs' => [
 *         ['name' => 'Home', 'url' => home_url('/')],
 *         ['name' => 'Services', 'url' => home_url('/services/')],
 *         ['name' => 'AC Repair', 'url' => ''] // Empty URL for current page
 *     ],
 *     'title' => 'HVAC Services in Miami',
 *     'description' => 'Professional heating, cooling, and air quality services',
 *     'show_ctas' => true, // Optional, defaults to true
 *     'bg_color' => 'white', // Optional: 'white' or 'gradient', defaults to 'white'
 * ]);
 */

// Extract args
$breadcrumbs = $args['breadcrumbs'] ?? array();
$title       = $args['title'] ?? '';
$description = $args['description'] ?? '';
$show_ctas   = $args['show_ctas'] ?? true;
$bg_color    = $args['bg_color'] ?? 'white';

// Determine background class
$bg_class = $bg_color === 'gradient'
	? 'bg-gradient-to-r from-[#fb9939] to-[#e5462f]'
	: 'bg-white';

// Determine text colors based on background
$breadcrumb_color     = $bg_color === 'gradient' ? 'text-white/80' : 'text-gray-600';
$breadcrumb_hover     = $bg_color === 'gradient' ? 'hover:text-white' : 'hover:text-orange-500';
$breadcrumb_active    = $bg_color === 'gradient' ? 'text-white font-semibold' : 'text-orange-500 font-semibold';
$breadcrumb_separator = $bg_color === 'gradient' ? 'text-white/60' : 'text-gray-400';
$description_color    = $bg_color === 'gradient' ? 'text-white/90' : 'text-gray-600';
?>

<!-- Page Header - Breadcrumbs & Title -->
<header class="entry-header <?php echo esc_attr( $bg_class ); ?> rounded-[20px] p-6 md:p-10 mb-6">
	<?php if ( ! empty( $breadcrumbs ) ) : ?>
		<!-- Breadcrumbs -->
		<nav aria-label="Breadcrumb" class="mb-6 flex justify-center" itemscope itemtype="https://schema.org/BreadcrumbList">
			<ol class="flex flex-wrap items-center gap-2 text-sm <?php echo esc_attr( $breadcrumb_color ); ?>">
				<?php
				$position = 1;
				$total    = count( $breadcrumbs );
				foreach ( $breadcrumbs as $crumb ) :
					$is_last    = ( $position === $total );
					$crumb_name = $crumb['name'] ?? '';
					$crumb_url  = $crumb['url'] ?? '';
					?>
					<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
						<?php if ( ! empty( $crumb_url ) && ! $is_last ) : ?>
							<a itemprop="item" href="<?php echo esc_url( $crumb_url ); ?>" class="<?php echo esc_attr( $breadcrumb_hover ); ?> transition-colors">
								<span itemprop="name"><?php echo esc_html( $crumb_name ); ?></span>
							</a>
						<?php else : ?>
							<span itemprop="name" class="<?php echo esc_attr( $breadcrumb_active ); ?>"><?php echo esc_html( $crumb_name ); ?></span>
						<?php endif; ?>
						<meta itemprop="position" content="<?php echo esc_attr( $position ); ?>">
					</li>
					<?php if ( ! $is_last ) : ?>
						<li class="<?php echo esc_attr( $breadcrumb_separator ); ?>">/</li>
					<?php endif; ?>
					<?php
					++$position;
				endforeach;
				?>
			</ol>
		</nav>
	<?php endif; ?>

	<?php if ( ! empty( $title ) ) : ?>
		<!-- Main Title with Gradient -->
		<div class="text-center mb-8">
			<h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold leading-tight mb-4">
				<?php if ( $bg_color === 'white' ) : ?>
					<span class="bg-gradient-to-r from-[#fb9939] to-[#e5462f] bg-clip-text text-transparent">
						<?php echo esc_html( $title ); ?>
					</span>
				<?php else : ?>
					<span class="bg-gradient-to-r from-white to-white/80 bg-clip-text text-transparent">
						<?php echo esc_html( $title ); ?>
					</span>
				<?php endif; ?>
			</h1>

			<?php if ( ! empty( $description ) ) : ?>
				<div class="text-lg md:text-xl <?php echo esc_attr( $description_color ); ?> max-w-4xl mx-auto leading-relaxed">
					<?php echo wp_kses_post( $description ); ?>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<?php if ( $show_ctas ) : ?>
		<!-- CTA Buttons -->
		<?php if ( $bg_color === 'gradient' ) : ?>
			<!-- Gradient Background - Use high contrast buttons with borders -->
			<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
				<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
					class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-white px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none shadow-lg"
					aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
					<span class="text-base lg:text-lg font-bold text-orange-500 whitespace-nowrap">
						Schedule Service Now
					</span>
				</a>

				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
					class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-all hover:scale-105 hover:shadow-xl focus:ring-2 focus:ring-blue-300 focus:ring-offset-2 focus:ring-offset-orange-500 focus:outline-none shadow-lg"
					>
					<span class="text-base lg:text-lg font-bold text-white whitespace-nowrap">
						Get a Free Quote
					</span>
				</a>
			</div>
		<?php else : ?>
			<!-- White Background - Use gradient buttons -->
			<div class="flex flex-col sm:flex-row justify-center gap-4 mt-8">
				<a href="tel:<?php echo esc_attr( SUNNYSIDE_TEL_HREF ); ?>"
					class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#fb9939] to-[#e5462f] px-6 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 focus:outline-none"
					aria-label="Call to schedule service - <?php echo esc_attr( SUNNYSIDE_PHONE_DISPLAY ); ?>">
					<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
						Schedule Service Now
					</span>
				</a>

				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>"
					class="inline-flex items-center justify-center gap-2 rounded-[35px] bg-gradient-to-r from-[#7fcbf2] to-[#594bf7] px-6 py-4 transition-opacity hover:opacity-90 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:outline-none">
					<span class="text-base lg:text-lg font-medium text-white whitespace-nowrap">
						Get a Free Quote
					</span>
				</a>
			</div>
		<?php endif; ?>
	<?php endif; ?>
</header>
