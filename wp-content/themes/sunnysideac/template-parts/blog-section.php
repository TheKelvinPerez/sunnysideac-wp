<?php
/**
 * Blog Section Component
 * Self-contained component with blog posts display
 */

// Component data (like props in React)
$blog_posts = [
	[
		'id'          => 1,
		'title'       => '5 Signs Your AC Needs Professional Maintenance',
		'description' => 'Learn the warning signs that indicate your air conditioning system needs immediate professional attention to avoid costly breakdowns.',
		'author'      => 'SunnySide AC',
		'date'        => '15 DEC 2024',
		'image'       => sunnysideac_asset_url('assets/images/home-page/blog/blog-post-image-1.png'),
		'category'    => 'Maintenance',
		'slug'        => 'ac-maintenance-warning-signs',
	],
	[
		'id'          => 2,
		'title'       => 'How to Improve Indoor Air Quality in Your Home',
		'description' => "Discover practical tips and solutions to enhance your home's air quality for better health and comfort for your family.",
		'author'      => 'SunnySide AC',
		'date'        => '10 DEC 2024',
		'image'       => sunnysideac_asset_url('assets/images/home-page/blog/blog-post-image-2.png'),
		'category'    => 'Health & Safety',
		'slug'        => 'improve-indoor-air-quality',
	],
	[
		'id'          => 3,
		'title'       => 'Energy-Efficient HVAC Tips to Lower Your Bills',
		'description' => 'Simple strategies to optimize your heating and cooling system for maximum efficiency and significant cost savings.',
		'author'      => 'SunnySide AC',
		'date'        => '05 DEC 2024',
		'image'       => sunnysideac_asset_url('assets/images/home-page/blog/blog-post-image-3.png'),
		'category'    => 'Energy Savings',
		'slug'        => 'energy-efficient-hvac-tips',
	],
];

$images = [
	'blog_title_icon'    => sunnysideac_asset_url('assets/images/home-page/blog/blog-title-icon.svg'),
	'air_con_blog_icon'  => sunnysideac_asset_url('assets/images/home-page/blog/air-con-blog-icon.svg'),
	'blog_auther_icon'   => sunnysideac_asset_url('assets/images/home-page/blog/blog-auther-icon.svg'),
	'read_more_arrow'    => sunnysideac_asset_url('assets/images/home-page/blog/read-more-arrow-up-right.svg'),
];
?>

<section
	class="w-full rounded-2xl bg-white px-4 py-12 sm:px-6 lg:px-8"
	aria-labelledby="our-blog-heading"
>
	<div class="mx-auto max-w-7xl">
		<!-- Header Section -->
		<header class="mb-12 text-center">
			<?php
			get_template_part('template-parts/title', null, [
				'icon'  => $images['blog_title_icon'],
				'title' => 'Our Blog',
				'id'    => 'our-blog'
			]);
			?>
			<?php
			get_template_part('template-parts/subheading', null, [
				'text'  => 'Stay Cool, Stay Warm, Stay Informed',
				'class' => 'mt-4 text-center'
			]);
			?>
		</header>

		<!-- Blog Cards Grid -->
		<div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
			<?php foreach ($blog_posts as $post): ?>
				<a
					href="<?php echo esc_url('/blog/' . $post['slug']); ?>"
					aria-label="<?php echo esc_attr('Read full article: ' . $post['title']); ?>"
					class="group mx-auto block max-w-[375px] cursor-pointer overflow-hidden rounded-b-2xl bg-white shadow-lg transition-all duration-300 hover:shadow-xl focus:ring-2 focus:ring-[#F79E37] focus:ring-offset-2 focus:outline-none"
				>
					<!-- Image Container with Date Badge -->
					<div class="relative h-[292px] w-full overflow-hidden rounded-[20px]">
						<img
							src="<?php echo esc_url($post['image']); ?>"
							alt="<?php echo esc_attr($post['title']); ?>"
							class="h-full w-full object-cover object-center transition-transform duration-300 group-hover:scale-105"
						/>

						<!-- Gradient Overlay -->
						<div
							class="absolute right-0 bottom-0 left-0 h-3/4 rounded-[20px]"
							style="background: linear-gradient(360deg, #000000 0%, rgba(102, 102, 102, 0) 100%)"
						></div>

						<!-- Date Badge -->
						<div class="absolute right-4 bottom-4 z-10">
							<div class="rounded-lg bg-gradient-to-r from-[#FDC85F] to-[#E64B30] px-3 py-2 text-center shadow-lg">
								<?php
								$date_parts = explode(' ', $post['date']);
								if (count($date_parts) >= 3):
								?>
									<div class="text-sm font-bold text-white">
										<?php echo esc_html($date_parts[0]); ?>
									</div>
									<div class="text-xs font-thin text-white">
										<?php echo esc_html($date_parts[1]); ?>
									</div>
									<div class="text-xs font-thin text-white">
										<?php echo esc_html($date_parts[2]); ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>

					<!-- Card Content -->
					<div class="cursor-pointer p-6">
						<!-- Author and Category -->
						<div class="mb-4 flex items-center justify-between">
							<!-- Author -->
							<div class="flex items-center gap-2">
								<img
									src="<?php echo esc_url($images['blog_auther_icon']); ?>"
									alt="Author"
									class="h-4 w-4"
								/>
								<span class="text-sm font-medium text-gray-600">
									<?php echo esc_html($post['author']); ?>
								</span>
							</div>

							<!-- Category -->
							<div class="flex items-center gap-2">
								<img
									src="<?php echo esc_url($images['air_con_blog_icon']); ?>"
									alt="Category"
									class="h-4 w-4"
								/>
								<span class="text-sm font-medium text-[#F79E37]">
									<?php echo esc_html($post['category']); ?>
								</span>
							</div>
						</div>

						<!-- Title -->
						<h3 class="mb-3 text-lg leading-tight font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#F79E37]">
							<?php echo esc_html($post['title']); ?>
						</h3>

						<!-- Description -->
						<p class="mb-4 text-sm leading-relaxed text-gray-600">
							<?php echo esc_html($post['description']); ?>
						</p>

						<!-- Read More Link -->
						<div class="flex cursor-pointer items-center gap-2">
							<span class="cursor-pointer text-sm font-semibold text-gray-900 transition-colors duration-200 group-hover:text-[#F79E37]">
								Read More
							</span>
							<img
								src="<?php echo esc_url($images['read_more_arrow']); ?>"
								alt="Read more"
								class="h-3 w-3 transition-transform duration-200 group-hover:translate-x-1 group-hover:translate-y-[-2px]"
							/>
						</div>
					</div>
				</a>
			<?php endforeach; ?>
		</div>
	</div>
</section>