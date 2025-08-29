<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Machine Dashboard</title>
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/dashboard_machine.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/modal.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/header.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/tables.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/buttons.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/sidebar.css">
</head>
<body>
    <?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/sidebar.php';
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

    // Get disabled machines
    require_once __DIR__ . '/../models/read_machines.php';
    $disabled_machines = getDisabledMachines(10, 0);
    ?>

    <div class="container">
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
                        <button type="button" class="btn btn-primary" onclick="refreshPage(this)">
                            Refresh Data
                        </button>
                        <button type="button" class="btn btn-primary" onclick="openAddPartsModal()">
                            Add Parts
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
                                                <button class="btn-small btn-reset"
                                                        data-id="<?= htmlspecialchars($row['machine_id']) ?>"
                                                        onclick="openResetModal(this)">
                                                        Reset
                                                </button>
                                                <button class="btn-small btn-edit"
                                                        data-id="<?= htmlspecialchars($row['machine_id']) ?>"
                                                        onclick="openUndoModal(this)">
                                                        Undo
                                                </button>
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
            <!-- Table 1: Custom Parts -->
            <div class="tables-grid">
                <div class="data-section">
                    <div class="section-header">
                        <div class="section-title">
                            🔧 Custom Parts
                            <span class="section-badge">3</span>
                        </div>
                        <div class="expand-icon">▼</div>
                    </div>
                    <div class="section-content expanded">
                        <div class="search-filter">
                            <input type="text" class="search-input" placeholder="Search custom parts...">
                        </div>
                        <div class="table-container">
                            <table class="data-table" id="partsTable"   >
                                <thead>
                                    <tr>
                                        <th>Part Name</th>
                                        <th>Created At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="tableBody">
                                    <?php foreach ($custom_machine_parts as $part): ?>
                                    <!-- Wire Crimper -->
                                    <tr>
                                        <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $part['part_name']))) ?></td>
                                        <td><?= htmlspecialchars(date('Y-m-d', strtotime($part['created_at']))) ?></td>
                                        <td>
                                            <?php $partNameTitle = ucwords(str_replace('_', ' ', strtolower($part['part_name']))); ?>
                                            <button class="btn btn-edit" 
                                                    data-part-id="<?= htmlspecialchars($part['part_id']) ?>" 
                                                    data-part-name="<?= htmlspecialchars($partNameTitle, ENT_QUOTES) ?>">
                                                Edit
                                            </button>
                                            <button class="btn btn-delete" 
                                                    data-part-id="<?= htmlspecialchars($part['part_id']) ?>" 
                                                    data-part-type="MACHINE">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>  
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Table 2: Recently Deleted Machine -->
                <div class="data-section">
                    <div class="section-header">
                        <div class="section-title">
                            🗑️ Recently Deleted Machine
                            <span class="section-badge">3</span>
                        </div>
                        <div class="expand-icon">▼</div>
                    </div>
                    <div class="section-content expanded">
                        <div class="search-filter">
                            <input type="text" class="search-input" placeholder="Search deleted machines...">
                        </div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Actions</th>
                                        <th>Control Number</th>
                                        <th>Model</th>
                                        <th>Maker</th>
                                        <th>Last Encoded</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($disabled_machines as $machine): ?>
                                    <tr>
                                        <td>
                                            <button id="restore-machine-<?= htmlspecialchars($machine['machine_id']) ?>"
                                                    class="tab-btn"
                                                    data-machine-id="<?= htmlspecialchars($machine['machine_id']) ?>">
                                                Restore
                                            </button>
                                        </td>
                                        <td><?php echo htmlspecialchars($machine['control_no']); ?></td>
                                        <td><?php echo htmlspecialchars($machine['model']); ?></td>
                                        <td><?php echo htmlspecialchars($machine['maker']); ?></td>
                                        <td><?php echo htmlspecialchars($machine['last_encoded']); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

                <!-- Table 3: Recently Deleted Outputs Section -->
            <div class="full-width-table">
                <div class="data-section">
                    <div class="section-header">
                        <div class="section-title">
                            📤 Recently Deleted Outputs
                            <span class="section-badge">3</span>
                        </div>
                        <div class="expand-icon">▼</div>
                    </div>
                    <div class="section-content expanded">
                        <div class="search-filter">
                            <input type="text" class="search-input" placeholder="Search deleted outputs...">
                            <button class="filter-btn">All Outputs</button>
                            <button class="filter-btn">Reports</button>
                            <button class="filter-btn">Data Files</button>
                            <button class="filter-btn">Logs</button>
                        </div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Output ID</th>
                                        <th>Output Name</th>
                                        <th>Type</th>
                                        <th>File Size</th>
                                        <th>Machine Source</th>
                                        <th>Generated Date</th>
                                        <th>Deleted Date</th>
                                        <th>Deleted By</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>OUT-2024-001</td>
                                        <td>Production Report Q3 2024</td>
                                        <td>Report</td>
                                        <td>2.4 MB</td>
                                        <td>AM-003</td>
                                        <td>2024-08-15</td>
                                        <td>2024-08-26</td>
                                        <td>John Smith</td>
                                        <td><span class="status-badge status-critical">Deleted</span></td>
                                    </tr>
                                    <tr>
                                        <td>OUT-2024-002</td>
                                        <td>Wire Assembly Log 08-24</td>
                                        <td>Log File</td>
                                        <td>850 KB</td>
                                        <td>HP-002</td>
                                        <td>2024-08-20</td>
                                        <td>2024-08-24</td>
                                        <td>Mike Johnson</td>
                                        <td><span class="status-badge status-critical">Deleted</span></td>
                                    </tr>
                                    <tr>
                                        <td>OUT-2024-003</td>
                                        <td>Quality Control Data Export</td>
                                        <td>Data File</td>
                                        <td>1.8 MB</td>
                                        <td>AM-002</td>
                                        <td>2024-08-18</td>
                                        <td>2024-08-22</td>
                                        <td>Sarah Davis</td>
                                        <td><span class="status-badge status-critical">Deleted</span></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Custom Part Modal -->
    <div id="addCustomPartModalDashboardApplicator" class="modal-overlay">
        <div class="modal">
            <button class="modal-close-btn" onclick="closeAddCustomPartModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">➕ Add Custom Part</h1>
                <p class="form-subtitle">Add a new custom part to this applicator</p>
            </div>

            <form id="addCustomPartForm" method="POST" action="../controllers/add_custom_part.php">
                <input type="hidden" name="equipment_type" value="MACHINE">

                <div class="form-section">
                    <div class="form-group">
                        <label for="customPartName">Part Name</label>
                        <input type="text" id="custom_part_name" name="custom_part_name" class="form-input" placeholder="Enter part name..." required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Add Part</button>
                    <button type="button" class="btn btn-secondary" onclick="closeAddCustomPartModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Custom Part Modal -->
    <div id="editCustomPartModalDashboardMachine" class="modal-overlay">
        <div class="modal">
            <button class="modal-close-btn" onclick="closeEditCustomPartModal()">×</button>

            <div class="form-header">
                <h1 class="form-title">✏️ Edit Custom Part</h1>
                <p class="form-subtitle">Edit the details of this custom part</p>
            </div>

            <form id="editCustomPartForm" method="POST" action="../controllers/edit_custom_part.php">
                <input type="hidden" name="equipment_type" value="MACHINE">
                <input type="hidden" name="part_id" id="edit_part_id">

                <div class="form-section">
                    <div class="form-group">
                        <label for="customPartName">Part Name</label>
                        <input type="text" id="edit_part_name" name="custom_part_name" class="form-input" placeholder="Enter part name..." required>
                    </div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" onclick="closeEditCustomPartModal()">Cancel</button>
                </div>
            </form>
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
                    <button type="button" class="btn-cancel" onclick="closeResetModal()">Cancel</button>
                    <button type="submit" class="btn-confirm">Confirm Reset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Undo Reset Modal -->
    <div id="undoModalDashboardMachine" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeUndoModal()">×</button>

            <div class="form-header">
                <h1 class="form-title">↩️ Undo Reset</h1>
                <p class="form-subtitle">Revert machine reset to previous state</p>
            </div>

            <form id="editForm" method="POST" action="../controllers/undo_reset_machine.php">
                <input type="hidden" name="machine_id" id="undo_machine_id">

                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">🕒</div>
                        <div class="section-info">
                            <div class="section-title">Undo Selection</div>
                            <div class="section-description">Select the part and timestamp to revert to</div>
                        </div>
                    </div>

                    <div class="form-grid">
                        <div class="form-group">
                            <label class="form-label">
                                Select Machine Part to Undo
                                <span class="required-badge">Required</span>
                            </label>
                            <select id="undoPartSelect" name="part_name" class="form-input" required>
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
                        
                        <div class="form-group">
                            <label class="form-label">
                                Dates Replaced
                                <span class="required-badge">Required</span>
                            </label>
                            <select id="editStatus" name="reset_time" class="form-input" required>
                                <option value="">Select a part first</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="warning-section">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <span class="warning-icon">⚠️</span>
                        <div>
                            <strong>Caution: Data Loss Warning</strong>
                            <p>Reverting to previous timestamp will disable all records encoded later than the timestamp! Proceed with caution!</p>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn-cancel" onclick="closeUndoModal()">Cancel</button>
                    <button type="submit" class="btn-confirm">Confirm</button>
                </div>
            </form>
        </div>      
    </div>

    <!-- Machine Modal -->
    <div id="machineModalDashboardMachine" class="modal-overlay">
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
    <script src="../../public/assets/js/sidebar.js"></script>
</body>
</html>