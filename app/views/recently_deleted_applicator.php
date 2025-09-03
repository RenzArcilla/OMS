<div class="data-section" id="disabled-applicators-section">
    <div class="section-header expanded" onclick="toggleSection(this)">
        <div class="section-title">
            <span class="filter-info">
                📤 Recently Deleted Applicators
            </span>
        </div>
        <div class="expand-icon">▼</div>
    </div>
    <div class="search-filter">
        <input type="text" class="search-input" placeholder="Search here..." onkeyup="applyDisabledApplicatorFilters()">
        <select id="applicatorDescription" class="filter-select" onchange="applyDisabledApplicatorFilters()">  
            <option value="ALL">All Types</option>
            <option value="SIDE">SIDE</option>
            <option value="END">END</option>
            <option value="CLAMP">CLAMP</option>
            <option value="STRIP AND CRIMP">STRIP AND CRIMP</option>
        </select>
        <select id="applicatorWireType" class="filter-select" onchange="applyDisabledApplicatorFilters()">  
            <option value="ALL">All Types</option>
            <option value="SMALL">Small</option>
            <option value="BIG">Big</option>
        </select>
    </div>
    <div class="section-content expanded">
        <div class="table-container">
            <table class="data-table" id="metricsTable">
                <thead>
                    <tr>
                        <th>Actions</th>
                        <th>HP Number</th>
                        <th>Description</th>
                        <th>Terminal Maker</th>
                        <th>Applicator Maker</th>
                        <th>Last Updated</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetch disabled applicator data as table rows through AJAX -->
                </tbody>
            </table>
        </div>
    </div>
</div>