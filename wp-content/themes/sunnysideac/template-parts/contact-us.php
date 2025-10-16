<?php
/**
 * Contact Us Section Component
 * Self-contained component with contact form and contact information
 */

// Component data (like props in React)
$company_email = 'kelvin@sunnyside247ac.com';

$service_types = [
	'Air Conditioning Repair',
	'Heating Repair',
	'Installation',
	'Maintenance',
	'Emergency Service',
];

$select_options = [
	'Residential',
	'Commercial',
	'Emergency',
	'Consultation',
];

$images = [
	'contact_us_icon' => sunnysideac_asset_url('assets/images/home-page/contact-us/contact-us-icon.svg'),
	'email_icon'      => sunnysideac_asset_url('assets/images/home-page/contact-us/email-contact-icon.svg'),
	'phone_icon'      => sunnysideac_asset_url('assets/images/home-page/contact-us/phone-contact-icon.svg'),
	'location_icon'   => sunnysideac_asset_url('assets/images/home-page/contact-us/location-contact-icon.svg'),
	'chevron_down'    => sunnysideac_asset_url('assets/images/home-page/contact-us/chevron-down-contact-form.svg'),
];
?>

<section
	class="rounded-2xl bg-white p-5 lg:p-10"
	aria-labelledby="contact-heading"
>
	<!-- Header Section -->
	<header class="mb-8 text-center">
		<?php
		get_template_part('template-parts/title', null, [
			'icon'  => $images['contact_us_icon'],
			'title' => 'Contact Us'
		]);
		?>
		<?php
		get_template_part('template-parts/subheading', null, [
			'text' => 'Fast, Friendly HVAC Support — 24/7',
			'class' => 'mt-4 text-center'
		]);
		?>
	</header>

	<!-- Main Content Grid -->
	<div class="grid gap-8 lg:grid-cols-2">
		<!-- Contact Form -->
		<article
			class="rounded-[20px] bg-gray-50 p-6 md:p-8 lg:p-10"
			aria-labelledby="contact-form-heading"
		>
			<h2 id="contact-form-heading" class="sr-only">
				Contact Form
			</h2>
			<form
				id="contact-form"
				class="space-y-6"
				role="form"
				aria-label="HVAC Service Request Form"
				method="POST"
			>
				<!-- Web3Forms Required Fields -->
				<input type="hidden" name="access_key" value="<?php echo esc_attr($_ENV['WEB3_FORM_ACCESS_KEY'] ?? ''); ?>" />
				<input type="hidden" name="subject" value="SSAC - New Website Contact Form Submission" />
				<input type="checkbox" name="botcheck" style="display: none;" />

				<!-- Name and Phone Row -->
				<fieldset class="grid gap-4 md:grid-cols-2">
					<legend class="sr-only">Personal Information</legend>
					<div>
						<label for="fullName" class="sr-only">
							Full Name (Required)
						</label>
						<input
							id="fullName"
							name="fullName"
							type="text"
							placeholder="Full Name*"
							required
							minlength="2"
							class="w-full rounded-[12px] border-0 bg-white px-5 py-4 text-base font-normal text-black outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-orange-400"
							aria-describedby="fullName-help"
						/>
						<p id="fullName-error" role="alert" class="mt-1 text-sm text-red-600 hidden">
							Full name is required (minimum 2 characters)
						</p>
					</div>
					<div>
						<label for="phoneNumber" class="sr-only">
							Phone Number (Required)
						</label>
						<input
							id="phoneNumber"
							name="phoneNumber"
							type="tel"
							placeholder="Phone Number*"
							required
							pattern="[0-9+()\-\s]{7,}"
							class="w-full rounded-[12px] border-0 bg-white px-5 py-4 text-base font-normal text-black outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-orange-400"
							aria-describedby="phoneNumber-help"
						/>
						<p id="phoneNumber-error" role="alert" class="mt-1 text-sm text-red-600 hidden">
							Enter a valid phone number
						</p>
					</div>
				</fieldset>

				<!-- Email and Service Type Row -->
				<fieldset class="grid gap-4 md:grid-cols-2">
					<legend class="sr-only">
						Contact and Service Information
					</legend>
					<div>
						<label for="emailAddress" class="sr-only">
							Email Address (Required)
						</label>
						<input
							id="emailAddress"
							name="emailAddress"
							type="email"
							placeholder="Email Address*"
							required
							class="w-full rounded-[12px] border-0 bg-white px-5 py-4 text-base font-normal text-black outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-orange-400"
							aria-describedby="emailAddress-help"
						/>
						<p id="emailAddress-error" role="alert" class="mt-1 text-sm text-red-600 hidden">
							Enter a valid email address
						</p>
					</div>
					<div class="relative">
						<label for="serviceType" class="sr-only">
							Service Type (Required)
						</label>
						<select
							id="serviceType"
							name="serviceType"
							required
							class="w-full appearance-none rounded-[12px] border-0 bg-white px-5 py-4 text-base font-normal outline-none focus:ring-2 focus:ring-orange-400 text-gray-400"
							aria-describedby="serviceType-help"
						>
							<option value="" disabled selected class="text-gray-400">
								Service Type*
							</option>
							<?php foreach ($service_types as $service): ?>
								<option value="<?php echo esc_attr($service); ?>">
									<?php echo esc_html($service); ?>
								</option>
							<?php endforeach; ?>
						</select>
						<p id="serviceType-error" role="alert" class="mt-1 text-sm text-red-600 hidden">
							Please select a service type
						</p>
						<div class="pointer-events-none absolute top-1/2 right-4 -translate-y-1/2">
							<img
								class="h-3 w-3"
								alt="Dropdown Arrow"
								src="<?php echo esc_url($images['chevron_down']); ?>"
							/>
						</div>
					</div>
				</fieldset>

				<!-- Select Category -->
				<fieldset class="relative">
					<legend class="sr-only">Service Category</legend>
					<label for="selectCategory" class="sr-only">
						Select Service Category (Required)
					</label>
					<select
						id="selectCategory"
						name="selectCategory"
						required
						class="w-full appearance-none rounded-[12px] border-0 bg-white px-5 py-4 text-base font-normal outline-none focus:ring-2 focus:ring-orange-400 text-gray-400"
						aria-describedby="selectCategory-help"
					>
						<option value="" disabled selected class="text-gray-400">
							Select Category*
						</option>
						<?php foreach ($select_options as $option): ?>
							<option value="<?php echo esc_attr($option); ?>">
								<?php echo esc_html($option); ?>
							</option>
						<?php endforeach; ?>
					</select>
					<p id="selectCategory-error" role="alert" class="mt-1 text-sm text-red-600 hidden">
						Please select a category
					</p>
					<div class="pointer-events-none absolute top-1/2 right-4 -translate-y-1/2">
						<img
							class="h-3 w-3"
							alt="Dropdown Arrow"
							src="<?php echo esc_url($images['chevron_down']); ?>"
						/>
					</div>
				</fieldset>

				<!-- Message -->
				<fieldset>
					<legend class="sr-only">Additional Message</legend>
					<label for="message" class="sr-only">
						Your Message (Optional)
					</label>
					<textarea
						id="message"
						name="message"
						placeholder="Your Message"
						rows="6"
						class="w-full resize-none rounded-[12px] border-0 bg-white px-5 py-4 text-base font-normal text-black outline-none placeholder:text-gray-400 focus:ring-2 focus:ring-orange-400"
						aria-describedby="message-help"
					></textarea>
				</fieldset>

				<!-- Submit Button -->
				<button
					type="submit"
					id="submit-btn"
					class="w-full rounded-full bg-gradient-to-r from-[#F79E37] to-[#E5462F] px-8 py-4 text-base font-medium text-white transition-opacity duration-200 hover:opacity-90 focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:outline-none md:text-xl"
					aria-label="Schedule Service Now"
				>
					Schedule Service Now
				</button>

				<!-- Success/Error Messages -->
				<div id="form-status" class="hidden">
					<div id="success-message" class="hidden rounded-lg bg-green-50 p-4 text-green-800">
						Thank you! Your message has been sent successfully. We'll get back to you soon.
					</div>
					<div id="error-message" class="hidden rounded-lg bg-red-50 p-4 text-red-800">
						Sorry, there was an error sending your message. Please try again or call us directly.
					</div>
				</div>
			</form>
		</article>

		<!-- Contact Information -->
		<aside
			class="rounded-[20px] bg-gray-50 p-6 md:p-8 lg:p-10"
			aria-labelledby="contact-info-heading"
		>
			<h2
				id="contact-info-heading"
				class="mb-6 text-center text-2xl font-bold text-black"
			>
				Contact Information
			</h2>

			<p class="mb-8 text-center text-lg font-light break-words text-black">
				Having HVAC trouble or planning an upgrade? Call, email, or visit us
				today — we'll get your home back to perfect comfort, fast.
			</p>

			<!-- Contact Details -->
			<address class="space-y-6 not-italic">
				<!-- Email -->
				<div class="flex items-center gap-4">
					<div class="flex-shrink-0">
						<img
							class="h-16 w-16"
							alt="Email contact icon"
							src="<?php echo esc_url($images['email_icon']); ?>"
						/>
					</div>
					<div>
						<h3 class="font-semibold text-black md:text-lg">Email</h3>
						<a
							href="<?php echo esc_attr('mailto:' . $company_email); ?>"
							class="font-light break-all text-black hover:underline focus:underline focus:outline-none md:text-lg"
							aria-label="<?php echo esc_attr('Email us at ' . $company_email); ?>"
							itemprop="email"
						>
							<?php echo esc_html($company_email); ?>
						</a>
					</div>
				</div>

				<!-- Location -->
				<div class="flex items-center gap-4">
					<div class="flex-shrink-0">
						<img
							class="h-16 w-16"
							alt="Location contact icon"
							src="<?php echo esc_url($images['location_icon']); ?>"
						/>
					</div>
					<div>
						<h3 class="font-semibold text-black md:text-lg">
							Location
						</h3>
						<div
							class="font-light break-words text-black md:text-lg"
							itemprop="address"
							itemscope
							itemtype="https://schema.org/PostalAddress"
						>
							<span itemprop="streetAddress"><?php echo esc_html(SUNNYSIDE_ADDRESS_STREET); ?></span>
							<br />
							<span itemprop="addressLocality"><?php echo esc_html(SUNNYSIDE_ADDRESS_CITY); ?></span>,
							<span itemprop="addressRegion"><?php echo esc_html(SUNNYSIDE_ADDRESS_STATE); ?></span>
							<span itemprop="postalCode"><?php echo esc_html(SUNNYSIDE_ADDRESS_ZIP); ?></span>
						</div>
					</div>
				</div>

				<!-- Phone -->
				<div class="flex items-center gap-4">
					<div class="flex-shrink-0">
						<img
							class="h-16 w-16"
							alt="Phone contact icon"
							src="<?php echo esc_url($images['phone_icon']); ?>"
						/>
					</div>
					<div>
						<h3 class="font-semibold text-black md:text-lg">Phone</h3>
						<a
							href="<?php echo esc_attr(SUNNYSIDE_TEL_HREF); ?>"
							class="font-light text-black hover:underline focus:underline focus:outline-none md:text-lg"
							aria-label="<?php echo esc_attr('Call us at ' . SUNNYSIDE_PHONE_DISPLAY); ?>"
							itemprop="telephone"
						>
							<?php echo esc_html(SUNNYSIDE_PHONE_DISPLAY); ?>
						</a>
					</div>
				</div>
			</address>

			<!-- Social Media -->
			<nav class="mt-8" aria-label="Social media links">
				<h3 class="mb-4 text-center text-xl font-bold text-black">
					Follow Us:
				</h3>
				<div class="flex justify-center">
					<?php get_template_part('template-parts/social-icons', null, ['size' => 'md']); ?>
				</div>
			</nav>
		</aside>
	</div>
</section>