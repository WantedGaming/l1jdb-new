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

// Get command name from URL
if (isset($_GET['name']) && !empty($_GET['name'])) {
    $command_name = $_GET['name'];
} else {
    // No command name provided, redirect back to the list
    $_SESSION['message'] = "No command specified for deletion.";
    $_SESSION['message_type'] = "danger";
    header("Location: gm_commands.php");
    exit;
}

// Check if command exists
$check_sql = "SELECT COUNT(*) as count FROM commands WHERE name = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $command_name);
$check_stmt->execute();
$result = $check_stmt->get_result();
$row = $result->fetch_assoc();

if ($row['count'] === 0) {
    $_SESSION['message'] = "Command not found.";
    $_SESSION['message_type'] = "danger";
    header("Location: gm_commands.php");
    exit;
}

// Process deletion
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['confirm']) && $_GET['confirm'] === 'yes') {
    // User has confirmed deletion
    $delete_sql = "DELETE FROM commands WHERE name = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("s", $command_name);
    
    if ($delete_stmt->execute()) {
        $_SESSION['message'] = "Command deleted successfully!";
        $_SESSION['message_type'] = "success";
        header("Location: gm_commands.php");
        exit;
    } else {
        $errors[] = "Error deleting command: " . $conn->error;
    }
}

// Fetch the command data for display
$select_sql = "SELECT name, access_level, class_name, description FROM commands WHERE name = ?";
$select_stmt = $conn->prepare($select_sql);
$select_stmt->bind_param("s", $command_name);
$select_stmt->execute();
$result = $select_stmt->get_result();
$command = $result->fetch_assoc();
?>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-header bg-danger text-white">
                        <h5 class="mb-0">Delete Command</h5>
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
                        
                        <div class="alert alert-warning">
                            <h5 class="alert-heading">Warning!</h5>
                            <p>You are about to delete the following command:</p>
                            <hr>
                            <p><strong>Command Name:</strong> <?= htmlspecialchars($command['name']) ?></p>
                            <p><strong>Access Level:</strong> <?= $command['access_level'] ?></p>
                            <p><strong>Class:</strong> <?= htmlspecialchars($command['class_name']) ?></p>
                            <p><strong>Description:</strong> <?= htmlspecialchars($command['description'] ?: 'No description') ?></p>
                            <hr>
                            <p class="mb-0">This action cannot be undone. Are you sure you want to proceed?</p>
                        </div>
                        
                        <div class="d-flex justify-content-between mt-4">
                            <a href="gm_commands.php" class="btn btn-outline-light">Cancel</a>
                            <a href="?name=<?= urlencode($command_name) ?>&confirm=yes" class="btn btn-danger">Yes, Delete Command</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../../includes/admin_footer.php'; ?>