<?php get_header(); ?>

<main class="min-h-screen bg-gray-50">
    <!-- Hero Section -->
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 text-white py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl">
                <h1 class="text-5xl font-bold mb-6">
                    Welcome to <?php bloginfo('name'); ?>
                </h1>
                <p class="text-xl text-blue-100 mb-8">
                    A modern WordPress starter theme built with Vite and Tailwind CSS v4. Fast development with Hot Module Replacement.
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="#features" class="bg-white text-blue-600 px-8 py-3 rounded-lg font-semibold hover:bg-blue-50 transition-all transform hover:scale-105">
                        View Features
                    </a>
                    <a href="#documentation" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-blue-600 transition-all">
                        Documentation
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Features</h2>
                <p class="text-xl text-gray-600">Everything you need to build modern WordPress themes</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1: Vite -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Vite Development</h3>
                    <p class="text-gray-600">
                        Lightning-fast Hot Module Replacement (HMR) for instant feedback during development.
                    </p>
                </div>

                <!-- Feature 2: Tailwind -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-green-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Tailwind CSS v4</h3>
                    <p class="text-gray-600">
                        Modern utility-first CSS framework with CSS-native configuration and automatic tree-shaking.
                    </p>
                </div>

                <!-- Feature 3: Smart Detection -->
                <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-purple-100 rounded-lg flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Smart Detection</h3>
                    <p class="text-gray-600">
                        Automatically switches between dev server and production builds. No configuration needed.
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
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Latest Posts</h2>
                <p class="text-xl text-gray-600">Your blog posts will appear here</p>
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
    <section id="documentation" class="py-20 bg-gradient-to-r from-blue-600 to-blue-800 text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl font-bold mb-6">Ready to Get Started?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Clone this starter theme and start building your next WordPress project with modern tools.
            </p>
            <a href="https://github.com/yourusername/wordpress-vite-starter" class="bg-white text-blue-600 px-8 py-4 rounded-lg font-semibold text-lg hover:bg-blue-50 transition-all transform hover:scale-105 inline-block">
                View on GitHub
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
                    <strong>Console Check:</strong> Open your browser console - you should see "WordPress Vite Starter theme loaded with Vite"
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
