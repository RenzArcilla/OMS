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
</head>
<body>
    <!-- Form for recording outputs -->
    <div>
        <form action="../controllers/record_output.php" method="POST">
        <h2>RECORD AN OUTPUT (MANUALLY)</h2>

        <label for="date_inspected">Date Inspected:</label>
        <input type="date" id="date_inspected" name="date_inspected" value="<?= date('Y-m-d') ?>" required><br><br>

        <label for="shift">Shift</label>
        <select id="shift" name="shift" required>
            <option value="">--Select--</option>
            <option value="FIRST">FIRST</option>
            <option value="SECOND">SECOND</option>
            <option value="NIGHT">NIGHT</option>
        </select><br><br>

        <label for="app1">Applicator 1:</label>
        <input type="text" id="app1" name="app1" required><br><br>

        <label for="app2">Applicator 2:</label>
        <input type="text" id="app2" name="app2"><br><br>

        <label for="app1_output">App. 1 Output:</label>
        <input type="text" id="app1_output" name="app1_output" required><br><br>

        <label for="app2_output">App. 2 Output:</label>
        <input type="text" id="app2_output" name="app2_output"><br><br>

        <label for="machine">Machine Number:</label>
        <input type="text" id="machine" name="machine" required><br><br>

        <label for="machine_output">Machine Output:</label>
        <input type="text" id="machine_output" name="machine_output" required><br><br>

        <button type="submit">Submit Machine</button>
    </form>
    </div>
</body>
</html>