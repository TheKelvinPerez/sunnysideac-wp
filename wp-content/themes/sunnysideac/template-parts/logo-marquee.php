<?php
/**
 * Logo Marquee Component
 * Displays a continuous scrolling marquee of company logos
 */

// Define company logos
$company_logos = array(
	array(
		'id'      => 1,
		'name'    => 'Bryant',
		'src'     => sunnysideac_asset_url( 'assets/images/optimize/Bryant-Logo.webp' ),
		'alt'     => 'Bryant HVAC logo',
		'website' => home_url( '/brands/bryant/' ),
	),
	array(
		'id'      => 2,
		'name'    => 'Carrier',
		'src'     => sunnysideac_asset_url( 'assets/images/optimize/Carrier-Logo.webp' ),
		'alt'     => 'Carrier air conditioning logo',
		'website' => home_url( '/brands/carrier/' ),
	),
	array(
		'id'      => 3,
		'name'    => 'Goodman',
		'src'     => sunnysideac_asset_url( 'assets/images/optimize/Goodman-Logo.webp' ),
		'alt'     => 'Goodman HVAC logo',
		'website' => home_url( '/brands/goodman/' ),
	),
	array(
		'id'      => 4,
		'name'    => 'Lennox',
		'src'     => sunnysideac_asset_url( 'assets/images/optimize/Lennox-Logo.webp' ),
		'alt'     => 'Lennox HVAC logo',
		'website' => home_url( '/brands/lennox/' ),
	),
	array(
		'id'      => 5,
		'name'    => 'Rheem',
		'src'     => sunnysideac_asset_url( 'assets/images/optimize/Rheem-Logo.webp' ),
		'alt'     => 'Rheem HVAC logo',
		'website' => home_url( '/brands/rheem/' ),
	),
	array(
		'id'      => 6,
		'name'    => 'Trane',
		'src'     => sunnysideac_asset_url( 'assets/images/optimize/Trane-Logo.webp' ),
		'alt'     => 'Trane air conditioning logo',
		'website' => home_url( '/brands/trane/' ),
	),
	array(
		'id'      => 7,
		'name'    => 'Daikin',
		'src'     => sunnysideac_asset_url( 'assets/images/optimize/daikin-logo.webp' ),
		'alt'     => 'Daikin HVAC logo',
		'website' => home_url( '/brands/daikin/' ),
	),
);

$icon_url = get_template_directory_uri() . '/assets/images/home-page/trusted-brands-section-icon.svg';
?>

<section
	class="w-full overflow-hidden rounded-[20px] bg-white py-12"
	aria-label="Trusted brands"
>
	<div class="mx-auto max-w-7xl px-6 md:px-8 lg:px-10">
		<header class="mb-8 text-center">
			<?php
			get_template_part(
				'template-parts/title',
				null,
				array(
					'icon'  => $icon_url,
					'title' => 'Brands We Service',
					'id'    => 'trusted-brands-heading',
				)
			);
			?>
		</header>

		<div class="relative overflow-hidden rounded-[20px]">
			<div
				id="logo-marquee-container"
				class="flex items-center"
				style="width: fit-content;"
				data-logos="<?php echo esc_attr( wp_json_encode( $company_logos ) ); ?>"
			>
				<!-- Logo instances will be dynamically added here -->
			</div>

			<!-- Left fade overlay -->
			<div
				class="pointer-events-none absolute top-0 left-0 z-10 h-full w-16 bg-gradient-to-r from-white to-transparent md:w-20 lg:w-24"
				aria-hidden="true"
			></div>

			<!-- Right fade overlay -->
			<div
				class="pointer-events-none absolute top-0 right-0 z-10 h-full w-16 bg-gradient-to-r from-transparent to-white md:w-20 lg:w-24"
				aria-hidden="true"
			></div>
		</div>
	</div>
</section>