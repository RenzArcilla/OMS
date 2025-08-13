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
*/
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Record Output</title>
    <!-- link rel="stylesheet" href="../../public/assets/css/record_output.css" -->
    <!-- link rel="stylesheet" href="../../public/assets/css/add_entry.css" -->
</head>
<body>
    <div class="container">
        <!-- Add Record Button -->
        <div class="tab-section">
            <button class="tab-btn" onclick="openModal()">
                ➕ Add New Record
            </button>
        </div>

        <!-- Modal Overlay -->
        <div class="modal-overlay" id="modalOverlay">
            <div class="form-container">
                <button class="modal-close-btn" onclick="closeModal()">×</button>
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
        </div>


        <!-- Scrollable container for infinite scrolling -->
        <div class="records-section">
            <div class="records-header">
                <div class="records-title">
                    📊 Latest Records
                    <span class="section-badge">10</span>
                </div>
            </div>

            <div class="search-filter">
                <input type="text" class="search-input" placeholder="Search records..." onkeyup="filterTable(this.value)">
                <button class="filter-btn active" onclick="filterByStatus(this, 'all')">All</button>
                <button class="filter-btn" onclick="filterByStatus(this, 'recent')">Recent</button>
                <button class="filter-btn" onclick="filterByStatus(this, 'today')">Today</button>
            </div>

            <div id="table-container" style="height: 500px; overflow-y: auto;">
                <table id="data-table">
                    <thead>
                        <tr>
                            <th>Record ID</th>
                            <th>Date Inspected</th>
                            <th>Date Encoded</th>
                            <th>Last Updated</th>
                            <th>Shift</th>
                            <th>Applicator1</th>
                            <th>App1 Output</th>
                            <th>Applicator2</th>
                            <th>App2 Output</th>
                            <th>Machine</th>
                            <th>Machine Output</th>
                            <th>Actions</th>
                        </tr>
                    </thead>

                    <?php   
                    // Include database connection and machine reader logic
                    require_once __DIR__ . '/../includes/db.php';
                    require_once __DIR__ . '/../models/read_joins/record_and_outputs.php';

                    // Fetch initial set of machines (first 20 entries)
                    $records = getRecordsAndOutputs(20, 0);
                    ?>

                    <tbody id="recordsTableBody">
                        <!-- Render fetched machine data as table rows -->
                        <?php foreach ($records as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['record_id']) ?></td>
                                <td><?= htmlspecialchars($row['date_inspected']) ?></td>
                                <td><?= htmlspecialchars(explode(' ', $row['date_encoded'])[0]) ?></td>
                                <td><?= htmlspecialchars(explode(' ', $row['last_updated'])[0]) ?></td>
                                <td><?= htmlspecialchars($row['shift']) ?></td>
                                <td><?= htmlspecialchars($row['hp1_no']) ?></td>
                                <td><?= htmlspecialchars($row['app1_output']) ?></td>
                                <td><?= htmlspecialchars($row['hp2_no']) ?></td>
                                <td><?= htmlspecialchars($row['app2_output']) ?></td>
                                <td><?= htmlspecialchars($row['control_no']) ?></td>
                                <td><?= htmlspecialchars($row['machine_output']) ?></td>
                                <td>
                                    <a href="#" onclick="openRecordEditModal(this)" 
                                        data-id="<?= htmlspecialchars($row['record_id']) ?>"
                                        data-date-inspected="<?= htmlspecialchars($row['date_inspected']) ?>"
                                        data-shift="<?= htmlspecialchars($row['shift'] === '1st' ? 'FIRST' : ($row['shift'] === '2nd' ? 'SECOND' : $row['shift'])) ?>"
                                        data-hp1-no="<?= htmlspecialchars($row['hp1_no']) ?>"
                                        data-app1-output="<?= htmlspecialchars($row['app1_output']) ?>"
                                        data-hp2-no="<?= htmlspecialchars($row['hp2_no']) ?>"
                                        data-app2-output="<?= htmlspecialchars($row['app2_output']) ?>"
                                        data-control-no="<?= htmlspecialchars($row['control_no']) ?>"
                                        data-machine-output="<?= htmlspecialchars($row['machine_output']) ?>"
                                    >✏️</a>

                                    <!-- Delete form -->
                                    <form action="/SOMS/app/controllers/delete_record.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                        <input type="hidden" name="record_id" value="<?= htmlspecialchars($row['record_id']) ?>">
                                        <button type="submit">🗑️</button>
                                    </form>
                                    </td>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Record Modal -->
        <div id="editRecordModal" class="modal-overlay">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title">
                        Edit Record
                    </h2>
                </div>
                
                <div class="modal-body">
                    <form id="editRecordForm" action="../controllers/edit_record.php" method="POST">
                        <!-- Hidden input to store record ID, previous IDs, and previous outputs -->
                        <input type="hidden" name="record_id" id="edit_record_id" required>
                        <input type="hidden" name="prev_app1" id="edit_prev_app1" required>
                        <input type="hidden" name="prev_app2" id="edit_prev_app2" required>
                        <input type="hidden" name="prev_machine" id="edit_prev_machine" required>
                        <input type="hidden" name="prev_app1_output" id="edit_prev_app1_output" required>
                        <input type="hidden" name="prev_app2_output" id="edit_prev_app2_output" required>
                        <input type="hidden" name="prev_machine_output" id="edit_prev_machine_output" required>
                        
                        <div class="form-group">
                            <label>Date Inspected:</label>
                            <input type="date" name="date_inspected" id="edit_date_inspected" value="<?= date('Y-m-d') ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Work Shift:</label>
                            <select name="shift" id="edit_shift" required>
                                <option value="">Choose your work shift</option>
                                <option value="FIRST">First Shift (Morning)</option>
                                <option value="SECOND">Second Shift (Afternoon)</option>
                                <option value="NIGHT">Night Shift (Overnight)</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Applicator 1:</label>
                            <input type="text" name="app1" id="edit_app1" required>
                        </div>

                        <div class="form-group">
                            <label>Applicator 1 Output:</label>
                            <input type="text" name="app1_output" id="edit_app1_output" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Applicator 2:</label>
                            <input type="text" name="app2" id="edit_app2">
                        </div>

                        <div class="form-group">
                            <label>Applicator 2 Output:</label>
                            <input type="text" name="app2_output" id="edit_app2_output">
                        </div>
                        
                        <div class="form-group">
                            <label>Machine Number:</label>
                            <input type="text" name="machine" id="edit_machine" required>
                        </div>

                        <div class="form-group">
                            <label>Machine Output:</label>
                            <input type="text" name="machine_output" id="edit_machine_output" required>
                        </div>

                        <div class="modal-footer">
                            <button type="button" onclick="closeRecordModal()">Cancel</button>
                            <button type="submit" form="editRecordForm">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        <!-- Infinite scroll logic -->
        <script src="../../public/assets/js/load_records.js" defer></script>
        <!-- Load modal logic for editing records -->
        <script src="../../public/assets/js/edit_record_modal.js" defer></script>
</body>
</html>