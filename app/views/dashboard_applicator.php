<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Admin Dashboard</title>
    <link rel="stylesheet" href="../../public/assets/css/dashboard.css">
<body>
    <div class="admin-container">
        <!-- Main Content -->
        <div class="main-content">
            <!-- Dashboard Tab -->
            <div id="dashboard-tab" class="tab-content">
                <div class="page-header">
                    <h1 class="page-title">📊 Dashboard</h1>
                    <div class="header-actions">
                        <button class="btn btn-secondary">
                            <i data-lucide="download"></i>
                            Export Report
                        </button>
                        <button class="btn btn-primary">
                            <i data-lucide="refresh-ccw"></i>
                            Refresh Data
                        </button>
                    </div>
                </div>
                
                <div class="stats-grid">

                    <div class="stat-card">
                        <div class="stat-card-content">
                            <div class="stat-info">
                                <h3>Cut Operations</h3>
                                <div class="stat-value">1,624</div>
                                <div class="stat-change positive">
                                    <i data-lucide="trending-up"></i>
                                    +8.2% this week
                                </div>
                            </div>
                            
                        </div>
                    </div>

                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-info">
                            <h3>Strip Operations</h3>
                            <div class="stat-value">1,223</div>
                            <div class="stat-change positive">
                                <i data-lucide="trending-up"></i>
                                +12.1% this week
                            </div>
                        </div>
                    </div>
                </div>

                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-info">
                            <h3>Strip Operations</h3>
                            <div class="stat-value">1,223</div>
                            <div class="stat-change positive">
                                <i data-lucide="trending-up"></i>
                                +12.1% this week
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-info">
                            <h3>Strip Operations</h3>
                            <div class="stat-value">1,223</div>
                            <div class="stat-change positive">
                                <i data-lucide="trending-up"></i>
                                +12.1% this week
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-info">
                            <h3>Strip Operations</h3>
                            <div class="stat-value">1,223</div>
                            <div class="stat-change positive">
                                <i data-lucide="trending-up"></i>
                                +12.1% this week
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-info">
                            <h3>Strip Operations</h3>
                            <div class="stat-value">1,223</div>
                            <div class="stat-change positive">
                                <i data-lucide="trending-up"></i>
                                +12.1% this week
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-info">
                            <h3>Strip Operations</h3>
                            <div class="stat-value">1,223</div>
                            <div class="stat-change positive">
                                <i data-lucide="trending-up"></i>
                                +12.1% this week
                            </div>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="stat-card-content">
                        <div class="stat-info">
                            <h3>Strip Operations</h3>
                            <div class="stat-value">1,223</div>
                            <div class="stat-change positive">
                                <i data-lucide="trending-up"></i>
                                +12.1% this week
                            </div>
                        </div>
                    </div>
                </div>
                    <!-- Tool Lifespan Warnings -->
                    <!--<div class="stat-card warning">
                        <div class="stat-card-content">
                            <div class="stat-info">
                                <h3>Tool Lifespan Warnings</h3>
                                <div class="stat-value">3</div>
                                <div class="stat-change warning">
                                    <i data-lucide="alert-triangle"></i>
                                    Attention required
                                </div>
                            </div>
                            <div class="stat-icon warning">
                                <i data-lucide="alert-triangle"></i>
                            </div>
                        </div>
                    </div>  -->
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
                    <div class="section-content expanded">
                        <div class="search-filter">
                            <input type="text" class="search-input" placeholder="Search applicator..." onkeyup="filterTable(this.value)">
                            <button class="filter-btn active" onclick="filterByStatus(this, 'all')">All</button>
                            <button class="filter-btn" onclick="filterByStatus(this, 'success')">Active</button>
                            <button class="filter-btn" onclick="filterByStatus(this, 'warning')">Warning</button>
                        </div>
                        <table class="data-table" id="metricsTable">
                            <thead>
                                <tr>
                                    <th>HP No.</th>
                                    <th>Wire</th>
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
                                </tr>
                            </thead>
                            <tbody id="metricsBody">
                                <tr data-status="success">
                                    <td class="metric-value">Connect to Database the HP No.</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="status-cell">
                                        <div class="status-indicator success"></div>
                                        Optimal
                                    </td>
                                </tr>
                                <tr data-status="success">
                                    <td class="metric-value">Connect to Database the HP No.</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="status-cell">
                                        <div class="status-indicator success"></div>
                                        Optimal
                                    </td>
                                </tr>
                                <tr data-status="warning">
                                    <td>Connect to Database the HP No.</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="metric-value">Data Here</td>
                                    <td class="status-cell">
                                        <div class="status-indicator success"></div>
                                        Optimal
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                        </div>

                
            </div>
        </div>
    </div>
    <script src="public/assets/js/side_bar.js"></script>
</body>
</html>