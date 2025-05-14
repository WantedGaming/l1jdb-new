<?php
require_once '../../includes/header.php';

// Initialize search parameters
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$type = isset($_GET['type']) ? sanitizeInput($_GET['type']) : '';
$material = isset($_GET['material']) ? sanitizeInput($_GET['material']) : '';

// Pagination settings
$items_per_page = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Build the base query for counting total records
$count_sql = "SELECT COUNT(*) as total FROM armor WHERE 1=1";

// Add search conditions if provided
if (!empty($search)) {
    $count_sql .= " AND desc_en LIKE '%$search%'";
}

if (!empty($type)) {
    $count_sql .= " AND type = '$type'";
}

if (!empty($material)) {
    $count_sql .= " AND material = '$material'";
}

// Execute count query
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_items = $count_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Build the query for fetching the current page's items
$sql = "SELECT item_id, desc_en, type, material, ac, safenchant, iconId FROM armor WHERE 1=1";

// Add search conditions if provided
if (!empty($search)) {
    $sql .= " AND desc_en LIKE '%$search%'";
}

if (!empty($type)) {
    $sql .= " AND type = '$type'";
}

if (!empty($material)) {
    $sql .= " AND material = '$material'";
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
            <h1 class="hero-title">Armor <span>Database</span></h1>
            <p class="hero-subtitle">Browse all available armor in L1J Remastered</p>
            
            <!-- Search Form -->
<form class="search-form mt-4" method="GET" action="">
    <!-- Search input on top -->
    <div class="input-group mb-3">
        <input type="text" class="form-control search-input" name="search" placeholder="Search armor..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-accent" type="submit">Search</button>
    </div>
    
    <!-- Filter options below -->
    <div class="filter-options">
        <div class="row">
            <div class="col-md-6 mb-2">
                <select class="form-control filter-select" name="type">
                    <option value="">All Types</option>
                    <option value="HELMET" <?php echo ($type == 'HELMET') ? 'selected' : ''; ?>>Helmet</option>
                    <option value="ARMOR" <?php echo ($type == 'ARMOR') ? 'selected' : ''; ?>>Armor</option>
                    <option value="T_SHIRT" <?php echo ($type == 'T_SHIRT') ? 'selected' : ''; ?>>T-Shirt</option>
                    <option value="CLOAK" <?php echo ($type == 'CLOAK') ? 'selected' : ''; ?>>Cloak</option>
                    <option value="GLOVE" <?php echo ($type == 'GLOVE') ? 'selected' : ''; ?>>Gloves</option>
                    <option value="BOOTS" <?php echo ($type == 'BOOTS') ? 'selected' : ''; ?>>Boots</option>
                    <option value="SHIELD" <?php echo ($type == 'SHIELD') ? 'selected' : ''; ?>>Shield</option>
                    <option value="AMULET" <?php echo ($type == 'AMULET') ? 'selected' : ''; ?>>Amulet</option>
                    <option value="RING" <?php echo ($type == 'RING') ? 'selected' : ''; ?>>Ring</option>
                    <option value="BELT" <?php echo ($type == 'BELT') ? 'selected' : ''; ?>>Belt</option>
                    <option value="EARRING" <?php echo ($type == 'EARRING') ? 'selected' : ''; ?>>Earring</option>
                </select>
            </div>
            
            <div class="col-md-6 mb-2">
                <select class="form-control filter-select" name="material">
                    <option value="">All Materials</option>
                    <option value="CLOTH(천)" <?php echo ($material == 'CLOTH(천)') ? 'selected' : ''; ?>>Cloth</option>
                    <option value="LEATHER(가죽)" <?php echo ($material == 'LEATHER(가죽)') ? 'selected' : ''; ?>>Leather</option>
                    <option value="METAL(금속)" <?php echo ($material == 'METAL(금속)') ? 'selected' : ''; ?>>Metal</option>
                    <option value="WOOD(나무)" <?php echo ($material == 'WOOD(나무)') ? 'selected' : ''; ?>>Wood</option>
                    <option value="BONE(뼈)" <?php echo ($material == 'BONE(뼈)') ? 'selected' : ''; ?>>Bone</option>
                    <option value="DRAGON_HIDE(용비늘)" <?php echo ($material == 'DRAGON_HIDE(용비늘)') ? 'selected' : ''; ?>>Dragon Hide</option>
                    <option value="IRON(철)" <?php echo ($material == 'IRON(철)') ? 'selected' : ''; ?>>Iron</option>
                    <option value="COPPER(구리)" <?php echo ($material == 'COPPER(구리)') ? 'selected' : ''; ?>>Copper</option>
                    <option value="SILVER(은)" <?php echo ($material == 'SILVER(은)') ? 'selected' : ''; ?>>Silver</option>
                    <option value="GOLD(금)" <?php echo ($material == 'GOLD(금)') ? 'selected' : ''; ?>>Gold</option>
                    <option value="PLATINUM(백금)" <?php echo ($material == 'PLATINUM(백금)') ? 'selected' : ''; ?>>Platinum</option>
                    <option value="MITHRIL(미스릴)" <?php echo ($material == 'MITHRIL(미스릴)') ? 'selected' : ''; ?>>Mithril</option>
                    <option value="PLASTIC(블랙미스릴)" <?php echo ($material == 'PLASTIC(블랙미스릴)') ? 'selected' : ''; ?>>Black Mithril</option>
                    <option value="GEMSTONE(보석)" <?php echo ($material == 'GEMSTONE(보석)') ? 'selected' : ''; ?>>Gemstone</option>
                    <option value="ORIHARUKON(오리하루콘)" <?php echo ($material == 'ORIHARUKON(오리하루콘)') ? 'selected' : ''; ?>>Oriharukon</option>
                </select>
            </div>
        </div>
    </div>
</form>
        </div>
    </div>
</section>

<!-- Armor List Section -->
<section class="container mt-5">
    <h2 class="section-title">Armor List</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Material</th>
                        <th class="text-center-column">AC</th>
                        <th class="text-center-column">Safe Enchant</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): 
                        // Clean the item name
                        $cleanName = cleanItemName($row['desc_en']);
                    ?>
                        <tr class="clickable-row" data-href="armor_detail.php?id=<?php echo $row['item_id']; ?>">
                            <td>
                                <img src="/l1jdb-new/assets/img/icons/icons/<?php echo $row['iconId']; ?>.png" 
                                     alt="<?php echo htmlspecialchars($cleanName); ?>" 
                                     style="width: 32px; height: 32px;" 
                                     onerror="this.src='/l1jdb-new/assets/img/placeholders/noiconid.png';">
                            </td>
                            <td><?php echo htmlspecialchars($cleanName); ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <?php echo htmlspecialchars(normalizeText($row['type'])); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars(normalizeMaterial($row['material'])); ?></td>
                            <td class="text-center-column"><?php echo $row['ac']; ?></td>
                            <td class="text-center-column"><?php echo $row['safenchant']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="text-center mt-4">
            <nav aria-label="Armor list pagination">
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
                Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $items_per_page, $total_items); ?> of <?php echo $total_items; ?> armor items
            </p>
        </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-info">No armor found matching your criteria. Try adjusting your search.</div>
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