<?php
// Start session first, before anything else
session_start();

// Include security and database connection files
require_once '../../../includes/config.php';
require_once '../../../includes/functions.php';
require_once '../../../includes/admin_security.php';

// Verify authentication
requireAdminAuth();

// Define function for weapon deletion with droplist cleanup
function deleteWeaponAndDependencies($conn, $weapon_id, $remove_from_droplist = false) {
    // Start transaction
    $conn->begin_transaction();
    $success = true;
    $message_details = [];
    
    try {
        // If removing from droplist is chosen
        if ($remove_from_droplist) {
            // Count first for the message
            $count_sql = "SELECT COUNT(*) as count FROM droplist WHERE itemId = ?";
            $count_stmt = $conn->prepare($count_sql);
            $count_stmt->bind_param("i", $weapon_id);
            $count_stmt->execute();
            $count_result = $count_stmt->get_result();
            $count_row = $count_result->fetch_assoc();
            $droplist_count = $count_row['count'];
            $count_stmt->close();
            
            // Now delete
            if ($droplist_count > 0) {
                $delete_droplist_sql = "DELETE FROM droplist WHERE itemId = ?";
                $delete_droplist_stmt = $conn->prepare($delete_droplist_sql);
                $delete_droplist_stmt->bind_param("i", $weapon_id);
                $delete_droplist_stmt->execute();
                
                if ($delete_droplist_stmt->affected_rows > 0) {
                    $message_details[] = "Removed from $droplist_count droplist entries";
                }
                $delete_droplist_stmt->close();
            }
        }
        
        // Delete the weapon
        $delete_weapon_sql = "DELETE FROM weapon WHERE item_id = ?";
        $delete_weapon_stmt = $conn->prepare($delete_weapon_sql);
        $delete_weapon_stmt->bind_param("i", $weapon_id);
        $delete_weapon_stmt->execute();
        
        if ($delete_weapon_stmt->affected_rows > 0) {
            $message_details[] = "Weapon deleted successfully";
        } else {
            // If weapon wasn't deleted, rollback
            throw new Exception("Failed to delete weapon");
        }
        
        $delete_weapon_stmt->close();
        
        // If we got this far, commit the transaction
        $conn->commit();
        return [true, $message_details];
        
    } catch (Exception $e) {
        // Something went wrong, rollback
        $conn->rollback();
        return [false, [$e->getMessage()]];
    }
}

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "No weapon ID specified";
    $_SESSION['message_type'] = "danger";
    header("Location: weapon_list.php");
    exit;
}

$weapon_id = intval($_GET['id']);

// Fetch weapon details for confirmation
$sql = "SELECT item_id, desc_en, type, iconId, itemGrade FROM weapon WHERE item_id = $weapon_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $_SESSION['message'] = "Weapon not found";
    $_SESSION['message_type'] = "danger";
    header("Location: weapon_list.php");
    exit;
}

$weapon = $result->fetch_assoc();
$cleanWeaponName = cleanItemName($weapon['desc_en']);

// Check if the weapon is in droplist
$check_droplist_sql = "SELECT COUNT(*) as drop_count FROM droplist WHERE itemId = $weapon_id";
$droplist_result = $conn->query($check_droplist_sql);
$is_in_droplist = false;
$drop_count = 0;

if ($droplist_result && $droplist_result->num_rows > 0) {
    $droplist_row = $droplist_result->fetch_assoc();
    $drop_count = $droplist_row['drop_count'];
    $is_in_droplist = ($drop_count > 0);
}

// Check if weapon is associated with weapon_skill
$is_in_weapon_skill = false;
$weapon_skill_check_sql = "SELECT COUNT(*) as count FROM weapon_skill WHERE weapon_id = $weapon_id";
$weapon_skill_result = $conn->query($weapon_skill_check_sql);
if ($weapon_skill_result) {
    $weapon_skill_row = $weapon_skill_result->fetch_assoc();
    $is_in_weapon_skill = ($weapon_skill_row['count'] > 0);
}

// Process deletion request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_delete'])) {
    $remove_from_droplist = isset($_POST['remove_from_droplist']) && $_POST['remove_from_droplist'] == 'yes';
    
    // If the weapon is in droplist and we don't want to remove it, or if it's in weapon_skill
    // then we should not proceed with deletion
    if (($is_in_droplist && !$remove_from_droplist) || $is_in_weapon_skill) {
        $_SESSION['message'] = "Cannot delete this weapon because it has dependencies that weren't addressed.";
        if ($is_in_droplist && !$remove_from_droplist) {
            $_SESSION['message'] .= " Remove it from the droplist first.";
        }
        if ($is_in_weapon_skill) {
            $_SESSION['message'] .= " It is associated with weapon skills.";
        }
        $_SESSION['message_type'] = "danger";
    } else {
        // Proceed with deletion
        list($success, $details) = deleteWeaponAndDependencies($conn, $weapon_id, $remove_from_droplist);
        
        if ($success) {
            $_SESSION['message'] = implode(", ", $details);
            $_SESSION['message_type'] = "success";
            
            // Log the activity
            if (function_exists('logAdminActivity')) {
                logAdminActivity('delete', 'weapon', "Deleted weapon with ID: $weapon_id");
            }
            
            header("Location: weapon_list.php");
            exit;
        } else {
            $_SESSION['message'] = "Error: " . implode(", ", $details);
            $_SESSION['message_type'] = "danger";
        }
    }
}

// NOW we can include the header that will output HTML
include '../../../includes/admin_header.php';
?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Delete <span>Weapon</span></h1>
                <p class="hero-subtitle">Are you sure you want to delete this weapon?</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Confirm Deletion</h5>
                        <div>
                            <a href="weapon_list.php" class="btn btn-sm btn-outline-light">Back to List</a>
                            <a href="../../../pages/weapon/weapon_detail.php?id=<?php echo $weapon_id; ?>" class="btn btn-sm btn-outline-accent" target="_blank">View Details</a>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <?php if (isset($_SESSION['message'])): ?>
                        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show" role="alert">
                            <?php 
                            echo $_SESSION['message'];
                            unset($_SESSION['message']);
                            unset($_SESSION['message_type']);
                            ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php endif; ?>
                        
                        <div class="row mb-4">
                            <div class="col-md-5">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Weapon Image</h5>
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="weapon-image-container">
                                            <img src="/l1jdb-new/assets/img/icons/<?php echo $weapon['iconId']; ?>.png" 
                                                 alt="<?php echo htmlspecialchars($cleanWeaponName); ?>" 
                                                 class="weapon-image" 
                                                 onerror="this.src='/l1jdb-new/assets/img/icons/9175.png';">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-7">
                                <div class="card">
                                    <div class="card-header">
                                        <h5 class="mb-0">Weapon Details</h5>
                                    </div>
                                    <div class="card-body">
                                        <table class="table table-dark table-hover">
                                            <tbody>
                                                <tr>
                                                    <th class="stat-label">Weapon Name</th>
                                                    <td><?php echo htmlspecialchars($cleanWeaponName); ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="stat-label">ID</th>
                                                    <td><?php echo $weapon['item_id']; ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="stat-label">Type</th>
                                                    <td><?php echo normalizeText($weapon['type']); ?></td>
                                                </tr>
                                                <tr>
                                                    <th class="stat-label">Grade</th>
                                                    <td><?php echo $weapon['itemGrade']; ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <?php if ($is_in_droplist || $is_in_weapon_skill): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">Dependencies Found</h5>
                            </div>
                            <div class="card-body">
                                <?php if ($is_in_droplist): ?>
                                <div class="alert alert-warning">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    <strong>This weapon appears in <?php echo $drop_count; ?> monster drop tables.</strong>
                                    <p class="mt-2 mb-0">You can choose to remove these entries when deleting the weapon.</p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($is_in_weapon_skill): ?>
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-circle me-2"></i>
                                    <strong>This weapon is associated with weapon skills.</strong>
                                    <p class="mt-2 mb-0">You must remove these associations before deleting the weapon.</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="alert alert-danger">
                            <h5 class="text-center mb-3">Warning: Delete Confirmation</h5>
                            <p>You are about to permanently delete this weapon. This action cannot be undone.</p>
                            
                            <?php if (!$is_in_droplist && !$is_in_weapon_skill): ?>
                            <p>Before proceeding, please confirm that this weapon is not:</p>
                            <ul>
                                <li>Referenced in quests or other game systems</li>
                                <li>Currently equipped by players in the game</li>
                            </ul>
                            <?php else: ?>
                            <p>Deleting a weapon that is still in use may cause game issues.</p>
                            <?php endif; ?>
                        </div>
                        
                        <form action="" method="POST">
                            <?php if ($is_in_droplist): ?>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="remove_from_droplist" name="remove_from_droplist" value="yes">
                                    <label class="form-check-label" for="remove_from_droplist">
                                        <strong>Remove this weapon from all monster drop tables (<?php echo $drop_count; ?> entries)</strong>
                                    </label>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                                <a href="weapon_list.php" class="btn btn-outline-light">Cancel</a>
                                <button type="submit" name="confirm_delete" value="yes" class="btn btn-danger" <?php echo $is_in_weapon_skill ? 'disabled' : ''; ?>>
                                    Delete Weapon
                                </button>
                            </div>
                            
                            <?php if ($is_in_weapon_skill): ?>
                            <div class="text-center mt-3">
                                <small class="text-warning">The delete button is disabled because this weapon has weapon skill associations.</small>
                            </div>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../../includes/admin_footer.php'; ?>
