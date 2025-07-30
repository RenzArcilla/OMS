<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HEPC - Admin Manage User</title>
    <link rel="stylesheet" href="../../public/assets/css/admin_manage_user.css">
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <button class="back-btn" onclick="window.history.back()">
                    ←
                </button>
                <div>
                    <h1 class="title">Manage Users</h1>
                    <p class="subtitle">Manage user accounts, permissions, and access controls</p>
                </div>
            </div>
            <button class="add-user-btn" onclick="openModal('create')">
                + Add New User
            </button>
        </div>

        <!-- Filters -->
        <div class="filters-card">
            <div class="filters-grid">
                <div class="search-wrapper">
                    <span class="search-icon">🔍</span>
                    <input type="text" id="searchInput" placeholder="Search users..." class="search-input">
                </div>
                
                <select id="roleFilter" class="filter-select">
                    <option value="all">All Roles</option>
                    <option value="Admin">Admin</option>
                    <option value="Moderator">Moderator</option>
                    <option value="User">User</option>
                </select>
                
                <select id="statusFilter" class="filter-select">
                    <option value="all">All Status</option>
                    <option value="Active">Active</option>
                    <option value="Inactive">Inactive</option>
                    <option value="Pending">Pending</option>
                    <option value="Suspended">Suspended</option>
                </select>
                
                <button type="button" class="btn-secondary" onclick="exportUsers()">📥 Export</button>
                <button type="button" class="btn-secondary" onclick="refreshData()">🔄 Refresh</button>
            </div>
        </div>

        <!-- Users Table -->
        <div class="users-table-card">
            <table class="users-table">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Last Login</th>
                        <th>Files</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="usersTableBody">
                    <!-- Users will be populated by JavaScript -->
                </tbody>
            </table>

            <!-- Pagination -->
            <div class="pagination">
                <div class="pagination-info" id="paginationInfo">
                    Showing 0 to 0 of 0 users
                </div>
                <div class="pagination-controls">
                    <button id="prevBtn" class="pagination-btn" onclick="previousPage()" disabled>Previous</button>
                    <button id="nextBtn" class="pagination-btn" onclick="nextPage()" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- User Modal -->
    <div id="userModal" class="modal-overlay">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modalTitle" class="modal-title">
                    <span id="modalAvatar" class="modal-avatar">👤</span>
                    <span id="modalTitleText">User Details</span>
                </h2>
                <button class="close-btn" onclick="closeModal()">✕</button>
            </div>
            
            <div class="modal-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label class="form-label">Full Name *</label>
                        <input type="text" id="userName" class="form-input" placeholder="Enter full name">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Email Address *</label>
                        <input type="email" id="userEmail" class="form-input" placeholder="Enter email address">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Phone Number *</label>
                        <input type="tel" id="userPhone" class="form-input" placeholder="Enter phone number">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Department *</label>
                        <input type="text" id="userDepartment" class="form-input" placeholder="Enter department">
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <select id="userRole" class="form-select">
                            <option value="User">User</option>
                            <option value="Moderator">Moderator</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Status</label>
                        <select id="userStatus" class="form-select">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Pending">Pending</option>
                            <option value="Suspended">Suspended</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Join Date</label>
                        <div class="form-field">
                            <span class="field-icon">📅</span>
                            <span id="userJoinDate">-</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label class="form-label">Last Login</label>
                        <div class="form-field">
                            <span class="field-icon">🕒</span>
                            <span id="userLastLogin">-</span>
                        </div>
                    </div>
                </div>
                
                <div class="activity-section">
                    <h3 class="activity-title">Activity Summary</h3>
                    <div class="activity-stats">
                        <div class="activity-stat">
                            <div id="userFilesUploaded" class="stat-number">0</div>
                            <div class="stat-label">Files Uploaded</div>
                        </div>
                        <div class="activity-stat">
                            <div id="userDaysActive" class="stat-number">0</div>
                            <div class="stat-label">Days Active</div>
                        </div>
                        <div class="activity-stat">
                            <div class="stat-number">5</div>
                            <div class="stat-label">Login Sessions</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer">
                <button class="btn-secondary" onclick="closeModal()">Close</button>
                <button id="modalActionBtn" class="btn-primary" style="display: none;" onclick="saveUser()">Save Changes</button>
            </div>
        </div>
    </div>
    <script src="../../public/assets/js/admin_manage_user.js"></script>
</body>
</html>