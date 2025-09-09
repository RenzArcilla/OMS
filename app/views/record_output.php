<?php
/*
    This section is for recording a new output record to the system.
    It includes a form for entering details and submitting them to the server.
*/

/*
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}
*/

// Pagination settings
$items_per_page = isset($_GET['items_per_page']) ? max(5, min(50, (int)$_GET['items_per_page'])) : 10;
$current_page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($current_page - 1) * $items_per_page;

// Handle search and shift filter
$search = $_GET['search'] ?? '';
$shift = $_GET['shift'] ?? 'ALL';
$search_result = null;
$is_searching = false;

if (!empty(trim($search))) {
    $is_searching = true;
    // Include the model for searching records
    require_once __DIR__ . '/../models/read_joins/record_and_outputs.php';
    $search_result = getFilteredRecords(1000, 0, strtoupper(trim($search)), $shift); // Get all search results
    
    // If searching, use search result instead of all records
    $records = $search_result;
    $total_records = count($search_result);
    $total_pages = 1;
} else {
    // Include the model for fetching records
    require_once __DIR__ . '/../models/read_joins/record_and_outputs.php';
    
    // Get total count for pagination
    $total_records = getRecordsCount(null, $shift);
    $total_pages = ceil($total_records / $items_per_page);
    
    // Use existing logic for all records with pagination
    $records = getFilteredRecords($items_per_page, $offset, null, $shift);
}
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/OMS/public/assets/images/favicon.ico">
    <title>Record Output</title>
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/base/header.css">
    <link rel="stylesheet" href="../../public/assets/css/record_output.css">
    <link rel="stylesheet" href="../../public/assets/css/add_entry.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/modal.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/header.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/tables.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/buttons.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/layout/grid.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/export_modal.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/search_filter.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/info.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/file_upload.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/sidebar.php'; ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/header.php'; ?>
    <div class="container">
        <!-- Page Header -->
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title">📊 Production Records</h1>
                <div class="header-actions">
                    <button type="button" class="btn-primary add-record-btn">
                        Add New Record
                    </button>
                    <button type="button" class="btn-primary export-btn">
                        Export Report
                    </button>
                    <button type="button" class="btn-primary export-reset-data-btn" onclick="downloadFileUploadFormat()">
                        <div>Download File Upload Format</div> 
                    </button> 
                </div>
            </div>
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/views/file_upload.php'; ?>


            <!-- Scrollable container for infinite scrolling -->
            <div class="data-section">
                <div class="section-header expanded" onclick="toggleSection(this)">
                    <div class="section-title">
                        <span class="filter-info">
                        Record Output
                        </span>
                    </div>
                </div>


                <!-- Filters -->
                <div class="search-filter">
                    <form method="GET" style="display: flex; gap: 10px; align-items: center;">
                        <?php if (isset($_GET['items_per_page']) && !$is_searching): ?>
                            <input type="hidden" name="items_per_page" value="<?= htmlspecialchars($_GET['items_per_page']) ?>">
                        <?php endif; ?>
                        
                        <input type="text" 
                                name="search" 
                                class="search-input" 
                                placeholder="Search here..." 
                                value="<?= htmlspecialchars($search) ?>"
                                onkeyup="if(event.key==='Enter') this.form.submit()"
                                autocomplete="off">
                        <select 
                            name="shift" 
                            class="filter-select" 
                            onchange="this.form.submit()"
                            required
                        >  
                            <option value="ALL" <?= $shift === 'ALL' ? 'selected' : '' ?>>All</option>
                            <option value="1st" <?= $shift === '1st' ? 'selected' : '' ?>>1st</option>
                            <option value="2nd" <?= $shift === '2nd' ? 'selected' : '' ?>>2nd</option>    
                            <option value="NIGHT" <?= $shift === 'NIGHT' ? 'selected' : '' ?>>Night</option>
                        </select>
                        <button type="submit" class="filter-btn">Search</button>
                    </form>
                    
                    <button style="position: relative; left: -10px;" class="tab-btn" onclick="window.location.href = window.location.pathname;">
                        Clear
                    </button>
                </div>

                <div class="section-content expanded" id="table-container">
                    <div class="table-container">
                        <table class="data-table" id="data-table">
                            <thead>
                                <tr>
                                    <th>Actions</th>
                                    <th>Record ID</th>
                                    <th>Date Inspected</th>
                                    <th>Date Encoded</th>
                                    <th>Last Updated</th>
                                    <th>Shift</th>
                                    <th>Applicator1</th>
                                    <th>App1 Output</th>
                                    <th>Applicator2</th>
                                    <th>App2 Output</th>
                                    <th>Machine</th>
                                    <th>Machine Output</th>
                            </thead>
                            <tbody id="recordsTableBody">
                                <?php if (empty($records)): ?>
                                    <tr><td colspan="12" style="text-align:center;">No records found.</td></tr>
                                <?php else: ?>
                                    <?php foreach ($records as $row): ?>
                                        <tr>
                                            <td>
                                                <div class="actions">
                                                    <button class="edit-btn" onclick="openRecordEditModalSafe(this); return false;"
                                                        data-id="<?= htmlspecialchars($row['record_id']) ?>"
                                                        data-date-inspected="<?= htmlspecialchars($row['date_inspected']) ?>"
                                                        data-shift="<?= htmlspecialchars($row['shift']) ?>"
                                                        data-hp1-no="<?= htmlspecialchars($row['hp1_no'] ?? '') ?>"
                                                        data-app1-output="<?= htmlspecialchars($row['app1_output'] ?? '') ?>"
                                                        data-hp2-no="<?= htmlspecialchars($row['hp2_no'] ?? '') ?>"
                                                        data-app2-output="<?= htmlspecialchars($row['app2_output'] ?? '') ?>"
                                                        data-control-no="<?= htmlspecialchars($row['control_no'] ?? '') ?>"
                                                        data-machine-output="<?= htmlspecialchars($row['machine_output'] ?? '') ?>"
                                                        title="Edit Record"
                                                    >Edit</button>

                                                    <!-- Delete Record Modal Trigger -->
                                                    <button type="button" title="Delete Record" class="delete-btn" onclick="openDeleteRecordModal('<?= htmlspecialchars($row['record_id']) ?>')">Delete</button>

                                                    <!-- Delete Record Modal (one instance, outside the loop, but included here for clarity; move to bottom of file in production) -->
                                                    <div id="deleteRecordModal" class="modal-overlay" style="display:none;">
                                                        <div class="form-container">
                                                            <button class="modal-close-btn" onclick="closeDeleteRecordModal()">×</button>
                                                            
                                                            <div class="form-header">
                                                                <span class="delete-icon">🗑️</span>
                                                                <h1 class="form-title">Delete Record</h1>
                                                                <p class="form-subtitle">Are you sure you want to delete this record?</p>
                                                            </div>
                                                            <form id="deleteRecordForm" method="POST" action="/OMS/app/controllers/disable_record.php">
                                                                <input type="hidden" name="record_id" id="delete_record_id" value="">
                                                                <div class="warning-section">
                                                                    <span class="warning-icon">⚠️</span>
                                                                    <div class="warning-title">This action cannot be undone.</div>
                                                                    <div class="warning-text">
                                                                        Deleting this record will remove it from the system. This action is irreversible.
                                                                    </div>
                                                                </div>
                                                                <div class="button-group">
                                                                    <button type="button" class="cancel-btn" onclick="closeDeleteRecordModal()">Cancel</button>
                                                                    <button type="submit" class="btn btn-danger">Delete</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                    <script>
                                                        function openDeleteRecordModal(recordId) {
                                                            document.getElementById('delete_record_id').value = recordId;
                                                            document.getElementById('deleteRecordModal').style.display = 'flex';
                                                        }
                                                        function closeDeleteRecordModal() {
                                                            document.getElementById('deleteRecordModal').style.display = 'none';
                                                        }
                                                        // Optional: Close modal on overlay click
                                                        document.addEventListener('DOMContentLoaded', function() {
                                                            var modal = document.getElementById('deleteRecordModal');
                                                            if (modal) {
                                                                modal.addEventListener('click', function(e) {
                                                                    if (e.target === modal) closeDeleteRecordModal();
                                                                });
                                                            }
                                                        });
                                                    </script>
                                                </div>
                                            </td>
                                            <td><?= htmlspecialchars($row['record_id']) ?></td>
                                            <td><?= htmlspecialchars($row['date_inspected']) ?></td>
                                            <td><?= htmlspecialchars($row['date_encoded'] ? explode(' ', $row['date_encoded'])[0] : '') ?></td>
                                            <td><?= htmlspecialchars($row['last_updated'] ? explode(' ', $row['last_updated'])[0] : '') ?></td>
                                            <td><?= htmlspecialchars($row['shift']) ?></td>
                                            <td><?= htmlspecialchars($row['hp1_no'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['app1_output'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['hp2_no'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['app2_output'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['control_no'] ?? '') ?></td>
                                            <td><?= htmlspecialchars($row['machine_output'] ?? '') ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
                            <a href="?page=<?= $current_page - 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= $shift !== 'ALL' ? '&shift=' . urlencode($shift) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
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
                                <a href="?page=1<?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= $shift !== 'ALL' ? '&shift=' . urlencode($shift) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
                                    class="pagination-btn">1</a>
                                <?php if ($start_page > 2): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif; ?>
                            <?php endif; ?>
                            
                            <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                                <?php if ($i == $current_page): ?>
                                    <span class="pagination-btn pagination-current"><?= $i ?></span>
                                <?php else: ?>
                                    <a href="?page=<?= $i ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= $shift !== 'ALL' ? '&shift=' . urlencode($shift) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
                                        class="pagination-btn"><?= $i ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($end_page < $total_pages): ?>
                                <?php if ($end_page < $total_pages - 1): ?>
                                    <span class="pagination-ellipsis">...</span>
                                <?php endif; ?>
                                <a href="?page=<?= $total_pages ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= $shift !== 'ALL' ? '&shift=' . urlencode($shift) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
                                    class="pagination-btn"><?= $total_pages ?></a>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Next Button -->
                        <?php if ($current_page < $total_pages): ?>
                            <a href="?page=<?= $current_page + 1 ?><?= !empty($search) ? '&search=' . urlencode($search) : '' ?><?= $shift !== 'ALL' ? '&shift=' . urlencode($shift) : '' ?><?= isset($_GET['items_per_page']) ? '&items_per_page=' . htmlspecialchars($_GET['items_per_page']) : '' ?>" 
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
                                <option value="50" <?= $items_per_page == 50 ? 'selected' : '' ?>>50</option>
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
            <div class="full-width-table">
                <?php include_once __DIR__ . '/recently_deleted_outputs_table.php'; ?>
            </div>
        </div>
    </div>
<!-- Add Record Modal Overlay -->
<div class="modal-overlay" id="addRecordModal">
    <div class="form-container">
        <button class="modal-close-btn" onclick="closeModal()">×</button>
        <div class="form-header">
            <h1 class="form-title">Record Production Output</h1>
            <p class="form-subtitle">Enter your production data</p>
            
            <div class="instructions">
                <h3>📋 How to complete this form:</h3>
                <ul>
                    <li>Required fields are marked with a red badge</li>
                    <li>Optional fields can be left empty if not applicable</li>
                </ul>
            </div>
        </div>

    <!-- Form for recording outputs -->
        <form action="../controllers/record_output.php" method="POST">
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">📅</div>
                    <div class="section-info">
                        <div class="section-title">Basic Information</div>
                        <div class="section-description">Enter the date and shift for this production record</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="date_inspected" class="form-label">
                            Inspection Date
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="date" id="date_inspected" name="date_inspected" class="form-input" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="shift" class="form-label">
                            Work Shift
                            <span class="required-badge">Required</span>
                        </label>
                        <select id="shift" name="shift" class="form-input" required>
                            <option value="">Choose your work shift</option>
                            <option value="FIRST">First Shift (Morning)</option>
                            <option value="SECOND">Second Shift (Afternoon)</option>
                            <option value="NIGHT">Night Shift (Overnight)</option>
                        </select>
                        <div class="input-help">Select the shift when production occurred</div>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">🔧</div>
                    <div class="section-info">
                        <div class="section-title">Applicator</div>
                        <div class="section-description">Enter applicator IDs and their output values</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="app1" class="form-label">
                            Applicator 1
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" id="app1" name="app1" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="app1_output" class="form-label">
                            Applicator 1 Output
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" id="app1_output" name="app1_output" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="app2" class="form-label">
                            Applicator 2
                            <span class="optional-badge">Optional</span>
                        </label>
                        <input type="text" id="app2" name="app2" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="app2_output" class="form-label">
                            Applicator 2 Output
                            <span class="optional-badge">Optional</span>
                        </label>
                        <input type="text" id="app2_output" name="app2_output" class="form-input">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">🏭</div>
                    <div class="section-info">
                        <div class="section-title">Machine Data</div>
                        <div class="section-description">Enter machine identification and total output</div>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="machine" class="form-label">
                            Machine Number
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" id="machine" name="machine" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="machine_output" class="form-label">
                            Machine Output
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" id="machine_output" name="machine_output" class="form-input" required>
                    </div>
                </div>
            </div>

            <!-- Submit -->
            <div class="button-group">
                <button type="button" class="cancel-btn">
                    Cancel
                </button>
                <button type="submit" class="submit-btn">
                    Submit Record
                </button>

            </div>
        </form>
    </div>
</div>
<!-- Edit Record Modal -->
<div id="editRecordModal" class="modal-overlay" style="display: none;">
    <div class="form-container">
        <button type="button" class="modal-close-btn" onclick="closeRecordModal()">×</button>
        
        <div class="form-header">
            <h1 class="form-title">✏️ Edit Record</h1>
            <p class="form-subtitle">Update production record information</p>
        </div>
        
        <form id="editRecordForm" action="../controllers/edit_record.php" method="POST" onsubmit="return validateEditForm()">
            <!-- Hidden inputs for tracking previous values -->
            <input type="hidden" name="record_id" id="edit_record_id" required>
            <input type="hidden" name="prev_date_inspected" id="edit_prev_date_inspected">
            <input type="hidden" name="prev_shift" id="edit_prev_shift">
            <input type="hidden" name="prev_app1" id="edit_prev_app1">
            <input type="hidden" name="prev_app2" id="edit_prev_app2">
            <input type="hidden" name="prev_machine" id="edit_prev_machine">
            <input type="hidden" name="prev_app1_output" id="edit_prev_app1_output">
            <input type="hidden" name="prev_app2_output" id="edit_prev_app2_output">
            <input type="hidden" name="prev_machine_output" id="edit_prev_machine_output">

            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">📅</div>
                    <div class="section-info">
                        <div class="section-title">Basic Information</div>
                        <div class="section-description">Update date and shift information</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_date_inspected" class="form-label">
                            Date Inspected
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="date" name="date_inspected" id="edit_date_inspected" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_shift" class="form-label">
                            Work Shift
                            <span class="required-badge">Required</span>
                        </label>
                        <select name="shift" id="edit_shift" class="form-input" required>
                            <option value="">Choose your work shift</option>
                            <option value="1st">First Shift (Morning)</option>
                            <option value="2nd">Second Shift (Afternoon)</option>
                            <option value="NIGHT">Night Shift (Overnight)</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">🔧</div>
                    <div class="section-info">
                        <div class="section-title">Applicators</div>
                        <div class="section-description">Update applicator information and output values</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_app1" class="form-label">
                            Applicator 1
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" name="app1" id="edit_app1" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_app1_output" class="form-label">
                            Applicator 1 Output
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="number" name="app1_output" id="edit_app1_output" class="form-input" min="0" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="edit_app2" class="form-label">
                            Applicator 2
                            <span class="optional-badge">Optional</span>
                        </label>
                        <input type="text" name="app2" id="edit_app2" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="edit_app2_output" class="form-label">
                            Applicator 2 Output
                            <span class="optional-badge">Optional</span>
                        </label>
                        <input type="number" name="app2_output" id="edit_app2_output" class="form-input" min="0">
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">🏭</div>
                    <div class="section-info">
                        <div class="section-title">Machine Data</div>
                        <div class="section-description">Update machine information and output values</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="edit_machine" class="form-label">
                            Machine Number
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" name="machine" id="edit_machine" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="edit_machine_output" class="form-label">
                            Machine Output
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="number" name="machine_output" id="edit_machine_output" class="form-input" min="0" required>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeRecordModal()">Cancel</button>
                <button type="submit" class="submit-btn">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Export Modal -->
<div id="exportModal" class="modal-overlay">
    <div class="form-container">
        <button class="modal-close-btn" onclick="closeExportModal()">×</button>

        <form method="POST" action="../controllers/export_record.php">
            <div class="form-header">
                <h1 class="form-title">Export Data</h1>
                <p style="font-size: 14px; color: #6B7280;">Choose your export format and options</p>
            </div>

            <!-- Export Format Section -->
            <div class="form-section">
                <div class="info-section">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <span class="info-icon">ℹ️</span>
                        <div>
                            <strong>Export Information</strong>
                            <p>The report will include all current production outputs. The data will be exported in Excel format.</p>
                        </div>
                    </div>
                </div>

            <!-- Date Range Section -->
                <div class="section-header">
                    <div class="section-icon">📅</div>
                    <div class="section-info">
                        <div class="section-title">Date Range</div>
                        <div class="section-description">Select the time period for your export</div>
                    </div>
                </div>
                
                <div class="form-group">
                    <select id="dateRange" class="form-select" name="dateRange" onchange="toggleCustomDates(this)">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="custom">Custom Date Range</option>  <!-- This triggers the show/hide -->
                    </select>

                    <div id="customDates" class="date-inputs hidden">  <!-- This gets shown/hidden -->
                        <div class="form-group">
                            <label class="form-label">Start Date</label>
                            <input type="date" id="startDate" name="startDate" class="form-input">
                        </div>
                        <div class="form-group">
                            <label class="form-label">End Date</label>
                            <input type="date" id="endDate" name="endDate" class="form-input">
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
                        <input type="checkbox" id="includeHeaders" name="includeHeaders" class="checkbox-input" checked>
                        <span class="checkbox-label">Include column headers</span>
                    </label>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeExportModal()">Cancel</button>
                <button type="submit" class="export-btn">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7,10 12,15 17,10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Export Data
                </button>
            </div>
        </form>
    </div>
</div>
<script src="../../public/assets/js/utils/exit.js"></script>
<script src="../../public/assets/js/utils/enter.js"></script>
<!-- Load modal logic for editing records -->
<script src="../../public/assets/js/edit_record_modal.js" defer></script>
<!-- Load modal logic for editing records -->
<script src="../../public/assets/js/record_output.js" defer></script>
<!-- Sidebar functionality -->
<script src="../../public/assets/js/sidebar.js" defer></script>
<!-- Search and filter functionality -->
<script src="../../public/assets/js/search_records.js" defer></script>
<!-- Infinite scroll logic>
<script src="../../public/assets/js/load_records.js" defer></script -->
</body>
</html>