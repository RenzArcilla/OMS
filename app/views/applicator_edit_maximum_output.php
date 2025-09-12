<div id="editMaxOutputModal" class="modal-overlay">
    <div class="form-container">
        <button class="modal-close-btn" onclick="closeEditOutputModal()" aria-label="Close modal">×</button>
        
        <div class="form-header">
            <h1 class="form-title">⚡ Edit Maximum Output Limit</h1>
            <p class="form-subtitle">Configure applicator maximum output limit</p>
        </div>

        <form id="editMaxOutputForm" method="POST" action="../controllers/edit_applicator_part_max_limit.php">

            <!-- HP Selection Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">🔧</div>
                    <div class="section-info">
                        <div class="section-title">Maximum Output Configuration</div>
                        <div class="section-description">Select the maximum output limit for this applicator</div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="hp_rating">
                            Applicator Number
                            <span class="required-badge">Required</span>
                        </label>                     
                        <input type="text" 
                                name="hp_number" 
                                class="form-input" 
                                placeholder="Enter an HP number...">
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
                    <?php 
                    $standard_parts = ['wire_crimper', 'wire_anvil', 'insulation_crimper', 'insulation_anvil', 'slide_cutter',
                                        'cutter_holder','shear_blade', 'cutter_a', 'cutter_b'];
                    
                    // Fetch custom parts from the database
                    require_once __DIR__ . '/../models/read_custom_parts.php';
                    $custom_parts = [];
                    $custom_parts = getCustomParts("APPLICATOR");

                    foreach ($standard_parts as $part): ?>
                        <label class="checkbox-item" for="<?php echo htmlspecialchars($part); ?>">
                            <input 
                                type="checkbox" 
                                id="<?php echo htmlspecialchars($part); ?>" 
                                name="parts[]" 
                                value="<?php echo htmlspecialchars($part); ?>" 
                                class="checkbox-input">
                            <div>
                                <div class="checkbox-label"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $part))); ?></div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                    <?php foreach ($custom_parts as $custom_part): ?>
                        <label class="checkbox-item" for="<?php echo htmlspecialchars($custom_part['part_name']); ?>">
                            <input 
                                type="checkbox" 
                                id="<?php echo htmlspecialchars($custom_part['part_name']); ?>" 
                                name="parts[]" 
                                value="<?php echo htmlspecialchars($custom_part['part_name']); ?>" 
                                class="checkbox-input">
                            <div>
                                <div class="checkbox-label"><?php echo htmlspecialchars(ucwords(str_replace('_', ' ', $custom_part['part_name']))); ?></div>
                            </div>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Maximum Output Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon">📊</div>
                    <div class="section-info">
                        <div class="section-title">Maximum Output limit</div>
                        <div class="section-description">Specify the maximum output limit of the applicator parts for this configuration</div>
                    </div>
                </div>
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label" for="max_output">
                            Maximum Output Limit
                            <span class="required-badge">Required</span>
                        </label>
                        <div class="output-input-group">
                            <div class="output-input-wrapper">
                                <input 
                                    type="number" 
                                    id="max_output" 
                                    name="output_limit" 
                                    class="form-input" 
                                    placeholder="Enter maximum output limit..." 
                                    required
                                    min="0"
                                    step="10000">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="button-group">
                <button type="button" class="cancel-btn" onclick="closeEditOutputModal()">
                    Cancel
                </button>
                <button type="submit" class="submit-btn" id="submitBtn">
                    💾 Save Configuration
                </button>
            </div>
        </form>
    </div>
</div>