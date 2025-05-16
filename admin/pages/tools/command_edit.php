<?php
// Include security check and database connection
require_once '../../../admin/secure.php';

// Suppress notices for this include to avoid the session_start notice
$original_error_reporting = error_reporting();
error_reporting(E_ALL & ~E_NOTICE);

// Include header
include '../../../includes/admin_header.php';

// Restore original error reporting level
error_reporting($original_error_reporting);

// Initialize variables
$errors = [];
$success = false;

// Get command name from URL
if (isset($_GET['name']) && !empty($_GET['name'])) {
    $command_name = $_GET['name'];
} else {
    // No command name provided, redirect back to the list
    $_SESSION['message'] = "No command specified for editing.";
    $_SESSION['message_type'] = "danger";
    header("Location: gm_commands.php");
    exit;
}

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    $name = isset($_POST['name']) ? sanitizeInput($_POST['name']) : '';
    $access_level = isset($_POST['access_level']) ? intval($_POST['access_level']) : '';
    $class_name = isset($_POST['class_name']) ? sanitizeInput($_POST['class_name']) : '';
    $description = isset($_POST['description']) ? sanitizeInput($_POST['description']) : '';
    
    // Validation checks
    if (empty($name)) {
        $errors[] = "Command name is required";
    }
    
    if (empty($class_name)) {
        $errors[] = "Class name is required";
    }
    
    // If name has changed, check for duplicates
    if ($name !== $command_name) {
        $check_sql = "SELECT COUNT(*) as count FROM commands WHERE name = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("s", $name);
        $check_stmt->execute();
        $result = $check_stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] > 0) {
            $errors[] = "Command name already exists. Please choose a different name.";
        }
    }
    
    // If no errors, update the command
    if (empty($errors)) {
        $update_sql = "UPDATE commands SET name = ?, access_level = ?, class_name = ?, description = ? WHERE name = ?";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param("sisss", $name, $access_level, $class_name, $description, $command_name);
        
        if ($update_stmt->execute()) {
            $_SESSION['message'] = "Command updated successfully!";
            $_SESSION['message_type'] = "success";
            header("Location: gm_commands.php");
            exit;
        } else {
            $errors[] = "Error updating command: " . $conn->error;
        }
    }
} else {
    // Fetch the current command data
    $select_sql = "SELECT name, access_level, class_name, description FROM commands WHERE name = ?";
    $select_stmt = $conn->prepare($select_sql);
    $select_stmt->bind_param("s", $command_name);
    $select_stmt->execute();
    $result = $select_stmt->get_result();
    
    if ($result->num_rows === 0) {
        $_SESSION['message'] = "Command not found.";
        $_SESSION['message_type'] = "danger";
        header("Location: gm_commands.php");
        exit;
    }
    
    // Load current values
    $command = $result->fetch_assoc();
    $name = $command['name'];
    $access_level = $command['access_level'];
    $class_name = $command['class_name'];
    $description = $command['description'];
}
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Edit Command</h5>
                    </div>
                    
                    <div class="card-body">
                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Command Name</label>
                                <input type="text" class="form-control" id="name" name="name" 
                                       value="<?= htmlspecialchars($name ?? '') ?>" required>
                                <small class="text-muted">This is the command players/admins type in-game</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="access_level" class="form-label">Access Level</label>
                                <select class="form-select" id="access_level" name="access_level" required>
                                    <option value="0" <?= ($access_level ?? '') == 0 ? 'selected' : '' ?>>Admin (0)</option>
                                    <option value="50" <?= ($access_level ?? '') == 50 ? 'selected' : '' ?>>GM (50)</option>
                                    <option value="200" <?= ($access_level ?? '') == 200 ? 'selected' : '' ?>>Mod (200)</option>
                                    <option value="9999" <?= ($access_level ?? '') == 9999 ? 'selected' : '' ?>>Player (9999)</option>
                                </select>
                                <small class="text-muted">Determines who can use this command</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="class_name" class="form-label">Class Name</label>
                                <input type="text" class="form-control" id="class_name" name="class_name" 
                                       value="<?= htmlspecialchars($class_name ?? '') ?>" required>
                                <small class="text-muted">The Java class that implements this command</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($description ?? '') ?></textarea>
                                <small class="text-muted">Brief explanation of what the command does</small>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <a href="gm_commands.php" class="btn btn-outline-light">Cancel</a>
                                <button type="submit" class="btn btn-accent">Update Command</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../../includes/admin_footer.php'; ?>