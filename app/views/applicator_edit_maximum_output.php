<div id="editMaxOutputModal" class="modal-overlay">
    <div class="form-container">
        <button class="modal-close-btn" onclick="closeEditOutputModal()" aria-label="Close modal">×</button>
        
        <div class="form-header">
            <h1 class="form-title">⚡ Edit Maximum Output</h1>
            <p class="form-subtitle">Configure applicator maximum output capacity</p>
        </div>

        <form id="editMaxOutputForm" method="POST" action="../controllers/edit_max_output.php">
            <input type="hidden" name="applicator_id" value="123">

            <!-- HP Selection Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">🔧</div>
                    <div class="section-info">
                        <div class="section-title">Maximum Output Configuration</div>
                        <div class="section-description">Select the maximum output capacity for this applicator</div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="hp_rating">
                            Applicator Number
                            <span class="required-badge">Required</span>
                        </label>
                        <select id="hp_rating" name="hp_rating" class="form-select" required>
                            <option value="">Select applicator number...</option>
                            <option value="25">HP001</option>
                            <option value="40">HP002</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Parts Selection Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">⚙️</div>
                    <div class="section-info">
                        <div class="section-title">Applicator Parts</div>
                        <div class="section-description">Select all parts included in this applicator configuration</div>
                    </div>
                </div>
                <div class="checkbox-grid">
                    <label class="checkbox-item" for="part_pump">
                        <input type="checkbox" id="part_pump" name="parts[]" value="pump" class="checkbox-input">
                        <div>
                            <div class="checkbox-label">Wire Crimper</div>
                        </div>
                    </label>
                    
                    <label class="checkbox-item" for="part_hoses">
                        <input type="checkbox" id="part_hoses" name="parts[]" value="hoses" class="checkbox-input">
                        <div>
                            <div class="checkbox-label">Wire Anvil</div>
                        </div>
                    </label>
                </div>
            </div>

            <!-- Maximum Output Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">📊</div>
                    <div class="section-info">
                        <div class="section-title">Maximum Output Capacity</div>
                        <div class="section-description">Specify the maximum application rate for this configuration</div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="max_output">
                            Maximum Output Rate
                            <span class="required-badge">Required</span>
                        </label>
                        <div class="output-input-group">
                            <div class="output-input-wrapper">
                                <input 
                                    type="number" 
                                    id="max_output" 
                                    name="max_output" 
                                    class="form-input" 
                                    placeholder="Enter maximum rate..." 
                                    required
                                    min="1"
                                    max="5000"
                                    step="0.1"
                                >
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="button-group">
                <button type="submit" class="submit-btn" id="submitBtn">
                    💾 Save Configuration
                </button>
                <button type="button" class="cancel-btn" onclick="closeEditOutputModal()">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<script src="../assets/js/maximum_output.js"></script>