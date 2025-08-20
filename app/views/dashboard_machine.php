<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Machine Dashboard</title>
    <link rel="stylesheet" href="../../public/assets/css/dashboard_machine.css">
</head>
<body>
    <?php
    // First, get custom parts
    require_once "../models/read_custom_parts.php";
    $custom_machine_parts = getCustomParts("MACHINE");

    // Initialize part names array
    $part_names_array = [];
    foreach ($custom_machine_parts as $part) {
        $part_names_array[] = $part['part_name'];
    }

    // Include the read join function and the alert function
    require_once __DIR__ . '/../models/read_joins/read_monitor_machine_and_machine.php';
    require_once __DIR__ . '/../includes/js_alert.php';

    // Handle search if control number is provided
    $search_ctrl = $_GET['search_ctrl'] ?? '';
    $search_result = null;
    $is_searching = false;
    
    if (!empty(trim($search_ctrl))) {
        $is_searching = true;
        $search_result = searchMachineByControlNo(trim($search_ctrl), $part_names_array);
        
        // CHECK FOR SEARCH RESULT AND REDIRECT IMMEDIATELY IF NOT FOUND
        if (!$search_result) {
            jsAlertRedirect("Machine not found!", $_SERVER['PHP_SELF']);
            exit(); // Stop execution to prevent any further output
        }
        
        // If searching, use search result instead of all records
        $machine_total_outputs = [$search_result]; // Single result in array
    } else {
        // Use existing logic for all records
        $machine_total_outputs = getRecordsAndOutputs(10, 0, $part_names_array);
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
    ?>

    <div class="admin-container">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Tab -->
            <div id="dashboard-tab" class="tab-content">
                <div class="page-header">
                    <h1 class="page-title">📊 Machine Dashboard</h1>
                    <div class="header-actions">
                        <button type="button" class="btn btn-secondary" onclick="exportData()">
                            Export Report
                        </button>
                        <button type="button" class="btn btn-primary" onclick="refreshPage()">
                            Refresh Data
                        </button>
                    </div>
                </div>
                
                <div class="stats-overview">
                    <div class="stat-card status-good" onclick="openMachineModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good" onclick="openMachineModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                </div>

                <!-- Machine Status Section -->
                <div class="data-section">
                    <div class="section-header expanded" onclick="toggleSection(this)">
                        <div class="section-title">
                            <span class="filter-info">
                                <?= htmlspecialchars($filter_display) ?>
                            </span>
                        </div>
                    </div>
                    
                    <div class="search-filter">
                        <!-- Search form -->
                        <form method="GET" style="display: inline;">
                            <!-- Preserve existing filter parameter if present -->
                            <?php if (isset($_GET['filter_by']) && !$is_searching): ?>
                                <input type="hidden" name="filter_by" value="<?= htmlspecialchars($_GET['filter_by']) ?>">
                            <?php endif; ?>
                            
                            <input type="text" 
                                    name="search_ctrl" 
                                    class="search-input" 
                                    placeholder="Search by HP number..." 
                                    value="<?= htmlspecialchars($search_ctrl) ?>"
                                    onkeyup="if(event.key==='Enter') this.form.submit()">
                            <button type="submit" class="filter-btn">Search</button>
                        </form>
                        
                        <?php if (!$is_searching): ?>
                            <button class="filter-btn active" onclick="filterByStatus(this, 'all')">All</button>
                        <?php endif; ?>
                        
                        <button class="auto-filter-btn" onclick="window.location.href = window.location.pathname;">
                            🔄 Auto-Filter
                        </button>
                    </div>

                    <div class="section-content expanded">
                        <div class="table-container">
                            <table class="data-table" id="metricsTable">
                                <thead>
                                    <tr>
                                        <th>Actions</th>
                                        <th><a href="?filter_by=control_no">Machine Number</a></th>
                                        <th><a href="?filter_by=last_updated">Last Updated</a></th>
                                        <th><a href="?filter_by=total_machine_output">Total Output</a></th>
                                        <th><a href="?filter_by=cut_blade_output">Cut Blade</a></th>
                                        <th><a href="?filter_by=strip_blade_a_output">Strip Blade A</a></th>
                                        <th><a href="?filter_by=strip_blade_b_output">Strip Blade B</a></th>
                                        <?php foreach ($custom_machine_parts as $part): ?>
                                            <th>
                                                <a href="?filter_by=<?= urlencode($part['part_name']) ?>">
                                                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $part['part_name']))) ?>
                                                </a>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>

                                <!-- Table Rows -->
                                <tbody id="metricsBody">
                                    <?php foreach ($machine_total_outputs as $row): ?>
                                        <tr>
                                            <td>
                                                <button class="btn-small btn-edit" onclick="openUndoModalDashboardMachine()">Undo</button>
                                                <button class="btn-small btn-reset" onclick="openResetModal()">Reset</button>
                                            </td>
                                            <td><?= htmlspecialchars($row['control_no']) ?></td>
                                            <td><strong><?= htmlspecialchars(explode(' ', $row['last_updated'])[0]) ?></strong></td>
                                            <td><strong><?= htmlspecialchars($row['total_machine_output']) ?></strong></td>
                                            <td>
                                                <div><strong><?= htmlspecialchars($row['cut_blade_output']) ?></strong> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 42%;"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><strong><?= htmlspecialchars($row['strip_blade_a_output']) ?></strong> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 42%;"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><strong><?= htmlspecialchars($row['strip_blade_b_output']) ?></strong> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 38%;"></div>
                                                </div>
                                            </td>
                                            <?php foreach ($part_names_array as $part_name): ?>
                                                <td>
                                                    <div><strong><?= htmlspecialchars($row['custom_parts_output'][$part_name] ?? 0) ?></strong> / 1.5M</div>
                                                    <div class="progress-bar">
                                                        <div class="progress-fill" style="width: 26%;"></div>
                                                    </div>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Machine Modal -->
    <div id="resetModalDashboardMachine" class="modal-overlay">
        <div class="modal modal-reset">
            <button class="modal-close-btn" onclick="closeResetModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">🔄 Reset Machine</h1>
                <p class="form-subtitle">Reset machine component usage counter</p>
            </div>

            <form id="resetForm" method="POST" action="../controllers/reset_machine.php">
                <input type="hidden" name="machine_id" id="reset_machine_id">

                <div class="form-group">
                    <label class="form-label">
                        Select Machine Part to Reset
                        <span class="required-badge">Required</span>
                    </label>
                    <select id="editWireType" name="part_name" class="form-input">
                        <option value="">Select Part</option>
                        <option value="cut_blade_output">Cut Blade</option>
                        <option value="strip_blade_a_output">Strip Blade A</option>
                        <option value="strip_blade_b_output">Strip Blade B</option>
                        <?php foreach ($custom_machine_parts as $row): ?>
                            <option value="<?= htmlspecialchars($row['part_name']) ?>">
                                <?= ucwords(str_replace('_', ' ', htmlspecialchars($row['part_name']))) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="warning-section">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <span class="warning-icon">⚠️</span>
                        <div>
                            <strong>Are you sure you want to reset the machine?</strong>
                            <p>This action will reset the selected component's usage counter to zero!</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeResetModalDashboardMachine()">Cancel</button>
                    <button type="submit" class="btn-confirm">Confirm Reset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Undo Reset Modal -->
    <div id="undoModalDashboardMachine" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Undo Machine<span id="editHpNumber"></span></h2>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-group">
                        <label class="form-label">Select Machine Part to Undo</label>
                        <select id="editWireType" class="form-input">
                            <option>Cut Blade</option>
                            <option>Strip Blade A</option>
                            <option>Strip Blade B</option>
                        </select>
                    </div>
                </form>
                    
                    <div class="form-group">
                        <label class="form-label">Dates Replaced</label>
                        <select id="editStatus" class="form-input">
                            <option value="07/21/2025">07/21/2025</option>
                            <option value="07/22/2025">07/22/2025</option>
                            <option value="07/23/2025">07/23/2025</option>
                            <option value="07/24/2025">07/24/2025</option>
                            <option value="07/25/2025">07/25/2025</option>
                            <option value="07/26/2025">07/26/2025</option>
                            <option value="07/27/2025">07/27/2025</option>
                            <option value="07/28/2025">07/28/2025</option>
                            <option value="07/29/2025">07/29/2025</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeUndoModalDashboardMachine()">Cancel</button>
                <button type="button" class="btn-confirm" onclick="saveChanges()">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Machine Modal -->
    <div id="machineModalDashboardApplicator" class="modal-overlay">
        <div class="modal">
            <div class="parts-list">
                <!-- Cut Blade -->
                <div class="part-item">
                    <div class="part-header">
                        <h4 class="part-name">Cut Blade</h4>
                        <span class="status-badge status-good">Good</span>
                    </div>
                    <div class="part-details">
                        <span class="cycles-info">Cycles: 234,567</span>
                        <span class="percentage">42%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill progress-good" style="width: 42%;"></div>
                    </div>
                </div>

                <!-- Strip Blade A -->
                <div class="part-item">
                    <div class="part-header">
                        <h4 class="part-name">Strip Blade A</h4>
                        <span class="status-badge status-good">Good</span>
                    </div>
                    <div class="part-details">
                        <span class="cycles-info">Cycles: 189,234</span>
                        <span class="percentage">38%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill progress-good" style="width: 38%;"></div>
                    </div>
                </div>

                <!-- Strip Blade B -->
                <div class="part-item">
                    <div class="part-header">
                        <h4 class="part-name">Strip Blade B</h4>
                        <span class="status-badge status-warn">Warn</span>
                    </div>
                    <div class="part-details">
                        <span class="cycles-info">Cycles: 387,945</span>
                        <span class="percentage">78%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill progress-warn" style="width: 78%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="../../public/assets/js/dashboard_machine.js"></script>
</body>
</html>