<?php
/**
 * Reusable FAQ Component Template Part
 *
 * Displays an interactive FAQ accordion with JSON-LD schema markup and semantic HTML
 *
 * Usage:
 * get_template_part('template-parts/faq-component', null, [
 *     'faq_data' => [
 *         ['question' => 'Question 1?', 'answer' => 'Answer 1'],
 *         ['question' => 'Question 2?', 'answer' => 'Answer 2'],
 *     ],
 *     'title' => 'Frequently Asked Questions',          // Optional, defaults to 'Frequently Asked Questions!'
 *     'mobile_title' => 'FAQ',                          // Optional, defaults to 'FAQ'
 *     'subheading' => 'Got Questions? We\'ve Got Answers!', // Optional
 *     'description' => 'Find answers to common questions', // Optional
 *     'show_schema' => true,                            // Optional, defaults to true
 *     'section_id' => 'faq-section',                    // Optional, defaults to 'faq-section'
 * ]);
 */

// Extract args with defaults
$faq_data     = $args['faq_data'] ?? array();
$title        = $args['title'] ?? 'Frequently Asked Questions!';
$mobile_title = $args['mobile_title'] ?? 'FAQ';
$subheading   = $args['subheading'] ?? 'Got Questions? We\'ve Got Answers!';
$description  = $args['description'] ?? '';
$show_schema  = $args['show_schema'] ?? true;
$section_id   = $args['section_id'] ?? 'faq-section';

// Return early if no FAQ data provided
if ( empty( $faq_data ) ) {
	return;
}

// Add unique IDs if not present
foreach ( $faq_data as $index => &$faq ) {
	if ( ! isset( $faq['id'] ) ) {
		$faq['id'] = $index + 1;
	}
}
unset( $faq ); // Break reference

// Icons data
$icons = array(
	'faq_section' => sunnysideac_asset_url( 'assets/images/home-page/faq-section-icon.svg' ),
	'faq_chevron' => sunnysideac_asset_url( 'assets/images/home-page/faq-chevron-down-circle.svg' ),
);

// Generate JSON-LD FAQ Schema (prevent duplicates)
if ( $show_schema && ! defined( 'SUNNYSIDE_FAQ_SCHEMA_GENERATED' ) ) {
	define( 'SUNNYSIDE_FAQ_SCHEMA_GENERATED', true );

	$faq_schema_items = array();
	foreach ( $faq_data as $faq ) {
		$faq_schema_items[] = array(
			'@type' => 'Question',
			'name' => $faq['question'],
			'acceptedAnswer' => array(
				'@type' => 'Answer',
				'text' => $faq['answer']
			)
		);
	}
	$faq_schema_data = array(
		'@context' => 'https://schema.org',
		'@type' => 'FAQPage',
		'mainEntity' => $faq_schema_items
	);
	echo '<script type="application/ld+json">' . wp_json_encode( $faq_schema_data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE ) . '</script>' . "\n";
}
?>

<section class="w-full rounded-2xl bg-white px-4 py-12 md:px-10 md:py-16 lg:py-20" id="<?php echo esc_attr( $section_id ); ?>" aria-labelledby="<?php echo esc_attr( $section_id ); ?>-heading">
	<div class="mx-auto max-w-7xl">
		<!-- Header -->
		<header class="mb-12 text-center md:mb-16">
			<?php
			get_template_part(
				'template-parts/title',
				null,
				array(
					'icon'         => $icons['faq_section'],
					'title'        => $title,
					'mobile_title' => $mobile_title,
					'id'           => esc_attr( $section_id ) . '-heading',
				)
			);
			?>

			<?php if ( ! empty( $subheading ) ) : ?>
				<?php
				get_template_part(
					'template-parts/subheading',
					null,
					array(
						'text'  => $subheading,
						'class' => 'mb-4 text-gray-600 md:text-4xl md:leading-tight lg:text-5xl',
					)
				);
				?>
			<?php endif; ?>

			<?php if ( ! empty( $description ) ) : ?>
				<p class="text-base font-normal text-gray-700 md:text-lg">
					<?php echo esc_html( $description ); ?>
				</p>
			<?php endif; ?>
		</header>

		<!-- FAQ Grid with Native Details Elements -->
		<div class="grid gap-4 md:gap-6 max-w-4xl mx-auto">
			<?php foreach ( $faq_data as $faq ) : ?>
				<details class="faq-details group w-full rounded-[20px] border-2 border-transparent bg-[#f6f6f6] transition-all duration-300 ease-in-out hover:shadow-md open:bg-[#ffeac0] open:border-[#fed7aa]">
					<summary class="flex items-start justify-between p-6 cursor-pointer list-none">
						<h3 class="pr-4 text-lg leading-relaxed font-semibold text-black md:text-xl">
							<?php echo esc_html( $faq['question'] ); ?>
						</h3>

						<div class="faq-chevron h-[35px] w-[35px] flex-shrink-0 rounded-full bg-gradient-to-l from-[#F79E37] to-[#E5462F] shadow-md transition-all duration-300 ease-in-out group-hover:scale-110 group-open:shadow-lg">
							<img class="chevron-icon h-full w-full transition-transform duration-300 ease-in-out"
								alt="Toggle FAQ"
								src="<?php echo esc_url( $icons['faq_chevron'] ); ?>"
								loading="lazy"
								decoding="async" />
						</div>
					</summary>

					<div class="faq-answer px-6 pb-6 text-base leading-relaxed font-normal text-gray-700 md:text-lg">
						<div>
							<?php echo esc_html( $faq['answer'] ); ?>
						</div>
					</div>
				</details>
			<?php endforeach; ?>
		</div>
	</div>
</section>

<style>
/* Remove default details marker */
.faq-details summary::-webkit-details-marker,
.faq-details summary::marker {
	display: none;
}

/* Enhanced open state styling */
.faq-details[open] {
	background-color: #ffeac0;
	border-color: #fed7aa;
}

/* Smooth animation for content reveal */
.faq-details summary {
	transition: margin-bottom 200ms ease-out;
}

.faq-details[open] summary {
	margin-bottom: 0;
}

/* Chevron rotation on open */
.faq-details[open] .chevron-icon {
	transform: rotate(180deg);
}

/* Enhanced shadow on open */
.faq-details[open] .faq-chevron {
	box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}

/* Hover effect for chevron */
.faq-details:hover .faq-chevron {
	transform: scale(1.1);
}

/* Ensure smooth transitions */
.faq-details,
.faq-chevron,
.chevron-icon {
	transition: all 300ms ease-in-out;
}
</style>
