<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOMS - Home</title>
    <link rel="stylesheet" href="../../public/assets/css/home.css">
    <link rel="stylesheet" href="../../public/assets/css/components/sidebar.css">
</head>
<body>
    
    <?php 
    include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/sidebar.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/header.php';
    // First, get custom parts
    require_once "../models/read_custom_parts.php";
    $custom_applicator_parts = getCustomParts("APPLICATOR");
    
    // Initialize part names array
    $part_names_array = [];
    foreach ($custom_applicator_parts as $part) {
        $part_names_array[] = $part['part_name'];
    }
    
    // Include the read join function and the alert function
    require_once __DIR__ . '/../models/read_joins/read_monitor_applicator_and_applicator.php';
    require_once __DIR__ . '/../includes/js_alert.php';

    // Handle search if HP number is provided
    $search_hp = $_GET['search_hp'] ?? '';
    $search_result = null;
    $is_searching = false;
    
    if (!empty(trim($search_hp))) {
        $is_searching = true;
        $search_result = searchApplicatorByHpNo(trim($search_hp), $part_names_array);
        
        // CHECK FOR SEARCH RESULT AND REDIRECT IMMEDIATELY IF NOT FOUND
        if (!$search_result) {
            jsAlertRedirect("Applicator not found!", $_SERVER['PHP_SELF']);
            exit(); // Stop execution to prevent any further output
        }
        
        // If searching, use search result instead of all records
        $applicator_total_outputs = [$search_result]; // Single result in array
    } else {
        // Use existing logic for all records
        $applicator_total_outputs = getApplicatorRecordsAndOutputs(10, 0, $part_names_array);
    }
    
    // Get current filter info (only if not searching)
    if (!$is_searching) {
        $current_filter = $_GET['filter_by'] ?? null;
        if (!$current_filter) {
            // Get the auto-selected highest output part
            $current_filter = findHighestOutputPart($part_names_array);
            $filter_display = "Auto-sorted by: " . str_replace('_output', '', ucwords(str_replace('_', ' ', $current_filter)));
        } else {
            $filter_display = "Filtered by: " . str_replace('_output', '', ucwords(str_replace('_', ' ', $current_filter)));
        }
    } else {
        $filter_display = "Search Results";
    }
    
    // Get parts priority data
    $parts_ordered = getPartsOrderedByOutput($part_names_array);
    $top_3_parts = array_slice($parts_ordered, 0, 3);

    // Get disabled applicators
    require_once __DIR__ . '/../models/read_applicators.php';
    $disabled_applicators = getDisabledApplicators(10, 0);
    ?>






    
<!-- Animated Background -->
    <div class="background-canvas">
            <div class="floating-orb orb-1"></div>
            <div class="floating-orb orb-2"></div>
            <div class="floating-orb orb-3"></div>
        </div>

        <!-- Hero Section -->
        <div class="container">
            <div class="hero-container">
                <div class="hero-content">
                    <div class="hero-badge">
                        <span class="hero-text">Input Privilege Here</span>
                    </div>
                    
                    <h1 class="hero-title">
                        Storage and <br>
                        <span class="gradient-text">Output</span> <br>
                        Monitoring System
                    </h1>
                    
                    <p class="hero-subtitle">
                        Monitor and control your storage and output devices with ease.
                    </p>
                    
                    <div class="hero-actions">
                        <button class="btn btn-primary">
                            <a href="dashboard.php">Dashboard</a>
                        </button>
                        <button class="btn btn-primary"><a href="login.php">Log In</a></button>
                    </div>
                </div>

                <div class="dashboard-container">
                    <div class="dashboard-main">
                        <div class="dashboard-header">
                            <h3 class="dashboard-title">System Overview</h3>
                            <div class="dashboard-controls">
                                <div class="view-toggle">
                                    <button class="view-btn active" data-view="summary">Summary</button>
                                    <button class="view-btn" data-view="detailed">Detailed</button>
                                </div>
                                
                            </div>
                        </div>

                        <div class="dashboard-content">
                            <!-- Summary Cards -->
                            <div class="summary-cards">
                                <div class="summary-card">
                                    <div class="summary-value">Connect to Database</div>
                                    <div class="summary-label">No. of Machines in Operation</div>
                                    <div class="summary-change positive">+12.5%</div>
                                </div>
                                <div class="summary-card">
                                    <div class="summary-value">Connect to Database</div>
                                    <div class="summary-label">No. of Machines in Operation</div>
                                    <div class="summary-change positive">+23.1%</div>
                                </div>
                                
                            </div>

                            <!-- Applicator Status Section -->
                            <div class="data-section">
                                <div class="section-header expanded" onclick="toggleSection(this)">
                                    <div class="section-title">
                                        Applicator Status
                                        <span class="section-badge">24</span>
                                    </div>
                                    <svg class="expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                <div class="section-content expanded">
                                    <div class="search-filter">
                                        <input type="text" class="search-input" placeholder="Search metrics..." onkeyup="filterTable(this.value)">
                                    </div>
                                    <table class="data-table" id="metricsTable">
                                        <thead>
                                            <tr>
                                                <th>HP No.</th>
                                                <th>Wire Crimper</th>
                                                <th>Wire Anvil</th>
                                                <th>Insulation Crimper</th>
                                                <th>Insulation Anvil</th>
                                            </tr>
                                        </thead>
                                        <tbody id="metricsBody">
                                            <?php foreach ($applicator_total_outputs as $row): ?>
                                                <tr data-status="success">
                                                    <td><?= htmlspecialchars($row['hp_no']) ?></td>
                                                    <td>
                                                        <div><strong><?= htmlspecialchars($row['wire_crimper_output']) ?></strong> / 1.5M</div>
                                                        <div class="progress-bar">
                                                            <div class="progress-fill" style="width: 42%;"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div><strong><?= htmlspecialchars($row['wire_anvil_output']) ?></strong> / 1.5M</div>
                                                        <div class="progress-bar">
                                                            <div class="progress-fill" style="width: 38%;"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div><strong><?= htmlspecialchars($row['insulation_crimper_output']) ?></strong> / 1.5M</div>
                                                        <div class="progress-bar">
                                                            <div class="progress-fill" style="width: 51%;"></div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div><strong><?= htmlspecialchars($row['insulation_anvil_output']) ?></strong> / 1.5M</div>
                                                        <div class="progress-bar">
                                                            <div class="progress-fill" style="width: 46%;"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Machine Status Section -->
                            <div class="data-section">
                                <div class="section-header expanded" onclick="toggleSection(this)">
                                    <div class="section-title">
                                        Machine Status
                                        <span class="section-badge">24</span>
                                    </div>
                                    <svg class="expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                                <div class="section-content expanded">
                                    <div class="search-filter">
                                        <input type="text" class="search-input" placeholder="Search metrics..." onkeyup="filterTable(this.value)">
                                    </div>
                                    <table class="data-table" id="metricsTable">
                                        <thead>
                                            <tr>
                                                <th>AM No.</th>
                                                <th>Strip Blade</th>
                                                <th>Cut Blade</th>
                                            </tr>
                                        </thead>
                                        <tbody id="metricsBody">
                                            <tr data-status="success">
                                                <td>Connect to Database the AM No.</td>
                                                <td class="metric-value">Data Here</td>
                                                <td class="metric-value">Data Here</td>
                                            </tr>
                                            <tr data-status="success">
                                                <td>Connect to Database the AM No.</td>
                                                <td class="metric-value">Data Here</td>
                                                <td class="metric-value">Data Here</td>
                                            </tr>
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <script src="../../public/assets/js/sidebar.js"></script>
</body>
</html>