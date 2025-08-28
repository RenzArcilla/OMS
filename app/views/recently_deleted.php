<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recently Deleted</title>
    <link rel="stylesheet" href="../../public/assets/css/base/base.css">
    <link rel="stylesheet" href="../../public/assets/css/components/sidebar.css">
    <link rel="stylesheet" href="../../public/assets/css/components/tables.css">
    <link rel="stylesheet" href="../../public/assets/css/components/buttons.css">
</head>
<body>
    <div id="dashboard-tab" class="tab-content">
        <!-- Applicator Status Section -->
        <div class="data-section">
            <div class="section-header expanded" onclick="toggleSection(this)">
                <div class="section-title">
                    Applicator Status
                    <span class="section-badge">24</span>
                </div>

            </div>
            <div class="search-filter">
                <input type="text" class="search-input" placeholder="Search applicator..." onkeyup="filterTable(this.value)">
            </div>
            <div class="section-content expanded">
                <div class="table-container">
                    <table class="data-table" id="metricsTable">
                        <thead>
                            <tr>
                                <th>HP Number</th>
                                <th>Status</th>
                                <th>Wire Type</th>
                                <th>Last Encoded</th>
                                <th>Total Output</th>
                                <th>Wire Crimper</th>
                                <th>Wire Anvil</th>
                                <th>Insulation Crimper</th>
                                <th>Insulation Anvil</th>
                                <th>Slide Cutter</th>
                                <th>Cutter Holder</th>
                                <th>Shear Blade</th>
                                <th>Cutter A</th>
                                <th>Cutter B</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="metricsBody">
                            <tr>
                                <td><strong>HP-001</strong></td>
                                <td><span class="status-badge status-good">Good</span></td>
                                <td>BIG</td>
                                <td>07/21/2025</td>
                                <td><strong>847</strong></td>
                                <td>
                                    <div><strong>630K</strong> / 1.5M</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 42%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div><strong>570K</strong> / 1.5M</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 38%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div><strong>765K</strong> / 1.5M</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 51%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div><strong>690K</strong> / 1.5M</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 46%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div><strong>825K</strong> / 1.5M</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 55%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div><strong>450K</strong> / 1.5M</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 30%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div><strong>390K</strong> / 1.5M</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 26%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div>390K / 1.5M</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 26%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <div><strong>320K</strong> / 1.5M</div>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: 21%;"></div>
                                    </div>
                                </td>
                                <td>
                                    <button class="btn-small btn-undo" onclick="openUndoModal()">Undo</button>
                                    <button class="btn-small btn-reset" onclick="openResetModal()">Reset</button>
                                </td>
                            </tr>
                            
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>