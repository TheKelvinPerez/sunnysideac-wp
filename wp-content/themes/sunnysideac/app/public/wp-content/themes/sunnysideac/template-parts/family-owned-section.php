<?php
/**
 * Family Owned Section Template Part
 * Displays the family-owned legacy section with images and story content
 */

// Images data - optimized with WebP/AVIF support
$images = array(
	'family_owned_icon' => sunnysideac_asset_url( 'assets/images/home-page/family-owned-icon.svg' ),
	'mom_and_dad'       => array(
		'avif' => sunnysideac_asset_url( 'assets/images/optimize/mom-and-dad.avif' ),
		'webp' => sunnysideac_asset_url( 'assets/images/optimize/mom-and-dad.webp' ),
		'png'  => sunnysideac_asset_url( 'assets/images/optimize/mom-and-dad.png' ),
	),
	'kelvins_picture'   => array(
		'avif' => sunnysideac_asset_url( 'assets/images/optimize/kelvins-picture.avif' ),
		'webp' => sunnysideac_asset_url( 'assets/images/optimize/kelvins-picture.webp' ),
		'png'  => sunnysideac_asset_url( 'assets/images/optimize/kelvins-picture.png' ),
	),
);

// Story content paragraphs
$story_paragraphs = array(
	'In the early 1980s, Florentino left the Dominican Republic for New York, determined to build a better future. There, he discovered a passion for refrigeration and air conditioning, working hands-on as a technician and honing his skills in the field.',

	'By the early 2000s, he moved his family to Miami, Florida, where he continued to grow his expertise. It was here that Florentino\'s unwavering determination truly shone through one of his greatest challenges yet—earning his contractor\'s license. What many don\'t know is that this achievement came after a testament to his incredible perseverance. Florentino attempted the contractor\'s exam four times, each failure only strengthening his resolve. On his fifth and final attempt—the last chance allowed—he passed. This moment perfectly encapsulates the spirit that drives everything he does: never giving up, no matter how difficult the path becomes.',

	'In 2014, armed with his hard-earned contractor\'s license and decades of experience, Florentino founded Sunny Side 24/7 AC—a family-owned and operated business built on trust, quality, and personal care.',

	'Today, the company is still proudly run by the family. Florentino leads all field operations, bringing over 1,000+ successful installations worth of experience and a deep commitment to customer satisfaction. His wife manages the administrative side, ensuring the business runs smoothly day-to-day, while their son, Kelvin, leads technology and sales, modernizing the company\'s operations and expanding its reach.',

	'Clients know they can count on Sunny Side 24/7 AC not just for reliable cooling solutions, but for the warmth and integrity of a family that treats every customer like their own. The same perseverance that carried Florentino through five contractor\'s exams now ensures that every project—from the first handshake to the final inspection—is completed with passion, precision, and a promise of lasting comfort.',
);
?>

<section class="w-full">
	<div class="mx-auto max-w-7xl">
		<div class="overflow-hidden rounded-[20px] bg-white">
			<!-- Header Section -->
			<div class="px-6 pt-8 pb-6 text-center sm:px-8 lg:px-12">
				<div class="mb-4">
					<?php
					get_template_part(
						'template-parts/title',
						null,
						array(
							'icon'  => $images['family_owned_icon'] ?? '',
							'title' => 'A Family-Owned Legacy of',
						)
					);
					?>
					<?php
					get_template_part(
						'template-parts/subheading',
						null,
						array(
							'text'  => 'Quality, Care & Comfort',
							'class' => 'mx-auto max-w-4xl text-lg font-normal text-gray-600 md:text-4xl md:leading-tight',
						)
					);
					?>
				</div>
			</div>

			<!-- Content Grid -->
			<div class="grid grid-cols-1 gap-8 px-6 pb-12 sm:px-8 lg:grid-cols-2 lg:px-12">
				<!-- Images Section -->
				<div class="flex items-center justify-center py-20 md:py-0">
					<div class="relative flex items-center justify-center">
						<!-- Mom and Dad - Left Image -->
						<div class="relative -top-24 z-10 md:-top-40">
							<?php echo sunnysideac_responsive_image(
								array(
									'avif' => 'assets/images/optimize/mom-and-dad.avif',
									'webp' => 'assets/images/optimize/mom-and-dad.webp',
									'png'  => 'assets/images/optimize/mom-and-dad.png',
								),
								array(
									'alt' => 'Florentino and his wife - founders of Sunny Side AC',
									'class' => 'h-full w-full rounded-[20px] object-cover shadow-md sm:h-72 sm:w-72 lg:h-90 lg:w-90',
									'loading' => 'lazy'
								)
							); ?>
						</div>

						<!-- Kelvin's Picture - Right Image with slight overlap -->
						<div class="relative top-8 right-6 z-20 md:top-24 md:-left-10">
							<?php echo sunnysideac_responsive_image(
								array(
									'avif' => 'assets/images/optimize/kelvins-picture.avif',
									'webp' => 'assets/images/optimize/kelvins-picture.webp',
									'png'  => 'assets/images/optimize/kelvins-picture.png',
								),
								array(
									'alt' => 'Kelvin - son and technology lead at Sunny Side AC',
									'class' => 'h-full w-full rounded-[20px] object-cover shadow-lg sm:h-72 sm:w-72 lg:h-90 lg:w-90',
									'loading' => 'lazy'
								)
							); ?>
						</div>
					</div>
				</div>

				<!-- Story Content -->
				<div class="flex flex-col justify-center">
					<div class="space-y-4 text-center text-sm leading-relaxed text-gray-700 md:text-right lg:text-base">
						<?php foreach ( $story_paragraphs as $paragraph ) : ?>
							<p>
								<?php echo esc_html( $paragraph ); ?>
							</p>
						<?php endforeach; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>