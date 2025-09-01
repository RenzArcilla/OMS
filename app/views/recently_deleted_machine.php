<div class="data-section" id="disabled-machines-section">
    <div class="section-header">
        <div class="section-title">
            🗑️ Recently Deleted Machine
            <span class="section-badge">3</span>
        </div>
        <div class="expand-icon">▼</div>
    </div> 
    <div class="section-content expanded">
        <!-- Filters -->
        <div class="search-filter">
            <div class="search-filter">
                <input type="text" class="search-input" placeholder="Search here..." onkeyup="applyDisabledMachineFilters()">
            </div>
            <select id="disabledMachineDescription" class="filter-select" onchange="applyDisabledMachineFilters()">  
                <option value="ALL">All</option>
                <option value="AUTOMATIC">Automatic</option>
                <option value="SEMI-AUTOMATIC">Semi-Automatic</option>
            </select>
        </div>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Actions</th>
                        <th>Control Number</th>
                        <th>Model</th>
                        <th>Maker</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($disabled_machines as $machine): ?>
                    <tr>
                        <td>
                            <button id="restore-machine-<?= htmlspecialchars($machine['machine_id']) ?>"
                                    class="restore-btn restore-machine-btn"
                                    data-machine-id="<?= htmlspecialchars($machine['machine_id']) ?>">
                                Restore
                            </button>
                        </td>
                        <td><?= htmlspecialchars($machine['control_no']) ?></td>
                        <td><?= htmlspecialchars($machine['model']) ?></td>
                        <td><?= htmlspecialchars($machine['maker']) ?></td>
                        <td><?= htmlspecialchars($machine['last_encoded']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>