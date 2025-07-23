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

        <!-- Table for displaying machines -->
        <div>
            <h3>Latest Machines</h3>

            <div id="machine-container" style="height: 300px; overflow-y: auto;">
                <table border="1">
                    <thead>
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
                    <tbody id="machine-body">
                        <?php
                        require_once __DIR__ . '/../includes/db.php';

                        try {
                            $stmt = $pdo->query("SELECT machine_id, control_no, description, model, maker, serial_no, invoice_no 
                                                FROM machines 
                                                ORDER BY machine_id DESC
                                                LIMIT 10");

                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                echo "<tr>";
                                echo "<td>" . htmlspecialchars($row['machine_id']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['control_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['model']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['maker']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['serial_no']) . "</td>";
                                echo "<td>" . htmlspecialchars($row['invoice_no']) . "</td>";
                                echo "</tr>";
                            }
                        } catch (PDOException $e) {
                            echo "<tr><td colspan='7'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- JS for AJAX infinite scroll in machine table -->
        <script>
            let offset = 0;
            const limit = 10;
            let loading = false;

            function loadMachines() {
                if (loading) return;
                loading = true;

                fetch(`../ajax/get_machines.php?offset=${offset}&limit=${limit}`)
                    .then(response => response.json())
                    .then(data => {
                        const tbody = document.getElementById('machine-body');

                        data.forEach(row => {
                            const tr = document.createElement('tr');

                            tr.innerHTML = `
                                <td>${row.machine_id}</td>
                                <td>${row.control_no}</td>
                                <td>${row.description}</td>
                                <td>${row.model}</td>
                                <td>${row.maker}</td>
                                <td>${row.serial_no}</td>
                                <td>${row.invoice_no}</td>
                            `;

                            tbody.appendChild(tr);
                        });

                        // Update offset and allow more loading
                        offset += data.length;
                        loading = false;

                        // Optional: Stop scrolling if no more data
                        if (data.length < limit) {
                            document.getElementById('machine-container').removeEventListener('scroll', scrollHandler);
                        }
                    })
                    .catch(error => {
                        console.error("Error loading machines:", error);
                        loading = false;
                    });
            }

            // Separate handler function for easy removal later
            function scrollHandler() {
                const container = document.getElementById('machine-container');
                if (container.scrollTop + container.clientHeight >= container.scrollHeight - 5) {
                    loadMachines();
                }
            }

            // Attach scroll listener and load initial data
            document.getElementById('machine-container').addEventListener('scroll', scrollHandler);
            loadMachines();
        </script>







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
    </body>
</html>