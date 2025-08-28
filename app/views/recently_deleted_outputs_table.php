<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Deleted</title>
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="../../public/assets/css/components/tables.css">
    <link rel="stylesheet" href="../../public/assets/css/components/buttons.css">
</head>
<body>
    <?php 
    // Include the necessary PHP files
    require_once '../models/read_joins/record_and_outputs.php';
    $disabled_records = getDisabledRecordsAndOutputs(20, 0);
    ?>

    <div id="dashboard-tab" class="tab-content">
        <!-- Applicator Status Section -->
        <div class="data-section">
            <div class="section-header expanded" onclick="toggleSection(this)">
                <div class="section-title">
                    Applicator Status
                    <span class="section-badge">24</span>
                </div>

            </div>
            <div class="search-filter">
                <input type="text" class="search-input" placeholder="Search applicator..." onkeyup="filterTable(this.value)">
            </div>
            <div class="section-content expanded">
                <div class="table-container">
                    <table class="data-table" id="metricsTable">
                        <thead>
                            <tr>
                                <th>Actions</th>
                                <th>Record ID</th>
                                <th>Date Inspected</th>
                                <th>Last Encoded</th>
                                <th>Last Updated</th>
                                <th>Shift</th>
                                <th>Applicator1</th>
                                <th>App1 Output</th>
                                <th>Applicator2</th>
                                <th>App2 Output</th>
                                <th>Machine</th>
                                <th>Machine Output</th>
                            </tr>
                        </thead>
                        <tbody id="metricsBody">
                            <!-- Render fetched record data as table rows -->
                            <?php foreach ($disabled_records as $row): ?>
                                <tr>
                                    <td>
                                        
                                        <a href="#" onclick="openRecordEditModalSafe(this); return false;" 
                                            data-id="<?= htmlspecialchars($row['record_id']) ?>"
                                            data-date-inspected="<?= htmlspecialchars($row['date_inspected']) ?>"
                                            data-shift="<?= htmlspecialchars($row['shift']) ?>"
                                            data-hp1-no="<?= htmlspecialchars($row['hp1_no'] ?? '') ?>"
                                            data-app1-output="<?= htmlspecialchars($row['app1_output'] ?? '') ?>"
                                            data-hp2-no="<?= htmlspecialchars($row['hp2_no'] ?? '') ?>"
                                            data-app2-output="<?= htmlspecialchars($row['app2_output'] ?? '') ?>"
                                            data-control-no="<?= htmlspecialchars($row['control_no'] ?? '') ?>"
                                            data-machine-output="<?= htmlspecialchars($row['machine_output'] ?? '') ?>"
                                            title="Edit Record" 
                                        ><span class="edit-btn">✏️</span></a>

                                        <!-- Delete form -->
                                        <form action="/SOMS/app/controllers/disable_record.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this record?');">
                                            <input type="hidden" name="record_id" value="<?= htmlspecialchars($row['record_id']) ?>">
                                            <button type="submit" title="Delete Record" class="delete-btn">🗑️</button>
                                        </form>
                                    </td>
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
                                    
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script src="../../public/assets/js/sidebar.js"></script>
</body>