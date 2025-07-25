<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOMS - Home</title>
    <link rel="stylesheet" href="../assets/css/soms_home_viewer.css">
</head>
<!-- Animated Background -->
<div class="background-canvas">
        <div class="floating-orb orb-1"></div>
        <div class="floating-orb orb-2"></div>
        <div class="floating-orb orb-3"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-container">
            <div class="logo"><img src="../assets/images/hepc_black.png" alt="Logo" style="width: 100%; max-width: 100px; position: relative; left: -50px; top: 10px;"></div>

            <ul class="nav-menu">
                <li class="nav-home"><a href="#platform">Home</a></li>
                <li class="nav-dashboard"><a href="#solutions">Dashboard</a></li>
            </ul>
            <button class="nav-cta">Log In</button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-container">
            <div class="hero-content">
                <div class="hero-badge">
                    <span class="hero-text">View-Access Only</span>
                </div>
                
                <h1 class="hero-title">
                    Storage and <br>
                    <span class="gradient-text">Output</span> <br>
                    Monitoring System
                </h1>
                
                <p class="hero-subtitle">
                    Monitor and control your storage and output devices with ease.
                </p>
                
                <div class="hero-actions">
                    <button class="btn btn-primary">
                        <span>Dashboard</span>
                    </button>
                    
                </div>
            </div>

            <div class="dashboard-container">
                <div class="dashboard-main">
                    <div class="dashboard-header">
                        <h3 class="dashboard-title">System Overview</h3>
                        <div class="dashboard-controls">
                            <div class="view-toggle">
                                <button class="view-btn active" data-view="summary">Summary</button>
                                <button class="view-btn" data-view="detailed">Detailed</button>
                            </div>
                            
                        </div>
                    </div>

                    <div class="dashboard-content">
                        <!-- Summary Cards -->
                        <div class="summary-cards">
                            <div class="summary-card">
                                <div class="summary-value">Connect to Database</div>
                                <div class="summary-label">No. of Machines in Operation</div>
                                <div class="summary-change positive">+12.5%</div>
                            </div>
                            <div class="summary-card">
                                <div class="summary-value">Connect to Database</div>
                                <div class="summary-label">No. of Machines in Operation</div>
                                <div class="summary-change positive">+23.1%</div>
                            </div>
                            
                        </div>

                        <!-- Performance Metrics Section -->
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
                                    <input type="text" class="search-input" placeholder="Search metrics..." onkeyup="filterTable(this.value)">
                                    <button class="filter-btn active" onclick="filterByStatus(this, 'all')">All</button>
                                    <button class="filter-btn" onclick="filterByStatus(this, 'success')">Active</button>
                                    <button class="filter-btn" onclick="filterByStatus(this, 'warning')">Warning</button>
                                </div>
                                <table class="data-table" id="metricsTable">
                                    <thead>
                                        <tr>
                                            <th>HP No.</th>
                                            <th>Wire Crimper</th>
                                            <th>Wire Anvil</th>
                                            <th>Insulation Crimper</th>
                                            <th>Insulation Anvil</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody id="metricsBody">
                                        <tr data-status="success">
                                            <td>Connect to Database the HP No.</td>
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
                                        <td>Connect to Database the HP No.</td>
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
                                            <td class="status-cell">
                                                <div class="status-indicator warning"></div>
                                                Monitor
                                            </td>
                                        </tr>
                                        <tr data-status="success">
                                            <td>Connect to Database the HP No.</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="status-cell">
                                                <div class="status-indicator success"></div>
                                                Normal
                                            </td>
                                        </tr>
                                        <tr data-status="success">
                                            <td>Connect to Database the HP No.</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="status-cell">
                                                <div class="status-indicator success"></div>
                                                Healthy
                                            </td>
                                        </tr>
                                        <tr data-status="warning">
                                            <td>Connect to Database the HP No.</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="status-cell">
                                                <div class="status-indicator warning"></div>
                                                Watch
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- User Analytics Section -->
                        <div class="data-section">
                            <div class="section-header expanded" onclick="toggleSection(this)">
                                <div class="section-title">
                                    Performance Metrics
                                    <span class="section-badge">24</span>
                                </div>
                                <svg class="expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                            <div class="section-content expanded">
                                <div class="search-filter">
                                    <input type="text" class="search-input" placeholder="Search metrics..." onkeyup="filterTable(this.value)">
                                    <button class="filter-btn active" onclick="filterByStatus(this, 'all')">All</button>
                                    <button class="filter-btn" onclick="filterByStatus(this, 'success')">Active</button>
                                    <button class="filter-btn" onclick="filterByStatus(this, 'warning')">Warning</button>
                                </div>
                                <table class="data-table" id="metricsTable">
                                    <thead>
                                        <tr>
                                            <th>AM No.</th>
                                            <th>Strip Blade</th>
                                            <th>Cut Blade</th>
                                            <th>Status</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody id="metricsBody">
                                        <tr data-status="success">
                                            <td>Connect to Database the AM No.</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="metric-value">Data Here</td>
                                            
                                            <td class="status-cell">
                                                <div class="status-indicator success"></div>
                                                Optimal
                                            </td>
                                        </tr>
                                        <tr data-status="success">
                                            <td>Connect to Database the AM No.</td>
                                            <td class="metric-value">Data Here</td>
                                            <td class="metric-value">Data Here</td>
                                            
                                            <td class="status-cell">
                                                <div class="status-indicator success"></div>
                                                Good
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
    </section>
</body>
</html>