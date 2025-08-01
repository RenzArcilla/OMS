<?php
/*
    This section is for adding a new applicator or machine to the system.
    It includes a form for entering details and submitting them to the server.
*/

// Start session and check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

include_once __DIR__ . '/../includes/header.php'; // Include the header file for the navigation and logo
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Machine or Applicator</title>
    <!-- Load machine infinite scroll logic -->
    <link rel="stylesheet" href="../../public/assets/css/add_entry.css">
    <script src="../../public/assets/js/load_machines.js" defer></script>
    <!-- Load applicator infinite scroll logic -->
    <script src="../../public/assets/js/load_applicators.js" defer></script>
    <!-- Load modal logic for editing machines -->
    <script src="../../public/assets/js/edit_machine_modal.js" defer></script>
    <!-- Load modal logic for editing applicators -->
    <script src="../../public/assets/js/edit_applicator_modal.js" defer></script>
    <!-- For animation effects -->
    <script src="../../public/assets/js/animate.js" defer></script>
</head>
<body>
    <div classs="main-container">
        <!--Page Header -->
        <header class="page-header">
            <h1 class="page-title">ADD TOOLS</h1>
            <p class="page-subtitle">Manage nachines and applicators</p>
        </header>

        <!-- Selection Buttons -->
        <nav class="tab-navigation">
            <button class="tab-btn active" data-tab="machines">
                <span>Machines</span>
            </button>
            <button class="tab-btn" data-tab="applicators">
                <span>Applicators</span>
            </button>
        </nav>

        <!-- Add Machine Form -->
        <section id="machines-section" class="content-section active">
            <!-- Add Machine Form -->
            <div class="form-card">
                <div class="card-header">
                    <h2 class="card-title">Add New Machine</h2>
                    <p class="card-subtitle">Register a new machine to your inventory</p>
                </div>
                
                <form id="machine-form" action="../controllers/add_machine.php" method="POST">
                    <div class="form-group">    
                        <h2>Machine Information</h2>
                        <label for="machine_ctrl_no">Control No:</label>
                        <input type="text" id="machine_ctrl_no" name="control_no" required><br><br>

                    <label for="description">Description:</label>
                    <select id="description" name="description" required>
                        <option value="">--Select--</option>
                        <option value="AUTOMATIC">AUTOMATIC</option>
                        <option value="SEMI-AUTOMATIC">SEMI-AUTOMATIC</option>
                    </select><br><br>

                    <div class="form-group">
                        <label class="form-label" for="model">Model:</label>
                        <input type="text" id="model" name="model" required><br><br>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="machine_maker">Machine Maker:</label>
                        <input type="text" id="machine_maker" name="machine_maker" required><br><br>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="machine_serial_no">Serial No:</label>
                        <input type="text" id="machine_serial_no" name="serial_no"><br><br>
                    </div>

                    <div class="form-group">
                        <label class="machine_invoice_no">Invoice No:</label>
                        <input type="text" id="machine_invoice_no" name="invoice_no"><br><br>
                    </div>
                    
                    <button type="submit">Submit Machine</button>
                </form>

        <hr>


        <!-- Section: Table Display for Machines -->
        <div>
            <h3>Latest Machines Added</h3>

            <!-- Scrollable container for infinite scrolling -->
            <div id="machine-container" style="height: 300px; overflow-y: auto;">
                <table border="1">
                    <thead>
                        <!-- Table headers defining machine data columns -->
                        <tr>
                            <th>Control No</th>
                            <th>Description</th>
                            <th>Model</th>
                            <th>Maker</th>
                            <th>Serial No</th>
                            <th>Invoice No</th>
                            <th>Actions</th> 
                        </tr>
                    </thead>

                    <?php
                    // Include database connection and machine reader logic
                    require_once __DIR__ . '/../includes/db.php';
                    require_once __DIR__ . '/../models/read_machines.php';

                    // Fetch initial set of machines (first 10 entries)
                    $machines = getMachines($pdo, 10, 0);
                    ?>

                    <tbody id="machine-body">
                        <!-- Render fetched machine data as table rows -->
                        <?php foreach ($machines as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['control_no']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= htmlspecialchars($row['model']) ?></td>
                                <td><?= htmlspecialchars($row['maker']) ?></td>
                                <td><?= htmlspecialchars($row['serial_no']) ?></td>
                                <td><?= htmlspecialchars($row['invoice_no']) ?></td>
                                <td>

                                <!-- Edit link with data attributes -->
                                <button 
                                    type="button"
                                    onclick="openEditModal(this)"
                                    data-id="<?= $row['machine_id'] ?>"
                                    data-control="<?= htmlspecialchars($row['control_no'], ENT_QUOTES) ?>"
                                    data-description="<?= $row['description'] ?>"
                                    data-model="<?= htmlspecialchars($row['model'], ENT_QUOTES) ?>"
                                    data-maker="<?= htmlspecialchars($row['maker'], ENT_QUOTES) ?>"
                                    data-serial="<?= htmlspecialchars($row['serial_no'], ENT_QUOTES) ?>"
                                    data-invoice="<?= htmlspecialchars($row['invoice_no'], ENT_QUOTES) ?>"
                                >✏️</button>

                                <!-- Delete form -->
                                <form action="/SOMS/app/controllers/delete_machine.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this machine?');">
                                    <input type="hidden" name="machine_id" value="<?= $row['machine_id'] ?>">
                                    <button type="submit">🗑️</button>
                                </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>

        <!-- Edit Machine Modal -->
        <div id="editModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%);
            background:#fff; padding:20px; border:1px solid #ccc; box-shadow:0 0 10px rgba(0,0,0,0.2); z-index:999;">
            
            <form id="editMachineForm" action="../controllers/edit_machine.php" method="POST">
                <input type="hidden" name="machine_id" id="edit_machine_id">

                <h2>Edit Machine</h2>

                <label>Control No:</label>
                <input type="text" name="control_no" id="edit_control_no" required><br><br>

                <label>Description:</label>
                <select name="description" id="edit_description" required>
                    <option value="">--Select--</option>
                    <option value="AUTOMATIC">AUTOMATIC</option>
                    <option value="SEMI-AUTOMATIC">SEMI-AUTOMATIC</option>
                </select><br><br>

                <label>Model:</label>
                <input type="text" name="model" id="edit_model" required><br><br>

                <label>Maker:</label>
                <input type="text" name="machine_maker" id="edit_maker" required><br><br>

                <label>Serial No:</label>
                <input type="text" name="serial_no" id="edit_serial_no"><br><br>

                <label>Invoice No:</label>
                <input type="text" name="invoice_no" id="edit_invoice_no"><br><br>

                <button type="submit">Save</button>
                <button type="button" onclick="closeModal()">Cancel</button>
            </form>
        </div>

        <!-- Add Applicator Form -->
        <form action="../controllers/add_applicator.php" method="POST">
            <h2>Applicator Information</h2>

            <label for="applicator_ctrl_no">Control No:</label>
            <input type="text" id="applicator_ctrl_no" name="control_no" required><br><br>

            <label for="terminal_no">Terminal No:</label>
            <input type="text" id="terminal_no" name="terminal_no" required><br><br>

            <label for="description">Description:</label>
            <select id="description" name="description" required>
                <option value="">--Select--</option>
                <option value="SIDE">SIDE</option>
                <option value="END">END</option>
            </select><br><br>

            <label for="wire_type">Wire Type:</label>
            <select id="wire_type" name="wire_type" required>
                <option value="">--Select--</option>
                <option value="BIG">BIG</option>
                <option value="SMALL">SMALL</option>
            </select><br><br>

            <label for="terminal_maker">Terminal Maker:</label>
            <input type="text" id="terminal_maker" name="terminal_maker" required><br><br>

            <label for="applicator_maker">Applicator Maker:</label>
            <input type="text" id="applicator_maker" name="applicator_maker" required><br><br>

            <label for="applicator_serial_no">Serial No:</label>
            <input type="text" id="applicator_serial_no" name="serial_no"><br><br>

            <label for="applicator_invoice_no">Invoice No:</label>
            <input type="text" id="applicator_invoice_no" name="invoice_no"><br><br>

            <button type="submit">Submit Applicator</button>
        </form>


        <!-- Section: Table Display for Applicators -->
        <div>
            <h3>Latest Applicators Added</h3>

            <!-- Scrollable container for infinite scrolling -->
            <div id="applicator-container" style="height: 300px; overflow-y: auto;">
                <table border="1">
                    <thead>
                        <tr>
                            <th>HP No</th>
                            <th>Terminal No</th>
                            <th>Description</th>
                            <th>Wire Type</th>
                            <th>Terminal Maker</th>
                            <th>Applicator Maker</th>
                            <th>Serial No</th>
                            <th>Invoice No</th>
                            <th>Actions</th> 
                        </tr>
                    </thead>

                    <tbody id="applicator-body">
                        <?php
                            require_once __DIR__ . '/../includes/db.php';
                            require_once __DIR__ . '/../models/read_applicators.php';
                            $applicators = getApplicators($pdo, 10, 0);
                            foreach ($applicators as $row):
                        ?>
                            <tr>
                                <td><?= htmlspecialchars($row['hp_no']) ?></td>
                                <td><?= htmlspecialchars($row['terminal_no']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= htmlspecialchars($row['wire']) ?></td>
                                <td><?= htmlspecialchars($row['terminal_maker']) ?></td>
                                <td><?= htmlspecialchars($row['applicator_maker']) ?></td>
                                <td><?= htmlspecialchars($row['serial_no']) ?></td>
                                <td><?= htmlspecialchars($row['invoice_no']) ?></td>
                                <td>
                                <!-- Edit link with data attributes -->
                                <a href="#" onclick="openApplicatorEditModal(this)" 
                                    data-id="<?= $row['applicator_id'] ?>"
                                    data-control="<?= htmlspecialchars($row['hp_no']) ?>"
                                    data-terminal="<?= htmlspecialchars($row['terminal_no']) ?>"
                                    data-description="<?= htmlspecialchars($row['description']) ?>"
                                    data-wire="<?= htmlspecialchars($row['wire']) ?>"
                                    data-terminal-maker="<?= htmlspecialchars($row['terminal_maker']) ?>"
                                    data-applicator-maker="<?= htmlspecialchars($row['applicator_maker']) ?>"
                                    data-serial="<?= htmlspecialchars($row['serial_no']) ?>"
                                    data-invoice="<?= htmlspecialchars($row['invoice_no']) ?>"
                                >✏️</a>

                                <!-- Delete form -->
                                <form action="/SOMS/app/controllers/delete_applicator.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this applicator?');">
                                    <input type="hidden" name="applicator_id" value="<?= htmlspecialchars($row['applicator_id']) ?>">
                                    <button type="submit">🗑️</button>
                                </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Edit Applicator Modal -->
        <div id="editApplicatorModal" style="display:none; position:fixed; top:10%; left:50%; transform:translateX(-50%);
            background:#fff; padding:20px; border:1px solid #ccc; box-shadow:0 0 10px rgba(0,0,0,0.2); z-index:999;">
            
            <form id="editApplicatorForm" action="../controllers/edit_applicator.php" method="POST">
                <!-- Hidden input to store applicator ID -->
                <input type="hidden" name="applicator_id" id="edit_applicator_id">

                <h2>Edit Applicator</h2>

                <label>Control No:</label>
                <input type="text" name="control_no" id="edit_applicator_control" required><br><br>

                <label>Terminal No:</label>
                <input type="text" name="terminal_no" id="edit_terminal_no" required><br><br>

                <label>Description:</label>
                <select name="description" id="edit_applicator_description" required>
                    <option value="">--Select--</option>
                    <option value="SIDE">SIDE</option>
                    <option value="END">END</option>
                </select><br><br>

                <label>Wire Type:</label>
                <select name="wire_type" id="edit_wire_type" required>
                    <option value="">--Select--</option>
                    <option value="BIG">BIG</option>
                    <option value="SMALL">SMALL</option>
                </select><br><br>

                <label>Terminal Maker:</label>
                <input type="text" name="terminal_maker" id="edit_terminal_maker" required><br><br>

                <label>Applicator Maker:</label>
                <input type="text" name="applicator_maker" id="edit_applicator_maker" required><br><br>

                <label>Serial No:</label>
                <input type="text" name="serial_no" id="edit_applicator_serial_no"><br><br>

                <label>Invoice No:</label>
                <input type="text" name="invoice_no" id="edit_applicator_invoice_no"><br><br>

                <button type="submit">Save</button>
                <button type="button" onclick="closeApplicatorModal()">Cancel</button>
            </form>
        </div>
</body>
</html>