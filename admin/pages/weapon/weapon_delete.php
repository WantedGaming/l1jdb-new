<?php
// Start session first, before anything else
session_start();

// Include security and database connection files
require_once '../../../includes/config.php';
require_once '../../../includes/functions.php';
require_once '../../../includes/admin_security.php';

// Verify authentication
requireAdminAuth();

// Process all logic that might use header() redirects BEFORE including the header

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "No weapon ID specified";
    $_SESSION['message_type'] = "danger";
    header("Location: weapon_list.php");
    exit;
}

$weapon_id = intval($_GET['id']);

// If confirmation is received
if (isset($_POST['confirm_delete']) && $_POST['confirm_delete'] == 'yes') {
    // Check if there are any references to this weapon in other tables
    $check_sql = "SELECT COUNT(*) as count FROM droplist WHERE itemId = $weapon_id";
    $check_result = $conn->query($check_sql);
    $check_row = $check_result->fetch_assoc();
    
    $weapon_in_use = $check_row['count'] > 0;
    
    // Also check weapon_skill if the table exists
    $skill_check_sql = "SELECT COUNT(*) as count FROM weapon_skill WHERE weapon_id = $weapon_id";
    $skill_check_result = $conn->query($skill_check_sql);
    if ($skill_check_result) {
        $skill_check_row = $skill_check_result->fetch_assoc();
        $weapon_in_use = $weapon_in_use || ($skill_check_row['count'] > 0);
    }
    
    if ($weapon_in_use) {
        $_SESSION['message'] = "Cannot delete this weapon because it is referenced in other tables (droplist or weapon_skill).";
        $_SESSION['message_type'] = "danger";
    } else {
        // Delete the weapon
        $delete_sql = "DELETE FROM weapon WHERE item_id = $weapon_id";
        if ($conn->query($delete_sql)) {
            // Log the activity
            if (function_exists('logAdminActivity')) {
                logAdminActivity('delete', 'weapon', "Deleted weapon with ID: $weapon_id");
            }
            
            $_SESSION['message'] = "Weapon deleted successfully";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "Error deleting weapon: " . $conn->error;
            $_SESSION['message_type'] = "danger";
        }
    }
    
    header("Location: weapon_list.php");
    exit;
}

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
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Confirm Deletion</h5>
                        <div>
                            <a href="weapon_list.php" class="btn btn-sm btn-outline-light">Back to List</a>
                            <a href="../../pages/weapon/weapon_detail.php?id=<?php echo $weapon_id; ?>" class="btn btn-sm btn-outline-accent" target="_blank">View Details</a>
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
                        
                        <div class="text-center mb-4">
                            <img src="/l1jdb-new/assets/img/icons/<?php echo $weapon['iconId']; ?>.png" 
                                 alt="<?php echo htmlspecialchars($cleanWeaponName); ?>" 
                                 class="weapon-image" 
                                 style="max-height: 128px; max-width: 128px;" 
                                 onerror="this.src='/l1jdb-new/assets/img/icons/9175.png';">
                        </div>
                        
                        <div class="alert alert-danger text-center">
                            <p class="mb-0"><strong>Warning:</strong> You are about to delete the following weapon:</p>
                            <h4 class="mt-2"><?php echo htmlspecialchars($cleanWeaponName); ?></h4>
                            <p class="mb-0">
                                ID: <?php echo $weapon['item_id']; ?>, 
                                Type: <?php echo normalizeText($weapon['type']); ?>, 
                                Grade: <?php echo $weapon['itemGrade']; ?>
                            </p>
                            <p class="mt-3 mb-0">This action cannot be undone!</p>
                        </div>
                        
                        <form action="" method="POST" class="mt-4">
                            <input type="hidden" name="confirm_delete" value="yes">
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                                <a href="weapon_list.php" class="btn btn-outline-light">Cancel</a>
                                <button type="submit" class="btn btn-danger">Delete Weapon</button>
                            </div>
                        </form>
                        
                        <div class="mt-4">
                            <div class="card bg-dark">
                                <div class="card-header">
                                    <h6 class="mb-0">Before Deleting</h6>
                                </div>
                                <div class="card-body">
                                    <p>Make sure this weapon is not:</p>
                                    <ul>
                                        <li>Used in monster drop tables</li>
                                        <li>Associated with weapon skills</li>
                                        <li>Referenced in quests or other game systems</li>
                                        <li>Equipped by players in the game</li>
                                    </ul>
                                    <p class="mb-0">Deleting a weapon that is still in use can cause game issues.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../../../includes/admin_footer.php'; ?>