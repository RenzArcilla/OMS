<!-- Delete Record Modal (one instance, outside the loop, but included here for clarity; move to bottom of file in production) -->
<div id="deleteRecordModal" class="modal-overlay" style="display:none;">
    <div class="form-container">
        <button class="modal-close-btn" onclick="closeDeleteRecordModal()">×</button>
        
        <div class="form-header">
            <span class="delete-icon">🗑️</span>
            <h1 class="form-title">Delete Record</h1>
            <p class="form-subtitle">Are you sure you want to delete this record?</p>
        </div>
        <form id="deleteRecordForm" method="POST" action="/OMS/app/controllers/disable_record.php">
            <input type="hidden" name="record_id" id="delete_record_id" value="">
            <div class="warning-section">
                <span class="warning-icon">⚠️</span>
                <div class="warning-title">This action cannot be undone.</div>
                <div class="warning-text">
                    Deleting this record will remove it from the system. This action is irreversible.
                </div>
            </div>
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeDeleteRecordModal()">Cancel</button>
                <button type="submit" class="btn btn-danger">Delete</button>
            </div>
        </form>
    </div>
</div>