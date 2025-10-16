<?php
/**
 * Footer Quick Links Subsection Component
 * Navigation links for quick access to important pages
 */

// Get links from arguments passed from parent template
$links = $args['links'] ?? [];
?>

<nav class="space-y-4" aria-label="Quick Links">
	<h3 class="text-xl font-semibold text-gray-900 sm:text-2xl">
		Quick Links
	</h3>

	<ul class="space-y-3">
		<?php foreach ($links as $index => $link): ?>
			<li>
				<a
					href="<?php echo esc_url($link['href']); ?>"
					class="font-light text-gray-700 hover:text-gray-900 hover:underline focus:underline focus:outline-2 focus:outline-blue-500 transition-colors duration-200"
				>
					<?php echo esc_html($link['text']); ?>
				</a>
			</li>
		<?php endforeach; ?>
	</ul>
</nav>