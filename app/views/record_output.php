<?php
/*
    This section is for recording a new output record to the system.
    It includes a form for entering details and submitting them to the server.
*/

/*
// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once __DIR__ . '/../includes/header.php'; // Include the header file for the navigation and logo
*/
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Record Output</title>
    <link rel="stylesheet" href="../../public/assets/css/record_output.css">
</head>
<body>
    <div class="form-container">
        <div class="form-header">
            <h1 class="form-title">Record Production Output</h1>
            <p class="form-subtitle">Enter your production data</p>
            
            <div class="instructions">
                <h3>📋 How to complete this form:</h3>
                <ul>
                    <li>Required fields are marked with a red badge</li>
                    <li>Optional fields can be left empty if not applicable</li>
                </ul>
            </div>
        </div>

        <div class="success-message" id="successMessage">
            ✅ Production record saved successfully! You can now enter another record.
        </div>
    <!-- Form for recording outputs -->
    
        <form action="../controllers/record_output.php" method="POST">
            <div class="form-section active">
                <div class="section-header">
                    <div class="section-icon">📅</div>
                    <div class="section-info">
                        <div class="section-title">Basic Information</div>
                        <div class="section-description">Enter the date and shift for this production record</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="date_inspected" class="form-label">
                            Inspection Date
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="date" id="date_inspected" name="date_inspected" class="form-input" value="<?= date('Y-m-d') ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="shift" class="form-label">
                            Work Shift
                            <span class="required-badge">Required</span>
                        </label>
                        <select id="shift" name="shift" class="form-input" required>
                            <option value="">Choose your work shift</option>
                            <option value="FIRST">First Shift (Morning)</option>
                            <option value="SECOND">Second Shift (Afternoon)</option>
                            <option value="NIGHT">Night Shift (Overnight)</option>
                        </select>
                        <div class="input-help">Select the shift when production occurred</div>
                    </div>
                </div>
            </div>

            <div class="form-section active">
                <div class="section-header">
                    <div class="section-icon">🔧</div>
                    <div class="section-info">
                        <div class="section-title">Applicator</div>
                        <div class="section-description">Enter applicator IDs and their output values</div>
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="app1" class="form-label">
                            Applicator 1
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" id="app1" name="app1" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="app1_output" class="form-label">
                            Applicator 1 Output
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" id="app1_output" name="app1_output" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="app2" class="form-label">
                            Applicator 2
                            <span class="optional-badge">Optional</span>
                        </label>
                        <input type="text" id="app2" name="app2" class="form-input">
                    </div>

                    <div class="form-group">
                        <label for="app2_output" class="form-label">
                            Applicator 2 Output
                            <span class="optional-badge">Optional</span>
                        </label>
                        <input type="text" id="app2_output" name="app2_output" class="form-input">
                    </div>
                </div>
            </div>

            <div class="form-section active">
                <div class="section-header">
                    <div class="section-icon">🏭</div>
                    <div class="section-info">
                        <div class="section-title">Machine Data</div>
                        <div class="section-description">Enter machine identification and total output</div>
                    </div>
                </div>
                
                <div class="form-grid">
                    <div class="form-group">
                        <label for="machine" class="form-label">
                            Machine Number
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" id="machine" name="machine" class="form-input" required>
                    </div>
    
                    <div class="form-group">
                        <label for="machine_output" class="form-label">
                            Machine Output
                            <span class="required-badge">Required</span>
                        </label>
                        <input type="text" id="machine_output" name="machine_output" class="form-input" required>
                    </div>
                </div>
            </div>
            
            <!-- Submit -->
            <div class="button-group">
                <button type="submit" class="submit-btn">
                    Submit Record
                </button>
            </div>
        </form>
    </div>
</body>
</html>