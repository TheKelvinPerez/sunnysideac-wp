<?php
/**
 * Front Page Template
 * This template is used when you set a static homepage in Settings > Reading
 */

get_header(); ?>

<main class="min-h-screen bg-gray-50">
	<!-- Mobile constraint wrapper - applies 20px padding on mobile only -->
	<div class="lg:px-0 max-w-7xl mx-auto">
		<section class="flex gap-10 flex-col">
			<!-- Hero Section -->
			<?php get_template_part( 'template-parts/hero-section' ); ?>

			<!-- Logo Marquee Section -->
			<?php get_template_part( 'template-parts/logo-marquee' ); ?>

			<!-- Work Process Section -->
			<?php get_template_part( 'template-parts/work-process' ); ?>

			<!-- Why Choose Us Section -->
			<?php get_template_part( 'template-parts/why-choose-us' ); ?>

			<!-- Family Owned Section -->
		<?php get_template_part( 'template-parts/family-owned-section' ); ?>

			<!-- Customer Reviews Section -->
		<?php get_template_part( 'template-parts/customer-reviews' ); ?>

			<!-- Areas We Serve Section -->
			<?php get_template_part( 'template-parts/areas-we-serve' ); ?>

			<!-- Blog Section -->
			<?php get_template_part( 'template-parts/blog-section' ); ?>

			<!-- Our Projects Section -->
			<?php get_template_part( 'template-parts/our-projects' ); ?>

			<!-- FAQ Section -->
			<?php
			// FAQ data for homepage
			$homepage_faqs = array(
				array(
					'question' => 'How often should I service my HVAC system?',
					'answer'   => 'We recommend servicing your HVAC system at least twice a year - once in spring before cooling season and once in fall before heating season. Regular maintenance helps prevent breakdowns, improves energy efficiency, and extends your system\'s lifespan.',
				),
				array(
					'question' => 'What are signs that my AC needs repair?',
					'answer'   => 'Common warning signs include weak airflow, warm air blowing from vents, unusual noises like grinding or squealing, foul odors, frequent cycling on and off, high humidity indoors, and rising energy bills. If you notice any of these issues, contact us for professional diagnosis.',
				),
				array(
					'question' => 'How long does AC installation typically take?',
					'answer'   => 'Standard AC installation usually takes 4-8 hours for a straightforward replacement. However, new installations or complex setups may take 1-2 days. We\'ll provide an accurate timeline during your consultation based on your specific needs and home layout.',
				),
				array(
					'question' => 'What size AC unit do I need for my home?',
					'answer'   => 'AC sizing depends on square footage, ceiling height, insulation, windows, and local climate. A unit too small won\'t cool effectively, while oversized units cycle on and off frequently, wasting energy. Our technicians perform load calculations to determine the perfect size for your home.',
				),
				array(
					'question' => 'How can I improve my AC\'s energy efficiency?',
					'answer'   => 'Regular maintenance, changing filters monthly, sealing air leaks, using programmable thermostats, ensuring proper insulation, and keeping vents unblocked all improve efficiency. Consider upgrading to a high-efficiency ENERGY STAR unit for maximum savings.',
				),
				array(
					'question' => 'When should I replace my AC instead of repairing it?',
					'answer'   => 'Consider replacement if your unit is over 10-15 years old, needs frequent repairs, uses R-22 refrigerant (being phased out), or repair costs exceed 50% of replacement cost. Newer units offer better efficiency, reliability, and warranty coverage.',
				),
				array(
					'question' => 'Do you offer emergency AC repair services?',
					'answer'   => 'Yes! We provide 24/7 emergency AC repair services because we understand that AC failures don\'t wait for business hours. Our experienced technicians are available nights, weekends, and holidays to restore your comfort quickly.',
				),
				array(
					'question' => 'What brands of AC equipment do you install and service?',
					'answer'   => 'We install and service all major HVAC brands including Carrier, Trane, Lennox, Rheem, Goodman, American Standard, and more. Our technicians are factory-trained and certified to work on various manufacturers\' equipment.',
				),
				array(
					'question' => 'How often should I change my air filters?',
					'answer'   => 'Standard 1-inch filters should be changed every 1-3 months, depending on usage, pets, and allergies. Thicker pleated filters may last 3-6 months. Check filters monthly - if they appear dirty or clogged, replace them immediately to maintain proper airflow and system efficiency.',
				),
				array(
					'question' => 'Do you provide financing options for new AC systems?',
					'answer'   => 'Yes, we offer flexible financing options to make AC installation affordable. We work with various lenders to provide competitive rates and terms that fit your budget. Ask our team about available financing programs during your consultation.',
				),
			);

			get_template_part(
				'template-parts/faq-component',
				null,
				array(
					'faq_data'     => $homepage_faqs,
					'title'        => 'Frequently Asked Questions!',
					'mobile_title' => 'FAQ',
					'subheading'   => 'Got Questions? We\'ve Got Answers!',
					'description'  => 'Find answers to common questions about our AC services, installation, and maintenance.',
					'show_schema'  => true,
					'section_id'   => 'faq-section',
				)
			);
			?>

			<!-- Contact Us Section -->
			<?php get_template_part( 'template-parts/contact-us' ); ?>
		</section>
	</div>

</main>

<?php get_footer(); ?>
