<?php
/**
 * Template Name: Test Dashboard
 *
 * A comprehensive testing dashboard for all city/service page combinations.
 * Displays all 360 possible city/service URLs with status tracking.
 *
 * @package SunnysideAC
 */

get_header();
?>

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900"><?php the_title(); ?></h1>
                    <p class="text-gray-600 mt-1">All 360 City/Service Page Combinations</p>
                </div>
                <div class="flex space-x-4">
                    <button onclick="expandAll()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        Expand All
                    </button>
                    <button onclick="collapseAll()" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition">
                        Collapse All
                    </button>
                    <a href="<?php echo admin_url(); ?>" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        WP Admin
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php
    // Define all cities and services
    $cities = [
        ['name' => 'Miami', 'slug' => 'miami'],
        ['name' => 'Fort Lauderdale', 'slug' => 'fort-lauderdale'],
        ['name' => 'Boca Raton', 'slug' => 'boca-raton'],
        ['name' => 'West Palm Beach', 'slug' => 'west-palm-beach'],
        ['name' => 'Palm Beach', 'slug' => 'palm-beach'],
        ['name' => 'Palm Springs', 'slug' => 'palm-springs'],
        ['name' => 'Wellington', 'slug' => 'wellington'],
        ['name' => 'Royal Palm Beach', 'slug' => 'royal-palm-beach'],
        ['name' => 'Lighthouse Point', 'slug' => 'light-house-point'],
        ['name' => 'Deerfield Beach', 'slug' => 'deerfield-beach'],
        ['name' => 'Homestead', 'slug' => 'homestead'],
        ['name' => 'Key Biscayne', 'slug' => 'key-biscayne'],
        ['name' => 'Pompano Beach', 'slug' => 'pompano-beach'],
        ['name' => 'Palmetto Bay', 'slug' => 'palmetto-bay'],
        ['name' => 'Tamarac', 'slug' => 'tamarac'],
        ['name' => 'Coral Gables', 'slug' => 'coral-gables'],
        ['name' => 'Coral Springs', 'slug' => 'coral-springs'],
        ['name' => 'Plantation', 'slug' => 'plantation'],
        ['name' => 'Sunrise', 'slug' => 'sunrise'],
        ['name' => 'Weston', 'slug' => 'weston'],
        ['name' => 'Hialeah Lakes', 'slug' => 'hialeah-lakes'],
        ['name' => 'Sunny Isles', 'slug' => 'sunny-isles'],
        ['name' => 'West Park', 'slug' => 'west-park'],
        ['name' => 'Pembroke Park', 'slug' => 'pembroke-park'],
        ['name' => 'Davie', 'slug' => 'davie'],
        ['name' => 'Hollywood', 'slug' => 'hollywood'],
        ['name' => 'Pembroke Pines', 'slug' => 'pembroke-pines'],
        ['name' => 'South West Ranches', 'slug' => 'south-west-ranches'],
        ['name' => 'Miami Lakes', 'slug' => 'miami-lakes'],
        ['name' => 'Miramar', 'slug' => 'miramar']
    ];

    $services = [
        ['name' => 'AC Repair', 'slug' => 'ac-repair', 'status' => 'completed'],
        ['name' => 'AC Installation', 'slug' => 'ac-installation', 'status' => 'completed'],
        ['name' => 'AC Replacement', 'slug' => 'ac-replacement', 'status' => 'pending'],
        ['name' => 'Heat Pumps', 'slug' => 'heat-pumps', 'status' => 'pending'],
        ['name' => 'Heating Repair', 'slug' => 'heating-repair', 'status' => 'pending'],
        ['name' => 'Heating Installation', 'slug' => 'heating-installation', 'status' => 'pending'],
        ['name' => 'Heating Replacement', 'slug' => 'heating-replacement', 'status' => 'pending'],
        ['name' => 'Furnaces', 'slug' => 'furnances', 'status' => 'pending', 'note' => 'slug typo'],
        ['name' => 'Indoor Air Quality', 'slug' => 'indoor-air-quality', 'status' => 'pending'],
        ['name' => 'Ductless Mini-Splits', 'slug' => 'ductless-mini-split', 'status' => 'pending'],
        ['name' => 'UV Lights', 'slug' => 'uv-lights', 'status' => 'pending'],
        ['name' => 'Filters', 'slug' => 'filteres', 'status' => 'pending', 'note' => 'slug typo']
    ];

    $total_pages = count($cities) * count($services);
    $completed_services = array_filter($services, function($service) { return $service['status'] === 'completed'; });
    $completed_pages = count($completed_services) * count($cities);
    $remaining_pages = $total_pages - $completed_pages;
    $completion_percentage = round(($completed_pages / $total_pages) * 100, 1);

    // Function to get status badge HTML
    function get_status_badge($service_status) {
        switch ($service_status) {
            case 'completed':
                return '<span class="px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded">✅ Complete</span>';
            case 'in_progress':
                return '<span class="px-2 py-1 text-xs font-semibold text-yellow-800 bg-yellow-100 rounded">⏳ In Progress</span>';
            default:
                return '<span class="px-2 py-1 text-xs font-semibold text-gray-800 bg-gray-100 rounded">⬜ Pending</span>';
        }
    }
    ?>

    <!-- Cache buster for testing -->
    <div style="display: none;">Updated: <?php echo date('Y-m-d H:i:s'); ?> v2.1</div>

    <!-- Statistics Cards -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="stats-card text-white p-6 rounded-lg shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="text-3xl font-bold"><?php echo $total_pages; ?></div>
                <div class="text-sm opacity-90">Total Pages</div>
            </div>
            <div class="text-white p-6 rounded-lg shadow-lg" style="background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);">
                <div class="text-3xl font-bold"><?php echo $completed_pages; ?></div>
                <div class="text-sm opacity-90">Completed (<?php echo $completion_percentage; ?>%)</div>
            </div>
            <div class="text-white p-6 rounded-lg shadow-lg" style="background: linear-gradient(135deg, #ffecd2 0%, #fcb69f 100%);">
                <div class="text-3xl font-bold"><?php echo $remaining_pages; ?></div>
                <div class="text-sm opacity-90">Remaining (<?php echo round(100 - $completion_percentage, 1); ?>%)</div>
            </div>
            <div class="text-white p-6 rounded-lg shadow-lg" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                <div class="text-3xl font-bold"><?php echo count($cities); ?></div>
                <div class="text-sm opacity-90">Cities</div>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="flex flex-wrap gap-4 items-center">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Status:</label>
                    <select id="statusFilter" onchange="filterPages()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">All Pages</option>
                        <option value="completed">Completed Only</option>
                        <option value="pending">Pending Only</option>
                        <option value="marked-completed">Marked Complete</option>
                        <option value="marked-pending">Marked Pending</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by Service:</label>
                    <select id="serviceFilter" onchange="filterPages()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">All Services</option>
                        <?php foreach ($services as $service): ?>
                            <option value="<?php echo $service['slug']; ?>"><?php echo $service['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filter by City:</label>
                    <select id="cityFilter" onchange="filterPages()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">All Cities</option>
                        <?php foreach ($cities as $city): ?>
                            <option value="<?php echo $city['slug']; ?>"><?php echo $city['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex items-end gap-2">
                    <button onclick="resetFilters()" class="px-4 py-2 bg-gray-500 text-white rounded-lg hover:bg-gray-600 transition">
                        Reset Filters
                    </button>
                    <button onclick="toggleAllComplete()" id="toggleAllBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
                        Mark All Complete
                    </button>
                    <button onclick="clearAllMarks()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                        Clear All Marks
                    </button>
                    <button onclick="exportMarks()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition">
                        Export Data
                    </button>
                </div>
            </div>
        </div>

        <!-- Progress Bar -->
        <div class="bg-white p-6 rounded-lg shadow mb-6">
            <div class="flex justify-between items-center mb-2">
                <h3 class="text-lg font-semibold">Overall Progress</h3>
                <span class="text-sm text-gray-600">
                    <span id="staticCompleted"><?php echo $completed_pages; ?></span> static +
                    <span id="markedCompleted">0</span> marked =
                    <span id="totalCompleted"><?php echo $completed_pages; ?></span> /
                    <?php echo $total_pages; ?> pages completed
                </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-4">
                <div id="progressBar" class="bg-gradient-to-r from-green-400 to-blue-500 h-4 rounded-full transition-all duration-500" style="width: <?php echo $completion_percentage; ?>%"></div>
            </div>
            <div class="mt-4 text-sm text-gray-600">
                <div class="flex flex-wrap gap-4">
                    <span>✅ Static Complete: <?php echo $completed_pages; ?></span>
                    <span>📝 Marked Complete: <span id="markedCount">0</span></span>
                    <span>📝 Marked Pending: <span id="markedPendingCount">0</span></span>
                    <span>⬜ Total Pending: <span id="actualPending"><?php echo $remaining_pages; ?></span></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
        <div class="space-y-8">
            <?php foreach ($services as $index => $service): ?>
                <div class="service-section bg-white rounded-lg shadow-lg overflow-hidden"
                     data-service="<?php echo $service['slug']; ?>"
                     data-status="<?php echo $service['status']; ?>">

                    <!-- Service Header -->
                    <div class="service-header px-6 py-4 bg-gradient-to-r from-gray-50 to-gray-100 border-b cursor-pointer hover:bg-gray-100 transition"
                         onclick="toggleService('<?php echo $service['slug']; ?>')">
                        <div class="flex justify-between items-center">
                            <div class="flex items-center space-x-3">
                                <h2 class="text-xl font-bold text-gray-800"><?php echo $service['name']; ?></h2>
                                <?php echo get_status_badge($service['status']); ?>
                                <?php if (isset($service['note'])): ?>
                                    <span class="text-xs text-amber-600 font-medium">⚠️ <?php echo $service['note']; ?></span>
                                <?php endif; ?>
                            </div>
                            <div class="flex items-center space-x-4">
                                <span class="text-sm text-gray-600">30 city pages</span>
                                <svg class="toggle-icon w-5 h-5 text-gray-500 transform transition-transform"
                                     id="icon-<?php echo $service['slug']; ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Cities Grid -->
                    <div class="cities-grid p-6 hidden" id="grid-<?php echo $service['slug']; ?>">
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            <?php foreach ($cities as $city): ?>
                                <div class="page-link city-item"
                                     data-city="<?php echo $city['slug']; ?>"
                                     data-service="<?php echo $service['slug']; ?>"
                                     data-static-status="<?php echo $service['status']; ?>"
                                     data-page-id="<?php echo $city['slug']; ?>-<?php echo $service['slug']; ?>">
                                    <div class="p-4 bg-white border border-gray-200 rounded-lg hover:border-blue-400 hover:shadow-md transition-all duration-200">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-semibold text-gray-800 text-sm"><?php echo $city['name']; ?></h3>
                                            <span class="status-indicator text-xs text-gray-500" data-page-id="<?php echo $city['slug']; ?>-<?php echo $service['slug']; ?>">
                                                <?php echo $service['status'] === 'completed' ? '✅' : '⬜'; ?>
                                            </span>
                                        </div>
                                        <div class="text-xs text-gray-600 mb-2">
                                            /<?php echo $city['slug']; ?>/<?php echo $service['slug']; ?>/
                                        </div>
                                        <div class="flex justify-between items-center mb-3">
                                            <span class="text-xs px-2 py-1 bg-blue-100 text-blue-800 rounded">
                                                <?php echo $service['name']; ?>
                                            </span>
                                        </div>
                                        <div class="flex gap-2">
                                            <a href="<?php echo home_url("/{$city['slug']}/{$service['slug']}/"); ?>"
                                               target="_blank"
                                               class="flex-1 px-3 py-2 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition text-center">
                                                Test Page
                                            </a>
                                            <button onclick="togglePageStatus('<?php echo $city['slug']; ?>-<?php echo $service['slug']; ?>', '<?php echo $service['status']; ?>')"
                                                    class="toggle-btn px-3 py-2 bg-gray-600 text-white text-xs rounded hover:bg-gray-700 transition"
                                                    data-page-id="<?php echo $city['slug']; ?>-<?php echo $service['slug']; ?>">
                                                Mark
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <!-- Quick Actions -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <div class="flex flex-wrap gap-3">
                                <button onclick="testAllCityPages('<?php echo $service['slug']; ?>')"
                                        class="px-4 py-2 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition">
                                    Test All <?php echo $service['name']; ?> Pages
                                </button>
                                <button onclick="openCityInNewTab('miami', '<?php echo $service['slug']; ?>')"
                                        class="px-4 py-2 bg-blue-600 text-white text-sm rounded-lg hover:bg-blue-700 transition">
                                    Test Miami (<?php echo $service['status'] === 'completed' ? 'Working' : 'Will Work'; ?>)
                                </button>
                                <a href="<?php echo admin_url('edit.php?post_type=service'); ?>"
                                   target="_blank"
                                   class="px-4 py-2 bg-purple-600 text-white text-sm rounded-lg hover:bg-purple-700 transition">
                                    Edit Services
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Page Content (if any) -->
    <?php if (have_posts()) : ?>
        <?php while (have_posts()) : the_post(); ?>
            <?php if (get_the_content()) : ?>
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
                    <div class="bg-white p-8 rounded-lg shadow">
                        <?php the_content(); ?>
                    </div>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    <?php endif; ?>
</div>


<script>
// localStorage data management
const STORAGE_KEY = 'sunnyside_test_dashboard_marks';

// Get stored marks from localStorage
function getStoredMarks() {
    const stored = localStorage.getItem(STORAGE_KEY);
    return stored ? JSON.parse(stored) : {};
}

// Save marks to localStorage
function saveStoredMarks(marks) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(marks));
}

// Get status for a specific page
function getPageStatus(pageId, staticStatus) {
    const marks = getStoredMarks();
    if (marks[pageId] !== undefined) {
        return marks[pageId];
    }
    return staticStatus === 'completed' ? true : false;
}

// Toggle page status
function togglePageStatus(pageId, staticStatus) {
    console.log(`🎯 Toggle clicked for: ${pageId} (Static: ${staticStatus})`);

    try {
        const marks = getStoredMarks();
        const currentStatus = getPageStatus(pageId, staticStatus);

        console.log(`📊 Current status: ${currentStatus}`);

        // Toggle the status
        marks[pageId] = !currentStatus;
        saveStoredMarks(marks);

        console.log(`💾 New status saved: ${marks[pageId]}`);

        // Update UI
        updatePageUI(pageId, marks[pageId], staticStatus);
        updateGlobalStats();

        console.log(`✅ Toggle complete for: ${pageId}`);
    } catch (error) {
        console.error(`❌ Error toggling ${pageId}:`, error);
        alert('Error toggling page status. Check console for details.');
    }
}

// Update individual page UI
function updatePageUI(pageId, isCompleted, staticStatus) {
    const statusIndicator = document.querySelector(`.status-indicator[data-page-id="${pageId}"]`);
    const toggleBtn = document.querySelector(`.toggle-btn[data-page-id="${pageId}"]`);
    const cityItem = document.querySelector(`.city-item[data-page-id="${pageId}"]`);

    if (statusIndicator) {
        statusIndicator.textContent = isCompleted ? '✅' : '⬜';
        statusIndicator.className = isCompleted ?
            'status-indicator text-xs text-green-600 font-semibold' :
            'status-indicator text-xs text-gray-500';
    }

    if (toggleBtn) {
        toggleBtn.textContent = isCompleted ? 'Undo' : 'Mark';
        toggleBtn.className = isCompleted ?
            'toggle-btn px-3 py-2 bg-orange-600 text-white text-xs rounded hover:bg-orange-700 transition' :
            'toggle-btn px-3 py-2 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition';
    }

    if (cityItem) {
        cityItem.dataset.markedStatus = isCompleted ? 'completed' : 'pending';
    }
}

// Update global statistics
function updateGlobalStats() {
    const marks = getStoredMarks();
    const allCityItems = document.querySelectorAll('.city-item');

    let markedCompleted = 0;
    let markedPending = 0;

    allCityItems.forEach(item => {
        const pageId = item.dataset.pageId;
        const staticStatus = item.dataset.staticStatus;
        const currentStatus = getPageStatus(pageId, staticStatus);

        if (staticStatus !== 'completed') {
            if (currentStatus) {
                markedCompleted++;
            } else {
                markedPending++;
            }
        }
    });

    const staticCompleted = parseInt(document.getElementById('staticCompleted').textContent);
    const totalCompleted = staticCompleted + markedCompleted;
    const totalPages = parseInt(document.querySelector('.text-3xl.font-bold').textContent);
    const percentage = Math.round((totalCompleted / totalPages) * 100 * 10) / 10;

    // Update counters
    document.getElementById('markedCompleted').textContent = markedCompleted;
    document.getElementById('totalCompleted').textContent = totalCompleted;
    document.getElementById('markedCount').textContent = markedCompleted;
    document.getElementById('markedPendingCount').textContent = markedPending;
    document.getElementById('actualPending').textContent = totalPages - totalCompleted;

    // Update progress bar
    const progressBar = document.getElementById('progressBar');
    progressBar.style.width = percentage + '%';

    // Update toggle all button
    const toggleAllBtn = document.getElementById('toggleAllBtn');
    const allMarkedCompleted = markedPending === 0 && allCityItems.length > staticCompleted * 30;
    toggleAllBtn.textContent = allMarkedCompleted ? 'Mark All Pending' : 'Mark All Complete';
    toggleAllBtn.className = allMarkedCompleted ?
        'px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition' :
        'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition';
}

// Toggle all complete/pending
function toggleAllComplete() {
    const marks = getStoredMarks();
    const allCityItems = document.querySelectorAll('.city-item');
    const toggleAllBtn = document.getElementById('toggleAllBtn');
    const markAllComplete = toggleAllBtn.textContent.includes('Mark All Complete');

    allCityItems.forEach(item => {
        const pageId = item.dataset.pageId;
        const staticStatus = item.dataset.staticStatus;

        if (staticStatus !== 'completed') {
            marks[pageId] = markAllComplete;
            updatePageUI(pageId, markAllComplete, staticStatus);
        }
    });

    saveStoredMarks(marks);
    updateGlobalStats();
}

// Clear all marks
function clearAllMarks() {
    if (confirm('This will clear all your custom marks. Are you sure?')) {
        localStorage.removeItem(STORAGE_KEY);

        // Reset all UI to original state
        document.querySelectorAll('.city-item').forEach(item => {
            const pageId = item.dataset.pageId;
            const staticStatus = item.dataset.staticStatus;
            const isCompleted = staticStatus === 'completed';
            updatePageUI(pageId, isCompleted, staticStatus);
        });

        updateGlobalStats();
    }
}

// Export marks data
function exportMarks() {
    const marks = getStoredMarks();
    const exportData = {
        timestamp: new Date().toISOString(),
        marks: marks,
        summary: {
            total: Object.keys(marks).length,
            markedCompleted: Object.values(marks).filter(v => v).length,
            markedPending: Object.values(marks).filter(v => !v).length
        }
    };

    const dataStr = JSON.stringify(exportData, null, 2);
    const dataUri = 'data:application/json;charset=utf-8,'+ encodeURIComponent(dataStr);

    const exportFileDefaultName = `sunnyside-test-dashboard-${new Date().toISOString().split('T')[0]}.json`;

    const linkElement = document.createElement('a');
    linkElement.setAttribute('href', dataUri);
    linkElement.setAttribute('download', exportFileDefaultName);
    linkElement.click();
}

// Toggle service section visibility
function toggleService(serviceSlug) {
    const grid = document.getElementById('grid-' + serviceSlug);
    const icon = document.getElementById('icon-' + serviceSlug);

    if (grid.classList.contains('hidden')) {
        grid.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        grid.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}

// Expand all sections
function expandAll() {
    document.querySelectorAll('.cities-grid').forEach(grid => {
        grid.classList.remove('hidden');
    });
    document.querySelectorAll('.toggle-icon').forEach(icon => {
        icon.classList.add('rotate-180');
    });
}

// Collapse all sections
function collapseAll() {
    document.querySelectorAll('.cities-grid').forEach(grid => {
        grid.classList.add('hidden');
    });
    document.querySelectorAll('.toggle-icon').forEach(icon => {
        icon.classList.remove('rotate-180');
    });
}

// Filter pages (enhanced)
function filterPages() {
    const statusFilter = document.getElementById('statusFilter').value;
    const serviceFilter = document.getElementById('serviceFilter').value;
    const cityFilter = document.getElementById('cityFilter').value;

    document.querySelectorAll('.service-section').forEach(section => {
        const serviceStatus = section.dataset.status;
        const serviceSlug = section.dataset.service;

        let showSection = true;

        // Status filter logic (enhanced)
        if (statusFilter !== 'all') {
            if (statusFilter === 'completed' || statusFilter === 'pending') {
                if (serviceStatus !== statusFilter) {
                    showSection = false;
                }
            } else if (statusFilter === 'marked-completed' || statusFilter === 'marked-pending') {
                // Check if any items in this section match the marked status
                const hasMatchingItems = Array.from(section.querySelectorAll('.city-item')).some(item => {
                    const pageId = item.dataset.pageId;
                    const staticStatus = item.dataset.staticStatus;
                    const currentStatus = getPageStatus(pageId, staticStatus);
                    return statusFilter === 'marked-completed' ? currentStatus && staticStatus !== 'completed' : !currentStatus && staticStatus !== 'completed';
                });
                if (!hasMatchingItems) showSection = false;
            }
        }

        // Service filter
        if (serviceFilter !== 'all' && serviceSlug !== serviceFilter) {
            showSection = false;
        }

        section.style.display = showSection ? 'block' : 'none';

        // City filter within visible sections
        if (showSection && cityFilter !== 'all') {
            section.querySelectorAll('.city-item').forEach(item => {
                const citySlug = item.dataset.city;
                item.style.display = citySlug === cityFilter ? 'block' : 'none';
            });
        } else if (showSection) {
            section.querySelectorAll('.city-item').forEach(item => {
                item.style.display = 'block';
            });
        }
    });
}

// Reset filters
function resetFilters() {
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('serviceFilter').value = 'all';
    document.getElementById('cityFilter').value = 'all';
    filterPages();
}

// Open specific city in new tab
function openCityInNewTab(citySlug, serviceSlug) {
    window.open(`<?php echo home_url(); ?>/${citySlug}/${serviceSlug}/`, '_blank');
}

// Test all city pages for a service (batch opening)
function testAllCityPages(serviceSlug) {
    const cities = <?php echo json_encode(array_column($cities, 'slug')); ?>;
    const baseUrl = '<?php echo home_url(); ?>';

    // Open first few pages immediately
    for (let i = 0; i < Math.min(5, cities.length); i++) {
        setTimeout(() => {
            window.open(`${baseUrl}/${cities[i]}/${serviceSlug}/`, '_blank');
        }, i * 200);
    }

    // Ask if user wants to open all
    if (cities.length > 5) {
        const openAll = confirm(`This will open ${cities.length} tabs. Continue?`);
        if (openAll) {
            for (let i = 5; i < cities.length; i++) {
                setTimeout(() => {
                    window.open(`${baseUrl}/${cities[i]}/${serviceSlug}/`, '_blank');
                }, i * 100);
            }
        }
    }
}

// Initialize page on load
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 Test Dashboard initializing...');

    // Check if elements exist
    const cityItems = document.querySelectorAll('.city-item');
    console.log(`📊 Found ${cityItems.length} city items`);

    // Initialize all page statuses from localStorage
    const marks = getStoredMarks();
    console.log('💾 Stored marks:', marks);

    cityItems.forEach((item, index) => {
        const pageId = item.dataset.pageId;
        const staticStatus = item.dataset.staticStatus;
        const currentStatus = getPageStatus(pageId, staticStatus);

        console.log(`🔄 Item ${index + 1}: ${pageId} - Static: ${staticStatus}, Current: ${currentStatus}`);
        updatePageUI(pageId, currentStatus, staticStatus);
    });

    // Update global stats
    console.log('📈 Updating global stats...');
    updateGlobalStats();

    // Expand the first completed service as example
    const firstCompleted = document.querySelector('[data-status="completed"]');
    if (firstCompleted) {
        const serviceSlug = firstCompleted.dataset.service;
        console.log(`🎯 Expanding first completed service: ${serviceSlug}`);
        toggleService(serviceSlug);
    }

    console.log('✅ Test Dashboard initialization complete!');
});

// Keyboard shortcuts (enhanced)
document.addEventListener('keydown', function(e) {
    if (e.ctrlKey || e.metaKey) {
        switch(e.key) {
            case 'e':
                e.preventDefault();
                expandAll();
                break;
            case 'w':
                e.preventDefault();
                collapseAll();
                break;
            case 'r':
                e.preventDefault();
                resetFilters();
                break;
            case 'a':
                e.preventDefault();
                toggleAllComplete();
                break;
            case 's':
                e.preventDefault();
                clearAllMarks();
                break;
        }
    }
});
</script>

<?php get_footer(); ?>