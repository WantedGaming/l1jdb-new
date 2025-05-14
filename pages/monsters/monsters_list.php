<?php
require_once '../../includes/header.php';

// Initialize search parameters
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';
$level_min = isset($_GET['level_min']) ? intval($_GET['level_min']) : '';
$level_max = isset($_GET['level_max']) ? intval($_GET['level_max']) : '';

// Pagination settings
$items_per_page = 20;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Build the base query for counting total records
$count_sql = "SELECT COUNT(*) as total FROM npc WHERE (impl = 'L1Monster' OR impl = 'L1Doppelganger')";

// Add search conditions if provided
if (!empty($search)) {
    $count_sql .= " AND desc_en LIKE '%$search%'";
}

if (!empty($level_min)) {
    $count_sql .= " AND lvl >= $level_min";
}

if (!empty($level_max)) {
    $count_sql .= " AND lvl <= $level_max";
}

// Execute count query
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_items = $count_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Build the query for fetching the current page's items
$sql = "SELECT npcid, desc_en, spriteId, lvl, hp, mp, ac FROM npc 
        WHERE (impl = 'L1Monster' OR impl = 'L1Doppelganger')";

// Add search conditions if provided
if (!empty($search)) {
    $sql .= " AND desc_en LIKE '%$search%'";
}

if (!empty($level_min)) {
    $sql .= " AND lvl >= $level_min";
}

if (!empty($level_max)) {
    $sql .= " AND lvl <= $level_max";
}

// Add pagination
$sql .= " ORDER BY lvl ASC, desc_en ASC LIMIT $items_per_page OFFSET $offset";

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
            <h1 class="hero-title">Monster <span>Database</span></h1>
            <p class="hero-subtitle">Browse all monsters in L1J Remastered</p>
            
            <!-- Search Form -->
<form class="search-form mt-4" method="GET" action="">
    <!-- Search input on top -->
    <div class="input-group mb-3">
        <input type="text" class="form-control search-input" name="search" placeholder="Search monsters..." value="<?php echo htmlspecialchars($search); ?>">
        <button class="btn btn-accent" type="submit">Search</button>
    </div>
    
    <!-- Filter options below -->
    <div class="filter-options">
        <div class="row">
            <div class="col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text">Min Level</span>
                    <input type="number" class="form-control filter-select" name="level_min" placeholder="Minimum level" value="<?php echo htmlspecialchars($level_min); ?>">
                </div>
            </div>
            <div class="col-md-6 mb-2">
                <div class="input-group">
                    <span class="input-group-text">Max Level</span>
                    <input type="number" class="form-control filter-select" name="level_max" placeholder="Maximum level" value="<?php echo htmlspecialchars($level_max); ?>">
                </div>
            </div>
        </div>
    </div>
</form>
        </div>
    </div>
</section>

<!-- Monster List Section -->
<section class="container mt-5">
    <h2 class="section-title">Monster List</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th class="text-center-column">Level</th>
                        <th class="text-center-column">HP</th>
                        <th class="text-center-column">MP</th>
                        <th class="text-center-column">AC</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="clickable-row" data-href="monster_detail.php?id=<?php echo $row['npcid']; ?>">
                            <td>
                                <img src="/l1jdb-new/assets/img/icons/ms<?php echo $row['spriteId']; ?>.gif" 
                                     alt="<?php echo htmlspecialchars($row['desc_en']); ?>" 
                                     style="width: 32px; height: 32px;" 
                                     onerror="this.onerror=null;this.src='/l1jdb-new/assets/img/icons/ms<?php echo $row['spriteId']; ?>.png'; 
                                             this.onerror=function(){this.src='/l1jdb-new/assets/img/icons/9175.png';}">
                            </td>
                            <td><?php echo htmlspecialchars($row['desc_en']); ?></td>
                            <td class="text-center-column"><?php echo $row['lvl']; ?></td>
                            <td class="text-center-column"><?php echo $row['hp']; ?></td>
                            <td class="text-center-column"><?php echo $row['mp']; ?></td>
                            <td class="text-center-column"><?php echo $row['ac']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="text-center mt-4">
            <nav aria-label="Monster list pagination">
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
                Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $items_per_page, $total_items); ?> of <?php echo $total_items; ?> monsters
            </p>
        </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-info">No monsters found matching your criteria. Try adjusting your search.</div>
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