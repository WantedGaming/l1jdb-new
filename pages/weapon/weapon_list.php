<?php
require_once '../../includes/header.php';

// Initialize search parameters
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$type = isset($_GET['type']) ? sanitizeInput($_GET['type']) : '';

// Build the query
$sql = "SELECT item_id, desc_en, type, dmg_small, dmg_large, safenchant, iconId FROM weapon WHERE 1=1";

// Add search conditions if provided
if (!empty($search)) {
    $sql .= " AND desc_en LIKE '%$search%'";
}

if (!empty($type)) {
    $sql .= " AND type = '$type'";
}

// Execute query
$result = $conn->query($sql);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Weapons <span>Database</span></h1>
            <p class="hero-subtitle">Browse all available weapons in L1J Remastered</p>
            
            <!-- Search Form -->
            <form class="search-form mt-4" method="GET" action="">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" placeholder="Search weapons..." value="<?php echo htmlspecialchars($search); ?>">
                    <select class="form-control" name="type" style="max-width: 200px; border-radius: 0;">
                        <option value="">All Types</option>
                        <option value="SWORD" <?php echo ($type == 'SWORD') ? 'selected' : ''; ?>>Sword</option>
                        <option value="DAGGER" <?php echo ($type == 'DAGGER') ? 'selected' : ''; ?>>Dagger</option>
                        <option value="TOHAND_SWORD" <?php echo ($type == 'TOHAND_SWORD') ? 'selected' : ''; ?>>Two-Handed Sword</option>
                        <option value="BOW" <?php echo ($type == 'BOW') ? 'selected' : ''; ?>>Bow</option>
                        <option value="SPEAR" <?php echo ($type == 'SPEAR') ? 'selected' : ''; ?>>Spear</option>
                        <option value="BLUNT" <?php echo ($type == 'BLUNT') ? 'selected' : ''; ?>>Blunt</option>
                        <option value="STAFF" <?php echo ($type == 'STAFF') ? 'selected' : ''; ?>>Staff</option>
                        <option value="EDORYU" <?php echo ($type == 'EDORYU') ? 'selected' : ''; ?>>Edoryu</option>
                        <option value="CLAW" <?php echo ($type == 'CLAW') ? 'selected' : ''; ?>>Claw</option>
                        <!-- Add more weapon types as needed -->
                    </select>
                    <button class="btn btn-accent" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Weapon List Section -->
<section class="container mt-5">
    <h2 class="section-title">Weapon List</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Damage (S)</th>
                        <th>Damage (L)</th>
                        <th>Safe Enchant</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <img src="../../assets/img/icons/<?php echo $row['iconId']; ?>.png" alt="<?php echo htmlspecialchars($row['desc_en']); ?>" style="width: 32px; height: 32px;" onerror="this.src='../../assets/img/placeholders/weapon.png';">
                            </td>
                            <td><?php echo htmlspecialchars($row['desc_en']); ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <?php 
                                        $type_display = str_replace('_', ' ', $row['type']);
                                        $type_display = str_replace('TOHAND', 'Two-Handed', $type_display);
                                        echo htmlspecialchars($type_display); 
                                    ?>
                                </span>
                            </td>
                            <td><?php echo $row['dmg_small']; ?></td>
                            <td><?php echo $row['dmg_large']; ?></td>
                            <td><?php echo $row['safenchant']; ?></td>
                            <td>
                                <a href="detail.php?id=<?php echo $row['item_id']; ?>" class="btn btn-accent btn-sm">Details</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No weapons found matching your criteria. Try adjusting your search.</div>
    <?php endif; ?>
</section>

<?php include '../../includes/footer.php'; ?>