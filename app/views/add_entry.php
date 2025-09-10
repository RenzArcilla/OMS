<?php
/*
    This section is for adding a new applicator or machine to the system.
    It includes a form for entering details and submitting them to the server.
*/

// Start session and check if user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Machine or Applicator</title>
    <link rel="icon" href="/OMS/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/add_entry.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/modal.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/header.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/tables.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/delete_modal.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/buttons.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/layout/grid.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/export_modal.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/base/header.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/search_filter.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/info.css">
    <link rel="stylesheet" href="/OMS/public/assets/css/components/pagination.css">
</head>
<body>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/sidebar.php'; ?>
    <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/OMS/app/includes/header.php'; ?>
    <div class="container">
        <div class="main-content">
            <!-- Dashboard Tab -->
            <div id="dashboard-tab" class="tab-content">
                <!-- Header -->
                <div class="page-header">
                    <h1 class="page-title">Manage Entries</h1>
                    <div class="header-actions">
                        <button type="button" class="btn-primary" onclick="refreshPage(this)">
                            Refresh Data
                        </button>
                        <button class="btn-primary" onclick="openMachineModal()">
                            Add Machine
                        </button>
                        <button class="btn-primary" onclick="openApplicatorModal()">
                            Add Applicator
                        </button>
                        <button type="button" class="btn-primary" onclick="showExportMachineModal()">
                            Export Machines
                        </button>
                        <button type="button" class="btn-primary" onclick="showExportApplicatorModal()">
                            Export Applicators
                        </button>
                    </div>
                </div>
                <!-- Tab Section -->
                <div class="tab-section">   
                    <button class="tab-btn active" onclick="switchTab('machines')">🔧 Machines</button>
                    <button class="tab-btn" onclick="switchTab('applicators')">⚡ Applicators</button>
                </div>  
                <!-- Machine Table -->
                <div class="data-section">
                    <div id="machine-table" class="section-content expanded">
                        <!-- Filters -->
                        <div class="search-filter" style="display: flex; gap: 10px; align-items: center;">
                            <form id="machineFilterForm" onsubmit="return false;">
                                <input 
                                    type="text" 
                                    class="search-input" 
                                    id="machineSearchInput"
                                    placeholder="Search here..." 
                                    onkeyup="applyMachineFilters(this.value)"
                                    autocomplete="off"
                                >
                                <select 
                                    id="machineDescription" 
                                    class="filter-select" 
                                    onchange="applyMachineFilters()"
                                    required
                                >  
                                    <option value="ALL">All Types</option>
                                    <option value="AUTOMATIC">Automatic</option>
                                    <option value="SEMI-AUTOMATIC">Semi-Automatic</option>
                                </select>
                            </form>
                        </div>
                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Actions</th>
                                        <th>Control No</th>
                                        <th>Description</th>
                                        <th>Model</th>
                                        <th>Maker</th>
                                        <th>Serial No</th>
                                        <th>Invoice No</th>
                                    </tr>
                                </thead>
                                <tbody id="machine-body">
                                    <!-- Fetch machine data as table rows via AJAX-->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Machine Pagination -->
                        <div id="machine-pagination" class="pagination-container" style="display: none;">
                            <div class="pagination-info">
                                <span class="pagination-text" id="machine-pagination-info">
                                    Showing 0 to 0 of 0 results
                                </span>
                            </div>
                            
                            <div class="pagination-controls">
                                <button id="machine-prev-btn" class="pagination-btn pagination-prev disabled" disabled>
                                    <span>←</span> Previous
                                </button>
                                
                                <div class="pagination-numbers" id="machine-pagination-numbers">
                                    <!-- Page numbers will be generated here -->
                                </div>
                                
                                <button id="machine-next-btn" class="pagination-btn pagination-next disabled" disabled>
                                    Next <span>→</span>
                                </button>
                            </div>
                            
                            <div class="pagination-items-per-page">
                                <form id="machine-items-per-page-form" style="display: flex; align-items: center; gap: 8px;">
                                    <label for="machine-items-per-page">Show:</label>
                                    <select id="machine-items-per-page" name="limit">
                                        <option value="10">10</option>
                                        <option value="20" selected>20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span>per page</span>
                                </form>
                            </div>
                        </div>
                    </div>
                    

                    <!-- Applicator Table -->
                    <div id="applicators-table" class="section-content expanded">
                        <!-- Filters -->
                        <div class="search-filter" style="display: flex; gap: 10px; align-items: center;">
                            <input 
                                type="text" 
                                class="search-input" 
                                placeholder="Search here..." 
                                onkeyup="applyApplicatorFilters(this.value)"
                                style="flex: 1 1 200px; min-width: 0;"
                            >
                            <select id="applicatorDescription" class="filter-select" onchange="applyApplicatorFilters()" style="min-width: 140px;">
                                <option value="ALL">All Types</option>
                                <option value="SIDE">SIDE</option>
                                <option value="END">END</option>
                                <option value="CLAMP">CLAMP</option>
                                <option value="STRIP AND CRIMP">STRIP AND CRIMP</option>
                            </select>
                            <select id="applicatorWireType" class="filter-select" onchange="applyApplicatorFilters()" style="min-width: 120px;">
                                <option value="ALL">All Types</option>
                                <option value="SMALL">Small</option>
                                <option value="BIG">Big</option>
                            </select>
                        </div>

                        <div class="table-container">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>Actions</th>
                                        <th>HP No</th>
                                        <th>Terminal No</th>
                                        <th>Description</th>
                                        <th>Wire Type</th>
                                        <th>Terminal Maker</th>
                                        <th>Applicator Maker</th>
                                        <th>Serial No</th>
                                        <th>Invoice No</th>
                                    </tr>
                                </thead>

                                <tbody id="applicator-body">
                                    <!-- Fetch applicator data as table rows via AJAX-->
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Applicator Pagination -->
                        <div id="applicator-pagination" class="pagination-container" style="display: none;">
                            <div class="pagination-info">
                                <span class="pagination-text" id="applicator-pagination-info">
                                    Showing 0 to 0 of 0 results
                                </span>
                            </div>
                            
                            <div class="pagination-controls">
                                <button id="applicator-prev-btn" class="pagination-btn pagination-prev disabled" disabled>
                                    <span>←</span> Previous
                                </button>
                                
                                <div class="pagination-numbers" id="applicator-pagination-numbers">
                                    <!-- Page numbers will be generated here -->
                                </div>
                                
                                <button id="applicator-next-btn" class="pagination-btn pagination-next disabled" disabled>
                                    Next <span>→</span>
                                </button>
                            </div>
                            
                            <div class="pagination-items-per-page">
                                <form id="applicator-items-per-page-form" style="display: flex; align-items: center; gap: 8px;">
                                    <label for="applicator-items-per-page">Show:</label>
                                    <select id="applicator-items-per-page" name="limit">
                                        <option value="10">10</option>
                                        <option value="20" selected>20</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                    <span>per page</span>
                                </form>
                            </div>
                        </div>
                    </div>
                <div>
            </div>
        </div>
    </div>
                <!-- Add Machine Form Modal-->
                <div id="addMachineModal" class="modal-overlay">
                    <div class="form-container">
                        <button class="modal-close-btn" onclick="closeAddMachineModal()">×</button>
                        
                        <div class="form-header">
                            <h1 class="form-title">🔧 Add Machine</h1>
                            <p class="form-subtitle">Enter new machine information</p>
                        </div>

                        <form id="addMachineForm" action="../controllers/add_machine.php" method="POST">
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">📋</div>
                                    <div class="section-info">
                                        <div class="section-title">Machine Details</div>
                                        <div class="section-description">Enter basic machine information</div>
                                    </div>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="machine_ctrl_no" class="form-label">
                                            Control No
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" id="machine_ctrl_no" name="control_no" class="form-input" required placeholder="Enter control number">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="description" class="form-label">
                                            Description
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <select id="description" name="description" class="form-input" required>
                                            <option value="">--Select--</option>
                                            <option value="AUTOMATIC">AUTOMATIC</option>
                                            <option value="SEMI-AUTOMATIC">SEMI-AUTOMATIC</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="model" class="form-label">
                                            Model
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" id="model" name="model" class="form-input" required placeholder="Enter model">
                                    </div>

                                    <div class="form-group">
                                        <label for="machine_maker" class="form-label">
                                            Machine Maker
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" id="machine_maker" name="machine_maker" class="form-input" required placeholder="Enter machine maker">
                                    </div>

                                    <div class="form-group">
                                        <label for="machine_serial_no" class="form-label">
                                            Serial No
                                            <span class="optional-badge">Optional</span>
                                        </label>
                                        <input type="text" id="machine_serial_no" name="serial_no" class="form-input" placeholder="Enter serial number">
                                    </div>

                                    <div class="form-group">
                                        <label for="machine_invoice_no" class="form-label">
                                            Invoice No
                                            <span class="optional-badge">Optional</span>
                                        </label>
                                        <input type="text" id="machine_invoice_no" name="invoice_no" class="form-input" placeholder="Enter invoice no.">
                                    </div>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="button" class="cancel-btn" onclick="closeAddMachineModal()">Cancel</button>
                                <button type="submit" id="machineActionBtn" class="submit-btn">Add Machine</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Applicator Modal -->
                <div id="addApplicatorModal" class="modal-overlay">
                    <div class="form-container">
                        <button class="modal-close-btn" onclick="closeAddApplicatorModal()">×</button>
                        
                        <div class="form-header">
                            <h1 class="form-title">⚡ Add Applicator</h1>
                            <p class="form-subtitle">Enter new applicator information</p>
                        </div>

                        <form id="applicatorForm" action="../controllers/add_applicator.php" method="POST">
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">🔧</div>
                                    <div class="section-info">
                                        <div class="section-title">Applicator Details</div>
                                        <div class="section-description">Enter basic applicator information</div>
                                    </div>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="add_applicator_ctrl_no" class="form-label">
                                            Control No
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" id="add_applicator_ctrl_no" name="control_no" class="form-input" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="add_applicator_terminal_no" class="form-label">
                                            Terminal No
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" id="add_applicator_terminal_no" name="terminal_no" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="add_applicator_description" class="form-label">
                                            Description
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <select id="add_applicator_description" name="description" class="form-input" required>
                                            <option value="">--Select--</option>
                                            <option value="SIDE">SIDE</option>
                                            <option value="END">END</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="add_applicator_wire_type" class="form-label">
                                            Wire Type
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <select id="add_applicator_wire_type" name="wire_type" class="form-input" required>
                                            <option value="">--Select--</option>
                                            <option value="BIG">BIG</option>
                                            <option value="SMALL">SMALL</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="add_terminal_maker" class="form-label">
                                            Terminal Maker
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" id="add_terminal_maker" name="terminal_maker" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="add_applicator_maker" class="form-label">
                                            Applicator Maker
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" id="add_applicator_maker" name="applicator_maker" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="add_applicator_serial_no" class="form-label">
                                            Serial No
                                            <span class="optional-badge">Optional</span>
                                        </label>
                                        <input type="text" id="add_applicator_serial_no" name="serial_no" class="form-input">
                                    </div>

                                    <div class="form-group">
                                        <label for="add_applicator_invoice_no" class="form-label">
                                            Invoice No
                                            <span class="optional-badge">Optional</span>
                                        </label>
                                        <input type="text" id="add_applicator_invoice_no" name="invoice_no" class="form-input">
                                    </div>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="button" class="cancel-btn" onclick="closeAddApplicatorModal()">Cancel</button>
                                <button type="submit" id="applicatorActionBtn" class="submit-btn">Add Applicator</button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Edit Machine Modal -->
                <div id="editModal" class="modal-overlay">
                    <div class="form-container">
                        <button class="modal-close-btn" onclick="closeModal()">×</button>
                        
                        <div class="form-header">
                            <h1 class="form-title">🔧 Edit Machine</h1>
                            <p class="form-subtitle">Update machine information</p>
                        </div>

                        <form id="editMachineForm" action="../controllers/edit_machine.php" method="POST">
                            <input type="hidden" name="machine_id" id="edit_machine_id">
                            
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="section-icon">✏️</div>
                                    <div class="section-info">
                                        <div class="section-title">Machine Details</div>
                                        <div class="section-description">Update machine information</div>
                                    </div>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="edit_control_no" class="form-label">
                                            Control No
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" name="control_no" id="edit_control_no" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_description" class="form-label">
                                            Description
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <select name="description" id="edit_description" class="form-input" required>
                                            <option value="">--Select--</option>
                                            <option value="AUTOMATIC">AUTOMATIC</option>
                                            <option value="SEMI-AUTOMATIC">SEMI-AUTOMATIC</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_model" class="form-label">
                                            Model
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" name="model" id="edit_model" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_maker" class="form-label">
                                            Maker
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" name="machine_maker" id="edit_maker" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_serial_no" class="form-label">
                                            Serial No
                                            <span class="optional-badge">Optional</span>
                                        </label>
                                        <input type="text" name="serial_no" id="edit_serial_no" class="form-input">
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_invoice_no" class="form-label">
                                            Invoice No
                                            <span class="optional-badge">Optional</span>
                                        </label>
                                        <input type="text" name="invoice_no" id="edit_invoice_no" class="form-input">
                                    </div>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="button" class="cancel-btn" onclick="closeMachineModal()">Cancel</button>
                                <button type="submit" class="submit-btn">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Edit Applicator Modal -->
                <div id="editApplicatorModal" class="modal-overlay">
                    <div class="form-container">
                        <button class="modal-close-btn" onclick="closeModal()">×</button>
                        
                        <div class="form-header">
                            <h1 class="form-title">⚡ Edit Applicator</h1>
                            <p class="form-subtitle">Update applicator information</p>
                        </div>

                        <form id="editApplicatorForm" action="../controllers/edit_applicator.php" method="POST">
                            <input type="hidden" name="applicator_id" id="edit_applicator_id">
                            
                            <div class="form-section">
                                <div class="section-header">
                                    <div class="edit-btn">✏️</div>
                                    <div class="section-info">
                                        <div class="section-title">Applicator Details</div>
                                        <div class="section-description">Update applicator information</div>
                                    </div>
                                </div>

                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="edit_applicator_control" class="form-label">
                                            HP No
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" name="control_no" id="edit_applicator_control" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_terminal_no" class="form-label">
                                            Terminal No
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" name="terminal_no" id="edit_terminal_no" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_applicator_description" class="form-label">
                                            Description
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <select name="description" id="edit_applicator_description" class="form-input" required>
                                            <option value="">--Select--</option>
                                            <option value="SIDE">SIDE</option>
                                            <option value="END">END</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_wire_type" class="form-label">
                                            Wire Type
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <select name="wire_type" id="edit_wire_type" class="form-input" required>
                                            <option value="">--Select--</option>
                                            <option value="BIG">BIG</option>
                                            <option value="SMALL">SMALL</option>
                                        </select>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_terminal_maker" class="form-label">
                                            Terminal Maker
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" name="terminal_maker" id="edit_terminal_maker" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_applicator_maker" class="form-label">
                                            Applicator Maker
                                            <span class="required-badge">Required</span>
                                        </label>
                                        <input type="text" name="applicator_maker" id="edit_applicator_maker" class="form-input" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_applicator_serial_no" class="form-label">
                                            Serial No
                                            <span class="optional-badge">Optional</span>
                                        </label>
                                        <input type="text" name="serial_no" id="edit_applicator_serial_no" class="form-input">
                                    </div>

                                    <div class="form-group">
                                        <label for="edit_applicator_invoice_no" class="form-label">
                                            Invoice No
                                            <span class="optional-badge">Optional</span>
                                        </label>
                                        <input type="text" name="invoice_no" id="edit_applicator_invoice_no" class="form-input">
                                    </div>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="button" class="cancel-btn" onclick="closeApplicatorModal()">Cancel</button>
                                <button type="submit" class="submit-btn">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-overlay" id="deleteModalOverlay">
                    <div class="delete-modal-container">
                        <button class="modal-close-btn" onclick="closeMachineDeleteModal()">×</button>
                        
                        <!-- Delete Icon -->
                        <div class="delete-icon-wrapper">
                            <span class="delete-icon" id="deleteIcon" style="font-size: 48px; display: flex; justify-content: center; align-items: center; margin-bottom: 16px;">🗑️</span>
                        </div>
                        
                        <!-- Title and Message -->
                        <h2 class="delete-title" id="deleteTitle" style="text-align: center; margin-bottom: 8px;">Delete Confirmation</h2>
                        <p class="delete-message" id="deleteMessage" style="text-align: center; color: #b91c1c; margin-bottom: 24px;">
                            Are you sure you want to delete this item? This action cannot be undone.
                        </p>
                        
                        <!-- Action Buttons -->
                        <div class="delete-actions" style="display: flex; justify-content: center; gap: 16px; margin-top: 16px;">
                            <button type="button" class="cancel-btn" onclick="closeMachineDeleteModal()">Cancel</button>
                            <button type="button" class="delete-btn" onclick="confirmDelete()">Delete</button>
                        </div>
                    </div>
                </div>
                
                <!-- Applicator Export Report Modal -->
                <div id="exportApplicatorReportModal" class="modal-overlay">
                    <div class="form-container">
                        <button class="modal-close-btn" onclick="closeExportApplicatorModal()">×</button>
                        
                        <form method="POST" action="../controllers/export_applicator.php">
                            <div class="form-header">
                                <h1 class="form-title">Export Applicator Data</h1>
                                <p style="font-size: 14px; color: #6B7280;">Generate reports for applicators</p>
                            </div>
                            
                            <!-- Export Format Section -->
                            <div class="form-section">
                                <div class="info-section">
                                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                                        <span class="info-icon">ℹ️</span>
                                        <div>
                                            <strong>Export Information</strong>
                                            <p>The report will include all current applicator data. The data will be exported in Excel format.</p>
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
                                <button type="button" class="cancel-btn" onclick="closeExportApplicatorModal()">Cancel</button>
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

                <!-- Machine Export Report Modal -->
                <div id="exportMachineReportModal" class="modal-overlay">
                    <div class="form-container">
                        <button class="modal-close-btn" onclick="closeExportMachineModal()">×</button>
                        
                        <form method="POST" action="../controllers/export_machine.php">
                            <div class="form-header">
                                <h1 class="form-title">Export Machine Data</h1>
                                <p style="font-size: 14px; color: #6B7280;">Generate reports for machine data</p>
                            </div>

                            <!-- Export Format Section -->
                            <div class="form-section">
                                <div class="info-section">
                                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                                        <span class="info-icon">ℹ️</span>
                                        <div>
                                            <strong>Export Information</strong>
                                            <p>The report will include all current machine data. The data will be exported in Excel format.</p>
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
                                <button type="button" class="cancel-btn" onclick="closeExportMachineModal()">Cancel</button>
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

    <script src="../../public/assets/js/add_entry.js"></script>
    <!-- script src="../../public/assets/js/export_entry.js"></script -->
    <script src="../../public/assets/js/sidebar.js"></script>
    <script src="../../public/assets/js/utils/exit.js"></script>
    <script src="../../public/assets/js/utils/enter.js"></script>
    <!-- Load edit modals -->
    <script src="../../public/assets/js/edit_machine_modal.js" defer></script>
    <script src="../../public/assets/js/edit_applicator_modal.js" defer></script>
    <!-- Load search logic -->
    <script src="../../public/assets/js/search_machines.js" defer></script>
    <script src="../../public/assets/js/search_applicators.js" defer></script>
    <!-- Load infinite scroll logic >
    <script src="../../public/assets/js/load_machines.js" defer></script>
    <script src="../../public/assets/js/load_applicators.js" defer></script -->

</body>
</html>