<link rel="stylesheet" href="../../public/assets/css/components/pagination.css">

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
                    <!-- Fetch disabled machine data as table rows through AJAX -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Controls -->
        <div id="disabled-machines-pagination" class="pagination-container" style="display: none;">
            <div class="pagination-info">
                <span id="pagination-info-text-machines">Showing 1 to 10 of 0 machines</span>
            </div>
            <div class="pagination-controls">
                <a href="#" id="pagination-prev-machines" class="pagination-btn pagination-prev" onclick="goToPageMachines(currentPageMachines - 1)">
                    Previous
                </a>
                <div id="pagination-numbers-machines" class="pagination-numbers">
                    <!-- Page numbers will be dynamically generated -->
                </div>
                <a href="#" id="pagination-next-machines" class="pagination-btn pagination-next" onclick="goToPageMachines(currentPageMachines + 1)">
                    Next
                </a>
            </div>
            <div class="pagination-items-per-page">
                <label for="items-per-page-machines">Items per page:</label>
                <select id="items-per-page-machines" onchange="changeItemsPerPageMachines(this.value)">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
            </div>
        </div>
    </div>
</div>