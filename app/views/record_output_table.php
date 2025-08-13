<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Record Output Table</title>
    <link rel="stylesheet" href="../../public/assets/css/base.css">
    <link rel="stylesheet" href="/css/styles.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button class="back-btn" onclick="history.back()">←</button>
                <div>
                    <h1 class="title">Output Records</h1>
                    <p class="subtitle">Track and monitor production data across all shifts</p>
                </div>
            </div>
            <a href="record_output.php" class="add-entry-btn">
                ➕ Add New Record
            </a>
        </div>
        <!-- Filters -->
        <div class="filters-card">
            <div class="filters-grid">
                <div class="search-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" class="search-input" id="searchInput" placeholder="Search by machine, applicator, or any field...">
                </div>
                
                <select class="filter-select" id="shiftFilter">
                    <option value="">All Shifts</option>
                    <option value="FIRST">First Shift</option>
                    <option value="SECOND">Second Shift</option>
                    <option value="NIGHT">Night Shift</option>
                </select>

                <select class="filter-select" id="dateFilter">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>

                <button class="btn-secondary" onclick="clearFilters()">Clear Filters</button>
            </div>
        </div>
        <!-- Table -->
        <div class="entries-table-card">
            <div class="table-header">
                <div class="table-title">
                    📊  Records
                </div>
            </div>
            
            <table class="entries-table" id="productionTable">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Shift</th>
                        <th>Machine</th>
                        <th>Machine Output</th>
                        <th>Applicator 1</th>
                        <th>App 1 Output</th>
                        <th>Applicator 2</th>
                        <th>App 2 Output</th>
                        <th>Total Output</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    <!-- Sample data will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>