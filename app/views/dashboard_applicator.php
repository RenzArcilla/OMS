<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Admin Dashboard</title>
    <link rel="stylesheet" href="../../public/assets/css/dashboard_applicator.css">
</head>
<body>
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
                    <div class="stat-card status-good">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-warning">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-warning">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-good">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    <div class="stat-card status-critical">
                        <div class="stat-value">12,847</div>
                        <div class="stat-label">HP-001</div>
                    </div>
                    
                </div>

                <!-- Applicator Status Section -->
                <div class="data-section">
                    <div class="section-header expanded" onclick="toggleSection(this)">
                        <div class="section-title">
                            Applicator Status
                            <span class="section-badge">24</span>
                        </div>
                        <svg class="expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </div>
                        <div class="search-filter">
                            <input type="text" class="search-input" placeholder="Search applicator..." onkeyup="filterTable(this.value)">
                            <button class="filter-btn active" onclick="filterByStatus(this, 'all')">All</button>
                            <button class="filter-btn" onclick="filterByStatus(this, 'success')">Active</button>
                            <button class="filter-btn" onclick="filterByStatus(this, 'warning')">⚠️ Warning</button>
                        </div>
                    <div class="section-content expanded">
                        <div class="table-container">
                            <table class="data-table" id="metricsTable">
                                <thead>
                                    <tr>
                                        <th>HP No.</th>
                                        <th>Status</th>
                                        <th>Wire Type</th>
                                        <th>Last Encoded</th>
                                        <th>Total Output</th>
                                        <th>Wire Crimper</th>
                                        <th>Wire Anvil</th>
                                        <th>Insulation Crimper</th>
                                        <th>Insulation Anvil</th>
                                        <th>Slide Cutter</th>
                                        <th>Cutter Holder</th>
                                        <th>Shear Blade</th>
                                        <th>Cutter A</th>
                                        <th>Cutter B</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="metricsBody">
                                    <tr>
                                        <td><strong>HP-001</strong></td>
                                        <td><span class="status-badge status-good">Good</span></td>
                                        <td>BIG</td>
                                        <td>07/21/2025</td>
                                        <td>847</td>
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
                                        <td>
                                            <div>765K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 51%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>690K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 46%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>825K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 55%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>450K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 30%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>390K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 26%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>390K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 26%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div>390K / 1.5M</div>
                                            <div class="progress-bar">
                                                <div class="progress-fill" style="width: 26%;"></div>
                                            </div>
                                        </td>
                                        <td>
                                            <button class="btn-small btn-edit" onclick="openUndoModal()">Undo</button>
                                            <button class="btn-small btn-reset" onclick="openResetModal()">Reset</button>
                                        </td>
                                    </tr>
                                    
                                    </tr>
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
                <h2 class="modal-title">Undo Applicator - <span id="editHpNumber"></span></h2>
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
    <div id="resetModalDashboardApplicator" class="modal-overlay">
        <div class="modal modal-reset">
            <div class="modal-header">
                <h2 class="modal-title">Reset Applicator<span id="editHpNumber"></span></h2>
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
                        <label class="form-label">Are you sure you want to reset the applicator?</label>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-cancel" onclick="closeResetModal()">Cancel</button>
                <button type="button" class="btn-confirm" onclick="saveReset()">Confirm</button>
            </div>
        </div>
    </div>
    <!-- Machine Modal -->
    <!--div id="machineModalDashboardApplicator" class="modal-overlay">
        <div class="modal">
            <div class="modal-header">
                <h2 class="modal-title">Machine - <span id="editHpNumber"></span></h2>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <div class="form-group">
                        <label class="form-label">Select Machine</label>
                        <select id="editWireType" class="form-input">
                            <option>Select Machine</option>
                            <option>Wire Crimper</option>
                            <option>Wire Anvil</option>
                            <option>Insulation Crimper</option>
                            <option>Insulation Anvil</option>
                            <option>Slide Cutter</option>
                            <option>Cutter Holder</option>
                            <option>Shear Blade</option>
                            <option>Cutter A</option>
                            <option value="MEDIUM">Cutter B</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Select Machine</label>
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
    </div -->
    <script src="../../public/assets/js/dashboard_applicator.js"></script>
</body>
</html>