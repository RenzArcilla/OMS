<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Admin Dashboard</title>
    <link rel="stylesheet" href="../../public/assets/css/base.css">
    <link rel="stylesheet" href="../../public/assets/css/dashboard_applicator.css">
</head>
<body>
    <?php 
    // Move all PHP logic to the top, before any HTML output
    
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

    <?php // include '../includes/side_bar.php'; ?>
    <div class="admin-container">
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
                    <div class="stat-card status-warning" onclick="openApplicatorModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good" onclick="openApplicatorModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good" onclick="openApplicatorModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-warning" onclick="openApplicatorModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good" onclick="openApplicatorModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good" onclick="openApplicatorModal()">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-critical" onclick="openApplicatorModal()">
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
                                        <th>Status</th>
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
                                                    <?= htmlspecialchars($part['part_name']) ?>
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
                                                <button class="btn-small btn-edit" onclick="openUndoModal()">Undo</button>
                                                <button class="btn-small btn-reset" onclick="openResetModal()">Reset</button>
                                            </td>
                                            <td><?= htmlspecialchars($row['hp_no']) ?></td>
                                            <td><span class="status-badge status-good">Good</span></td>
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
            </div>
        </div>
    </div>

    <!-- Undo Modal -->
    <div id="undoModalDashboardApplicator" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Undo Applicator<span id="editHpNumber"></span></h2>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-group">
                        <label class="form-label">Select Applicator Part to Reset</label>
                        <select id="editWireType" class="form-input">
                            <option>Select Part</option>
                            <option>Wire Crimper</option>
                            <option>Wire Anvil</option>
                            <option>Insulation Crimper</option>
                            <option>Insulation Anvil</option>
                            <option>Slide Cutter</option>
                            <option>Cutter Holder</option>
                            <option>Shear Blade</option>
                            <option>Cutter A</option>
                            <option>Cutter B</option>
                        </select>
                    </div>
                    
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
                <button type="button" class="btn-cancel" onclick="closeUndoModal()">Cancel</button>
                <button type="button" class="btn-confirm" onclick="saveUndo()">Confirm</button>
            </div>
        </div>
    </div>

    <!-- Reset Modal -->
    <div id="resetModalDashboardApplicator" class="modal-overlay">
        <div class="modal modal-reset">
            <div class="modal-header">
                <h2 class="modal-title">Reset Applicator<span id="editHpNumber"></span></h2>
            </div>
            <div class="modal-body">
                <form id="resetForm">
                    <div class="form-group">
                        <label class="form-label">Select Applicator Part to Reset</label>
                        <select id="resetWireType" class="form-input">
                            <option>Select Part</option>
                            <option>Wire Crimper</option>
                            <option>Wire Anvil</option>
                            <option>Insulation Crimper</option>
                            <option>Insulation Anvil</option>
                            <option>Slide Cutter</option>
                            <option>Cutter Holder</option>
                            <option>Shear Blade</option>
                            <option>Cutter A</option>
                            <option>Cutter B</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">⚠️ Are you sure you want to reset the applicator?</label>
                        <p style="color: #6b7280; font-size: 0.9rem; margin-top: 8px;">
                            This action will reset the selected component's usage counter to zero. This cannot be undone.
                        </p>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeResetModal()">Cancel</button>
                <button type="button" class="btn-confirm" onclick="saveReset()">Confirm</button>
            </div>
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
    <script src="../../public/assets/js/dashboard_applicator.js"></script>
</body>
</html>