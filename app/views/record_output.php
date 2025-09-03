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
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="icon" href="/SOMS/public/assets/images/favicon.ico">
    <title>Record Output</title>
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/base/header.css">
    <link rel="stylesheet" href="../../public/assets/css/record_output.css">
    <link rel="stylesheet" href="../../public/assets/css/add_entry.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/modal.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/header.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/tables.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/buttons.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/layout/grid.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/export_modal.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/search_filter.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/components/info.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/sidebar.php'; ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/SOMS/app/includes/header.php'; ?>
    <div class="container">
        <!-- Page Header -->
        <div class="main-content">
            <div class="page-header">
                <h1 class="page-title">📊 Production Records</h1>
                <div class="header-actions">
                    <button type="button" class="btn-secondary" onclick="exportData()">
                        Export Report
                    </button>
                    <button type="button" class="btn-primary" onclick="openModal()">
                        ➕ Add New Record
                    </button>
                </div>
            </div>
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
                    <form id="recordFilterForm" onsubmit="return false;" style="display: flex; gap: 10px; align-items: center;">
                        <input 
                            type="text" 
                            class="search-input" 
                            id="recordSearchInput"
                            placeholder="Search here..." 
                            onkeyup="applyRecordFilters(this.value)"
                            autocomplete="off"
                        >
                        <select 
                            id="recordShift" 
                            class="filter-select" 
                            onchange="applyRecordFilters()"
                            required
                        >  
                            <option value="ALL">All</option>
                            <option value="1st">1st</option>
                            <option value="2nd">2nd</option>    
                            <option value="NIGHT">Night</option>
                        </select>
                        <button 
                            type="button" 
                            class="tab-btn" 
                            id="refreshRecordTableBtn"
                            onclick="refreshData()"
                        >Refresh</button>
                    </form>
                </div>

                <div class="section-content expanded" id="table-container" style="height: 500px; overflow-y: auto;">
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
                                <!-- Render fetched machine data as table rows through AJAX -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="full-width-table">
                <?php include_once __DIR__ . '/recently_deleted_outputs_table.php'; ?>
            </div>
        </div>
    </div>
<!-- Modal Overlay -->
<div class="modal-overlay" id="modalOverlay">
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
                <button type="submit" class="submit-btn">
                    Submit Record
                </button>
                <button type="button" class="cancel-btn">
                    Cancel
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
                    <select id="dateRange" class="form-select" name="dateRange">
                        <option value="today">Today</option>
                        <option value="week">This Week</option>
                        <option value="month">This Month</option>
                        <option value="quarter">This Quarter</option>
                        <option value="custom">Custom Date Range</option>
                    </select>
                </div>

                <div id="customDates" class="date-inputs hidden">
                    <div class="form-group">
                        <label class="form-label" style="font-size: 12px; color: #6B7280;">Start Date</label>
                        <input type="date" id="startDate" name="startDate" class="form-input">
                    </div>
                    <div class="form-group">
                        <label class="form-label" style="font-size: 12px; color: #6B7280;">End Date</label>
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