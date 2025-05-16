<?php
// Include security check and database connection
require_once '../../../admin/secure.php';
include '../../../includes/admin_header.php';

// Initialize variables
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$access_level = isset($_GET['access_level']) ? intval($_GET['access_level']) : '';

// Build base queries - no pagination needed
$count_sql = "SELECT COUNT(*) as total FROM commands WHERE 1=1";
$data_sql = "SELECT name, access_level, class_name, description FROM commands WHERE 1=1";

// Add search conditions
if (!empty($search)) {
    $count_sql .= " AND (name LIKE '%".$conn->real_escape_string($search)."%' OR description LIKE '%".$conn->real_escape_string($search)."%')";
    $data_sql .= " AND (name LIKE '%".$conn->real_escape_string($search)."%' OR description LIKE '%".$conn->real_escape_string($search)."%')";
}

// Add access level filter
if (!empty($access_level)) {
    $count_sql .= " AND access_level = ".$conn->real_escape_string($access_level);
    $data_sql .= " AND access_level = ".$conn->real_escape_string($access_level);
}

// Get total count
$count_result = $conn->query($count_sql);
$total_items = $count_result->fetch_assoc()['total'];

// Get all data - no LIMIT or OFFSET
$data_sql .= " ORDER BY name ASC";
$result = $conn->query($data_sql);
?>

<section class="hero-section">
    <div class="hero-background">
        <div class="hero-bg-img" style="background: url('../../../assets/img/placeholders/commands-bg.jpg') no-repeat center center;"></div>
    </div>
    <div class="container position-relative">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">GM <span>Commands</span> Guide</h1>
                <p class="hero-subtitle">Complete reference for all administrator commands</p>
                
                <form class="search-form" method="GET" action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control search-input" name="search" 
                               placeholder="Search commands..." 
                               value="<?= htmlspecialchars($search) ?>">
                        <button class="btn btn-accent" type="submit">Search</button>
                    </div>
                    
                    <div class="filter-options">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <select class="form-control filter-select" name="access_level" onchange="this.form.submit()">
                                    <option value="">All Access Levels</option>
                                    <option value="0" <?= $access_level === 0 ? 'selected' : '' ?>>Admin (0)</option>
                                    <option value="50" <?= $access_level === 50 ? 'selected' : '' ?>>GM (50)</option>
                                    <option value="200" <?= $access_level === 200 ? 'selected' : '' ?>>Mod (200)</option>
                                    <option value="9999" <?= $access_level === 9999 ? 'selected' : '' ?>>Player (9999)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Available Commands (<?= $total_items ?>)</h5>
                <div>
                    <a href="command_add.php" class="btn btn-sm btn-accent">Add New Command</a>
                </div>
            </div>
            
            <div class="card-body" style="max-height: none; overflow: visible;">
                <?php if (isset($_SESSION['message'])): ?>
                <div class="alert alert-<?= $_SESSION['message_type'] ?> alert-dismissible fade show">
                    <?= $_SESSION['message'] ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                <?php 
                unset($_SESSION['message']);
                unset($_SESSION['message_type']);
                endif; ?>
                
                <?php if ($result && $result->num_rows > 0): ?>
                <!-- Add custom styles to ensure full table visibility -->
                <style>
                    .full-table-container {
                        overflow: visible;
                        max-height: none;
                    }
                    .full-table-container .table-responsive {
                        overflow: visible;
                        max-height: none;
                    }
                    @media (max-width: 768px) {
                        .full-table-container .table-responsive {
                            overflow-x: auto;
                        }
                    }
                </style>
                
                <div class="full-table-container">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Command</th>
                                    <th class="text-center">Access</th>
                                    <th>Class</th>
                                    <th>Description</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><span class="badge bg-accent"><?= htmlspecialchars($row['name']) ?></span></td>
                                    <td class="text-center">
                                        <span class="badge <?= 
                                            $row['access_level'] == 0 ? 'bg-danger' : 
                                            ($row['access_level'] <= 50 ? 'bg-warning' : 
                                            ($row['access_level'] <= 200 ? 'bg-primary' : 'bg-secondary')) 
                                        ?>">
                                            <?= $row['access_level'] ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($row['class_name']) ?></td>
                                    <td><?= htmlspecialchars($row['description']) ?: '<span class="text-muted">No description</span>' ?></td>
                                    <td class="text-center">
                                        <a href="command_edit.php?name=<?= urlencode($row['name']) ?>" class="btn btn-sm btn-outline-light">Edit</a>
                                        <a href="command_delete.php?name=<?= urlencode($row['name']) ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Delete this command permanently?')">Delete</a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <p class="text-center text-muted mt-4">
                    Showing all <?= $total_items ?> commands
                </p>
                
                <?php else: ?>
                <div class="alert alert-info">No commands found matching your criteria.</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../../../includes/admin_footer.php'; ?>