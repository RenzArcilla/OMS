<link rel="stylesheet" href="/OMS/public/assets/css/components/modal.css">
<link rel="stylesheet" href="/OMS/public/assets/css/components/buttons.css">
<link rel="stylesheet" href="/OMS/public/assets/css/components/checkbox.css">
<link rel="stylesheet" href="/OMS/public/assets/css/logout.css">

<!-- Enhanced Logout Modal -->
<div class="logout-modal-overlay" id="logoutModalOverlay">
    <div class="logout-container">
        <button class="modal-close-btn">×</button>
        
        <!-- Enhanced Logout Header -->
        <div class="logout-header">
            <div class="logout-icon">🚪</div>
            <h1 class="logout-title">Log Out</h1>
            <p class="logout-subtitle">Are you sure you want to sign out of your account?</p>
        </div>
        
        <!-- Enhanced Logout Content -->
        <div class="logout-content">
            <div class="logout-section-header">
                <div class="logout-section-icon">⚠️</div>
                <div class="logout-section-info">
                    <div class="logout-section-title">Confirm Logout</div>
                    <div class="logout-section-description">Please confirm that you want to log out. You'll need to sign in again to access your account.</div>
                </div>
            </div>
            
            <div class="logout-checkbox-group">
                <label class="logout-checkbox-item">
                    <input type="checkbox" id="confirmLogout" name="confirmLogout" class="logout-checkbox-input" onchange="toggleLogoutButton()" required>
                    <span class="logout-checkbox-label">Yes, I want to log out of my account</span>
                </label>
            </div>
        </div>
        
        <!-- Enhanced Action Buttons -->
        <div class="logout-button-group">
            <button type="button" class="logout-cancel-btn" onclick="closeLogoutModal()">
                <span>Cancel</span>
            </button>
            <button type="button" class="logout-confirm-btn" id="logoutBtn" onclick="confirmLogout()">
                <span>Log Out</span>
            </button>
        </div>
    </div>
</div>

<script src="../../public/assets/js/utils/exit.js"></script>
<script src="../../public/assets/js/utils/enter.js"></script>
<script src="../../public/assets/js/utils/logout.js"></script>
<script src="../../public/assets/js/utils/checkbox.js"></script>

<script src="../../public/assets/js/logout.js"></script>