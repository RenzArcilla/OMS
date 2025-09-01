<div class="data-section">
    <div class="section-header expanded" onclick="toggleSection(this)">
        <div class="section-title">
            <span class="filter-info">
                🔧 Custom Parts
            </span>
        </div>
        <div class="expand-icon">▼</div>
    </div>
    <div class="section-content expanded">
        <div class="search-filter">
            <input type="text" class="search-input" placeholder="Search custom parts...">
        </div>
        <div class="table-container">
            <table class="data-table" id="customPartsTable">
                <thead>
                    <tr>
                        <th>Part Name</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <?php foreach ($custom_applicator_parts as $part): ?>
                    <!-- Wire Crimper -->
                    <tr>
                        <td><?= htmlspecialchars(ucwords(str_replace('_', ' ', $part['part_name']))) ?></td>
                        <td><strong><?= htmlspecialchars(date('Y-m-d', strtotime($part['created_at']))) ?></strong></td>
                        <td>
                            <div class="actions">
                                <?php $partNameTitle = ucwords(str_replace('_', ' ', strtolower($part['part_name']))); ?>
                                <button
                                    class="edit-btn"
                                    type="button"
                                    data-part-id="<?= htmlspecialchars($part['part_id']) ?>"
                                    data-part-name="<?= htmlspecialchars($partNameTitle, ENT_QUOTES) ?>">
                                    Edit
                                </button>
                                <button
                                    class="delete-btn"
                                    type="button"
                                    data-part-id="<?= htmlspecialchars($part['part_id']) ?>"
                                    data-part-type="APPLICATOR">
                                    Delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>