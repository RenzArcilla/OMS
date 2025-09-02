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

        <form id="exportForm" method="POST" action="../controllers/export_reset_applicator.php">
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">🎯</div>
                    <div class="section-info">
                        <div class="section-title">Export Configuration</div>
                        <div class="section-description">Choose date range and data fields to include</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">
                            From Date
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="date" name="date_from" class="form-input" required>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">
                            To Date
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="date" name="date_to" class="form-input" required>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px;">
                    <label class="form-label">
                        Export Format
                        <span class="required-badge">Required</span>
                    </label>
                    <select name="export_format" class="form-input" required>
                        <option value="">Select Format</option>
                        <option value="excel">Excel (.xlsx)</option>
                        <option value="csv">CSV (.csv)</option>
                        <option value="pdf">PDF Report</option>
                    </select>
                </div>
            </div>


            <div class="info-section">
                <div style="display: flex; align-items: flex-start; gap: 8px;">
                    <span class="info-icon">ℹ️</span>
                    <div>
                        <strong>Export Information</strong>
                        <p>The report will include all reset activities within the selected date range. Excel and CSV formats are suitable for data analysis, while PDF provides a formatted report for printing and sharing.</p>
                    </div>
                </div>
            </div>

            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeExportModal()">Cancel</button>
                <button type="submit" class="export-btn" onclick="handleExport(this)">
                    📊 Generate Export
                </button>
            </div>
        </form>
    </div>      
</div>

<script src="../../public/assets/js/utils/exit.js"></script>
    <script src="../../public/assets/js/utils/enter.js"></script>