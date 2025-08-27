<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Applicator Dashboard</title>
    <link rel="stylesheet" href="../../public/assets/css/dashboard_applicator.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/header.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/modal.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/tables.css">
</head>
<body>
    <?php 
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
        $applicator_total_outputs = getRecordsAndOutputs(10, 0, $part_names_array);
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

    <div class="container">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Tab -->
            <div id="dashboard-tab" class="tab-content">
                <div class="page-header">
                    <h1 class="page-title">📊 Applicator Dashboard</h1>
                    <div class="header-actions">    
                        <button type="button" class="btn btn-secondary" onclick="exportData()">
                            Export Report
                        </button>
                        <button type="button" class="btn btn-primary" onclick="refreshPage()">
                            Refresh Data
                        </button>
                        <button type="button" class="btn btn-primary" onclick="openPartsInventoryModal()">
                            Parts Inventory
                        </button>
                        <button type="button" class="btn btn-primary" onclick="openAddCustomPartModal()">
                            Add Parts
                        </button>
                    </div>
                </div>
                
                <div class="stats-overview">
                    <div class="stat-card status-good" onclick="openApplicatorModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good" onclick="openApplicatorModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                </div>

                <!-- Applicator Status Section -->
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
                                    name="search_hp" 
                                    class="search-input" 
                                    placeholder="Search by HP number..." 
                                    value="<?= htmlspecialchars($search_hp) ?>"
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
                            <!-- Table section -->
                            <table class="data-table" id="metricsTable">
                                <!-- Table Headers -->
                                <thead>
                                    <tr>
                                        <th>Actions</th>
                                        <th><a href="?filter_by=hp_no">HP Number</a></th>
                                        <th><a href="?filter_by=last_updated">Last Updated</a></th>
                                        <th><a href="?filter_by=total_output">Total Output</a></th>
                                        <th><a href="?filter_by=wire_crimper_output">Wire Crimper</a></th>
                                        <th><a href="?filter_by=wire_anvil_output">Wire Anvil</a></th>
                                        <th><a href="?filter_by=insulation_crimper_output">Insulation Crimper</a></th>
                                        <th><a href="?filter_by=insulation_anvil_output">Insulation Anvil</a></th>
                                        <th><a href="?filter_by=slide_cutter_output">Slide Cutter</a></th>
                                        <th><a href="?filter_by=cutter_holder_output">Cutter Holder</a></th>
                                        <th><a href="?filter_by=shear_blade_output">Shear Blade</a></th>
                                        <th><a href="?filter_by=cutter_a_output">Cutter A</a></th>
                                        <th><a href="?filter_by=cutter_b_output">Cutter B</a></th>
                                        <?php foreach ($custom_applicator_parts as $part): ?>
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
                                    <?php foreach ($applicator_total_outputs as $row): ?>
                                        <tr>
                                            <td>
                                                <button
                                                    class="btn-small btn-reset"
                                                    type="button"
                                                    onclick="openResetModal(this)"
                                                    data-id="<?= $row['applicator_id'] ?>">
                                                    Reset
                                                </button>
                                                <button
                                                    class="btn-small btn-edit"
                                                    type="button"
                                                    onclick="openUndoModal(this)"
                                                    data-id="<?= $row['applicator_id'] ?>">
                                                    Undo
                                                </button>
                                            </td>
                                            <td><?= htmlspecialchars($row['hp_no']) ?></td>
                                            <td><strong><?= htmlspecialchars(explode(' ', $row['last_updated'])[0]) ?></strong></td>
                                            <td><strong><?= htmlspecialchars($row['total_output']) ?></strong></td>
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
                                            <td>
                                                <div><strong><?= htmlspecialchars($row['slide_cutter_output']) ?></strong> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 55%;"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><strong><?= htmlspecialchars($row['cutter_holder_output']) ?></strong> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 30%;"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><strong><?= htmlspecialchars($row['shear_blade_output']) ?></strong> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 26%;"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><?= htmlspecialchars($row['cutter_a_output']) ?> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 26%;"></div>
                                                </div>
                                            </td>
                                            <td>
                                                <div><strong><?= htmlspecialchars($row['cutter_b_output']) ?></strong> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 21%;"></div>
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
                                <table class="data-table">
                                    <thead>
                                        <tr>
                                            <th>Part Name</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Custom Wire Crimper Pro</td>
                                        </tr>
                                        <tr>
                                            <td>Enhanced Cut Blade X1</td>
                                        </tr>
                                        <tr>
                                            <td>Precision Wire Anvil V2</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>

    <!-- Parts Inventory Modal -->
    <div id="partsInventoryModalDashboardApplicator" class="modal-overlay" style="display: none;">
        <div class="modal">
            <button class="modal-close-btn" onclick="closePartsInventoryModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">📋 Parts Inventory</h1>
                <p class="form-subtitle">View and manage custom applicator parts</p>
            </div>
            
            <div class="section-content">

                <!-- Data Table -->
                <div class="table-container">
                    <table class="data-table" id="partsTable">
                        <thead>
                            <tr>
                                <th>Part Name</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($custom_applicator_parts as $part): ?>
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
                                    <button class="btn btn-delete" data-part-id="<?= htmlspecialchars($part['part_id']) ?>" >Delete</button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>    
                    </table>
                </div>
            </div>
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closePartsInventoryModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Edit Custom Part Modal -->
    <div id="editCustomPartModalDashboardApplicator" class="modal-overlay">
        <div class="modal">
            <button class="modal-close-btn" onclick="closeEditCustomPartModal()">×</button>

            <div class="form-header">
                <h1 class="form-title">✏️ Edit Custom Part</h1>
                <p class="form-subtitle">Edit the details of this custom part</p>
            </div>

            <form id="editCustomPartForm" method="POST" action="../controllers/edit_custom_part.php">
                <input type="hidden" name="equipment_type" value="APPLICATOR">
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

    <!-- Add Custom Part Modal -->
    <div id="addCustomPartModalDashboardApplicator" class="modal-overlay">
        <div class="modal">
            <button class="modal-close-btn" onclick="closeAddCustomPartModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">➕ Add Custom Part</h1>
                <p class="form-subtitle">Add a new custom part to this applicator</p>
            </div>

            <form id="addCustomPartForm" method="POST" action="../controllers/add_custom_part.php">
                <input type="hidden" name="equipment_type" value="APPLICATOR">

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

    <!-- Reset Applicator Modal -->
    <div id="resetModalDashboardApplicator" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeResetModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">🔄 Reset Applicator</h1>
                <p class="form-subtitle">Reset applicator component usage counter</p>
            </div>

            <form id="resetForm" method="POST" action="../controllers/reset_applicator.php">
                <input type="hidden" name="applicator_id" id="reset_applicator_id">
                
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <div class="section-info">
                            <div class="section-title">Component Selection</div>
                            <div class="section-description">Choose the applicator part to reset</div>
                        </div>
                    </div>

                    <div class="form-grid-vertical">
                        <div class="form-group">
                            <label class="form-label">
                                Select Applicator Part to Reset
                                <span class="required-badge">Required</span>
                            </label>
                            <select id="resetWireType" name="part_name" class="form-input" required>
                                <option value="">Select Part</option>
                                <option value="wire_crimper_output">Wire Crimper</option>
                                <option value="wire_anvil_output">Wire Anvil</option>
                                <option value="insulation_crimper_output">Insulation Crimper</option>
                                <option value="insulation_anvil_output">Insulation Anvil</option>
                                <option value="slide_cutter_output">Slide Cutter</option>
                                <option value="cutter_holder_output">Cutter Holder</option>
                                <option value="shear_blade_output">Shear Blade</option>
                                <option value="cutter_a_output">Cutter A</option>
                                <option value="cutter_b_output">Cutter B</option>
                                <?php foreach ($custom_applicator_parts as $row): ?>
                                    <option value="<?= htmlspecialchars($row['part_name']) ?>">
                                        <?= ucwords(str_replace('_', ' ', htmlspecialchars($row['part_name']))) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="warning-section">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <span class="warning-icon">⚠️</span>
                        <div>
                            <strong>Are you sure you want to reset the applicator?</strong>
                            <p>This action will reset the selected component's usage counter to zero!</p>
                        </div>
                    </div>
                </div>

                <div class="button-group">
                    <button type="button" class="cancel-btn" onclick="closeResetModal()">Cancel</button>
                    <button type="submit" class="btn-confirm-dashboard">Confirm Reset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Undo Reset Modal -->
    <div id="undoModalDashboardApplicator" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeUndoModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">↩️ Undo Reset</h1>
                <p class="form-subtitle">Revert applicator reset to previous state</p>
            </div>

            <form id="editForm" method="POST" action="../controllers/undo_reset_applicator.php">
                <input type="hidden" name="applicator_id" id="undo_applicator_id">
                
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
                                Select Applicator Part to Undo
                                <span class="required-badge">Required</span>
                            </label>
                            <select id="editWireType" name="part_name" class="form-input" required>
                                <option value="">Select Part</option>
                                <option value="wire_crimper_output">Wire Crimper</option>
                                <option value="wire_anvil_output">Wire Anvil</option>
                                <option value="insulation_crimper_output">Insulation Crimper</option>
                                <option value="insulation_anvil_output">Insulation Anvil</option>
                                <option value="slide_cutter_output">Slide Cutter</option>
                                <option value="cutter_holder_output">Cutter Holder</option>
                                <option value="shear_blade_output">Shear Blade</option>
                                <option value="cutter_a_output">Cutter A</option>
                                <option value="cutter_b_output">Cutter B</option>
                                <?php foreach ($custom_applicator_parts as $row): ?>
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

                <div class="button-group">
                    <button type="button" class="cancel-btn" onclick="closeUndoModal()">Cancel</button>
                    <button type="submit" class="btn-confirm-dashboard">Confirm Undo</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Applicator Modal -->
    <div id="applicatorModalDashboardApplicator" class="modal-overlay">
        <div class="modal">
            <div class="parts-list">
                <!-- Wire Crimper -->
                <div class="part-item">
                    <div class="part-header">
                        <h4 class="part-name">Wire Crimper</h4>
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

                <!-- Wire Anvil -->
                <div class="part-item">
                    <div class="part-header">
                        <h4 class="part-name">Wire Anvil</h4>
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

                <!-- Insulation Crimper -->
                <div class="part-item">
                    <div class="part-header">
                        <h4 class="part-name">Insulation Crimper</h4>
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

                <!-- Insulation Anvil -->
                <div class="part-item">
                    <div class="part-header">
                        <h4 class="part-name">Insulation Anvil</h4>
                        <span class="status-badge status-good">Good</span>
                    </div>
                    <div class="part-details">
                        <span class="cycles-info">Cycles: 298,671</span>
                        <span class="percentage">60%</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill progress-good" style="width: 60%;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Load JavaScript -->
    <script src="../../public/assets/js/dashboard_applicator.js"></script>
</body>
</html>