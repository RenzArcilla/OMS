    <!-- Login Section -->
    <section class="login-section" id="login">
        <div class="container">
            <div class="login-container">
                <h2 class="login-title">System Access</h2>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                    <div class="form-group">
                        <input type="text" name="username" class="form-input" placeholder="Username" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-input" placeholder="Password" required>
                    </div>
                    <div class="form-group">
                        <select name="role" class="form-input" required>
                            <option value="">Select Role</option>
                            <option value="operator">Viewer Access</option>
                            <option value="admin">Tool Keeper</option>
                        </select>
                    </div>
                    <button type="submit" class="login-btn">Access Dashboard</button>
                </form>
            </div>
        </div>
    </section>
