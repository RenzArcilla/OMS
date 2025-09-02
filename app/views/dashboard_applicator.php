<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Applicator Dashboard</title>
    <link rel="stylesheet" href="../../public/assets/css/base/header.css">
    <link rel="icon" href="/SOMS/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/dashboard_applicator.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/header.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/modal.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/tables.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/buttons.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/layout/grid.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/export_modal.css">
    <!--<link rel="stylesheet" href="/SOMS/public/assets/css/components/stats_modal.css">-->
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/search_filter.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/pagination.css">
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


    // Pagination settings
    $items_per_page = isset($_GET['items_per_page']) ? max(5, min(50, (int)$_GET['items_per_page'])) : 10;
    $current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($current_page - 1) * $items_per_page;


    // Handle search if HP number is provided
    $search_hp = $_GET['search_hp'] ?? '';
    $search_result = null;
    $is_searching = false;
    
    if (!empty(trim($search_hp))) {
        $is_searching = true;
        $search_result = searchApplicatorByHpNo(trim($search_hp), $part_names_array);
        
        // CHECK FOR SEARCH RESULT AND REDIRECT IMMEDIATELY IF NOT FOUND
        if (empty($search_result)) {
            jsAlertRedirect("Applicator not found!", $_SERVER['PHP_SELF']);
            exit();
        }

        $search_result = searchApplicatorByHpNo(trim($search_hp), $part_names_array);

        // If searching, use search result instead of all records
        $applicator_total_outputs = $search_result;
        $total_records = count($search_result);
        $total_pages = 1;
    } else {
        // Get total count for pagination
        $total_records = getApplicatorRecordsCount($part_names_array);
        $total_pages = ceil($total_records / $items_per_page);
        
        // Use existing logic for all records with pagination
        $applicator_total_outputs = getApplicatorRecordsAndOutputs($items_per_page, $offset, $part_names_array);
    }
    
    // Get current filter info (only if not searching)
    if (!$is_searching) {
        $current_filter = $_GET['filter_by'] ?? null;
        if (!$current_filter) {
            // Get the auto-selected highest output part
            $current_filter = findHighestOutputApplicatorPart($part_names_array);
            $filter_display = "Auto-sorted by: " . str_replace('_output', '', ucwords(str_replace('_', ' ', $current_filter)));
        } else {
            $filter_display = "Filtered by: " . str_replace('_output', '', ucwords(str_replace('_', ' ', $current_filter)));
        }
    } else {
        $filter_display = "Search Results";
    }
    
    // Get parts priority data
    $parts_ordered = getPartsOrderedByApplicatorOutput($part_names_array);
    $top_3_parts = array_slice($parts_ordered, 0, 3);

    // Get disabled applicators
    require_once __DIR__ . '/../models/read_applicators.php';
    $disabled_applicators = getDisabledApplicators(10, 0);
    ?>

    <div class="container">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Tab -->
            <div id="dashboard-tab" class="tab-content">
                <div class="page-header">
                    <h1 class="page-title">📊 Applicator Dashboard</h1>
                    <div class="header-actions">    
                        <button type="button" class="btn-secondary" onclick="exportData()">
                            Export Report
                        </button>
                        <button type="button" class="btn-primary" onclick="refreshPage()">
                            Refresh Data
                        </button>

                        <button type="button" class="btn-primary" onclick="openAddCustomPartModal()">
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
                    
                    <div class="search-filter" style="display: flex; gap: 10px; align-items: center;">
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

                        <button style="position: relative; left: -5px;" class="tab-btn" onclick="window.location.href = window.location.pathname;">
                            Auto-Sort
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
                                                <div class="actions">
                                                    <button
                                                        class="reset-btn"
                                                        type="button"
                                                        onclick="openResetModal(this)"
                                                        data-id="<?= $row['applicator_id'] ?>">
                                                        Reset
                                                    </button>
                                                    <button
                                                        class="undo-btn"
                                                        type="button"
                                                        onclick="openUndoModal(this)"
                                                        data-id="<?= $row['applicator_id'] ?>">
                                                        Undo
                                                    </button>
                                                </div>
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
                                
                <!-- Pagination Controls -->
                <?php if (!$is_searching && $total_pages > 1): ?>
                <div class="pagination-container">
                    <div class="pagination-info">
                        <span class="pagination-text">
                            Showing <?= ($offset + 1) ?> to <?= min($offset + $items_per_page, $total_records) ?> of <?= number_format($total_records) ?> results
                        </span>
                    </div>
                    
                    <div class="pagination-controls">
                        <!-- Previous Button -->
                        <?php if ($current_page > 1): ?>
                            <a href="?page=<?= $current_page - 1 ?><?= isset($_GET['filter_by']) ? '&filter_by=' . htmlspecialchars($_GET['filter_by']) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
                                class="pagination-btn pagination-prev">
                                <span>←</span> Previous
                            </a>
                        <?php else: ?>
                            <span class="pagination-btn pagination-prev disabled">
                                <span>←</span> Previous
                            </span>
                        <?php endif; ?>
                        
                        <!-- Page Numbers -->
                        <div class="pagination-numbers">
                            <?php
                            $start_page = max(1, $current_page - 2);
                            $end_page = min($total_pages, $current_page + 2);
                            
                            // Show first page if not in range
                            if ($start_page > 1): ?>
                                <a href="?page=1<?= isset($_GET['filter_by']) ? '&filter_by=' . htmlspecialchars($_GET['filter_by']) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
                                    class="pagination-btn">1</a>
                                <?php if ($start_page > 2): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <?php if ($i == $current_page): ?>
                                    <span class="pagination-btn pagination-current"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?page=<?= $i ?><?= isset($_GET['filter_by']) ? '&filter_by=' . htmlspecialchars($_GET['filter_by']) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
                                        class="pagination-btn"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($end_page < $total_pages): ?>
                                <?php if ($end_page < $total_pages - 1): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif; ?>
                                <a href="?page=<?= $total_pages ?><?= isset($_GET['filter_by']) ? '&filter_by=' . htmlspecialchars($_GET['filter_by']) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
                                    class="pagination-btn"><?= $total_pages ?></a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Next Button -->
                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?= $current_page + 1 ?><?= isset($_GET['filter_by']) ? '&filter_by=' . htmlspecialchars($_GET['filter_by']) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
                                class="pagination-btn pagination-next">
                                Next <span>→</span>
                            </a>
                        <?php else: ?>
                            <span class="pagination-btn pagination-next disabled">
                                Next <span>→</span>
                            </span>
                        <?php endif; ?>
                    </div>
                    
                    <!-- Items Per Page Selector -->
                    <div class="pagination-items-per-page">
                        <label for="items-per-page">Show:</label>
                        <select id="items-per-page" onchange="changeItemsPerPage(this.value)">
                            <option value="5" <?= $items_per_page == 5 ? 'selected' : '' ?>>5</option>
                            <option value="10" <?= $items_per_page == 10 ? 'selected' : '' ?>>10</option>
                            <option value="20" <?= $items_per_page == 20 ? 'selected' : '' ?>>20</option>
                            <option value="50" <?= $items_per_page == 50 ? 'selected' : '' ?>>50</option>
                        </select>
                        <span>per page</span>
                    </div>
                </div>
                <?php endif; ?>
                <!-- Table 1: Custom Parts -->
                <div class="tables-grid">
                    <?php include_once __DIR__ . '/applicator_custom_parts.php'; ?>
                    
                <!-- Table 2: Recently Deleted Applicators -->
                    <div class="tables-scroll-2">
                        <?php include_once __DIR__ . '/recently_deleted_applicator.php'; ?>
                    </div>
                </div>

                    <!-- Table 3: Recently Deleted Record -->
                <div class="full-width-table" style="position: relative; top: -50px;">
                    <?php include_once __DIR__ . '/recently_deleted_outputs_table.php'; ?>
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
            
            <div class="form-actions">
                <button type="button" class="btn btn-secondary" onclick="closePartsInventoryModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- Delete Custom Part Modal -->
    <div id="deleteCustomPartModalDashboardApplicator" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn">×</button>
            
            <div class="form-header">
                <span class="delete-icon">🗑️</span>
                <h1 class="form-title">Delete Custom Part</h1>
                <p class="form-subtitle">Permanently remove this custom part</p>
            </div>

            <div class="warning-section">
                <span class="warning-icon">⚠️</span>
                <div class="warning-title">Permanent Action</div>
                <div class="warning-text">
                    This custom part will be permanently removed from this applicator. This action cannot be undone.
                </div>
            </div>

            <div id="messageContainer"></div>

            <div class="part-details">
                <div class="part-info">
                    <div class="part-icon">⚙️</div>
                    <div class="part-content">
                        <div class="part-name" id="partName">Custom Valve Assembly</div>
                        <div class="part-meta">Added on March 15, 2024 • Part ID: #CP001</div>
                    </div>
                </div>
            </div>

            <div class="confirmation-section">
                <label class="confirmation-checkbox">
                    <input type="checkbox" id="confirmDelete" class="confirmation-input">
                    <span class="confirmation-label">
                        I understand that this action is permanent and cannot be undone. I want to delete this custom part.
                    </span>
                </label>
            </div>

            <form id="deleteCustomPartForm" method="POST" action="../controllers/delete_custom_part.php">
                <input type="hidden" name="equipment_type" value="APPLICATOR">
                <input type="hidden" name="part_id" value="" id="partIdInput">
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary" id="deleteBtn" disabled>Delete Part Permanently</button>
                    <button type="button" class="btn btn-secondary" onclick="closeEditCustomPartModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>
    <!-- Edit Custom Part Modal -->
    <div id="editCustomPartModalDashboardApplicator" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeEditCustomPartModal()">×</button>

            <div class="form-header">
                <h1 class="form-title">✏️ Edit Custom Part</h1>
                <p class="form-subtitle">Edit the details of this custom part</p>
            </div>

            <form id="editCustomPartForm" method="POST" action="../controllers/edit_custom_part.php">
                <input type="hidden" name="equipment_type" value="APPLICATOR">
                <input type="hidden" name="edit_part_id" id="edit_part_id">

                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <div class="section-info">
                            <div class="section-title">Part Name</div>
                            <div class="section-description">Edit the name of the custom part</div>
                        </div>
                    </div>
                    <div class="form-grid-vertical">
                        <div class="form-group">
                            <label class="form-label" for="edit_part_name">
                                Part Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input 
                                type="text" 
                                id="edit_part_name" 
                                name="edit_part_name" 
                                class="form-input" 
                                placeholder="Enter part name..." 
                                required
                            >
                        </div>
                    </div>
                </div>
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-secondary" onclick="closeEditCustomPartModal()">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Custom Part Modal -->
    <div id="addCustomPartModalDashboardApplicator" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeAddCustomPartModal()" aria-label="Close modal">×</button>
            
            <div class="form-header">
                <h1 class="form-title">➕ Add Custom Part</h1>
                <p class="form-subtitle">Add a new custom part to this applicator</p>
            </div>

            <div id="messageContainer"></div>

            <form id="addCustomPartForm" method="POST" action="../controllers/add_custom_part.php">
                <input type="hidden" name="equipment_type" value="APPLICATOR">

                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <div class="section-info">
                            <div class="section-title">Part Name</div>
                            <div class="section-description">Enter the name of the custom part</div>
                        </div>
                    </div>
                    <div class="form-grid-vertical">
                        <div class="form-group">
                            <label class="form-label" for="custom_part_name">
                                Part Name
                                <span class="required-badge">Required</span>
                            </label>
                            <input 
                                type="text" 
                                id="custom_part_name" 
                                name="custom_part_name" 
                                class="form-input" 
                                placeholder="Enter part name..." 
                                required
                                maxlength="100"
                                aria-describedby="part-name-help"
                            >
                        </div>
                    </div>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        Add Part
                    </button>
                    <button type="button" class="btn btn-secondary" onclick="closeAddCustomPartModal()">
                        Cancel
                    </button>
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
                    <button type="submit" class="reset-btn">Confirm Reset</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Undo Modal -->
    <div id="undoModalDashboardApplicator" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeUndoModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">↩️ Undo</h1>
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
                    <button type="submit" class="undo-btn">Confirm Undo</button>
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

    <!-- Export Modal -->
    <div id="exportModal" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn">×</button>
            
            <div class="form-header">
                <h1 class="form-title">Export Data</h1>
                <p style="font-size: 14px; color: #6B7280;">Choose your export format and options</p>
            </div>

            <!-- Export Format Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">📄</div>
                    <div class="section-info">
                        <div class="section-title">Export Format</div>
                        <div class="section-description">Choose the file format for your export</div>
                    </div>
                </div>
                
                <div class="format-options">
                    <div class="format-option selected" data-format="csv">
                        <div class="format-option-content">
                            <svg class="format-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                <polyline points="14,2 14,8 20,8"/>
                                <line x1="16" y1="13" x2="8" y2="13"/>
                                <line x1="16" y1="17" x2="8" y2="17"/>
                                <polyline points="10,9 9,9 8,9"/>
                            </svg>
                            <div class="format-details">
                                <div class="format-label">CSV File</div>
                                <div class="format-description">Comma-separated values, compatible with Excel</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="format-option" data-format="xlsx">
                        <div class="format-option-content">
                            <svg class="format-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <ellipse cx="12" cy="5" rx="9" ry="3"/>
                                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"/>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"/>
                            </svg>
                            <div class="format-details">
                                <div class="format-label">Excel File</div>
                                <div class="format-description">Native Excel format with formatting</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Date Range Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">📅</div>
                    <div class="section-info">
                        <div class="section-title">Date Range</div>
                        <div class="section-description">Select the time period for your export</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <select id="dateRange" class="form-select">
                        <option value="all">All Time</option>
                        <option value="today">Today</option>
                        <option value="week">Last 7 Days</option>
                        <option value="month">Last 30 Days</option>
                        <option value="quarter">Last 3 Months</option>
                        <option value="year">Last Year</option>
                        <option value="custom">Custom Date Range</option>
                    </select>
                </div>

                <div id="customDates" class="date-inputs hidden">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 12px; color: #6B7280;">Start Date</label>
                        <input type="date" id="startDate" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size: 12px; color: #6B7280;">End Date</label>
                        <input type="date" id="endDate" class="form-input">
                    </div>
                </div>
            </div>

            <!-- Additional Options Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">⚙️</div>
                    <div class="section-info">
                        <div class="section-title">Additional Options</div>
                        <div class="section-description">Configure export settings</div>
                    </div>
                </div>
                
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" id="includeHeaders" class="checkbox-input" checked>
                        <span class="checkbox-label">Include column headers</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="button-group">
                <button type="button" class="cancel-btn">Cancel</button>
                <button type="button" class="export-btn" onclick="handleExport()">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7,10 12,15 17,10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Export Data
                </button>
            </div>
        </div>
    </div>

    <!-- Load JavaScript -->
    <script src="../../public/assets/js/dashboard_applicator.js"></script>
    <script src="../../public/assets/js/sidebar.js"></script>
    <script src="../../public/assets/js/utils/exit.js"></script>
    <script src="../../public/assets/js/utils/enter.js"></script>
    <script src="../../public/assets/js/utils/checkbox.js"></script>
    <!-- Search Disabled Applicators -->
    <script src="../../public/assets/js/search_disabled_applicators.js"></script>
</body>
</html>