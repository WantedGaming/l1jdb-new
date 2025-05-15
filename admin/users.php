<?php include '../includes/admin_header.php'; ?>

<?php
// Process user management actions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check what action is being performed
    if (isset($_POST['action'])) {
        $login = sanitizeInput($_POST['login'] ?? '');
        
        if ($_POST['action'] == 'promote' && !empty($login)) {
            // Promote user to admin
            $stmt = $conn->prepare("UPDATE accounts SET access_level = 1 WHERE login = ?");
            $stmt->bind_param("s", $login);
            $stmt->execute();
            
            if ($stmt->affected_rows > 0) {
                $message = "User '$login' has been promoted to admin.";
                $message_type = "success";
                
                // Log admin activity
                if (function_exists('logAdminActivity')) {
                    logAdminActivity('promote', 'accounts', "Promoted user: $login to admin");
                }
            } else {
                $message = "Failed to promote user '$login'.";
                $message_type = "danger";
            }
            
            $stmt->close();
        } else if ($_POST['action'] == 'demote' && !empty($login)) {
            // Make sure we're not demoting ourselves
            if ($login == $_SESSION['admin_username']) {
                $message = "You cannot demote yourself from admin status.";
                $message_type = "danger";
            } else {
                // Demote user from admin
                $stmt = $conn->prepare("UPDATE accounts SET access_level = 0 WHERE login = ?");
                $stmt->bind_param("s", $login);
                $stmt->execute();
                
                if ($stmt->affected_rows > 0) {
                    $message = "User '$login' has been demoted from admin.";
                    $message_type = "success";
                    
                    // Log admin activity
                    if (function_exists('logAdminActivity')) {
                        logAdminActivity('demote', 'accounts', "Demoted user: $login from admin");
                    }
                } else {
                    $message = "Failed to demote user '$login'.";
                    $message_type = "danger";
                }
                
                $stmt->close();
            }
        }
    }
}

// Get admin users
$adminUsers = [];
$admin_result = $conn->query("SELECT login, lastactive FROM accounts WHERE access_level = 1 ORDER BY login");
if ($admin_result) {
    while ($row = $admin_result->fetch_assoc()) {
        $adminUsers[] = $row;
    }
}

// Get regular users (for potential promotion)
$regularUsers = [];
$user_result = $conn->query("SELECT login, lastactive FROM accounts WHERE access_level = 0 ORDER BY login LIMIT 50");
if ($user_result) {
    while ($row = $user_result->fetch_assoc()) {
        $regularUsers[] = $row;
    }
}
?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Admin <span>User Management</span></h1>
                <p class="hero-subtitle">Manage administrator accounts and permissions</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php if (isset($message)): ?>
        <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
            <?php echo $message; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <div class="row">
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Current Administrators</h5>
                        <span class="badge bg-accent"><?php echo count($adminUsers); ?></span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Last Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($adminUsers) > 0): ?>
                                        <?php foreach ($adminUsers as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['login']); ?></td>
                                            <td><?php echo $user['lastactive'] ? date('Y-m-d H:i', strtotime($user['lastactive'])) : 'Never'; ?></td>
                                            <td>
                                                <?php if ($user['login'] != $_SESSION['admin_username']): ?>
                                                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to demote this admin?');">
                                                    <input type="hidden" name="login" value="<?php echo htmlspecialchars($user['login']); ?>">
                                                    <input type="hidden" name="action" value="demote">
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Demote</button>
                                                </form>
                                                <?php else: ?>
                                                <span class="badge bg-secondary">Current User</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No administrators found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Regular Users</h5>
                        <div>
                            <form class="d-flex" action="" method="GET">
                                <input type="text" class="form-control form-control-sm me-2" name="search" placeholder="Search users..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                                <button type="submit" class="btn btn-sm btn-outline-light">Search</button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Last Active</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($regularUsers) > 0): ?>
                                        <?php foreach ($regularUsers as $user): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($user['login']); ?></td>
                                            <td><?php echo $user['lastactive'] ? date('Y-m-d H:i', strtotime($user['lastactive'])) : 'Never'; ?></td>
                                            <td>
                                                <form method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to promote this user to admin?');">
                                                    <input type="hidden" name="login" value="<?php echo htmlspecialchars($user['login']); ?>">
                                                    <input type="hidden" name="action" value="promote">
                                                    <button type="submit" class="btn btn-sm btn-outline-accent">Promote to Admin</button>
                                                </form>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="3" class="text-center">No regular users found</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="mt-3 text-center">
                            <small class="text-muted">Showing up to 50 regular users. Use search to find specific users.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Create New User</h5>
            </div>
            <div class="card-body">
                <form method="post" action="">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="new_username" class="form-label">Username</label>
                            <input type="text" class="form-control" id="new_username" name="new_username" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="new_password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="new_access_level" class="form-label">Access Level</label>
                            <select class="form-control" id="new_access_level" name="new_access_level">
                                <option value="0">Regular User</option>
                                <option value="1">Administrator</option>
                            </select>
                        </div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-accent" name="action" value="create_user">Create User</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/admin_footer.php'; ?>