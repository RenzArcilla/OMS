<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Machine Dashboard</title>
    <link rel="stylesheet" href="../../public/assets/css/base/header.css">
    <link rel="icon" href="/OMS/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/dashboard_machine.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/header.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/modal.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/tables.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/buttons.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/layout/grid.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/export_modal.css">
    <!--<link rel="stylesheet" href="/OMS/public/assets/css/components/stats_modal.css">-->
    <link rel="stylesheet" href="/OMS/public/assets/css/components/search_filter.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/pagination.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/info.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/progress_bars.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/checkbox.css">
</head>
<body>
    <?php
    include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/sidebar.php';
    include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/header.php';
    // First, get custom parts
    require_once __DIR__ . '/../models/read_custom_parts.php';
    $custom_machine_parts = getCustomParts("MACHINE");

    // Initialize part names array
    $part_names_array = [];
    foreach ($custom_machine_parts as $part) {
        $part_names_array[] = $part['part_name'];
    }

    // Include the read join function and the alert function
    require_once __DIR__ . '/../models/read_joins/read_monitor_machine_and_machine.php';
    require_once __DIR__ . '/../includes/js_alert.php';

    // Pagination settings
    $items_per_page = isset($_GET['items_per_page']) ? max(5, min(50, (int)$_GET['items_per_page'])) : 10;
    $current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $offset = ($current_page - 1) * $items_per_page;

    // Handle search if control number is provided
    $search_ctrl = $_GET['search_ctrl'] ?? '';
    $search_result = null;
    $is_searching = false;
    
    if (!empty(trim($search_ctrl))) {
        $is_searching = true;
        $search_results = searchMachineByControlNo(trim($search_ctrl), $part_names_array);
        
        // CHECK FOR SEARCH RESULT AND REDIRECT IMMEDIATELY IF NOT FOUND
        if (empty($search_results)) {
            jsAlertRedirect("Machine not found!", $_SERVER['PHP_SELF']);
            exit;
        }
        
        // If searching, use search result instead of all records
        $machine_total_outputs = $search_results;
        $total_records = count($search_results);
        $total_pages = 1;
    } else {
        // Get total count for pagination
        $total_records = getMachineRecordsCount($part_names_array);
        $total_pages = ceil($total_records / $items_per_page);
        
        // Use existing logic for all records with pagination
        $machine_total_outputs = getMachineRecordsAndOutputs($items_per_page, $offset, $part_names_array);
    }
    
    // Get current filter info (only if not searching)
    if (!$is_searching) {
        $current_filter = $_GET['filter_by'] ?? null;
        if (!$current_filter) {
            // Get the auto-selected highest output part
            $current_filter = findHighestOutputMachinePart($part_names_array);
            $filter_display = "Auto-sorted by: " . str_replace('_output', '', ucwords(str_replace('_', ' ', $current_filter)));
        } else {
            $filter_display = "Filtered by: " . str_replace('_output', '', ucwords(str_replace('_', ' ', $current_filter)));
        }
    } else {
        $filter_display = "Search Results";
    }
    
    // Get parts priority data
    $parts_ordered = getPartsOrderedByMachineOutput($part_names_array);
    $top_3_parts = array_slice($parts_ordered, 0, 3);
    ?>

    <div class="container">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Tab -->
            <div id="dashboard-tab" class="tab-content">
                <div class="page-header">
                    <h1 class="page-title">📊 Machine Dashboard</h1>
                    <div class="header-actions">
                        <button type="button" class="btn-primary" onclick="refreshPage(this)">
                            Refresh Data
                        </button>
                        <button type="button" class="btn-primary add-parts-machine">
                            Add Parts
                        </button>
                        <button type="button" class="btn-primary export-reset-data-machine">
                            <div>Export Reset Data</div>
                        </button>   
                        <button type="button" class="btn-primary export-output-data-machine">
                            <div>Export Output Data</div>
                        </button>
                        <button type="button" class="btn-primary edit-maximum-output-machine">
                            <div>Edit Maximum Output</div> 
                        </button>
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
                        
                        <button style="position: relative; left: -10px;" class="tab-btn" onclick="window.location.href = window.location.pathname;">
                            Auto-Filter
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
                                                <div class="actions">
                                                    <button class="reset-btn"
                                                            data-id="<?= htmlspecialchars($row['machine_id']) ?>"
                                                            onclick="openResetModal(this)">
                                                            Reset
                                                    </button>
                                                    <button class="undo-btn"
                                                            data-id="<?= htmlspecialchars($row['machine_id']) ?>"
                                                            onclick="openUndoModal(this)">
                                                            Undo
                                                    </button>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($row['control_no']) ?></td>
                                            <td><strong><?= htmlspecialchars(explode(' ', $row['last_updated'])[0]) ?></strong></td>
                                            <td><strong><?= htmlspecialchars($row['total_machine_output']) ?></strong></td>
                                            <td data-machine-id="<?= htmlspecialchars($row['machine_id']) ?>" data-part="cut_blade">
                                                <div><strong><?= htmlspecialchars($row['cut_blade_output']) ?></strong> / 2M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 42%;"></div>
                                                </div>
                                            </td>
                                            <td data-machine-id="<?= htmlspecialchars($row['machine_id']) ?>" data-part="strip_blade_a">
                                                <div><strong><?= htmlspecialchars($row['strip_blade_a_output']) ?></strong> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 42%;"></div>
                                                </div>
                                            </td>
                                            <td data-machine-id="<?= htmlspecialchars($row['machine_id']) ?>" data-part="strip_blade_b">
                                                <div><strong><?= htmlspecialchars($row['strip_blade_b_output']) ?></strong> / 1.5M</div>
                                                <div class="progress-bar">
                                                    <div class="progress-fill" style="width: 38%;"></div>
                                                </div>
                                            </td>
                                            <?php foreach ($part_names_array as $part_name): ?>
                                                <td data-machine-id="<?= htmlspecialchars($row['machine_id']) ?>" data-part="custom_parts_<?= htmlspecialchars($part_name) ?>">
                                                    <div><strong><?= htmlspecialchars($row['custom_parts_output'][$part_name] ?? 0) ?></strong> / 1.5M</div>
                                                    <div class="progress-bar">
                                                        <div class="progress-fill" style="width: 0%;"></div>
                                                    </div>
                                                </td>
                                            <?php endforeach; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
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
                        <div class="pagination-items-per-page" style="display: flex; align-items: center; gap: 24px;">
                            <form id="items-per-page-form" method="get" style="display: flex; align-items: center; gap: 8px;">
                                <?php
                                    // Preserve other GET parameters except items_per_page and page
                                    $query_params = $_GET;
                                    unset($query_params['items_per_page'], $query_params['page']);
                                    foreach ($query_params as $key => $value) {
                                        echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                                    }
                                ?>
                                <label for="items-per-page">Show:</label>
                                <select id="items-per-page" name="items_per_page" onchange="document.getElementById('items-per-page-form').submit()">
                                    <option value="5" <?= $items_per_page == 5 ? 'selected' : '' ?>>5</option>
                                    <option value="10" <?= $items_per_page == 10 ? 'selected' : '' ?>>10</option>
                                    <option value="20" <?= $items_per_page == 20 ? 'selected' : '' ?>>20</option>
                                </select>
                                <span>per page</span>
                            </form>
                            <!-- Go to Page Form -->
                            <form id="go-to-page-form" method="get" style="display: flex; align-items: center; gap: 8px;">
                                <?php
                                    // Preserve other GET parameters except page
                                    $query_params = $_GET;
                                    unset($query_params['page']);
                                    foreach ($query_params as $key => $value) {
                                        echo '<input type="hidden" name="' . htmlspecialchars($key) . '" value="' . htmlspecialchars($value) . '">';
                                    }
                                ?>
                                <label for="go-to-page-input">Go to page:</label>
                                <input 
                                    type="number" 
                                    id="go-to-page-input" 
                                    name="page" 
                                    min="1" 
                                    max="<?= $total_pages ?>" 
                                    value="<?= $current_page ?>" 
                                    style="width: 60px; padding: 2px 6px;"
                                    required
                                >
                                <button type="submit" class="btn btn-secondary" style="padding: 2px 10px;">Go</button>
                            </form>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Table 1: Custom Parts -->
            <div class="tables-grid">
                <?php include_once __DIR__ . '/machine_custom_parts.php'; ?>

                <!-- Table 2: Recently Deleted Machine -->
                <div class="tables-scroll-2">
                    <?php include_once __DIR__ . '/recently_deleted_machine.php'; ?>
                </div>
            </div>

                <!-- Table 3: Recently Deleted Outputs Section -->
            <div class="full-width-table" style="position: relative; top: -50px;">
                <?php include_once __DIR__ . '/recently_deleted_outputs_table.php'; ?>
            </div>
        </div>
    </div>

    
    <!-- Add Custom Part Modal -->
    <div id="addCustomPartModalDashboardMachine" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn">×</button>
            
            <div class="form-header">
                <h1 class="form-title">➕ Add Custom Part</h1>
                <p class="form-subtitle">Add a new custom part to this machine</p>
            </div>

            <form id="addCustomPartForm" method="POST" action="../controllers/add_custom_part.php">
                <input type="hidden" name="equipment_type" value="MACHINE">

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
                
                <div class="button-group">
                    <button type="button" class="btn btn-secondary" onclick="closeAddCustomPartModal()">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="submitBtn">
                        Add Part
                    </button>
                    
                </div>
            </form>
        </div>
    </div>
    
    
    <!-- Delete Custom Part Modal -->
    <div id="deleteCustomPartModalDashboardMachine" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeDeleteCustomPartModal()">×</button>
            
            <div class="form-header">
                <span class="delete-icon">🗑️</span>
                <h1 class="form-title">Delete Custom Part</h1>
                <p class="form-subtitle">Permanently remove this custom machine part</p>
            </div>

            <form id="deleteCustomPartForm" method="POST" action="../controllers/delete_custom_part.php">
                <input type="hidden" name="equipment_type" value="MACHINE">
                <input type="hidden" name="part_id" value="" id="delete_part_id">
                
                <div class="warning-section">
                    <span class="warning-icon">⚠️</span>
                    <div class="warning-title">Permanent Action</div>
                    <div class="warning-text">
                        This action will permanently remove this custom part for machines. This action cannot be undone.
                    </div>
                </div>

                <div class="part-details">
                    <div class="part-info">
                        <div class="part-icon">⚙️</div>
                        <div class="part-content">
                            <div class="part-name" id="delete_part_name">Custom Part</div>
                            <div class="part-meta">Part ID: <span id="delete_part_id_display">#CP001</span></div>
                        </div>
                    </div>
                </div>

                <div class="confirmation-section">
                    <label class="confirmation-checkbox">
                        <input type="checkbox" id="confirmDelete" class="confirmation-input" onchange="toggleDeleteButton()">
                        <span class="confirmation-label">
                            I understand that this action is permanent and cannot be undone. I want to delete this custom part.
                        </span>
                    </label>
                </div>
                <div class="button-group">
                    <button type="button" class="cancel-btn" onclick="closeDeleteCustomPartModal()">Cancel</button>
                    <button type="submit" class="btn delete-btn" id="deleteBtn" disabled>Delete Part Permanently</button>
                </div>
            
            </form>
        </div>
    </div>

    <!-- Edit Custom Part Modal -->
    <div id="editCustomPartModalDashboardMachine" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn">×</button>

            <div class="form-header">
                <h1 class="form-title">✏️ Edit Custom Part</h1>
                <p class="form-subtitle">Edit the details of this custom part</p>
            </div>

            <form id="editCustomPartForm" method="POST" action="../controllers/edit_custom_part.php">
                <input type="hidden" name="equipment_type" value="MACHINE">
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
                    <button type="button" class="cancel-btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reset Machine Modal -->
    <div id="resetModalDashboardMachine" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn" onclick="closeResetModal()">×</button>
            
            <div class="form-header">
                <h1 class="form-title">🔄 Reset Machine</h1>
                <p class="form-subtitle">Reset machine component usage counter</p>
            </div>

            <form id="resetForm" method="POST" action="../controllers/reset_machine.php">
                <input type="hidden" name="machine_id" id="reset_machine_id">

                <div class="form-section">
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <div class="section-info">
                            <div class="section-title">Component Selection</div>
                            <div class="section-description">Choose the machine part to reset</div>
                        </div>
                    </div>
                    <div class="form-grid-vertical">
                        <div class="form-group">
                            <label class="form-label">
                                Select Machine Part to Reset
                                <span class="required-badge">Required</span>
                            </label>
                            <select id="editWireType" name="part_name" class="form-input" required>
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
                    </div>
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

                <div class="button-group">
                    <button type="button" class="cancel-btn" onclick="closeResetModal()">Cancel</button>
                    <button type="submit" class="reset-btn">Confirm Reset</button>
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

                <div class="button-group">
                    <button type="button" class="cancel-btn" onclick="closeUndoModal()">Cancel</button>
                    <button type="submit" class="undo-btn">Confirm</button>
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

    <!-- Export Modal -->
    <div id="exportMachineModal" class="modal-overlay">
        <div class="form-container">
            <button class="modal-close-btn">×</button>
            
            <form method="POST" action="../controllers/export_machine_output.php">
                <div class="form-header">
                    <h1 class="form-title">Export Machine Output Data</h1>
                    <p style="font-size: 14px; color: #6B7280;">Generate reports for machine outputs</p>
                </div>

                <!-- Export Format Section -->
                <div class="form-section">
                    <div class="info-section">
                        <div style="display: flex; align-items: flex-start; gap: 8px;">
                            <span class="info-icon">ℹ️</span>
                            <div>
                                <strong>Export Information</strong>
                                <p>The report will include all current machine outputs. Machines without output will not be included. The data will be exported in Excel format.</p>
                            </div>
                        </div>
                    </div>
                    <div class="section-header">
                        <div class="section-icon">⚙️</div>
                        <div class="section-info">
                            <div class="section-title">Additional Options</div>
                            <div class="section-description">Configure export settings</div>
                        </div>
                    </div>

                    <div class="checkbox-group">
                        <label class="checkbox-item">
                            <input type="checkbox" id="includeHeaders" name="includeHeaders" class="checkbox-input" checked>
                            <span class="checkbox-label">Include column headers</span>
                        </label>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="button-group">
                    <button type="button" class="cancel-btn">Cancel</button>
                    <button type="submit" class="export-btn">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                            <polyline points="7,10 12,15 17,10"/>
                            <line x1="12" y1="15" x2="12" y2="3"/>
                        </svg>
                        Generate Data
                    </button>
                </div>
            </form>
        </div>
    </div>
    <!-- Recently Reset Modal -->
    <?php include_once __DIR__ . '/machine_recently_reset.php'; ?>
    <!-- Edit Maximum Output Modal -->
    <?php include_once __DIR__ . '/machine_edit_maximum_output.php'; ?>
    <!-- Restore Machine Modal -->
    <?php include_once __DIR__ . '/machine_restore_modal.php'; ?>

    <script src="../../public/assets/js/dashboard_machine.js"></script>
    <script src="../../public/assets/js/dashboard_machine_progress.js"></script>
    <script src="../../public/assets/js/sidebar.js"></script>
    <!-- Disabled Machines Pagination -->
    <script src="../../public/assets/js/disabled_machines_pagination.js"></script>
    <!-- Include utility scripts -->
    <script src="../../public/assets/js/utils/enter.js"></script>
    <script src="../../public/assets/js/utils/exit.js"></script>
    <script src="../../public/assets/js/utils/checkbox.js"></script>
    <script src="../../public/assets/js/utils/pagination.js"></script>
    <!-- Edit maximum output script -->
    <script src="../../public/assets/js/maximum_output.js"></script>
</body>
</html>