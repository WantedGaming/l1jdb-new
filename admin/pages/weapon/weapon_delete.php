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
    // Start transaction for comprehensive deletion
    $conn->begin_transaction();
    $success = true;
    $message_details = [];
    
    try {
        // Get dependency counts for reporting and delete related records
        
        // 1. Check and delete from droplist table (uses itemId)
        $count_droplist_sql = "SELECT COUNT(*) as count FROM droplist WHERE itemId = ?";
        $count_stmt = $conn->prepare($count_droplist_sql);
        if ($count_stmt) {
            $count_stmt->bind_param("i", $weapon_id);
            $count_stmt->execute();
            $result = $count_stmt->get_result();
            $row = $result->fetch_assoc();
            $count = $row['count'];
            $count_stmt->close();
            
            if ($count > 0) {
                $delete_sql = "DELETE FROM droplist WHERE itemId = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $weapon_id);
                $delete_stmt->execute();
                
                if ($delete_stmt->affected_rows > 0) {
                    $message_details[] = "Removed $count monster drop entries";
                }
                $delete_stmt->close();
            }
        }
        
        // 2. Check and delete from weapon_skill table (uses weapon_id)
        $count_skill_sql = "SELECT COUNT(*) as count FROM weapon_skill WHERE weapon_id = ?";
        $count_stmt = $conn->prepare($count_skill_sql);
        if ($count_stmt) {
            $count_stmt->bind_param("i", $weapon_id);
            $count_stmt->execute();
            $result = $count_stmt->get_result();
            $row = $result->fetch_assoc();
            $count = $row['count'];
            $count_stmt->close();
            
            if ($count > 0) {
                $delete_sql = "DELETE FROM weapon_skill WHERE weapon_id = ?";
                $delete_stmt = $conn->prepare($delete_sql);
                $delete_stmt->bind_param("i", $weapon_id);
                $delete_stmt->execute();
                
                if ($delete_stmt->affected_rows > 0) {
                    $message_details[] = "Removed $count weapon skill associations";
                }
                $delete_stmt->close();
            }
        }
        
        // 3. Check if weapon_skill_model exists and has item_id column before attempting operations
        $table_exists = $conn->query("SHOW TABLES LIKE 'weapon_skill_model'")->num_rows > 0;
        if ($table_exists) {
            // Check if the column exists
            $column_exists = $conn->query("SHOW COLUMNS FROM weapon_skill_model LIKE 'item_id'")->num_rows > 0;
            
            if ($column_exists) {
                $count_model_sql = "SELECT COUNT(*) as count FROM weapon_skill_model WHERE item_id = ?";
                $count_stmt = $conn->prepare($count_model_sql);
                if ($count_stmt) {
                    $count_stmt->bind_param("i", $weapon_id);
                    $count_stmt->execute();
                    $result = $count_stmt->get_result();
                    $row = $result->fetch_assoc();
                    $count = $row['count'];
                    $count_stmt->close();
                    
                    if ($count > 0) {
                        $delete_sql = "DELETE FROM weapon_skill_model WHERE item_id = ?";
                        $delete_stmt = $conn->prepare($delete_sql);
                        $delete_stmt->bind_param("i", $weapon_id);
                        $delete_stmt->execute();
                        
                        if ($delete_stmt->affected_rows > 0) {
                            $message_details[] = "Removed $count weapon skill models";
                        }
                        $delete_stmt->close();
                    }
                }
            }
        }
        
        // 4. Check if weapon_damege exists and check the correct column name
        $table_exists = $conn->query("SHOW TABLES LIKE 'weapon_damege'")->num_rows > 0;
        if ($table_exists) {
            // Check for both possible column names: 'item_id' or 'weapon_id'
            $item_id_exists = $conn->query("SHOW COLUMNS FROM weapon_damege LIKE 'item_id'")->num_rows > 0;
            $weapon_id_exists = $conn->query("SHOW COLUMNS FROM weapon_damege LIKE 'weapon_id'")->num_rows > 0;
            
            $column_name = $item_id_exists ? 'item_id' : ($weapon_id_exists ? 'weapon_id' : '');
            
            if (!empty($column_name)) {
                $count_damage_sql = "SELECT COUNT(*) as count FROM weapon_damege WHERE $column_name = ?";
                $count_stmt = $conn->prepare($count_damage_sql);
                if ($count_stmt) {
                    $count_stmt->bind_param("i", $weapon_id);
                    $count_stmt->execute();
                    $result = $count_stmt->get_result();
                    $row = $result->fetch_assoc();
                    $count = $row['count'];
                    $count_stmt->close();
                    
                    if ($count > 0) {
                        $delete_sql = "DELETE FROM weapon_damege WHERE $column_name = ?";
                        $delete_stmt = $conn->prepare($delete_sql);
                        $delete_stmt->bind_param("i", $weapon_id);
                        $delete_stmt->execute();
                        
                        if ($delete_stmt->affected_rows > 0) {
                            $message_details[] = "Removed $count weapon damage records";
                        }
                        $delete_stmt->close();
                    }
                }
            }
        }
        
        // Finally delete the weapon itself
        $delete_weapon_sql = "DELETE FROM weapon WHERE item_id = ?";
        $delete_weapon_stmt = $conn->prepare($delete_weapon_sql);
        $delete_weapon_stmt->bind_param("i", $weapon_id);
        $delete_weapon_stmt->execute();
        
        if ($delete_weapon_stmt->affected_rows > 0) {
            $message_details[] = "Weapon deleted successfully";
            $success = true;
        } else {
            // If weapon wasn't deleted, rollback
            throw new Exception("Failed to delete weapon");
        }
        
        $delete_weapon_stmt->close();
        
        // If we got this far, commit the transaction
        $conn->commit();
        
        // Set success message
        $_SESSION['message'] = implode(", ", $message_details);
        $_SESSION['message_type'] = "success";
        
        // Log the activity
        if (function_exists('logAdminActivity')) {
            logAdminActivity('delete', 'weapon', "Deleted weapon with ID: $weapon_id and all its associations");
        }
        
        header("Location: weapon_list.php");
        exit;
        
    } catch (Exception $e) {
        // Something went wrong, rollback
        $conn->rollback();
        $_SESSION['message'] = "Error: " . $e->getMessage();
        $_SESSION['message_type'] = "danger";
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
                        
                        <?php 
                        // Get dependency counts for information - More robust version
                        $dependencies = [];
                        
                        // Check droplist
                        $check_droplist_sql = "SELECT COUNT(*) as count FROM droplist WHERE itemId = $weapon_id";
                        $droplist_result = $conn->query($check_droplist_sql);
                        if ($droplist_result && $droplist_result->num_rows > 0) {
                            $droplist_row = $droplist_result->fetch_assoc();
                            $drop_count = $droplist_row['count'];
                            if ($drop_count > 0) {
                                $dependencies[] = "$drop_count monster drop table entries";
                            }
                        }
                        
                        // Check weapon skills
                        $check_skill_sql = "SELECT COUNT(*) as count FROM weapon_skill WHERE weapon_id = $weapon_id";
                        $skill_result = $conn->query($check_skill_sql);
                        if ($skill_result && $skill_result->num_rows > 0) {
                            $skill_row = $skill_result->fetch_assoc();
                            if ($skill_row['count'] > 0) {
                                $dependencies[] = $skill_row['count'] . " weapon skill associations";
                            }
                        }
                        
                        // Check weapon skill model - Check if table and column exist
                        $table_exists = $conn->query("SHOW TABLES LIKE 'weapon_skill_model'")->num_rows > 0;
                        if ($table_exists) {
                            $column_exists = $conn->query("SHOW COLUMNS FROM weapon_skill_model LIKE 'item_id'")->num_rows > 0;
                            if ($column_exists) {
                                $check_model_sql = "SELECT COUNT(*) as count FROM weapon_skill_model WHERE item_id = $weapon_id";
                                $model_result = $conn->query($check_model_sql);
                                if ($model_result && $model_result->num_rows > 0) {
                                    $model_row = $model_result->fetch_assoc();
                                    if ($model_row['count'] > 0) {
                                        $dependencies[] = $model_row['count'] . " weapon skill models";
                                    }
                                }
                            }
                        }
                        
                        // Check weapon damage - Check if table exists and find the right column
                        $table_exists = $conn->query("SHOW TABLES LIKE 'weapon_damege'")->num_rows > 0;
                        if ($table_exists) {
                            $item_id_exists = $conn->query("SHOW COLUMNS FROM weapon_damege LIKE 'item_id'")->num_rows > 0;
                            $weapon_id_exists = $conn->query("SHOW COLUMNS FROM weapon_damege LIKE 'weapon_id'")->num_rows > 0;
                            
                            $column_name = $item_id_exists ? 'item_id' : ($weapon_id_exists ? 'weapon_id' : '');
                            
                            if (!empty($column_name)) {
                                $check_damage_sql = "SELECT COUNT(*) as count FROM weapon_damege WHERE $column_name = $weapon_id";
                                $damage_result = $conn->query($check_damage_sql);
                                if ($damage_result && $damage_result->num_rows > 0) {
                                    $damage_row = $damage_result->fetch_assoc();
                                    if ($damage_row['count'] > 0) {
                                        $dependencies[] = $damage_row['count'] . " weapon damage records";
                                    }
                                }
                            }
                        }
                        
                        if (count($dependencies) > 0): 
                        ?>
                        <div class="card mb-4">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="mb-0">The following related data will also be deleted:</h5>
                            </div>
                            <div class="card-body">
                                <ul class="mb-0">
                                    <?php foreach($dependencies as $dependency): ?>
                                    <li><strong><?php echo $dependency; ?></strong></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <form action="" method="POST">
                            <div class="alert alert-danger">
                                <h5 class="text-center mb-3">Warning: Delete Confirmation</h5>
                                <p><strong>You are about to permanently delete this weapon and ALL its associated data.</strong></p>
                                <p>This action cannot be undone and will remove all related entries from other database tables.</p>
                                <p>Please confirm that this weapon is not currently equipped by players in the game.</p>
                            </div>
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                                <a href="weapon_list.php" class="btn btn-outline-light">Cancel</a>
                                <button type="submit" name="confirm_delete" value="yes" class="btn btn-danger">
                                    Delete Weapon and All Related Data
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../../includes/admin_footer.php'; ?>
