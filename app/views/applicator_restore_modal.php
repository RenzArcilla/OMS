<!-- Restore Applicator Modal -->
<div id="restoreApplicatorModalDashboardApplicator" class="modal-overlay">
    <div class="form-container">
        <button class="modal-close-btn" onclick="closeRestoreApplicatorModal()">×</button>
        
        <div class="form-header">
            <span class="delete-icon">🗑️</span>
            <h1 class="form-title">Restore Applicator</h1>
            <p class="form-subtitle">Confirm restoration of deleted applicator</p>
        </div>

        <form id="restoreApplicatorForm" method="POST" action="../controllers/restore_applicator.php">
            <input type="hidden" name="applicator_id" value="" id="restore_applicator_id">
            
            <div class="warning-section">
                <span class="warning-icon">⚠️</span>
                <div class="warning-title">Restoration Action</div>
                <div class="warning-text">
                    This applicator will be restored and made available for normal operations. All previous data and settings will be preserved.
                </div>
            </div>

            <div class="part-details">
                <div class="part-info">
                    <div class="part-icon">⚙️</div>
                    <div class="part-content">
                        <div class="part-name" id="restore_applicator_name">Custom Part</div>
                        <div class="part-meta">Part ID: <span id="restore_applicator_id_display">#CP001</span></div>
                    </div>
                </div>
            </div>

            <div class="confirmation-section">
                <label class="confirmation-checkbox">
                    <input type="checkbox" id="confirmRestore" class="confirmation-input" onchange="toggleRestoreButton()">
                    <span class="confirmation-label">
                        I understand that this action will restore the applicator with all its previous data and settings. The applicator will be available for normal operations immediately after restoration.
                    </span>
                </label>
            </div>
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeRestoreApplicatorModal()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="restoreBtn" disabled>Restore Applicator</button>
            </div>
        
        </form>
    </div>
</div>