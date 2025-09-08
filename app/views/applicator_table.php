<?php 
// Include the read join function and the alert function
require_once __DIR__ . '/../models/read_joins/read_monitor_applicator_and_applicator.php';
require_once __DIR__ . '/../includes/js_alert.php';

// Get applicator data - using a simple approach without custom parts
$applicator_total_outputs = getApplicatorRecordsAndOutputs(10, 0, []);
?>


<!-- Applicator Status Section -->
<div class="data-section">
    <div class="section-header expanded" onclick="toggleSection(this)">
        <div class="section-title">
            APPLICATOR STATUS
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
                    <th>HP UNIT</th>
                    <th>OUTPUT</th>
                    <th>WIRE CRIMPER</th>
                    <th>WIRE ANVIL</th>
                    <th>INSULATION CRIMPER</th>
                    <th>INSULATION ANVIL</th>
                </tr>
            </thead>
            <tbody id="metricsBody">
                <?php foreach ($applicator_total_outputs as $row): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['hp_no']) ?></td>
                        <td><strong><?= htmlspecialchars($row['total_output']) ?></strong></td>
                        <td>
                            <div><strong><?= htmlspecialchars($row['wire_crimper_output']) ?></strong> / 1.5M</div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 42%;"></div>
                            </div>
                        </td>
                        <td>
                            <div><strong><?= htmlspecialchars($row['wire_anvil_output']) ?></strong> / 1.5M</div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 38%;"></div>
                            </div>
                        </td>
                        <td>
                            <div><strong><?= htmlspecialchars($row['insulation_crimper_output']) ?></strong> / 1.5M</div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 51%;"></div>
                            </div>
                        </td>
                        <td>
                            <div><strong><?= htmlspecialchars($row['insulation_anvil_output']) ?></strong> / 1.5M</div>
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: 46%;"></div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>