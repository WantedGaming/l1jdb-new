<?php
require_once '../../includes/header.php';

// Initialize search parameters
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$type = isset($_GET['type']) ? sanitizeInput($_GET['type']) : '';

// Pagination settings
$items_per_page = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Build the base query for counting total records
$count_sql = "SELECT COUNT(*) as total FROM weapon WHERE 1=1";

// Add search conditions if provided
if (!empty($search)) {
    $count_sql .= " AND desc_en LIKE '%$search%'";
}

if (!empty($type)) {
    $count_sql .= " AND type = '$type'";
}

// Execute count query
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_items = $count_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Build the query for fetching the current page's items
$sql = "SELECT item_id, desc_en, type, dmg_small, dmg_large, safenchant, iconId FROM weapon WHERE 1=1";

// Add search conditions if provided
if (!empty($search)) {
    $sql .= " AND desc_en LIKE '%$search%'";
}

if (!empty($type)) {
    $sql .= " AND type = '$type'";
}

// Add pagination
$sql .= " ORDER BY item_id ASC LIMIT $items_per_page OFFSET $offset";

// Execute query
$result = $conn->query($sql);

// Function to generate pagination URL
function getPaginationUrl($page) {
    $params = $_GET;
    $params['page'] = $page;
    return '?' . http_build_query($params);
}
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
                    <input type="text" class="form-control search-input" name="search" placeholder="Search weapons..." value="<?php echo htmlspecialchars($search); ?>">
                    <select class="form-control weapon-type-select" name="type">
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
                        <th class="text-center-column">Damage (S)</th>
                        <th class="text-center-column">Damage (L)</th>
                        <th class="text-center-column">Safe Enchant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): 
                        // Clean the item name
                        $cleanName = cleanItemName($row['desc_en']);
                    ?>
                        <tr class="clickable-row" data-href="weapon_detail.php?id=<?php echo $row['item_id']; ?>">
                            <td>
                                <img src="/l1jdb-new/assets/img/icons/icons/<?php echo $row['iconId']; ?>.png" 
                                     alt="<?php echo htmlspecialchars($cleanName); ?>" 
                                     style="width: 32px; height: 32px;" 
                                     onerror="this.src='/l1jdb-new/assets/img/placeholders/noiconid.png';">
                            </td>
                            <td><?php echo htmlspecialchars($cleanName); ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <?php 
                                        $type_display = str_replace('_', ' ', $row['type']);
                                        $type_display = str_replace('TOHAND', 'Two-Handed', $type_display);
                                        echo htmlspecialchars($type_display); 
                                    ?>
                                </span>
                            </td>
                            <td class="text-center-column"><?php echo $row['dmg_small']; ?></td>
                            <td class="text-center-column"><?php echo $row['dmg_large']; ?></td>
                            <td class="text-center-column"><?php echo $row['safenchant']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="text-center mt-4">
            <nav aria-label="Weapon list pagination">
                <ul class="pagination justify-content-center">
                    <!-- Previous page link -->
                    <?php if ($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo getPaginationUrl($page - 1); ?>" aria-label="Previous">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-chevron-left"></i></span>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Page number links -->
                    <?php
                    // Calculate range of page numbers to display
                    $range = 2; // Number of pages to show on each side of current page
                    $start_page = max(1, $page - $range);
                    $end_page = min($total_pages, $page + $range);
                    
                    // First page link if not in range
                    if ($start_page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo getPaginationUrl(1); ?>">1</a>
                    </li>
                    <?php if ($start_page > 2): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                    <?php endif;
                    endif;
                    
                    // Page numbers
                    for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                        <a class="page-link" href="<?php echo getPaginationUrl($i); ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor;
                    
                    // Last page link if not in range
                    if ($end_page < $total_pages): 
                    if ($end_page < $total_pages - 1): ?>
                    <li class="page-item disabled">
                        <span class="page-link">...</span>
                    </li>
                    <?php endif; ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo getPaginationUrl($total_pages); ?>"><?php echo $total_pages; ?></a>
                    </li>
                    <?php endif; ?>
                    
                    <!-- Next page link -->
                    <?php if ($page < $total_pages): ?>
                    <li class="page-item">
                        <a class="page-link" href="<?php echo getPaginationUrl($page + 1); ?>" aria-label="Next">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                    <?php else: ?>
                    <li class="page-item disabled">
                        <span class="page-link"><i class="fas fa-chevron-right"></i></span>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
            
            <p class="pagination-info mt-3">
                Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $items_per_page, $total_items); ?> of <?php echo $total_items; ?> weapons
            </p>
        </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-info">No weapons found matching your criteria. Try adjusting your search.</div>
    <?php endif; ?>
</section>

<!-- Add JavaScript for clickable rows -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rows = document.querySelectorAll('.clickable-row');
    
    rows.forEach(function(row) {
        row.addEventListener('click', function() {
            window.location.href = this.getAttribute('data-href');
        });
    });
});
</script>

<?php include '../../includes/footer.php'; ?>