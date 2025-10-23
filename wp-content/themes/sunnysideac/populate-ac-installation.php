<?php
/**
 * Populate AC Installation Service Content
 */

$post_id = 59; // AC Installation post ID

// Service Benefits (Repeater)
$benefits = [
    ['benefit' => 'Free In-Home Consultation & System Assessment'],
    ['benefit' => 'Licensed, Insured & EPA Certified Technicians'],
    ['benefit' => 'Expert Load Calculation for Proper AC Sizing'],
    ['benefit' => 'All Major HVAC Brands - Carrier, Trane, Rheem & More'],
    ['benefit' => 'Professional Installation with Permits Handled'],
    ['benefit' => 'Flexible Financing Options Available'],
    ['benefit' => 'Energy Efficiency Rebates & Tax Credits Assistance'],
    ['benefit' => 'Comprehensive Warranty on Labor & Equipment'],
];

update_field('service_benefits', $benefits, $post_id);
echo "✓ Updated service_benefits\n";

// Service Process (Repeater)
$process = [
    [
        'title' => 'Free In-Home Consultation',
        'description' => 'We assess your home\'s cooling needs, discuss your preferences, and provide expert recommendations with upfront pricing.',
    ],
    [
        'title' => 'Custom System Design',
        'description' => 'Our technicians calculate precise cooling loads and design a system perfectly sized for your home\'s unique requirements.',
    ],
    [
        'title' => 'Professional Installation',
        'description' => 'Our certified team installs your new AC system following manufacturer specifications and local building codes. We handle all permits.',
    ],
    [
        'title' => 'System Testing & Calibration',
        'description' => 'We thoroughly test all components, check refrigerant levels, calibrate airflow, and ensure optimal performance and efficiency.',
    ],
    [
        'title' => 'Complete System Walkthrough',
        'description' => 'We explain how to operate your new system, program your thermostat, and answer all questions about maintenance and warranty coverage.',
    ],
];

update_field('service_process', $process, $post_id);
echo "✓ Updated service_process\n";

// Service FAQs (Repeater)
$faqs = [
    [
        'question' => 'How long does AC installation take?',
        'answer' => 'Most residential AC installations take 6-10 hours and are completed in a single day. Larger systems or complex installations may require 1-2 days. We provide an accurate timeline during your consultation based on your specific installation requirements.',
    ],
    [
        'question' => 'How do I know what size AC I need?',
        'answer' => 'We perform a detailed load calculation considering your home\'s square footage, insulation, windows, sun exposure, ceiling height, and local climate. This ensures proper sizing—an oversized or undersized unit leads to inefficiency, higher bills, and shorter equipment life. We handle all sizing calculations for you.',
    ],
    [
        'question' => 'What brands do you install?',
        'answer' => 'We install all major HVAC brands including Carrier, Trane, Lennox, Goodman, Rheem, York, American Standard, Bryant, Daikin, and Mitsubishi. We\'ll recommend the best brand and model for your budget, efficiency goals, and cooling needs.',
    ],
    [
        'question' => 'How much does a new AC system cost?',
        'answer' => 'Residential AC installation typically ranges from $3,500 to $12,000 depending on system size, efficiency rating (SEER), brand, and installation complexity. We provide free in-home consultations with detailed, upfront pricing. Financing options are available with approved credit.',
    ],
    [
        'question' => 'Do you handle permits and inspections?',
        'answer' => 'Yes, we handle all necessary permits and coordinate required inspections with local building departments. Permit costs are included in your installation quote. We ensure every installation meets or exceeds Florida building codes and manufacturer specifications.',
    ],
    [
        'question' => 'What is SEER and why does it matter?',
        'answer' => 'SEER (Seasonal Energy Efficiency Ratio) measures AC efficiency—higher numbers mean lower energy costs. Florida requires minimum 14 SEER. We typically recommend 16-18 SEER for best value, which can reduce cooling costs by 30-50% compared to older systems. High-efficiency systems may qualify for rebates.',
    ],
    [
        'question' => 'What warranty comes with new AC installation?',
        'answer' => 'New AC systems include manufacturer equipment warranties (typically 5-10 years on parts, sometimes lifetime on compressor) plus our comprehensive workmanship warranty. We also offer extended warranty options for additional protection and peace of mind.',
    ],
    [
        'question' => 'Can you install AC if I don\'t have existing ductwork?',
        'answer' => 'Yes! For homes without ductwork, we offer ductless mini-split systems that don\'t require ducts. For homes where adding ductwork is feasible, we can design and install a complete ducted system. We\'ll recommend the best solution during your consultation.',
    ],
    [
        'question' => 'Do you offer financing for AC installation?',
        'answer' => 'Yes, we offer flexible financing options with approved credit, including low monthly payments and special promotional rates. We also help you identify available manufacturer rebates, utility company incentives, and federal tax credits to reduce your total investment.',
    ],
    [
        'question' => 'When is the best time to install a new AC?',
        'answer' => 'While we install AC systems year-round, fall and winter often offer better pricing and availability. However, if your current system is failing during summer, don\'t wait—we offer same-day emergency installation services to restore your comfort quickly.',
    ],
];

update_field('service_faqs', $faqs, $post_id);
echo "✓ Updated service_faqs\n";

echo "\n✅ AC Installation service content fully populated!\n";
echo "Test at: /miami/ac-installation/\n";
