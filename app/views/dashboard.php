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
                                <h3>Total Outputs Today</h3>
                                <div class="stat-value">2,847</div>
                                <div class="stat-change positive">
                                    <i data-lucide="trending-up"></i>
                                    +15.3% vs yesterday
                                </div>
                            </div>
                            <div class="stat-icon">
                                <i data-lucide="scissors"></i>
                            </div>
                        </div>
                    </div>

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
                            <div class="stat-icon">
                                <i data-lucide="minus"></i>
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
                            <div class="stat-icon">
                                <i data-lucide="zap"></i>
                            </div>
                        </div>
                    </div>

                    <div class="stat-card warning">
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
                    </div>
                </div>

                <div class="dashboard-grid">
                    <div class="card">
                        <h3 class="card-title">📈 Weekly Productivity Overview</h3>
                        <div class="chart-placeholder">
                            <div>
                                <i data-lucide="trending-up" style="width: 48px; height: 48px; margin-bottom: 8px;"></i>
                                <p><strong>Productivity Chart</strong></p>
                                <p style="margin-top: 8px; font-size: 14px;">Daily/weekly productivity trends for cut and strip operations</p>
                                <p style="margin-top: 4px; font-size: 12px; color: #9CA3AF;">Chart visualization would display here</p>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px;">
                            <h3 class="card-title" style="margin: 0;">🔔 System Alerts</h3>
                            <i data-lucide="bell" style="width: 20px; height: 20px; color: #9ca3af;"></i>
                        </div>
                        <div class="activity-list">
                            <div class="activity-item">
                                <i data-lucide="alert-triangle" class="activity-icon warning"></i>
                                <div class="activity-content">
                                    <div class="activity-action">Applicator #3 nearing replacement</div>
                                    <div class="activity-user">85% wear detected</div>
                                </div>
                                <div class="activity-time">5 mins ago</div>
                            </div>
                            <div class="activity-item">
                                <i data-lucide="alert-circle" class="activity-icon critical"></i>
                                <div class="activity-content">
                                    <div class="activity-action">Cut blade #2 requires attention</div>
                                    <div class="activity-user">92% wear detected</div>
                                </div>
                                <div class="activity-time">15 mins ago</div>
                            </div>
                            <div class="activity-item">
                                <i data-lucide="check-circle" class="activity-icon success"></i>
                                <div class="activity-content">
                                    <div class="activity-action">User logged in</div>
                                    <div class="activity-user">Operator Johnson</div>
                                </div>
                                <div class="activity-time">22 mins ago</div>
                            </div>
                            <div class="activity-item">
                                <i data-lucide="alert-triangle" class="activity-icon warning"></i>
                                <div class="activity-content">
                                    <div class="activity-action">Strip tool #1 maintenance due</div>
                                    <div class="activity-user">Scheduled for tomorrow</div>
                                </div>
                                <div class="activity-time">1 hour ago</div>
                            </div>
                            <div class="activity-item">
                                <i data-lucide="activity" class="activity-icon info"></i>
                                <div class="activity-content">
                                    <div class="activity-action">Daily productivity report</div>
                                    <div class="activity-user">Generated automatically</div>
                                </div>
                                <div class="activity-time">2 hours ago</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional info cards -->
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 24px; margin-top: 24px;">
                    <div class="card">
                        <h3 class="card-title">🛠️ Applicator Status Summary</h3>
                        <div style="display: flex; flex-direction: column; gap: 12px;">
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(39, 174, 96, 0.05); border-radius: 8px; border-left: 4px solid #27AE60;">
                                <span style="font-weight: 600; color: #111827;">Applicator #1</span>
                                <span style="color: #27AE60; font-weight: 600;">42% wear</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(39, 174, 96, 0.05); border-radius: 8px; border-left: 4px solid #27AE60;">
                                <span style="font-weight: 600; color: #111827;">Applicator #2</span>
                                <span style="color: #27AE60; font-weight: 600;">58% wear</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(245, 158, 11, 0.05); border-radius: 8px; border-left: 4px solid #F59E0B;">
                                <span style="font-weight: 600; color: #111827;">Applicator #3</span>
                                <span style="color: #F59E0B; font-weight: 600;">85% wear ⚠️</span>
                            </div>
                            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(239, 68, 68, 0.05); border-radius: 8px; border-left: 4px solid #EF4444;">
                                <span style="font-weight: 600; color: #111827;">Cut Blade #2</span>
                                <span style="color: #EF4444; font-weight: 600;">92% wear 🚨</span>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
    <script src="public/assets/js/side_bar.js"></script>
</body>
</html>