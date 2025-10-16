<?php
/**
 * Footer Services Subsection Component
 * Links to various service pages
 */

// Get services from arguments passed from parent template
$services = $args['services'] ?? [];
?>

<nav class="space-y-4" aria-label="Our Services">
	<h3 class="text-xl font-semibold text-gray-900 sm:text-2xl">
		Our Services
	</h3>

	<ul class="space-y-3">
		<?php foreach ($services as $index => $service): ?>
			<li>
				<a
					href="<?php echo esc_url($service['href']); ?>"
					class="font-light text-gray-700 hover:text-gray-900 hover:underline focus:underline focus:outline-2 focus:outline-blue-500 transition-colors duration-200"
				>
					<?php echo esc_html($service['text']); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>

	<!-- Additional Service CTA -->
	<div class="pt-4 mt-4 border-t border-gray-200">
		<a
			href="/contact"
			class="inline-flex items-center space-x-2 text-sm font-medium text-[#F79E37] hover:text-[#E64B30] transition-colors duration-200"
		>
			<span>View All Services</span>
			<svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
				<path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
			</svg>
		</a>
	</div>
</nav>