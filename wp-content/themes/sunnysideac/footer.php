
<footer class="bg-gray-900 text-white mt-16">
	<div class="container mx-auto px-4 py-12">
		<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
			<!-- About Section -->
			<div>
				<h3 class="text-xl font-bold mb-4"><?php bloginfo( 'name' ); ?></h3>
				<p class="text-gray-400">
					<?php
					$description = get_bloginfo( 'description' );
					echo $description ? esc_html( $description ) : 'Your trusted HVAC service provider. Professional, reliable, and always here when you need us.';
					?>
				</p>
			</div>

			<!-- Quick Links -->
			<div>
				<h3 class="text-xl font-bold mb-4">Quick Links</h3>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'space-y-2',
						'fallback_cb'    => function () {
							echo '<ul class="space-y-2">';
							echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="text-gray-400 hover:text-white transition-colors">Home</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/services' ) ) . '" class="text-gray-400 hover:text-white transition-colors">Services</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/about' ) ) . '" class="text-gray-400 hover:text-white transition-colors">About Us</a></li>';
							echo '<li><a href="' . esc_url( home_url( '/contact' ) ) . '" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>';
							echo '</ul>';
						},
					)
				);
				?>
			</div>

			<!-- Contact Info -->
			<div>
				<h3 class="text-xl font-bold mb-4">Contact Us</h3>
				<div class="space-y-2 text-gray-400">
					<p>Email: info@sunnyside247ac.com</p>
				<p>Phone: <?php echo SUNNYSIDE_PHONE_DISPLAY; ?></p>
					<p class="text-yellow-400 font-semibold">24/7 Emergency Service</p>
				</div>
			</div>
		</div>

		<div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
			<p>&copy; <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.</p>
			<p class="mt-2 text-sm">Built with Vite + Tailwind CSS v4</p>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>

<script>
// Mobile menu toggle
document.addEventListener('DOMContentLoaded', function() {
	const menuButton = document.getElementById('mobile-menu-button');
	const mobileMenu = document.getElementById('mobile-menu');

	if (menuButton && mobileMenu) {
		menuButton.addEventListener('click', function() {
			mobileMenu.classList.toggle('hidden');
		});
	}
});
</script>

</body>
</html>
