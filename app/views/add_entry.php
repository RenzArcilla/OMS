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
    <script src="../../public/assets/js/load_machines.js" defer></script>
    <!-- Load applicator infinite scroll logic -->
    <script src="../../public/assets/js/load_applicators.js" defer></script>
</head>
    <body>

        <h1>Add Entry</h1>

        <!-- Selection Buttons -->
        <button type="button">Add Machine</button>
        <button type="button">Add Applicator</button>

        <hr>


        <!-- Add Machine Form -->
        <form action="../controllers/add_machine.php" method="POST">
            <h2>Machine Information</h2>
            <label for="machine_ctrl_no">Control No:</label>
            <input type="text" id="machine_ctrl_no" name="control_no" required><br><br>

            <label for="description">Description:</label>
            <select id="description" name="description" required>
                <option value="">--Select--</option>
                <option value="AUTOMATIC">AUTOMATIC</option>
                <option value="SEMI-AUTOMATIC">SEMI-AUTOMATIC</option>
            </select><br><br>

            <label for="model">Model:</label>
            <input type="text" id="model" name="model" required><br><br>

            <label for="machine_maker">Machine Maker:</label>
            <input type="text" id="machine_maker" name="machine_maker" required><br><br>

            <label for="machine_serial_no">Serial No:</label>
            <input type="text" id="machine_serial_no" name="serial_no"><br><br>

            <label for="machine_invoice_no">Invoice No:</label>
            <input type="text" id="machine_invoice_no" name="invoice_no"><br><br>

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
                            <th>ID</th>
                            <th>Control No</th>
                            <th>Description</th>
                            <th>Model</th>
                            <th>Maker</th>
                            <th>Serial No</th>
                            <th>Invoice No</th>
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
                                <td><?= htmlspecialchars($row['machine_id']) ?></td>
                                <td><?= htmlspecialchars($row['control_no']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= htmlspecialchars($row['model']) ?></td>
                                <td><?= htmlspecialchars($row['maker']) ?></td>
                                <td><?= htmlspecialchars($row['serial_no']) ?></td>
                                <td><?= htmlspecialchars($row['invoice_no']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>


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
                            <!-- Table headers defining machine data columns -->
                            <th>HP No</th>
                            <th>Terminal No</th>
                            <th>Description</th>
                            <th>Wire Type</th>
                            <th>Terminal Maker</th>
                            <th>Applicator Maker</th>
                            <th>Serial No</th>
                            <th>Invoice No</th>
                        </tr>
                    </thead>

                    <?php
                        // Include database connection and machine reader logic
                        require_once __DIR__ . '/../includes/db.php';
                        require_once __DIR__ . '/../models/read_applicators.php';

                        // Fetch initial set of machines (first 10 entries)
                        $applicators = getApplicators($pdo, 10, 0);
                        ?>

                    <tbody id="applicator-body">
                        <!-- Render fetched machine data as table rows -->
                        <?php foreach ($applicators as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['hp_no']) ?></td>
                                <td><?= htmlspecialchars($row['terminal_no']) ?></td>
                                <td><?= htmlspecialchars($row['description']) ?></td>
                                <td><?= htmlspecialchars($row['wire']) ?></td>
                                <td><?= htmlspecialchars($row['terminal_maker']) ?></td>
                                <td><?= htmlspecialchars($row['applicator_maker']) ?></td>
                                <td><?= htmlspecialchars($row['serial_no']) ?></td>
                                <td><?= htmlspecialchars($row['invoice_no']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </body>
</html>