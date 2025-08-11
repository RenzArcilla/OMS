<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machine Outputs</title>
    <?php 
        require_once "../models/read_joins/machines_and_monitor_machines.php"; 
        require_once "../models/read_custom_parts.php"; 
    ?>
</head>
<body>
    <!-- Applicator Table -->
    <?php
        
    ?>
    <div class="container">
        <div id="machine-outputs-table" style="height: 600px; overflow-y: auto;">
            <table class="outputs-table">
                <thead>
                    <tr>        
                        <th>Machine No</th>
                        <th>Machine Outputs</th>
                        <th>Cut Blade </th>
                        <th>Wire Type</th>
                        <th>Strip Blade A</th>
                        <th>Strip Blade B</th>
                            <?php $custom_machine_parts = getCustomParts("MACHINE");
                                if (is_string($custom_machine_parts)) {echo "$custom_machine_parts";}
                                foreach ($custom_machine_parts as $custom_part):?>
                                <th><?= ucwords(strtolower(str_replace('_', ' ', htmlspecialchars($custom_part['part_name'])))); ?></th>
                            <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody id="machine-outputs-body">
                    <?php
                        $machine_outputs = getRecordsAndOutputs(20, 0);
                        foreach ($machine_outputs as $machine_row): ?>
                        <tr>
                            <td><?= htmlspecialchars($machine_row['control_no']) ?></td>
                            <td><?= htmlspecialchars($machine_row['machine_output']) ?></td>
                            <td><?= htmlspecialchars($machine_row['cut_blade_output']) ?></td>
                            <td><?= htmlspecialchars($machine_row['strip_blade_a_output']) ?></td>
                            <td><?= htmlspecialchars($machine_row['strip_blade_b_output']) ?></td>

                            <?php foreach ($custom_machine_parts as $part): ?>
                                <td>
                                    <?= htmlspecialchars($machine_row['custom_parts_output'][$part['part_name']] ?? '') ?>
                                </td>
                            <?php endforeach; ?>

                            <td>
                                <div class="actions">
                                    <button class="action-btn edit-btn" type="button">✏️</button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <div>
    </div>
</body>
<footer>
</footer>   