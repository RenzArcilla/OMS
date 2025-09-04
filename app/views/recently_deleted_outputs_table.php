<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Deleted</title>
    <link rel="icon" href="/SOMS/public/assets/images/favicon.ico">
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="../../public/assets/css/components/tables.css">
    <link rel="stylesheet" href="../../public/assets/css/components/buttons.css">
    <link rel="stylesheet" href="/SOMS/public/assets/css/layout/grid.css">
    <link rel="stylesheet" href="../../public/assets/css/components/pagination.css">
    
</head>
<body>
    <div id="dashboard-tab" class="tab-content">
        <!-- Disabled Records Section -->
        <div class="data-section">
            <div class="section-header expanded" onclick="toggleSection(this)">
                <div class="section-title">
                    <span class="filter-info">Recently Deleted Outputs</span>
                </div>
            </div>

            <!-- Filters -->
            <div class="search-filter">
                <input type="text" class="search-input" placeholder="Search here..." onkeyup="applyDisabledRecordFilters(this.value)">
                <select id="recordShiftDisabled" class="filter-select" onchange="applyDisabledRecordFilters()">  
                    <option value="ALL">All</option>
                    <option value="1st">1st</option>
                    <option value="2nd">2nd</option>    
                    <option value="NIGHT">Night</option>
                </select>
            </div>
            
            <div class="section-content expanded">
                <div class="table-container">
                    <table class="data-table" id="metricsTable">
                        <thead> 
                            <tr>
                                <th>Actions</th>
                                <th>Record ID</th>
                                <th>Date Inspected</th>
                                <th>Date Encoded</th>
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
                        <tbody id="deletedRecordsMetricsBody">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Pagination Controls -->
        <div class="pagination-container" id="disabled-records-pagination" style="display: none;">
            <div class="pagination-info">
                <span class="pagination-text" id="pagination-info-text-records">
                    Showing 0 to 0 of 0 results
                </span>
            </div>
            
            <div class="pagination-controls">
                <!-- Previous Button -->
                <a href="#" id="pagination-prev-records" class="pagination-btn pagination-prev">
                    <span>←</span> Previous
                </a>
                
                <!-- Page Numbers -->
                <div class="pagination-numbers" id="pagination-numbers-records">
                    <!-- Page numbers will be dynamically generated -->
                </div>
                
                <!-- Next Button -->
                <a href="#" id="pagination-next-records" class="pagination-btn pagination-next">
                    Next <span>→</span>
                </a>
            </div>
            
            <!-- Items Per Page Selector -->
            <div class="pagination-items-per-page">
                <label for="items-per-page-records">Show:</label>
                <select id="items-per-page-records" onchange="changeItemsPerPageRecords(this.value)">
                    <option value="5">5</option>
                    <option value="10" selected>10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                </select>
                <span>per page</span>
            </div>
        </div>
    </div>
    <script src="../../public/assets/js/recently_deleted_outputs_table.js"></script>
    <!-- Disabled Records Pagination -->
    <script src="../../public/assets/js/disabled_records_pagination.js"></script>
</body>