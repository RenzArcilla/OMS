<div id="restoreApplicatorModalDashboardApplicator" class="modal-overlay">
    <div class="form-container">
        <button class="modal-close-btn" onclick="closeRestoreApplicatorModal()" aria-label="Close modal">×</button>
        
        <div class="form-header">
            <span class="restore-icon">♻️</span>
            <h1 class="form-title">Restore Applicator</h1>
            <p class="form-subtitle">Recover this deleted applicator</p>
        </div>

        <div id="messageContainer"></div>

        <div class="applicator-details">
            <div class="applicator-info">
                <div class="applicator-icon">🚜</div>
                <div class="applicator-content">
                    <div class="applicator-name" id="applicatorName">Case IH FLX4510 Floater</div>
                    <div class="applicator-meta" id="applicatorMeta">Serial: FLX45-2024-001 • Model Year: 2024</div>
                    
                    <div class="deleted-info">
                        <span class="deleted-icon">🗑️</span>
                        <div class="deleted-text" id="deletedInfo">
                            Deleted on March 20, 2024 at 2:30 PM
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="confirmation-section">
            <label class="confirmation-checkbox">
                <input type="checkbox" id="confirmRestore" class="confirmation-input">
                <span class="confirmation-label">
                    I confirm that I want to restore this applicator back to active status.
                </span>
            </label>
        </div>

        <form id="restoreApplicatorForm" method="POST" action="../controllers/restore_applicator.php">
            <input type="hidden" name="applicator_id" value="" id="applicatorIdInput">
            
            <div class="form-actions">
                <button type="submit" class="btn btn-success" id="restoreBtn" disabled>
                    Restore Applicator
                </button>
                <button type="button" class="btn btn-secondary" onclick="closeRestoreApplicatorModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
<script src="../../public/assets/js/utils/restore.js">
    
</script>