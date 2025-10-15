<?php
/**
 * Template Name: Twig Test Page
 * Description: A test page to verify Twig/Timber integration with Tailwind CSS
 */

$context = Timber\Timber::context();
$context['page'] = Timber\Timber::get_post();
$context['title'] = 'Twig & Tailwind Test Page';
$context['description'] = 'Testing Timber (Twig) integration with Tailwind CSS';

// Add some custom data to test
$context['features'] = [
    [
        'title' => 'Twig Templates',
        'description' => 'Clean, powerful templating with Twig syntax',
        'icon' => '🎨'
    ],
    [
        'title' => 'Tailwind CSS',
        'description' => 'Utility-first CSS framework for rapid development',
        'icon' => '⚡'
    ],
    [
        'title' => 'WordPress',
        'description' => 'The world\'s most popular CMS',
        'icon' => '📝'
    ],
    [
        'title' => 'Timber',
        'description' => 'Brings MVC-like structure to WordPress themes',
        'icon' => '🌲'
    ]
];

Timber\Timber::render('page-twig-test.twig', $context);
