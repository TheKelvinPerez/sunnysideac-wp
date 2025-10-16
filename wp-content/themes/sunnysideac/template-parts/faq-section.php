<?php
/**
 * FAQ Section Template Part
 * Displays an interactive FAQ accordion with common AC service questions
 */

// FAQ data - comprehensive AC service questions and answers
$faq_data = [
    [
        'id' => 1,
        'question' => 'How often should I service my HVAC system?',
        'answer' => 'We recommend servicing your HVAC system at least twice a year - once in spring before cooling season and once in fall before heating season. Regular maintenance helps prevent breakdowns, improves energy efficiency, and extends your system\'s lifespan.',
    ],
    [
        'id' => 2,
        'question' => 'What are signs that my AC needs repair?',
        'answer' => 'Common warning signs include weak airflow, warm air blowing from vents, unusual noises like grinding or squealing, foul odors, frequent cycling on and off, high humidity indoors, and rising energy bills. If you notice any of these issues, contact us for professional diagnosis.',
    ],
    [
        'id' => 3,
        'question' => 'How long does AC installation typically take?',
        'answer' => 'Standard AC installation usually takes 4-8 hours for a straightforward replacement. However, new installations or complex setups may take 1-2 days. We\'ll provide an accurate timeline during your consultation based on your specific needs and home layout.',
    ],
    [
        'id' => 4,
        'question' => 'What size AC unit do I need for my home?',
        'answer' => 'AC sizing depends on square footage, ceiling height, insulation, windows, and local climate. A unit too small won\'t cool effectively, while oversized units cycle on and off frequently, wasting energy. Our technicians perform load calculations to determine the perfect size for your home.',
    ],
    [
        'id' => 5,
        'question' => 'How can I improve my AC\'s energy efficiency?',
        'answer' => 'Regular maintenance, changing filters monthly, sealing air leaks, using programmable thermostats, ensuring proper insulation, and keeping vents unblocked all improve efficiency. Consider upgrading to a high-efficiency ENERGY STAR unit for maximum savings.',
    ],
    [
        'id' => 6,
        'question' => 'When should I replace my AC instead of repairing it?',
        'answer' => 'Consider replacement if your unit is over 10-15 years old, needs frequent repairs, uses R-22 refrigerant (being phased out), or repair costs exceed 50% of replacement cost. Newer units offer better efficiency, reliability, and warranty coverage.',
    ],
    [
        'id' => 7,
        'question' => 'Do you offer emergency AC repair services?',
        'answer' => 'Yes! We provide 24/7 emergency AC repair services because we understand that AC failures don\'t wait for business hours. Our experienced technicians are available nights, weekends, and holidays to restore your comfort quickly.',
    ],
    [
        'id' => 8,
        'question' => 'What brands of AC equipment do you install and service?',
        'answer' => 'We install and service all major HVAC brands including Carrier, Trane, Lennox, Rheem, Goodman, American Standard, and more. Our technicians are factory-trained and certified to work on various manufacturers\' equipment.',
    ],
    [
        'id' => 9,
        'question' => 'How often should I change my air filters?',
        'answer' => 'Standard 1-inch filters should be changed every 1-3 months, depending on usage, pets, and allergies. Thicker pleated filters may last 3-6 months. Check filters monthly - if they appear dirty or clogged, replace them immediately to maintain proper airflow and system efficiency.',
    ],
    [
        'id' => 10,
        'question' => 'Do you provide financing options for new AC systems?',
        'answer' => 'Yes, we offer flexible financing options to make AC installation affordable. We work with various lenders to provide competitive rates and terms that fit your budget. Ask our team about available financing programs during your consultation.',
    ],
];

// Icons data
$icons = [
    'faq_section' => sunnysideac_asset_url('assets/images/home-page/faq-section-icon.svg'),
    'faq_chevron' => sunnysideac_asset_url('assets/images/home-page/faq-chevron-down-circle.svg'),
];
?>

<section class="w-full rounded-2xl bg-white px-4 py-12 md:px-10 md:py-16 lg:py-20" id="faq-section" role="main" aria-labelledby="faq-heading">
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <header class="mb-12 text-center md:mb-16">
            <?php
            get_template_part(
                'template-parts/title',
                null,
                [
                    'icon' => $icons['faq_section'],
                    'title' => 'Frequently Asked Questions!',
                    'mobile_title' => 'FAQ',
                    'id' => 'faq-heading',
                ]
            );
            ?>

            <?php
            get_template_part(
                'template-parts/subheading',
                null,
                [
                    'text' => 'Got Questions? We\'ve Got Answers!',
                    'class' => 'mb-4 text-gray-600 md:text-4xl md:leading-tight lg:text-5xl',
                ]
            );
            ?>

            <p class="text-base font-light text-gray-700 md:text-lg">
                Find answers to common questions about our AC services, installation, and maintenance.
            </p>
        </header>

        <!-- FAQ Grid -->
        <div class="grid gap-4 md:gap-6">
            <?php foreach ($faq_data as $faq): ?>
                <div class="faq-item w-full">
                    <input type="checkbox" id="faq-<?php echo esc_attr($faq['id']); ?>" class="faq-toggle hidden" />

                    <label for="faq-<?php echo esc_attr($faq['id']); ?>" class="block w-full cursor-pointer">
                        <div class="faq-container relative w-full rounded-[20px] border-2 border-transparent bg-[#f6f6f6] transition-all duration-300 ease-in-out hover:shadow-md">
                            <div class="flex items-start justify-between p-6">
                                <h3 class="pr-4 text-lg leading-relaxed font-semibold text-black md:text-xl">
                                    <?php echo esc_html($faq['question']); ?>
                                </h3>

                                <div class="faq-chevron h-[35px] w-[35px] flex-shrink-0 rounded-full bg-gradient-to-l from-[#F79E37] to-[#E5462F] shadow-md transition-all duration-300 ease-in-out hover:scale-110">
                                    <img class="chevron-icon h-full w-full transition-transform duration-300 ease-in-out"
                                         alt="Toggle FAQ"
                                         src="<?php echo esc_url($icons['faq_chevron']); ?>"
                                         loading="lazy"
                                         decoding="async" />
                                </div>
                            </div>

                            <div class="faq-content max-h-0 overflow-hidden opacity-0 transition-all duration-300 ease-in-out">
                                <div class="px-6 pb-6 text-base leading-relaxed font-light text-gray-700 md:text-lg">
                                    <?php echo esc_html($faq['answer']); ?>
                                </div>
                            </div>
                        </div>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<style>
/* CSS-only accordion functionality */
.faq-toggle:checked + label .faq-container {
    background-color: #ffeac0;
    border-color: #fed7aa;
}

.faq-toggle:checked + label .faq-content {
    max-height: 1000px;
    opacity: 1;
}

.faq-toggle:checked + label .chevron-icon {
    transform: rotate(180deg);
}

.faq-toggle:checked + label .faq-chevron {
    box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
}

/* Enhanced hover effects */
.faq-item:hover .faq-chevron {
    transform: scale(1.1);
}
</style>