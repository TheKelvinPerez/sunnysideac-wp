<?php
/**
 * Why Choose Us Section Template Part
 * Displays the main "Why Choose Us" section with features grid and main image
 */

// Features data
$features = [
    [
        'id' => 1,
        'title' => 'Customer Centric Approach',
        'description' => 'We put you first, tailoring every solution to your needs and budget — because your comfort is our priority.',
        'icon' => sunnysideac_asset_url('assets/images/home-page/why-choose-us-heart.svg'),
        'icon_alt' => 'Customer centric approach with heart icon',
        'bg_color' => 'bg-white',
    ],
    [
        'id' => 2,
        'title' => '24/7 Emergency Service',
        'description' => 'No matter the time, our team is ready to restore your comfort when you need it most.',
        'icon' => sunnysideac_asset_url('assets/images/home-page/why-choose-us-emergency.svg'),
        'icon_alt' => '24/7 emergency service with clock icon',
        'bg_color' => 'bg-amber-50',
    ],
    [
        'id' => 3,
        'title' => 'Expert Technicians',
        'description' => 'Our certified HVAC professionals bring skill, precision, and care to every job — big or small.',
        'icon' => sunnysideac_asset_url('assets/images/home-page/why-choose-us-wrench.svg'),
        'icon_alt' => 'Expert technicians with wrench icon',
        'bg_color' => 'bg-white',
    ],
];

// Images data
$images = [
    'main' => sunnysideac_asset_url('assets/images/home-page/why-choose-us-main-image.png'),
    'icon' => sunnysideac_asset_url('assets/images/home-page/why-choose-us-icon.png'),
];
?>

<section
    class="w-full bg-gray-100"
    role="main"
    aria-labelledby="why-choose-us-heading"
>
    <div class="mx-auto max-w-7xl">
        <div class="rounded-[20px] bg-white p-6 md:p-8 lg:p-10">
            <!-- Header Section -->
            <header class="mb-6 text-center md:mb-8">
                <div class="flex items-center justify-center gap-3 mb-4">
                    <?php if (isset($images['icon'])) : ?>
                        <img
                            class="h-12 w-12 md:h-14 md:w-14"
                            alt="Why choose us icon"
                            src="<?php echo esc_url($images['icon']); ?>"
                        >
                    <?php endif; ?>
                    <h2
                        id="why-choose-us-heading"
                        class="text-2xl font-bold text-gray-900 md:text-3xl lg:text-4xl"
                    >
                        Why Choose Us
                    </h2>
                </div>

                <p class="mx-auto max-w-4xl text-base font-light text-gray-600 md:text-lg lg:text-xl">
                    Service You Can Trust, Comfort You Deserve
                </p>
            </header>

            <!-- Mobile Image - Shows under title on mobile only -->
            <div class="mb-6 flex justify-center lg:hidden">
                <div class="w-full max-w-sm">
                    <img
                        class="h-auto w-full rounded-2xl shadow-lg"
                        alt="Professional HVAC technician working on air conditioning unit"
                        src="<?php echo esc_url($images['main'] ?? ''); ?>"
                    >
                </div>
            </div>

            <!-- Main Content Grid -->
            <div class="lg:grid lg:grid-cols-2 lg:gap-6 lg:rounded-2xl lg:bg-gray-50 lg:p-6">
                <!-- Features Column -->
                <div class="flex flex-col gap-3 md:gap-4 lg:order-1">
                    <?php foreach ($features as $index => $feature) : ?>
                        <article
                            class="relative mt-10 rounded-2xl bg-gray-50 p-4 shadow-sm transition-all duration-300 hover:bg-orange-50 hover:shadow-md sm:mt-0 lg:bg-white lg:hover:bg-orange-50"
                        >
                            <!-- Icon positioned at top right on mobile only -->
                            <div class="absolute top-3 right-3 lg:hidden">
                                <img
                                    class="relative -top-8 left-8 h-auto w-14"
                                    alt="<?php echo esc_attr($feature['icon_alt']); ?>"
                                    src="<?php echo esc_url($feature['icon']); ?>"
                                >
                            </div>

                            <!-- Content -->
                            <div class="lg:flex lg:items-start lg:gap-4">
                                <!-- Desktop icon placeholder - hidden on mobile -->
                                <div class="hidden lg:block lg:flex-shrink-0">
                                    <img
                                        class="h-16 w-16"
                                        alt="<?php echo esc_attr($feature['icon_alt']); ?>"
                                        src="<?php echo esc_url($feature['icon']); ?>"
                                    >
                                </div>

                                <div class="lg:min-w-0 lg:flex-1">
                                    <h3 class="mb-2 text-lg leading-tight font-semibold text-gray-900 md:text-xl">
                                        <?php echo esc_html($feature['title']); ?>
                                    </h3>

                                    <p class="text-sm leading-snug font-light text-gray-700 md:text-base">
                                        <?php echo esc_html($feature['description']); ?>
                                    </p>
                                </div>
                            </div>
                        </article>
                    <?php endforeach; ?>
                </div>

                <!-- Desktop Image - Shows on desktop only -->
                <div class="hidden items-center justify-center lg:order-2 lg:flex">
                    <div class="w-full max-w-lg">
                        <img
                            class="h-auto w-full rounded-2xl shadow-lg"
                            alt="Professional HVAC technician working on air conditioning unit"
                            src="<?php echo esc_url($images['main'] ?? ''); ?>"
                        >
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>