<?php get_header(); ?>

<main class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold mb-6">
                    Stay Cool with <?php bloginfo('name'); ?>
                </h1>
                <p class="text-xl text-blue-100 mb-8">
                    Professional HVAC services available 24/7. Expert installation, maintenance, and emergency repairs for residential and commercial properties.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#services" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-all transform hover:scale-105">
                        Our Services
                    </a>
                    <a href="#contact" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-all">
                        Get a Free Quote
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Our Services</h2>
                <p class="text-xl text-gray-600">Professional HVAC solutions for every need</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Service 1: Installation -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">AC Installation</h3>
                    <p class="text-gray-600">
                        Expert installation of residential and commercial air conditioning systems with quality equipment and professional service.
                    </p>
                </div>

                <!-- Service 2: Maintenance -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Maintenance Plans</h3>
                    <p class="text-gray-600">
                        Regular maintenance to keep your system running efficiently and extend its lifespan. Prevent costly repairs.
                    </p>
                </div>

                <!-- Service 3: Emergency Repairs -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-red-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">24/7 Emergency Repairs</h3>
                    <p class="text-gray-600">
                        Fast response emergency repair service available around the clock. We're here when you need us most.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Blog Posts Section -->
    <?php if (have_posts()) : ?>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Latest News & Tips</h2>
                <p class="text-xl text-gray-600">Stay informed about HVAC maintenance, energy savings, and more</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php while (have_posts()) : the_post(); ?>
                    <article class="bg-gray-50 rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow">
                        <?php if (has_post_thumbnail()) : ?>
                            <a href="<?php the_permalink(); ?>">
                                <?php the_post_thumbnail('medium', array('class' => 'w-full h-48 object-cover')); ?>
                            </a>
                        <?php else : ?>
                            <div class="w-full h-48 bg-gradient-to-br from-blue-400 to-blue-600"></div>
                        <?php endif; ?>

                        <div class="p-6">
                            <div class="text-sm text-gray-500 mb-2">
                                <?php echo get_the_date(); ?>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-3 hover:text-blue-600 transition-colors">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h3>

                            <div class="text-gray-600 mb-4">
                                <?php echo wp_trim_words(get_the_excerpt(), 20); ?>
                            </div>

                            <a href="<?php the_permalink(); ?>" class="text-blue-600 font-semibold hover:text-blue-700 transition-colors">
                                Read More →
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-12 text-center">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => '← Previous',
                    'next_text' => 'Next →',
                    'class' => 'inline-flex gap-2'
                ));
                ?>
            </div>
        </div>
    </section>
    <?php else : ?>
    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">No posts found</h2>
            <p class="text-gray-600 mb-8">Get started by creating your first post!</p>
            <a href="<?php echo admin_url('post-new.php'); ?>" class="bg-blue-600 text-white px-6 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors inline-block">
                Create Post
            </a>
        </div>
    </section>
    <?php endif; ?>

    <!-- CTA Section -->
    <section id="contact" class="py-20 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">Need AC Service Today?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Call us now for fast, reliable HVAC service. Emergency repairs available 24/7.
            </p>
            <a href="tel:+15551234567" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-50 transition-all transform hover:scale-105 inline-block">
                Call (555) 123-4567
            </a>
        </div>
    </section>

    <!-- Tailwind Test Section -->
    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="bg-white rounded-lg shadow-lg p-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">🎨 Tailwind CSS + Vite Test</h2>
                <p class="text-gray-600 mb-6">This section confirms that Tailwind CSS and Vite are working correctly.</p>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <div class="bg-blue-500 text-white p-4 rounded-lg text-center font-semibold">Blue</div>
                    <div class="bg-green-500 text-white p-4 rounded-lg text-center font-semibold">Green</div>
                    <div class="bg-red-500 text-white p-4 rounded-lg text-center font-semibold">Red</div>
                    <div class="bg-purple-500 text-white p-4 rounded-lg text-center font-semibold">Purple</div>
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 text-blue-900 mb-4">
                    <strong>✓ Assets Loaded:</strong> If you see colors and styling, everything is working!
                </div>

                <div class="bg-green-50 border border-green-200 rounded-lg p-4 text-green-900">
                    <strong>Console Check:</strong> Open your browser console - you should see "SunnySide AC theme loaded with Vite"
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
