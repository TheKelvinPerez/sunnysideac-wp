<?php
/**
 * Footer Contact Info Component
 * Reusable footer contact info section - works like a React component
 *
 * @param array $props Component properties
 *   - 'title' (string) Section title
 *   - 'class' (string) Additional CSS classes
 */

$props = wp_parse_args($args, [
    'title' => 'Contact Us',
    'class' => ''
]);

// Contact info data
$contact_info = [
	[
		'id'      => 'phone',
		'icon'    => sunnysideac_asset_url('assets/images/home-page/footer/phone-num-icon.svg'),
		'label'   => 'Phone',
		'content' => SUNNYSIDE_PHONE_DISPLAY,
		'href'    => SUNNYSIDE_TEL_HREF,
	],
	[
		'id'      => 'address',
		'icon'    => sunnysideac_asset_url('assets/images/home-page/footer/location-footer-icon.svg'),
		'label'   => 'Address',
		'content' => SUNNYSIDE_ADDRESS_STREET . '<br />' . SUNNYSIDE_ADDRESS_CITY . ', ' . SUNNYSIDE_ADDRESS_STATE . ' ' . SUNNYSIDE_ADDRESS_ZIP,
		'href'    => SUNNYSIDE_ADDRESS_GOOGLE_MAPS_URL,
	],
	[
		'id'      => 'email',
		'icon'    => sunnysideac_asset_url('assets/images/home-page/footer/mail-footer-icon.svg'),
		'label'   => 'Email',
		'content' => SUNNYSIDE_EMAIL_ADDRESS,
		'href'    => SUNNYSIDE_MAILTO_HREF,
	],
	[
		'id'      => 'website',
		'icon'    => sunnysideac_asset_url('assets/images/home-page/footer/web-footer-icon.svg'),
		'label'   => 'Website',
		'content' => 'sunnyside247ac.com',
		'href'    => 'https://sunnyside247ac.com',
	],
];
?>

<section class="footer-contact-info <?php echo esc_attr($props['class']); ?>" aria-labelledby="contact-heading">
	<h3
		id="contact-heading"
		class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl"
	>
		<?php echo esc_html($props['title']); ?>
	</h3>

	<div class="space-y-4">
		<?php foreach ($contact_info as $item): ?>
			<div class="flex items-start space-x-3">
				<?php if ($item['icon']): ?>
					<img
						class="mt-1 h-5 w-5 flex-shrink-0"
						alt="<?php echo esc_attr($item['label'] . ' icon'); ?>"
						src="<?php echo esc_url($item['icon']); ?>"
					/>
				<?php endif; ?>

				<div class="flex-1">
					<?php if ($item['href']): ?>
						<a
							href="<?php echo esc_url($item['href']); ?>"
							class="font-light text-gray-700 hover:text-gray-900 hover:underline focus:outline-2 focus:outline-blue-500"
							<?php if ($item['id'] === 'website' || $item['id'] === 'address'): ?>
								target="_blank"
								rel="noopener noreferrer"
							<?php endif; ?>
						>
							<?php echo $item['content']; // HTML content allowed for address with <br> tag ?>
						</a>
					<?php else: ?>
						<span class="font-light text-gray-700">
							<?php echo $item['content']; // HTML content allowed for address with <br> tag ?>
						</span>
					<?php endif; ?>
				</div>
			</div>
		<?php endforeach; ?>
	</div>
</section>
