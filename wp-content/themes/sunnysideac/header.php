<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
    // Inline critical CSS to prevent FOUC in development
    $is_dev = function_exists('sunnysideac_is_vite_dev_server_running') && sunnysideac_is_vite_dev_server_running();
    if ($is_dev) : ?>
    <style>
        /* Prevent flash of unstyled content while Vite injects CSS */
        body {
            visibility: hidden;
            opacity: 0;
        }
        body.vite-ready {
            visibility: visible;
            opacity: 1;
            transition: opacity 0.1s ease-in;
        }
    </style>
    <script>
        // Mark body as ready once CSS is injected
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    document.body.classList.add('vite-ready');
                }, 50);
            });
        } else {
            setTimeout(function() {
                document.body.classList.add('vite-ready');
            }, 50);
        }
    </script>
    <?php endif; ?>

    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="bg-white shadow-lg">
    <div class="container mx-auto px-4 py-6">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <h1 class="text-2xl font-bold text-blue-600">
                        <a href="<?php echo esc_url(home_url('/')); ?>" class="hover:text-blue-700 transition-colors">
                            <?php bloginfo('name'); ?>
                        </a>
                    </h1>
                <?php endif; ?>
            </div>

            <nav class="hidden md:flex items-center gap-6">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'flex gap-6',
                    'fallback_cb' => function() {
                        echo '<div class="flex gap-6">';
                        echo '<a href="' . esc_url(home_url('/')) . '" class="text-gray-700 hover:text-blue-600 transition-colors">Home</a>';
                        echo '<a href="' . esc_url(home_url('/about')) . '" class="text-gray-700 hover:text-blue-600 transition-colors">About</a>';
                        echo '<a href="' . esc_url(home_url('/contact')) . '" class="text-gray-700 hover:text-blue-600 transition-colors">Contact</a>';
                        echo '</div>';
                    }
                ));
                ?>
            </nav>

            <button class="md:hidden text-gray-700 hover:text-blue-600" id="mobile-menu-button">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu -->
        <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4 border-t border-gray-200 pt-4">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'flex flex-col gap-3',
                'fallback_cb' => function() {
                    echo '<div class="flex flex-col gap-3">';
                    echo '<a href="' . esc_url(home_url('/')) . '" class="text-gray-700 hover:text-blue-600 transition-colors">Home</a>';
                    echo '<a href="' . esc_url(home_url('/about')) . '" class="text-gray-700 hover:text-blue-600 transition-colors">About</a>';
                    echo '<a href="' . esc_url(home_url('/contact')) . '" class="text-gray-700 hover:text-blue-600 transition-colors">Contact</a>';
                    echo '</div>';
                }
            ));
            ?>
        </div>
    </div>
</header>
