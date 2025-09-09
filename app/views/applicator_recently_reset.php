<link rel="stylesheet" href="../../public/assets/css/components/buttons.css">
<link rel="stylesheet" href="../../public/assets/css/components/modal.css">
<link rel="stylesheet" href="../../public/assets/css/components/search_filter.css">
<link rel="stylesheet" href="../../public/assets/css/components/info.css">
<div id="exportModalRecentlyReset" class="modal-overlay">
    <div class="form-container">
        <button class="modal-close-btn">×</button>

        <div class="form-header">
            <h1 class="form-title">📊 Export Reset Data</h1>
            <p class="form-subtitle">Generate reports for recently reset applicators</p>
        </div>

        <form id="exportForm" method="POST" action="../controllers/export_applicator_reset.php">
            <div class="form-section">
                <div class="info-section">
                    <div style="display: flex; align-items: flex-start; gap: 8px;">
                        <span class="info-icon">ℹ️</span>
                        <div>
                            <strong>Export Information</strong>
                            <p>The report will include all reset activities within the selected date range. Excel and CSV formats are suitable for data analysis, while PDF provides a formatted report for printing and sharing.</p>
                        </div>
                    </div>
                </div>
                <div class="section-header">
                    <div class="section-icon">🎯</div>
                    <div class="section-info">
                        <div class="section-title">Export Configuration</div>
                        <div class="section-description">Choose date range and data fields to include</div>
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
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" id="includeHeaders" name="includeHeaders" class="checkbox-input" checked>
                        <span class="checkbox-label">Include column headers</span>
                    </label>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeExportModal()">Cancel</button>
                <button type="submit" class="export-btn">
                    Generate Export
                </button>
            </div>
        </form>
    </div>      
</div>

<script src="../../public/assets/js/utils/exit.js"></script>
<script src="../../public/assets/js/utils/enter.js"></script>