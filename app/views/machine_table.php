<?php
// Include machine functions ONLY if they don't exist yet
if (!function_exists('searchMachineByControlNo')) {
    require_once __DIR__ . '/../models/read_joins/read_monitor_machine_and_machine.php';
}

// Get machine data - using a simple approach without custom parts
$machine_total_outputs = getMachineRecordsAndOutputs(10, 0, []);
?>
<div class="data-section">
    <div class="section-header expanded" onclick="toggleSection(this)">
        <div class="section-title">
            MACHINE STATUS
        </div>
        <svg class="expand-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </div>
    <div class="section-content expanded">
        <div class="search-filter">
            <input type="text" class="search-input" placeholder="Search systems..." onkeyup="filterTable(this.value)">
        </div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>CONTROL NO</th>
                    <th>OUTPUT</th>
                    <th>CUT BLADE</th>
                    <th>STRIP BLADE A</th>
                    <th>STRIP BLADE B</th>
                </tr>
            </thead>
            <tbody id="metricsBody">
                <?php foreach ($machine_total_outputs as $row): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['control_no']); ?></td>
                        <td><?php echo htmlspecialchars($row['total_machine_output']); ?></td>
                        <td><?php echo htmlspecialchars($row['cut_blade_output']); ?></td>
                        <td><?php echo htmlspecialchars($row['strip_blade_a_output']); ?></td>
                        <td><?php echo htmlspecialchars($row['strip_blade_b_output']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>