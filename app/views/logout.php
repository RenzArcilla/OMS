<link rel="stylesheet" href="/SOMS/public/assets/css/components/modal.css">
<link rel="stylesheet" href="/SOMS/public/assets/css/components/buttons.css">
    <div class="modal-overlay" id="logoutModalOverlay">
        <div class="form-container">
            <button class="modal-close-btn">×</button>
            
            <!-- Delete Icon -->
            <div class="form-header">
                <h1 class="form-title">🗑️Log out</h1>
            </div>
            
            <!-- Title and Message -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">🗑️</div>
                    <div class="section-info">
                        <div class="section-title">Log out</div>
                        <div class="section-description">Are you sure you want to log out?</div>
                    </div>
                </div>
                <div class="checkbox-group">
                    <label class="checkbox-item">
                        <input type="checkbox" id="includeHeaders" name="includeHeaders" class="checkbox-input" checked>
                        <span class="checkbox-label">Are you sure?</span>
                    </label>
                </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="delete-actions">
                <button type="button" class="cancel-btn" style="position: relative; left: 150px; top: 50px;" onclick="closeMachineDeleteModal()">Cancel</button>
                <button type="button" class="delete-btn" onclick="confirmDelete()">Delete</button>
            </div>
        </div>
    </div>

<script src="../../public/assets/js/utils/exit.js"></script>
<script src="../../public/assets/js/utils/enter.js"></script>