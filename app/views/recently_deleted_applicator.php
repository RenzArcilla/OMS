<link rel="stylesheet" href="../../public/assets/css/components/pagination.css">

<div class="data-section" id="disabled-applicators-section">
    <div class="section-header expanded" onclick="toggleSection(this)">
        <div class="section-title">
            <span class="filter-info">
                Restore Deleted Applicators
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

<!-- Pagination Controls -->
<div class="pagination-container" id="disabled-applicators-pagination" style="display: none;">
    <div class="pagination-info">
        <span class="pagination-text" id="pagination-info-text">
            Showing 0 to 0 of 0 results
        </span>
    </div>
    
    <div class="pagination-controls">
        <!-- Previous Button -->
        <a href="#" id="pagination-prev" class="pagination-btn pagination-prev">
            <span>←</span> Previous
        </a>
        
        <!-- Page Numbers -->
        <div class="pagination-numbers" id="pagination-numbers">
            <!-- Page numbers will be dynamically generated -->
        </div>
        
        <!-- Next Button -->
        <a href="#" id="pagination-next" class="pagination-btn pagination-next">
            Next <span>→</span>
        </a>
    </div>
    
    <!-- Items Per Page Selector -->
    <div class="pagination-items-per-page">
        <label for="items-per-page-disabled">Show:</label>
        <select id="items-per-page-disabled" onchange="changeItemsPerPageDisabled(this.value)">
            <option value="5">5</option>
            <option value="10" selected>10</option>
            <option value="20">20</option>
            <option value="50">50</option>
        </select>
        <span>per page</span>
    </div>
</div>