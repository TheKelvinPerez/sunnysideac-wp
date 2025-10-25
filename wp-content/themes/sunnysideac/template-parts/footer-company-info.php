<?php
/**
 * Footer Company Info Subsection Component
 * Company information with logo and description
 */

// Component data (like props in React)
$images = array(
	'company_logo' => get_template_directory_uri() . '/assets/images/optimize/sunny-side-logo.webp',
);

$company_info = array(
	'name'        => 'Sunny Side Air Conditioning',
	'description' => 'Your trusted HVAC service provider in South Florida. We offer professional, reliable, and affordable air conditioning services with 24/7 emergency support.',
	'tagline'     => 'Keeping South Florida Cool Since 2025',
);
?>

<section class="space-y-6" aria-labelledby="company-heading">
	<!-- Company Logo with Text -->
	<div class="mb-4">
		<a
			href="/"
			class="flex flex-col items-start space-y-3 sm:flex-row sm:items-center sm:space-y-0 sm:space-x-4 transition-opacity duration-300 hover:opacity-80 focus:outline-2 focus:outline-blue-500 focus:rounded-lg"
		>
			<img
				class="h-16 w-auto sm:h-20"
				alt="SunnySide 24/7 AC Logo"
				src="<?php echo esc_url( $images['company_logo'] ); ?>"
				srcset="<?php echo esc_url( $images['company_logo'] ); ?> 123w, <?php echo esc_url( $images['company_logo'] ); ?> 246w"
				sizes="(max-width: 640px) 64px, 80px"
				width="95"
				height="64"
			>
			<div class="flex flex-col">
				<div class="bg-gradient-to-r from-[#FFC13B] to-[#E5462F] bg-clip-text text-2xl font-extrabold text-transparent sm:text-3xl">
					SunnySide
				</div>
				<div class="bg-gradient-to-r from-[#E5462F] to-[#FFC13B] bg-clip-text text-3xl font-extrabold text-transparent sm:text-4xl">
					24/7 AC
				</div>
			</div>
		</a>
	</div>

	<!-- Company Description -->
	<div class="space-y-3">
		<p class="font-normal text-gray-700 leading-relaxed">
			<?php echo esc_html( $company_info['description'] ); ?>
		</p>

		<!-- Additional Company Info -->
		<div class="space-y-2 pt-2">
			<div class="flex items-center space-x-2">
				<div class="h-2 w-2 bg-[#F79E37] rounded-full"></div>
				<span class="text-sm font-normal text-gray-600">Licensed & Insured</span>
			</div>
			<div class="flex items-center space-x-2">
				<div class="h-2 w-2 bg-[#F79E37] rounded-full"></div>
				<span class="text-sm font-normal text-gray-600">24/7 Emergency Service</span>
			</div>
			<div class="flex items-center space-x-2">
				<div class="h-2 w-2 bg-[#F79E37] rounded-full"></div>
				<span class="text-sm font-normal text-gray-600">Satisfaction Guaranteed</span>
			</div>
		</div>
	</div>

	<!-- Social Media -->
	<div class="space-y-4 pt-4">
		<div class="text-xl font-semibold text-gray-900 sm:text-2xl" role="heading" aria-level="4">
			Follow Us:
		</div>

		<?php
		get_template_part(
			'template-parts/social-icons',
			null,
			array(
				'size'      => 'md',
				'direction' => 'horizontal',
				'class'     => 'justify-start',
			)
		);
		?>
	</div>
</section>