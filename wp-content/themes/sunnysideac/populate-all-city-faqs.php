<?php
/**
 * Populate City-Specific FAQs for All 30 Cities
 * SEO-focused questions targeting local searches
 */

// City configurations with ID and characteristics
$cities = [
    // Coastal cities
    ['id' => 108, 'name' => 'Miami', 'type' => 'coastal', 'characteristic' => 'salt air and high humidity'],
    ['id' => 109, 'name' => 'Fort Lauderdale', 'type' => 'coastal', 'characteristic' => 'salt air from ocean and Intracoastal'],
    ['id' => 102, 'name' => 'Hollywood', 'type' => 'coastal', 'characteristic' => 'ocean breezes and salt air corrosion'],
    ['id' => 114, 'name' => 'Pompano Beach', 'type' => 'coastal', 'characteristic' => 'Atlantic salt air and humidity'],
    ['id' => 118, 'name' => 'Boca Raton', 'type' => 'upscale-coastal', 'characteristic' => 'luxury homes and salt air'],
    ['id' => 117, 'name' => 'Deerfield Beach', 'type' => 'coastal', 'characteristic' => 'beachfront properties and salt air'],
    ['id' => 113, 'name' => 'Key Biscayne', 'type' => 'upscale-coastal', 'characteristic' => 'island location with aggressive salt air'],
    ['id' => 124, 'name' => 'Palm Beach', 'type' => 'upscale-coastal', 'characteristic' => 'exclusive island estates and salt air'],
    ['id' => 105, 'name' => 'Sunny Isles', 'type' => 'coastal', 'characteristic' => 'high-rise towers and oceanfront exposure'],
    ['id' => 116, 'name' => 'Light House Point', 'type' => 'coastal', 'characteristic' => 'waterfront properties and Intracoastal exposure'],

    // Inland cities
    ['id' => 112, 'name' => 'Coral Springs', 'type' => 'inland', 'characteristic' => 'extreme heat without ocean breezes'],
    ['id' => 96, 'name' => 'Pembroke Pines', 'type' => 'inland', 'characteristic' => 'intense heat and high humidity'],
    ['id' => 95, 'name' => 'Miramar', 'type' => 'inland', 'characteristic' => 'high temperatures and frequent storms'],
    ['id' => 101, 'name' => 'Davie', 'type' => 'inland', 'characteristic' => 'rural properties and extreme heat'],
    ['id' => 106, 'name' => 'Plantation', 'type' => 'inland', 'characteristic' => 'established homes and steady heat'],
    ['id' => 107, 'name' => 'Sunrise', 'type' => 'inland', 'characteristic' => 'consistent heat and humidity'],
    ['id' => 103, 'name' => 'Weston', 'type' => 'inland', 'characteristic' => 'western heat and afternoon storms'],
    ['id' => 110, 'name' => 'Tamarac', 'type' => 'inland', 'characteristic' => 'steady heat and aging systems'],
    ['id' => 119, 'name' => 'Homestead', 'type' => 'inland', 'characteristic' => 'South Florida\'s hottest temperatures'],
    ['id' => 98, 'name' => 'Miami Lakes', 'type' => 'inland', 'characteristic' => 'inland heat and lake humidity'],
    ['id' => 100, 'name' => 'Pembroke Park', 'type' => 'inland', 'characteristic' => 'urban heat and older homes'],
    ['id' => 99, 'name' => 'West Park', 'type' => 'inland', 'characteristic' => 'consistent heat demand'],
    ['id' => 97, 'name' => 'South West Ranches', 'type' => 'inland', 'characteristic' => 'ranch properties and extreme western heat'],
    ['id' => 104, 'name' => 'Hialeah Lakes', 'type' => 'inland', 'characteristic' => 'urban heat island effects'],

    // Mixed/Upscale
    ['id' => 111, 'name' => 'Coral Gables', 'type' => 'upscale', 'characteristic' => 'historic homes and luxury systems'],
    ['id' => 123, 'name' => 'West Palm Beach', 'type' => 'mixed', 'characteristic' => 'diverse properties from coastal to inland'],
    ['id' => 115, 'name' => 'Palmetto Bay', 'type' => 'upscale', 'characteristic' => 'upscale homes and bay proximity'],
    ['id' => 121, 'name' => 'Wellington', 'type' => 'inland', 'characteristic' => 'equestrian estates and western heat'],
    ['id' => 122, 'name' => 'Royal Palm Beach', 'type' => 'inland', 'characteristic' => 'inland Palm Beach County heat'],
    ['id' => 120, 'name' => 'Palm Springs', 'type' => 'inland', 'characteristic' => 'Palm Beach County inland heat'],
];

foreach ($cities as $city) {
    $city_id = $city['id'];
    $city_name = $city['name'];
    $city_type = $city['type'];
    $city_char = $city['characteristic'];

    // Base FAQs template - customize based on city type
    $faqs = [
        [
            'question' => "What makes HVAC service in {$city_name} different?",
            'answer' => "{$city_name} presents unique HVAC challenges due to {$city_char}. " . ($city_type === 'coastal' || $city_type === 'upscale-coastal' ? "Coastal properties face accelerated corrosion requiring specialized maintenance and corrosion-resistant components. " : "The lack of ocean breezes means AC systems work harder and require more frequent service. ") . "Our technicians understand these local conditions and provide service tailored to {$city_name}'s specific climate demands."
        ],
        [
            'question' => "How often should I service my AC in {$city_name}?",
            'answer' => "In {$city_name}'s demanding climate, we recommend AC maintenance at least twice annually—spring (before peak summer) and fall. " . ($city_type === 'coastal' || $city_type === 'upscale-coastal' ? "Coastal locations benefit from quarterly inspections to check for salt air corrosion. " : "The continuous operation and extreme heat require frequent professional inspection. ") . "Regular maintenance prevents breakdowns during {$city_name}'s hottest months."
        ],
        [
            'question' => "What AC problems are most common in {$city_name}?",
            'answer' => "{$city_name} AC systems commonly experience " . ($city_type === 'coastal' || $city_type === 'upscale-coastal' ? "corroded coils from salt air, " : "") . "refrigerant leaks, clogged condensate drains from high humidity, frozen coils from continuous operation, and electrical failures from frequent cycling. " . ($city_type === 'upscale' || $city_type === 'upscale-coastal' ? "Luxury homes may also experience issues with multi-zone systems and smart controls. " : "") . "Our technicians are experienced with all common {$city_name} AC issues."
        ],
        [
            'question' => "What areas of {$city_name} do you serve?",
            'answer' => "We provide comprehensive HVAC service throughout all {$city_name} neighborhoods and surrounding areas. " . ($city_type === 'coastal' || $city_type === 'upscale-coastal' ? "We specialize in both coastal waterfront properties and inland areas, understanding the unique needs of each location. " : "We serve both residential neighborhoods and commercial areas throughout the city. ") . "Fast response times and same-day service available throughout {$city_name}."
        ],
        [
            'question' => "How much does AC repair cost in {$city_name}?",
            'answer' => "{$city_name} AC repair costs typically range from \\$150-\\$500 for minor repairs (thermostats, capacitors, electrical) to \\$500-\\$2,500 for major repairs (compressors, coils, refrigerant leaks). " . ($city_type === 'upscale' || $city_type === 'upscale-coastal' ? "Complex multi-zone systems may have higher costs. " : "") . "We provide transparent upfront pricing before any work begins, with no hidden fees or surprises."
        ],
        [
            'question' => "Do you offer emergency AC repair in {$city_name}?",
            'answer' => "Yes! We provide 24/7 emergency AC repair throughout {$city_name} and surrounding areas. We understand that AC failures don't wait for business hours, especially during South Florida's brutal summer heat. Our experienced technicians respond quickly to restore your comfort, with most emergency calls answered within 1-2 hours."
        ],
        [
            'question' => "How long does AC repair take in {$city_name}?",
            'answer' => "Most {$city_name} AC repairs take 1-3 hours depending on the issue. Simple fixes like thermostat or capacitor replacements take under an hour, while refrigerant leaks or compressor work may take 2-4 hours. " . ($city_type === 'upscale' || $city_type === 'upscale-coastal' ? "Complex systems in luxury homes may require additional time. " : "") . "We provide accurate time estimates before starting work."
        ],
        [
            'question' => "Why choose Sunnyside AC for service in {$city_name}?",
            'answer' => "Our technicians know {$city_name}'s unique HVAC challenges including {$city_char}. We're licensed, insured, and factory-trained on all major brands. We provide upfront pricing, same-day service, and 24/7 emergency repairs. " . ($city_type === 'upscale' || $city_type === 'upscale-coastal' ? "We specialize in high-end systems and maintain the discretion expected in luxury communities. " : "") . "Local expertise makes the difference."
        ],
    ];

    update_field('city_faqs', $faqs, $city_id);
    echo "✓ {$city_name} (ID {$city_id}) - {$city_type} - " . count($faqs) . " FAQs\n";
}

echo "\n🎉 All 30 cities now have city-specific FAQs!\n";
echo "Each city page now shows: City FAQs (8) + Service FAQs (8-10) = 16-18 total FAQs\n";
echo "Total FAQ variations created: " . (count($cities) * 8) . " city-specific FAQs\n";
