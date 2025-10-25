<?php
/**
 * Work Process Component
 * Displays the 4-step work process: Consultation, Inspection, Repair, Maintenance
 */

// Define process steps - optimized data structure
$process_steps = array(
	array(
		'title'    => 'CONSULTATION',
		'subtitle' => 'Free',
	),
	array(
		'title'    => 'INSPECTION',
		'subtitle' => 'Assessment',
	),
	array(
		'title'    => 'REPAIR',
		'subtitle' => 'Installation',
	),
	array(
		'title'    => 'MAINTENANCE',
		'subtitle' => 'Support',
	),
);

// Common styles (extracted from redundant data)
$common_bg_color = 'bg-gray-50';
$common_icon_bg  = 'bg-gradient-to-br from-orange-200 to-orange-300';

$cog_wheel_icon_url   = get_template_directory_uri() . '/assets/images/home-page/work-process-cog-wheel.svg';
$our_process_icon_url = get_template_directory_uri() . '/assets/images/home-page/our-process-icon.svg';
?>

<section
	class="w-full"
	role="main"
	aria-labelledby="work-process-heading"
>
	<div class="mx-auto max-w-7xl rounded-[20px] bg-white p-10">
		<!-- Header -->
		<header class="mb-12 text-center">
			<?php
			get_template_part(
				'template-parts/title',
				null,
				array(
					'icon'  => $our_process_icon_url,
					'title' => 'Our AC Service Process | Professional HVAC Installation South Florida',
					'id'    => 'work-process-heading',
				)
			);
			?>
			<?php
			get_template_part(
				'template-parts/subheading',
				null,
				array(
					'text'  => 'Your Comfort, Our Process',
					'class' => 'text-gray-600 md:text-4xl md:leading-tight',
				)
			);
			?>
		</header>

		<!-- Process Steps Grid -->
		<div class="grid grid-cols-1 gap-4 md:grid-cols-2 xl:grid-cols-4 xl:gap-6">
			<?php foreach ( $process_steps as $index => $step ) : ?>
				<?php
				$step_number = $index + 1;
				$step_id     = 'step-' . $step_number;
				?>
				<article
					class="group"
					role="article"
					aria-labelledby="<?php echo esc_attr( $step_id ); ?>-title"
				>
					<div class="<?php echo esc_attr( $common_bg_color ); ?> flex h-full flex-col items-center rounded-2xl px-4 py-8 text-center transition-all duration-300 hover:scale-105 hover:bg-orange-50 hover:shadow-lg">
						<!-- Gear Icon with Number -->
						<div class="mb-6">
							<div class="relative inline-block opacity-100 transition-all duration-300 hover:opacity-60 hover:group-hover:opacity-100">
								<img
									src="<?php echo esc_url( $cog_wheel_icon_url ); ?>"
									alt="Process step cog wheel"
									class="h-auto w-full md:h-20 md:w-20"
								/>
								<div class="absolute inset-0 flex items-center justify-center">
									<span
										class="text-xl font-bold text-orange-500 transition-colors duration-300 hover:text-orange-300 hover:group-hover:text-orange-500 md:text-2xl"
										aria-label="Step <?php echo esc_attr( $step_number ); ?>"
									>
										<?php echo esc_html( $step_number ); ?>
									</span>
								</div>
							</div>
						</div>

						<!-- Text Content -->
						<div class="flex flex-col items-center space-y-1">
							<p class="text-lg font-thin text-gray-600">
								<?php echo esc_html( $step['subtitle'] ); ?>
							</p>
							<div
								id="<?php echo esc_attr( $step_id ); ?>-title"
								class="text-lg leading-tight font-bold text-gray-900 md:text-xl"
								role="heading"
								aria-level="4"
							>
								<?php echo esc_html( $step['title'] ); ?>
							</div>
						</div>
					</div>
				</article>
			<?php endforeach; ?>
		</div>
	</div>
</section>
