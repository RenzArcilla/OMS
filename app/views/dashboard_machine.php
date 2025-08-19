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
    ?>
    <div class="admin-container">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Tab -->
            <div id="dashboard-tab" class="tab-content">
                <div class="page-header">
                    <h1 class="page-title">📊 Dashboard</h1>
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
                            Machine Status
                            <span class="section-badge">1648</span>
                        </div>
                        <svg class="expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                    
                        <div class="search-filter">
                            <input type="text" class="search-input" placeholder="Search machine..." onkeyup="filterTable(this.value)">
                            <button class="filter-btn active" onclick="filterByStatus(this, 'all')">All</button>
                            <button class="filter-btn" onclick="filterByStatus(this, 'success')">Active</button>
                            <button class="filter-btn" onclick="filterByStatus(this, 'warning')">⚠️ Warning</button>
                        </div>

                    <div class="section-content expanded">
                        <div class="table-container">
                            <table class="data-table" id="metricsTable">
                                <thead>
                                    <tr>
                                        <th>Actions</th>
                                        <th>Machine Number</th>
                                        <th>Last Updated</th>
                                        <th>Total Output</th>
                                        <th>Cut Blade Output</th>
                                        <th>Strip Blade A Output</th>
                                        <th>Strip Blade B Output</th>
                                        <?php foreach ($custom_machine_parts as $part): ?>
                                            <th>
                                                <a href="?filter_by=<?= urlencode($part['part_name']) ?>">
                                                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $part['part_name']))) ?>
                                                </a>
                                            </th>
                                        <?php endforeach; ?>
                                    </tr>
                                </thead>
                                
                                <tbody id="metricsBody">
                                    <tr>
                                        <td><strong>HP-001</strong></td>
                                        <td><span class="status-badge status-good">Good</span></td>
                                        <td>07/21/2025</td>
                                        <td>847</td>
                                        <td>
                                            <div>630K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 42%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>630K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 42%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>570K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 38%;"></div>
                                            </div>
                                        </td>
                                        
                                        </td>
                                        <td>
                                            <button class="btn-small btn-edit" onclick="openUndoModalDashboardMachine()">Undo</button>
                                            <button class="btn-small btn-reset" onclick="openResetModal()">Reset</button>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    <div id="resetModalDashboardMachine" class="modal-overlay">
        <div class="modal modal-reset">
            <div class="modal-header">
                <h2 class="modal-title">Reset Machine<span id="editHpNumber"></span></h2>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-group">
                        <label class="form-label">Select Machine Part to Reset</label>
                        <select id="editWireType" class="form-input">
                            <option>Cut Blade</option>
                            <option>Strip Blade A</option>
                            <option>Strip Blade B</option>
                        </select>
                    </div>
                </form>
                <form id="editForm">
                    <div class="form-group">
                        <label class="form-label">Are you sure you want to reset the machine?</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeResetModalDashboardMachine()">Cancel</button>
                <button type="button" class="btn-confirm" onclick="saveReset()">Confirm</button>
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