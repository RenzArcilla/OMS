<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Blade Usage Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
        <div class="dashboard-container">
            <div class="header">
                <div class="logo">
                    <div class="logo-icon">S</div>
                    <span>SecureAuth</span>
                </div>
                <h1 class="dashboard-title">Blade Usage Dashboard</h1>
            </div>

            <div class="dashboard-grid">
                <div class="blade-card">
                    <div class="card-content">
                        <div class="blade-header">
                            <div class="blade-title">
                                <div class="blade-icon">S</div>
                                <div class="blade-name">Strip Blade</div>
                            </div>
                            <div class="status-badge warning" id="stripStatus">Needs Attention</div>
                        </div>
                        
                        <div class="usage-count" id="stripCount">1,200,000</div>
                        <div class="usage-label">Total Usage Count</div>
                        
                        <div class="progress-container">
                            <div class="progress-label">
                                <span>Usage Progress</span>
                                <span id="stripPercent">80%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill warning" id="stripProgress" style="width: 80%"></div>
                            </div>
                        </div>
                        
                        <div class="thresholds">
                            <div class="threshold-item">
                                <div class="threshold-dot good"></div>
                                <div class="threshold-value">500K</div>
                                <div class="threshold-desc">Good</div>
                            </div>
                            <div class="threshold-item">
                                <div class="threshold-dot warning"></div>
                                <div class="threshold-value">1M</div>
                                <div class="threshold-desc">Warning</div>
                            </div>
                            <div class="threshold-item">
                                <div class="threshold-dot critical"></div>
                                <div class="threshold-value">1.5M</div>
                                <div class="threshold-desc">Critical</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="blade-card">
                    <div class="card-content">
                        <div class="blade-header">
                            <div class="blade-title">
                                <div class="blade-icon">C</div>
                                <div class="blade-name">Cut Blade</div>
                            </div>
                            <div class="status-badge good" id="cutStatus">Good Condition</div>
                        </div>
                        
                        <div class="usage-count" id="cutCount">750,000</div>
                        <div class="usage-label">Total Usage Count</div>
                        
                        <div class="progress-container">
                            <div class="progress-label">
                                <span>Usage Progress</span>
                                <span id="cutPercent">37.5%</span>
                            </div>
                            <div class="progress-bar">
                                <div class="progress-fill good" id="cutProgress" style="width: 37.5%"></div>
                            </div>
                        </div>
                        
                        <div class="thresholds">
                            <div class="threshold-item">
                                <div class="threshold-dot good"></div>
                                <div class="threshold-value">800K</div>
                                <div class="threshold-desc">Good</div>
                            </div>
                            <div class="threshold-item">
                                <div class="threshold-dot warning"></div>
                                <div class="threshold-value">1.5M</div>
                                <div class="threshold-desc">Warning</div>
                            </div>
                            <div class="threshold-item">
                                <div class="threshold-dot critical"></div>
                                <div class="threshold-value">2M</div>
                                <div class="threshold-desc">Critical</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-title">Next Maintenance</div>
                    <div class="stat-value">Strip Blade</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Days Until Critical</div>
                    <div class="stat-value">45 days</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Overall System Health</div>
                    <div class="stat-value">Good</div>
                </div>
                <div class="stat-card">
                    <div class="stat-title">Last Updated</div>
                    <div class="stat-value">Today</div>
                </div>
            </div>
        </div>
    </body>
</html> 