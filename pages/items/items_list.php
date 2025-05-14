<?php
require_once '../../includes/header.php';

// Initialize search parameters
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$type = isset($_GET['type']) ? sanitizeInput($_GET['type']) : '';
$use_type = isset($_GET['use_type']) ? sanitizeInput($_GET['use_type']) : '';
$material = isset($_GET['material']) ? sanitizeInput($_GET['material']) : '';

// Pagination settings
$items_per_page = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Build the base query for counting total records
$count_sql = "SELECT COUNT(*) as total FROM etcitem WHERE 1=1";

// Add search conditions if provided
if (!empty($search)) {
    $count_sql .= " AND desc_en LIKE '%$search%'";
}

if (!empty($type)) {
    $count_sql .= " AND item_type = '$type'";
}

if (!empty($use_type)) {
    $count_sql .= " AND use_type = '$use_type'";
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
$sql = "SELECT item_id, desc_en, item_type, use_type, material, iconId FROM etcitem WHERE 1=1";

// Add search conditions if provided
if (!empty($search)) {
    $sql .= " AND desc_en LIKE '%$search%'";
}

if (!empty($type)) {
    $sql .= " AND item_type = '$type'";
}

if (!empty($use_type)) {
    $sql .= " AND use_type = '$use_type'";
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


// Function to normalize type display
function normalizeType($type) {
    // Replace underscores with spaces
    $type = str_replace('_', ' ', $type);
    
    // Convert to title case (capitalize first letter of each word)
    $type = ucwords(strtolower($type));
    
    return trim($type);
}

// Get unique item types for filter dropdown
$types_sql = "SELECT DISTINCT item_type FROM etcitem ORDER BY item_type";
$types_result = $conn->query($types_sql);
$item_types = [];
while ($row = $types_result->fetch_assoc()) {
    $item_types[] = $row['item_type'];
}

// Get unique use types for filter dropdown
$use_types_sql = "SELECT DISTINCT use_type FROM etcitem WHERE use_type != 'NONE' ORDER BY use_type";
$use_types_result = $conn->query($use_types_sql);
$use_types = [];
while ($row = $use_types_result->fetch_assoc()) {
    $use_types[] = $row['use_type'];
}

// Get unique materials for filter dropdown
$materials_sql = "SELECT DISTINCT material FROM etcitem ORDER BY material";
$materials_result = $conn->query($materials_sql);
$materials = [];
while ($row = $materials_result->fetch_assoc()) {
    $materials[] = $row['material'];
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Items <span>Database</span></h1>
            <p class="hero-subtitle">Browse all available items in L1J Remastered</p>
            
            <!-- Search Form -->
<form class="search-form mt-4" method="GET" action="">
    <!-- Search input on top -->
    <div class="input-group mb-3">
        <input type="text" class="form-control search-input" name="search" placeholder="Search items..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-accent" type="submit">Search</button>
    </div>
    
    <!-- Filter options below -->
    <div class="filter-options">
        <div class="row">
            <div class="col-md-4 mb-2">
                <select class="form-control filter-select" name="type">
                    <option value="">All Types</option>
                    <?php foreach ($item_types as $item_type): ?>
                        <option value="<?php echo $item_type; ?>" <?php echo ($type == $item_type) ? 'selected' : ''; ?>>
                            <?php echo normalizeType($item_type); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-4 mb-2">
                <select class="form-control filter-select" name="use_type">
                    <option value="">All Uses</option>
                    <?php foreach ($use_types as $use_type_option): ?>
                        <option value="<?php echo $use_type_option; ?>" <?php echo ($use_type == $use_type_option) ? 'selected' : ''; ?>>
                            <?php echo normalizeType($use_type_option); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-4 mb-2">
                <select class="form-control filter-select" name="material">
                    <option value="">All Materials</option>
                    <?php foreach ($materials as $material_option): ?>
                        <option value="<?php echo $material_option; ?>" <?php echo ($material == $material_option) ? 'selected' : ''; ?>>
                            <?php echo normalizeMaterial($material_option); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
    </div>
</form>
        </div>
    </div>
</section>

<!-- Items List Section -->
<section class="container mt-5">
    <h2 class="section-title">Items List</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Use</th>
                        <th>Material</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): 
                        // Clean the item name
                        $cleanName = cleanItemName($row['desc_en']);
                    ?>
                        <tr class="clickable-row" data-href="item_detail.php?id=<?php echo $row['item_id']; ?>">
                            <td>
                                <img src="/l1jdb-new/assets/img/icons/<?php echo $row['iconId']; ?>.png" 
                                     alt="<?php echo htmlspecialchars($cleanName); ?>" 
                                     style="width: 32px; height: 32px;" 
                                     onerror="this.src='/l1jdb-new/assets/img/icons/9175.png';">
                            </td>
                            <td><?php echo htmlspecialchars($cleanName); ?></td>
                            <td>
                                <span class="badge bg-info">
                                    <?php echo htmlspecialchars(normalizeType($row['item_type'])); ?>
                                </span>
                            </td>
                            <td>
                                <?php if ($row['use_type'] != 'NONE'): ?>
                                <span class="badge bg-secondary">
                                    <?php echo htmlspecialchars(normalizeType($row['use_type'])); ?>
                                </span>
                                <?php else: ?>
                                <span>-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo htmlspecialchars(normalizeMaterial($row['material'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="text-center mt-4">
            <nav aria-label="Item list pagination">
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
                Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $items_per_page, $total_items); ?> of <?php echo $total_items; ?> items
            </p>
        </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-info">No items found matching your criteria. Try adjusting your search.</div>
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