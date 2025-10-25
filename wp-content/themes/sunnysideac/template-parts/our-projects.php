<?php
/**
 * Our Projects Section Template Part
 * Displays an interactive gallery showcasing HVAC projects with carousel and lightbox functionality
 */

// Projects data - comprehensive HVAC project showcase
$projects = array(
	// Smart Technology & Controls
	array(
		'id'        => 1,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/smart_ecobee_thermostat_installation.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/smart_ecobee_thermostat_installation.png' ),
		'alt'       => 'Smart Ecobee thermostat installation showing modern climate control technology',
	),
	array(
		'id'        => 2,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/daikin_fit_control_board_closeup.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/daikin_fit_control_board_closeup.png' ),
		'alt'       => 'Daikin Fit control board closeup showing advanced system controls',
	),

	// Commercial & Heavy Equipment
	array(
		'id'        => 3,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/commercial_crane_hvac_installation.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/commercial_crane_hvac_installation.png' ),
		'alt'       => 'Commercial HVAC crane installation for large-scale projects',
	),
	array(
		'id'        => 4,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/technician_rooftop_commercial_service.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/technician_rooftop_commercial_service.png' ),
		'alt'       => 'Professional technician performing rooftop commercial HVAC service',
	),
	array(
		'id'        => 5,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/crane_assisted_hvac_positioning.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/crane_assisted_hvac_positioning.png' ),
		'alt'       => 'Crane-assisted HVAC unit positioning for commercial installation',
	),
	array(
		'id'        => 6,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/commercial_hvac_crane_operation.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/commercial_hvac_crane_operation.png' ),
		'alt'       => 'Commercial HVAC crane operation for heavy equipment placement',
	),
	array(
		'id'        => 7,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/commercial_ac_equipment_installation.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/commercial_ac_equipment_installation.png' ),
		'alt'       => 'Commercial AC equipment installation with professional setup',
	),
	array(
		'id'        => 8,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/professional_commercial_hvac_install.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/professional_commercial_hvac_install.png' ),
		'alt'       => 'Professional commercial HVAC installation project',
	),

	// Residential Installations
	array(
		'id'        => 9,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/daikin_outdoor_unit_residential_install.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/daikin_outdoor_unit_residential_install.png' ),
		'alt'       => 'Daikin outdoor unit residential installation with professional setup',
	),
	array(
		'id'        => 10,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/goodman_package_unit_residential_install.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/goodman_package_unit_residential_install.png' ),
		'alt'       => 'Goodman package unit residential installation with professional outdoor setup',
	),
	array(
		'id'        => 11,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/daikin_mini_split_system_install.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/daikin_mini_split_system_install.png' ),
		'alt'       => 'Daikin mini split system installation for efficient zone cooling',
	),
	array(
		'id'        => 12,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/residential_cooling_system_setup.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/residential_cooling_system_setup.png' ),
		'alt'       => 'Residential cooling system setup with professional installation',
	),
	array(
		'id'        => 13,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/cooling_system_installation_progress.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/cooling_system_installation_progress.png' ),
		'alt'       => 'Cooling system installation progress showing expert workmanship',
	),
	array(
		'id'        => 14,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/residential_ac_unit_installation.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/residential_ac_unit_installation.png' ),
		'alt'       => 'Residential AC unit installation with proper placement',
	),
	array(
		'id'        => 15,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/ac_condenser_installation_work.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/ac_condenser_installation_work.png' ),
		'alt'       => 'AC condenser installation work with professional techniques',
	),
	array(
		'id'        => 16,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/outdoor_unit_installation_progress.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/outdoor_unit_installation_progress.png' ),
		'alt'       => 'Outdoor unit installation progress with expert positioning',
	),

	// Furnace & Heating Systems
	array(
		'id'        => 17,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/basement_furnace_installation_complete.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/basement_furnace_installation_complete.png' ),
		'alt'       => 'Basement furnace installation completed with professional piping',
	),
	array(
		'id'        => 18,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/furnace_installation_progress.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/furnace_installation_progress.png' ),
		'alt'       => 'Furnace installation progress showing systematic approach',
	),
	array(
		'id'        => 19,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/furnace_system_garage_installation.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/furnace_system_garage_installation.png' ),
		'alt'       => 'Garage furnace system installation with proper ventilation',
	),
	array(
		'id'        => 20,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/furnace_ductwork_connection.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/furnace_ductwork_connection.png' ),
		'alt'       => 'Furnace ductwork connection showing professional installation',
	),
	array(
		'id'        => 21,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/completed_furnace_installation.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/completed_furnace_installation.png' ),
		'alt'       => 'Completed furnace installation with quality finish work',
	),
	array(
		'id'        => 22,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/residential_heating_system_install.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/residential_heating_system_install.png' ),
		'alt'       => 'Residential heating system installation with professional setup',
	),

	// Service & Maintenance
	array(
		'id'        => 23,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/carrier_indoor_unit_service_repair.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/carrier_indoor_unit_service_repair.png' ),
		'alt'       => 'Professional technician servicing Carrier indoor HVAC unit',
	),
	array(
		'id'        => 24,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/daikin_outdoor_unit_service_work.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/daikin_outdoor_unit_service_work.png' ),
		'alt'       => 'Daikin outdoor unit service work by experienced technician',
	),
	array(
		'id'        => 25,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/experienced_technician_outdoor_service.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/experienced_technician_outdoor_service.png' ),
		'alt'       => 'Experienced technician performing outdoor HVAC service work',
	),
	array(
		'id'        => 26,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/air_handler_unit_maintenance.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/air_handler_unit_maintenance.png' ),
		'alt'       => 'Air handler unit maintenance with thorough system inspection',
	),
	array(
		'id'        => 27,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/hvac_blower_motor_component_repair.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/hvac_blower_motor_component_repair.png' ),
		'alt'       => 'HVAC blower motor component repair showing internal system maintenance',
	),
	array(
		'id'        => 28,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/hvac_maintenance_equipment_setup.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/hvac_maintenance_equipment_setup.png' ),
		'alt'       => 'HVAC maintenance equipment setup for comprehensive service',
	),

	// Diagnostic & Professional Tools
	array(
		'id'        => 29,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/refrigerant_gauge_diagnostic_tool.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/refrigerant_gauge_diagnostic_tool.png' ),
		'alt'       => 'HVAC refrigerant pressure gauges showing professional diagnostic equipment',
	),
	array(
		'id'        => 30,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/ac_service_diagnostic_process.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/ac_service_diagnostic_process.png' ),
		'alt'       => 'AC service diagnostic process with precision testing equipment',
	),
	array(
		'id'        => 31,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/diagnostic_equipment_in_use.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/diagnostic_equipment_in_use.png' ),
		'alt'       => 'Professional diagnostic equipment in use for system analysis',
	),
	array(
		'id'        => 32,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/refrigeration_testing_equipment.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/refrigeration_testing_equipment.png' ),
		'alt'       => 'Refrigeration testing equipment for comprehensive system evaluation',
	),

	// Team & Professional Work
	array(
		'id'        => 33,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/professional_hvac_installation_team.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/professional_hvac_installation_team.png' ),
		'alt'       => 'Professional HVAC installation team working on complex project',
	),
	array(
		'id'        => 34,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/professional_hvac_work_progress.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/professional_hvac_work_progress.png' ),
		'alt'       => 'Professional HVAC work progress showing attention to detail',
	),
	array(
		'id'        => 35,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/hvac_system_components_installation.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/hvac_system_components_installation.png' ),
		'alt'       => 'HVAC system components installation with expert coordination',
	),
	array(
		'id'        => 36,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/professional_hvac_system_finish.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/professional_hvac_system_finish.png' ),
		'alt'       => 'Professional HVAC system finish work with quality standards',
	),

	// Company Branding
	array(
		'id'        => 37,
		'thumbnail' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/thumbnails/sunnyside_ac_service_van_branding.png' ),
		'full_size' => sunnysideac_asset_url( 'assets/images/home-page/our-projects-pictures/full-size/sunnyside_ac_service_van_branding.png' ),
		'alt'       => 'SunnySide AC service van showcasing professional branding and equipment',
	),
);

// Icons data
$icons = array(
	'our_projects'     => sunnysideac_asset_url( 'assets/images/home-page/our-projects-icon.svg' ),
	'schedule_service' => sunnysideac_asset_url( 'assets/icons/schedule-service-now-icon.svg' ),
	'left_chevron'     => sunnysideac_asset_url( 'assets/images/home-page/circle-left-chevron-projects.svg' ),
	'right_chevron'    => sunnysideac_asset_url( 'assets/images/home-page/circle-right-chevron-projects-1.svg' ),
);

// Get WordPress data
$tel_href      = SUNNYSIDE_TEL_HREF;
$phone_display = SUNNYSIDE_PHONE_DISPLAY;
?>

<section class="w-full rounded-2xl bg-white p-6 lg:p-9" id="our-projects" role="main" aria-labelledby="our-projects-heading">
	<div class="mx-auto max-w-7xl">
		<!-- Desktop 8-Grid Layout -->
		<div class="relative hidden lg:block" id="desktop-grid">
			<!-- 8-Grid Container (4x2 grid) -->
			<div class="grid h-[600px] grid-cols-4 grid-rows-2 gap-4">
				<!-- Position 1: Top Left - Content Section -->
				<div class="col-span-1 row-span-1 flex flex-col justify-center space-y-4 rounded-lg">
					<!-- Header -->
					<header>
						<?php
						get_template_part(
							'template-parts/title',
							null,
							array(
								'icon'  => $icons['our_projects'],
								'title' => 'Our Projects',
								'id'    => 'our-projects-heading',
								'align' => 'left',
							)
						);
						?>
					</header>

					<!-- Subtitle -->
					<p class="text-center text-2xl leading-tight font-semibold text-black md:text-left">
						Our Work Speaks for Itself - HVAC Projects from Start to Finish
					</p>

					<!-- CTA Button -->
					<a href="<?php echo esc_attr( $tel_href ); ?>"
						class="inline-flex w-full items-center justify-center gap-2 rounded-full bg-gradient-to-r from-yellow-400 to-red-500 px-4 py-3 transition-all duration-200 hover:scale-105 hover:opacity-90 focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:outline-none"
						aria-label="<?php printf( esc_attr__( 'Call to schedule HVAC service - %s', 'sunnysideac' ), esc_html( $phone_display ) ); ?>">
						<div class="h-4 w-4 flex-shrink-0" aria-hidden="true">
							<img class="h-full w-full object-contain"
								alt=""
								src="<?php echo esc_url( $icons['schedule_service'] ); ?>"
								loading="lazy"
								decoding="async" />
						</div>
						<span class="text-sm font-medium whitespace-nowrap text-white">
							Schedule Service Now
						</span>
					</a>
				</div>

				<!-- Project Images (Positions 2-8) -->
				<div class="col-span-1 row-span-1 desktop-project-image" data-position="0">
					<img class="h-full w-full cursor-pointer rounded-lg object-cover shadow-md transition-shadow duration-200 hover:shadow-lg"
						alt="<?php echo esc_attr( $projects[0]['alt'] ); ?>"
						src="<?php echo esc_url( $projects[0]['thumbnail'] ); ?>"
						loading="lazy"
						decoding="async"
						sizes="(max-width: 1024px) 50vw, 25vw" />
				</div>

				<div class="col-span-1 row-span-1 desktop-project-image" data-position="1">
					<img class="h-full w-full cursor-pointer rounded-lg object-cover shadow-md transition-shadow duration-200 hover:shadow-lg"
						alt="<?php echo esc_attr( $projects[1]['alt'] ); ?>"
						src="<?php echo esc_url( $projects[1]['thumbnail'] ); ?>"
						loading="lazy"
						decoding="async"
						sizes="(max-width: 1024px) 50vw, 25vw" />
				</div>

				<div class="col-span-1 row-span-2 desktop-project-image" data-position="2">
					<img class="h-full w-full cursor-pointer rounded-lg object-cover shadow-lg"
						alt="<?php echo esc_attr( $projects[2]['alt'] ); ?>"
						src="<?php echo esc_url( $projects[2]['thumbnail'] ); ?>"
						loading="lazy"
						decoding="async"
						sizes="(max-width: 1024px) 50vw, 25vw" />
				</div>

				<div class="col-span-1 row-span-1 desktop-project-image" data-position="3">
					<img class="h-full w-full cursor-pointer rounded-lg object-cover shadow-md transition-shadow duration-200 hover:shadow-lg"
						alt="<?php echo esc_attr( $projects[3]['alt'] ); ?>"
						src="<?php echo esc_url( $projects[3]['thumbnail'] ); ?>"
						loading="lazy"
						decoding="async"
						sizes="(max-width: 1024px) 50vw, 25vw" />
				</div>

				<div class="col-span-1 row-span-1 desktop-project-image" data-position="4">
					<img class="h-full w-full cursor-pointer rounded-lg object-cover shadow-md transition-shadow duration-200 hover:shadow-lg"
						alt="<?php echo esc_attr( $projects[4]['alt'] ); ?>"
						src="<?php echo esc_url( $projects[4]['thumbnail'] ); ?>"
						loading="lazy"
						decoding="async"
						sizes="(max-width: 1024px) 50vw, 25vw" />
				</div>

				<div class="col-span-1 row-span-1 desktop-project-image" data-position="5">
					<img class="h-full w-full cursor-pointer rounded-lg object-cover shadow-md transition-shadow duration-200 hover:shadow-lg"
						alt="<?php echo esc_attr( $projects[5]['alt'] ); ?>"
						src="<?php echo esc_url( $projects[5]['thumbnail'] ); ?>"
						loading="lazy"
						decoding="async"
						sizes="(max-width: 1024px) 50vw, 25vw" />
				</div>
			</div>

			<!-- Navigation Controls - Positioned absolutely over the grid -->
			<div class="absolute right-4 bottom-4 flex items-center gap-3">
				<button id="prev-desktop-group"
						class="flex h-16 w-16 items-center justify-center rounded-full transition-all duration-200 hover:scale-110 focus:ring-2 focus:ring-orange-400 focus:outline-none"
						aria-label="Previous project group">
					<img class="h-12 w-12" alt="Previous" src="<?php echo esc_url( $icons['left_chevron'] ); ?>" loading="lazy" decoding="async" />
				</button>

				<button id="next-desktop-group"
						class="flex h-16 w-16 items-center justify-center rounded-full transition-all duration-200 hover:scale-110 focus:ring-2 focus:ring-orange-400 focus:outline-none"
						aria-label="Next project group">
					<img class="h-12 w-12" alt="Next" src="<?php echo esc_url( $icons['right_chevron'] ); ?>" loading="lazy" decoding="async" />
				</button>
			</div>
		</div>

		<!-- Mobile Layout -->
		<div class="lg:hidden">
			<!-- Mobile Content Section -->
			<div class="mb-6 flex flex-col items-center justify-center space-y-4">
				<!-- Header -->
				<header>
					<?php
					get_template_part(
						'template-parts/title',
						null,
						array(
							'icon'  => $icons['our_projects'],
							'title' => 'Our Projects',
							'id'    => 'our-projects-heading-mobile',
						)
					);
					?>
				</header>

				<!-- Subtitle -->
				<p class="text-center text-2xl leading-tight font-semibold text-black md:text-left">
					Our Work Speaks for Itself - HVAC Projects from Start to Finish
				</p>

				<!-- CTA Button -->
				<a href="<?php echo esc_attr( $tel_href ); ?>"
					class="inline-flex w-fit items-center justify-center gap-2 rounded-full bg-gradient-to-r from-yellow-400 to-red-500 px-6 py-4 transition-all duration-200 hover:scale-105 hover:opacity-90 focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:outline-none"
					aria-label="<?php printf( esc_attr__( 'Call to schedule HVAC service - %s', 'sunnysideac' ), esc_html( $phone_display ) ); ?>">
					<div class="h-5 w-5 flex-shrink-0" aria-hidden="true">
						<img class="h-full w-full object-contain"
							alt=""
							src="<?php echo esc_url( $icons['schedule_service'] ); ?>"
							loading="lazy"
							decoding="async" />
					</div>
					<span class="text-lg font-medium whitespace-nowrap text-white">
						Schedule Service Now
					</span>
				</a>
			</div>

			<!-- Mobile Carousel View -->
			<div id="mobile-carousel" class="relative transition-all duration-300">
				<div class="relative">
					<div class="h-80 overflow-hidden rounded-lg sm:h-96 md:h-[28rem] lg:h-[32rem]">
						<img id="mobile-current-image"
							class="h-full w-full cursor-pointer object-cover"
							alt="<?php echo esc_attr( $projects[0]['alt'] ); ?>"
							src="<?php echo esc_url( $projects[0]['thumbnail'] ); ?>"
							loading="lazy"
							decoding="async"
							sizes="100vw" />
					</div>

					<!-- Mobile Navigation Controls -->
					<button id="prev-mobile-project"
							class="absolute top-1/2 -left-6 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full transition-all duration-200 hover:scale-110 focus:ring-2 focus:ring-orange-400 focus:outline-none"
							aria-label="Previous project">
						<img class="h-10 w-10"
							alt="Previous"
							src="<?php echo esc_url( $icons['left_chevron'] ); ?>"
							loading="lazy"
							decoding="async" />
					</button>

					<button id="next-mobile-project"
							class="absolute top-1/2 -right-6 z-10 flex h-12 w-12 -translate-y-1/2 items-center justify-center rounded-full transition-all duration-200 hover:scale-110 focus:ring-2 focus:ring-orange-400 focus:outline-none"
							aria-label="Next project">
						<img class="h-10 w-10"
							alt="Next"
							src="<?php echo esc_url( $icons['right_chevron'] ); ?>"
							loading="lazy"
							decoding="async" />
					</button>
				</div>

				<!-- Mobile Navigation Dots -->
				<div class="mt-4 flex justify-center space-x-2" id="mobile-dots">
					<?php foreach ( $projects as $index => $project ) : ?>
						<button class="<?php echo $index === 0 ? 'w-4 scale-120 bg-orange-500' : 'w-2 bg-gray-300 hover:bg-gray-400'; ?> h-2 rounded-full transition-colors duration-200 mobile-dot"
								data-index="<?php echo esc_attr( $index ); ?>"
								aria-label="<?php printf( esc_attr__( 'Go to project %d', 'sunnysideac' ), $index + 1 ); ?>">
						</button>
					<?php endforeach; ?>
				</div>

				<!-- View Full Gallery Button -->
				<div class="mt-6 flex justify-center">
					<button id="view-gallery-btn"
							class="inline-flex items-center justify-center gap-2 rounded-full bg-gradient-to-r from-yellow-400 to-red-500 px-6 py-3 transition-all duration-200 hover:scale-105 hover:opacity-90 focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:outline-none"
							aria-label="View full gallery of all HVAC projects">
						<span class="text-sm font-medium text-white">
							View Full Gallery
						</span>
					</button>
				</div>
			</div>

			<!-- Full Gallery View (Mobile) -->
			<div id="mobile-gallery" class="mt-8 transition-all duration-300 hidden">
				<!-- Gallery Header -->
				<div class="mb-6 flex items-center justify-between">
					<div class="text-xl font-semibold text-black">
						All Projects Gallery
					</div>
					<button id="back-to-carousel-btn"
							class="inline-flex items-center justify-center gap-2 rounded-full bg-gray-100 px-4 py-2 transition-all duration-200 hover:bg-gray-200 focus:ring-2 focus:ring-orange-400 focus:ring-offset-2 focus:outline-none"
							aria-label="Back to carousel view">
						<span class="text-sm font-medium text-gray-700">
							Back to Carousel
						</span>
					</button>
				</div>

				<!-- Gallery Grid -->
				<div class="grid grid-cols-2 gap-4 md:grid-cols-3" id="gallery-grid">
					<?php foreach ( $projects as $index => $project ) : ?>
						<div class="aspect-square overflow-hidden rounded-lg shadow-md gallery-item"
							data-index="<?php echo esc_attr( $index ); ?>">
							<img class="h-full w-full cursor-pointer object-cover transition-all duration-200 hover:scale-105"
								alt="<?php echo esc_attr( $project['alt'] ); ?>"
								src="<?php echo esc_url( $project['thumbnail'] ); ?>"
								loading="lazy"
								decoding="async"
								sizes="(max-width: 768px) 50vw, 33vw" />
						</div>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	</div>

	<!-- Lightbox Overlay -->
	<div id="lightbox" class="fixed inset-0 z-50 flex items-center justify-center hidden">
		<!-- Blurred Background -->
		<div class="absolute inset-0 bg-black/80 backdrop-blur-sm" id="lightbox-backdrop"></div>

		<!-- Lightbox Container with Padding -->
		<div class="relative flex h-full w-full items-center justify-center p-6 md:p-8 lg:p-12">
			<!-- Close Button -->
			<button id="lightbox-close"
					class="absolute top-6 right-6 z-60 flex h-14 w-14 items-center justify-center rounded-full bg-black/60 text-white backdrop-blur-sm transition-all duration-200 hover:scale-110 hover:bg-black/80 focus:ring-2 focus:ring-white focus:outline-none md:top-8 md:right-8 lg:top-12 lg:right-12"
					aria-label="Close lightbox">
				<span class="text-3xl leading-none">×</span>
			</button>

			<!-- Main Image Container -->
			<div class="relative flex max-h-[70vh] max-w-[70vw] items-center justify-center md:max-h-[65vh] md:max-w-[65vw] lg:max-h-[60vh] lg:max-w-[60vw]"
				id="lightbox-image-container">
				<img id="lightbox-image"
					class="max-h-full max-w-full rounded-2xl object-contain shadow-2xl"
					alt=""
					src=""
					loading="eager"
					decoding="sync" />
			</div>

			<!-- Previous Button -->
			<button id="lightbox-prev"
					class="absolute top-1/2 left-4 z-60 flex h-16 w-16 -translate-y-1/2 items-center justify-center transition-all duration-200 hover:scale-125 focus:outline-none md:left-6 md:h-20 md:w-20 lg:left-8"
					aria-label="Previous image">
				<img class="h-14 w-14 drop-shadow-lg md:h-16 md:w-16"
					alt="Previous"
					src="<?php echo esc_url( $icons['left_chevron'] ); ?>"
					loading="lazy"
					decoding="async" />
			</button>

			<!-- Next Button -->
			<button id="lightbox-next"
					class="absolute top-1/2 right-4 z-60 flex h-16 w-16 -translate-y-1/2 items-center justify-center transition-all duration-200 hover:scale-125 focus:outline-none md:right-6 md:h-20 md:w-20 lg:right-8"
					aria-label="Next image">
				<img class="h-14 w-14 drop-shadow-lg md:h-16 md:w-16"
					alt="Next"
					src="<?php echo esc_url( $icons['right_chevron'] ); ?>"
					loading="lazy"
					decoding="async" />
			</button>

			<!-- Image Counter -->
			<div class="absolute bottom-6 left-1/2 -translate-x-1/2 rounded-full bg-black/60 px-6 py-3 text-white backdrop-blur-sm md:bottom-8 lg:bottom-12">
				<span class="text-base font-medium md:text-lg" id="lightbox-counter">
					1 / <?php echo count( $projects ); ?>
				</span>
			</div>
		</div>
	</div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
	// Projects data from PHP
	const projects = <?php echo json_encode( $projects ); ?>;

	// Desktop image groups
	const desktopImageGroups = [
		// Group 1: Smart Tech & Residential Excellence
		[projects[0], projects[8], projects[9], projects[21], projects[15], projects[10]],
		// Group 2: Commercial & Heavy Equipment
		[projects[2], projects[3], projects[4], projects[6], projects[7], projects[31]],
		// Group 3: Service & Maintenance Excellence
		[projects[22], projects[23], projects[24], projects[25], projects[26], projects[21]],
		// Group 4: Heating & Furnace Systems
		[projects[16], projects[17], projects[18], projects[19], projects[20], projects[15]],
		// Group 5: Diagnostic & Professional Tools
		[projects[27], projects[28], projects[29], projects[30], projects[1], projects[32]],
		// Group 6: Cooling Systems & AC Units
		[projects[10], projects[11], projects[12], projects[13], projects[14], projects[9]],
		// Group 7: Advanced Commercial Projects
		[projects[5], projects[4], projects[6], projects[2], projects[7], projects[3]],
		// Group 8: Professional Excellence & Branding
		[projects[35], projects[31], projects[32], projects[33], projects[34], projects[0]],
	];

	// State variables
	let currentMobileIndex = 0;
	let currentDesktopGroupIndex = 0;
	let lightboxIndex = 0;
	let galleryOpen = false;
	let autoplayInterval = null;

	// DOM elements
	const lightbox = document.getElementById('lightbox');
	const lightboxImage = document.getElementById('lightbox-image');
	const lightboxCounter = document.getElementById('lightbox-counter');
	const mobileCurrentImage = document.getElementById('mobile-current-image');
	const mobileCarousel = document.getElementById('mobile-carousel');
	const mobileGallery = document.getElementById('mobile-gallery');

	// Utility functions
	function updateMobileImage() {
		const project = projects[currentMobileIndex];
		if (mobileCurrentImage) {
			mobileCurrentImage.src = project.thumbnail;
			mobileCurrentImage.alt = project.alt;
		}
		updateMobileDots();
	}

	function updateMobileDots() {
		const dots = document.querySelectorAll('.mobile-dot');
		dots.forEach((dot, index) => {
			if (index === currentMobileIndex) {
				dot.className = 'h-2 rounded-full transition-colors duration-200 mobile-dot w-4 scale-120 bg-orange-500';
			} else {
				dot.className = 'h-2 rounded-full transition-colors duration-200 mobile-dot w-2 bg-gray-300 hover:bg-gray-400';
			}
		});
	}

	function updateDesktopGrid() {
		const currentGroup = desktopImageGroups[currentDesktopGroupIndex % desktopImageGroups.length];
		const desktopImages = document.querySelectorAll('.desktop-project-image');

		desktopImages.forEach((imageDiv, index) => {
			if (currentGroup[index]) {
				const img = imageDiv.querySelector('img');
				if (img) {
					img.src = currentGroup[index].thumbnail;
					img.alt = currentGroup[index].alt;
				}
				imageDiv.dataset.projectIndex = projects.findIndex(p => p.id === currentGroup[index].id);
			}
		});
	}

	// Mobile navigation
	function nextMobileProject() {
		currentMobileIndex = (currentMobileIndex + 1) % projects.length;
		updateMobileImage();
	}

	function prevMobileProject() {
		currentMobileIndex = currentMobileIndex === 0 ? projects.length - 1 : currentMobileIndex - 1;
		updateMobileImage();
	}

	// Desktop navigation
	function nextDesktopGroup() {
		currentDesktopGroupIndex = (currentDesktopGroupIndex + 1) % desktopImageGroups.length;
		updateDesktopGrid();
	}

	function prevDesktopGroup() {
		currentDesktopGroupIndex = currentDesktopGroupIndex === 0 ? desktopImageGroups.length - 1 : currentDesktopGroupIndex - 1;
		updateDesktopGrid();
	}

	// Lightbox functions
	function openLightbox(index) {
		lightboxIndex = index;
		updateLightboxImage();
		lightbox.classList.remove('hidden');
		document.body.style.overflow = 'hidden';
	}

	function closeLightbox() {
		lightbox.classList.add('hidden');
		document.body.style.overflow = '';
	}

	function updateLightboxImage() {
		const project = projects[lightboxIndex];
		lightboxImage.src = project.full_size;
		lightboxImage.alt = project.alt;
		lightboxCounter.textContent = `${lightboxIndex + 1} / ${projects.length}`;
	}

	function nextLightboxImage() {
		lightboxIndex = (lightboxIndex + 1) % projects.length;
		updateLightboxImage();
	}

	function prevLightboxImage() {
		lightboxIndex = lightboxIndex === 0 ? projects.length - 1 : lightboxIndex - 1;
		updateLightboxImage();
	}

	// Auto-play for mobile
	function startAutoPlay() {
		if (window.innerWidth < 1024 && !galleryOpen) {
			autoplayInterval = setInterval(nextMobileProject, 5000);
		}
	}

	function stopAutoPlay() {
		if (autoplayInterval) {
			clearInterval(autoplayInterval);
			autoplayInterval = null;
		}
	}

	// Event listeners
	document.getElementById('next-mobile-project')?.addEventListener('click', nextMobileProject);
	document.getElementById('prev-mobile-project')?.addEventListener('click', prevMobileProject);
	document.getElementById('next-desktop-group')?.addEventListener('click', nextDesktopGroup);
	document.getElementById('prev-desktop-group')?.addEventListener('click', prevDesktopGroup);

	// Mobile dots navigation
	document.querySelectorAll('.mobile-dot').forEach((dot, index) => {
		dot.addEventListener('click', () => {
			currentMobileIndex = index;
			updateMobileImage();
		});
	});

	// Gallery toggle
	document.getElementById('view-gallery-btn')?.addEventListener('click', () => {
		galleryOpen = true;
		mobileCarousel.classList.add('hidden');
		mobileGallery.classList.remove('hidden');
		stopAutoPlay();
	});

	document.getElementById('back-to-carousel-btn')?.addEventListener('click', () => {
		galleryOpen = false;
		mobileGallery.classList.add('hidden');
		mobileCarousel.classList.remove('hidden');
		startAutoPlay();
	});

	// Lightbox events
	document.getElementById('lightbox-close')?.addEventListener('click', closeLightbox);
	document.getElementById('lightbox-backdrop')?.addEventListener('click', closeLightbox);
	document.getElementById('lightbox-next')?.addEventListener('click', (e) => {
		e.stopPropagation();
		nextLightboxImage();
	});
	document.getElementById('lightbox-prev')?.addEventListener('click', (e) => {
		e.stopPropagation();
		prevLightboxImage();
	});

	// Mobile image click to open lightbox
	mobileCurrentImage?.addEventListener('click', () => {
		openLightbox(currentMobileIndex);
	});

	// Desktop grid image clicks
	document.querySelectorAll('.desktop-project-image').forEach(imageDiv => {
		imageDiv.addEventListener('click', () => {
			const projectIndex = parseInt(imageDiv.dataset.projectIndex || '0');
			openLightbox(projectIndex);
		});
	});

	// Gallery image clicks
	document.querySelectorAll('.gallery-item').forEach(item => {
		item.addEventListener('click', () => {
			const index = parseInt(item.dataset.index || '0');
			openLightbox(index);
		});
	});

	// Keyboard navigation
	document.addEventListener('keydown', (event) => {
		if (lightbox.classList.contains('hidden')) return;

		switch (event.key) {
			case 'Escape':
				closeLightbox();
				break;
			case 'ArrowLeft':
				prevLightboxImage();
				break;
			case 'ArrowRight':
				nextLightboxImage();
				break;
		}
	});

	// Responsive auto-play
	function handleResize() {
		if (window.innerWidth >= 1024) {
			stopAutoPlay();
		} else if (!galleryOpen) {
			startAutoPlay();
		}
	}

	window.addEventListener('resize', handleResize);

	// Initialize
	updateDesktopGrid();
	updateMobileImage();
	startAutoPlay();

	// Pause auto-play on hover
	const section = document.getElementById('our-projects');
	section?.addEventListener('mouseenter', stopAutoPlay);
	section?.addEventListener('mouseleave', () => {
		if (!galleryOpen) startAutoPlay();
	});
});
</script>