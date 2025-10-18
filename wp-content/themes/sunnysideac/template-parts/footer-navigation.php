<?php
/**
 * Footer Navigation Component
 */

$footer_links = [
    'company' => [
        'title' => 'Company',
        'links' => [
            ['name' => 'About Us', 'href' => home_url('/about')],
            ['name' => 'Financing', 'href' => home_url('/financing')],
            ['name' => 'Maintenance Plan', 'href' => home_url('/maintenance-plan')],
            ['name' => 'Careers', 'href' => home_url('/careers')]
        ]
    ],
    'services' => [
        'title' => 'Services',
        'links' => [
            ['name' => 'AC Repair', 'href' => home_url('/service/ac-repair')],
            ['name' => 'AC Installation', 'href' => home_url('/service/ac-installation')],
            ['name' => 'AC Maintenance', 'href' => home_url('/service/ac-maintenance')],
            ['name' => 'Heating Services', 'href' => home_url('/service/heating-repair')],
            ['name' => 'All HVAC Services', 'href' => home_url('/services')]
        ]
    ],
    'areas' => [
        'title' => 'Service Areas',
        'links' => [
            ['name' => 'Pembroke Pines', 'href' => home_url('/service-areas/pembroke-pines')],
            ['name' => 'Miramar', 'href' => home_url('/service-areas/miramar')],
            ['name' => 'Weston', 'href' => home_url('/service-areas/weston')],
            ['name' => 'Hollywood', 'href' => home_url('/service-areas/hollywood')],
            ['name' => 'Fort Lauderdale', 'href' => home_url('/service-areas/fort-lauderdale')],
            ['name' => 'All Service Areas', 'href' => home_url('/service-areas')]
        ]
    ],
    'support' => [
        'title' => 'Support',
        'links' => [
            ['name' => 'FAQs', 'href' => home_url('/faq')],
            ['name' => 'Reviews', 'href' => home_url('/reviews')],
            ['name' => 'Brands', 'href' => home_url('/brands')],
            ['name' => 'Privacy Policy', 'href' => home_url('/privacy-policy')]
        ]
    ]
];
?>

<!-- Footer Navigation - Spans 2 columns in the 4-column grid -->
<?php
// Split the 4 sections into 2 columns (2 sections per column)
$sections_array = array_values($footer_links);
$column1 = array_slice($sections_array, 0, 2); // Company and Services
$column2 = array_slice($sections_array, 2, 2); // Service Areas and Support
?>

<!-- Column 2: Company & Services -->
<div class="space-y-6">
    <?php foreach ($column1 as $section) : ?>
        <div>
            <h3 class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl">
                <?php echo esc_html($section['title']); ?>
            </h3>
            <ul class="space-y-2">
                <?php foreach ($section['links'] as $link) : ?>
                    <li>
                        <a
                            href="<?php echo esc_url($link['href']); ?>"
                            class="font-light text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500"
                        >
                            <?php echo esc_html($link['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>

<!-- Column 3: Service Areas & Support -->
<div class="space-y-6">
    <?php foreach ($column2 as $section) : ?>
        <div>
            <h3 class="mb-4 text-xl font-semibold text-gray-900 sm:text-2xl">
                <?php echo esc_html($section['title']); ?>
            </h3>
            <ul class="space-y-2">
                <?php foreach ($section['links'] as $link) : ?>
                    <li>
                        <a
                            href="<?php echo esc_url($link['href']); ?>"
                            class="font-light text-gray-700 transition-colors duration-200 hover:text-[#fb9939] hover:underline focus:outline-2 focus:outline-blue-500"
                        >
                            <?php echo esc_html($link['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endforeach; ?>
</div>