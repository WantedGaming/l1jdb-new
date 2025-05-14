<?php
require_once '../../includes/header.php';

// Initialize search parameters
$search = isset($_GET['search']) ? sanitizeInput($_GET['search']) : '';

// Initialize filter parameters (checkboxes)
$underwater = isset($_GET['underwater']) ? 1 : 0;
$dungeon = isset($_GET['dungeon']) ? 1 : 0;
$beginZone = isset($_GET['beginZone']) ? 1 : 0;
$redKnightZone = isset($_GET['redKnightZone']) ? 1 : 0;
$ruunCastleZone = isset($_GET['ruunCastleZone']) ? 1 : 0;
$interWarZone = isset($_GET['interWarZone']) ? 1 : 0;
$interKind = isset($_GET['interKind']) ? 0 : -1; // Simplified to just check for interKind=0

// Determine if any filters are active
$filtersActive = $underwater || $dungeon || $beginZone || $redKnightZone || 
                $ruunCastleZone || $interWarZone || $interKind !== -1;

// Pagination settings
$items_per_page = 12; // For card view, fewer items per page looks better
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $items_per_page;

// Build the base query for counting total records
$count_sql = "SELECT COUNT(*) as total FROM mapids WHERE 1=1";

// Add search conditions if provided
if (!empty($search)) {
    $count_sql .= " AND (locationname LIKE '%$search%' OR mapid LIKE '%$search%')";
}

// Add filter conditions - only add if checkbox is checked
if ($underwater) {
    $count_sql .= " AND underwater = 1";
}
if ($dungeon) {
    $count_sql .= " AND dungeon = 1";
}
if ($beginZone) {
    $count_sql .= " AND (beginZone = 1 OR geradBuffZone = 1 OR growBuffZone = 1)";
}
if ($redKnightZone) {
    $count_sql .= " AND redKnightZone = 1";
}
if ($ruunCastleZone) {
    $count_sql .= " AND ruunCastleZone = 1";
}
if ($interWarZone) {
    $count_sql .= " AND interWarZone = 1";
}
if ($interKind > -1) {
    $count_sql .= " AND interKind = $interKind";
}

// Execute count query
$count_result = $conn->query($count_sql);
$count_row = $count_result->fetch_assoc();
$total_items = $count_row['total'];
$total_pages = ceil($total_items / $items_per_page);

// Build the query for fetching the current page's items
$sql = "SELECT * FROM mapids WHERE 1=1";

// Add search conditions if provided
if (!empty($search)) {
    $sql .= " AND (locationname LIKE '%$search%' OR mapid LIKE '%$search%')";
}

// Add filter conditions - only add if checkbox is checked
if ($underwater) {
    $sql .= " AND underwater = 1";
}
if ($dungeon) {
    $sql .= " AND dungeon = 1";
}
if ($beginZone) {
    $sql .= " AND (beginZone = 1 OR geradBuffZone = 1 OR growBuffZone = 1)";
}
if ($redKnightZone) {
    $sql .= " AND redKnightZone = 1";
}
if ($ruunCastleZone) {
    $sql .= " AND ruunCastleZone = 1";
}
if ($interWarZone) {
    $sql .= " AND interWarZone = 1";
}
if ($interKind > -1) {
    $sql .= " AND interKind = $interKind";
}

// Add pagination
$sql .= " ORDER BY locationname ASC LIMIT $items_per_page OFFSET $offset";

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
            <h1 class="hero-title">Map <span>Database</span></h1>
            <p class="hero-subtitle">Explore all regions and locations in L1J Remastered</p>
            
            <!-- Search Form -->
            <form class="search-form mt-4" method="GET" action="" id="mapFilterForm">
                <!-- Search input on top -->
                <div class="input-group mb-3">
                    <input type="text" class="form-control search-input" name="search" placeholder="Search maps by name or ID..." value="<?php echo htmlspecialchars($search); ?>">
                    <button class="btn btn-accent" type="submit">Search</button>
                </div>
                
                <!-- Filter options below -->
                <div class="filter-options">
                    <?php if ($filtersActive): ?>
                    <div class="text-center mb-3">
                        <a href="maps_list.php" class="btn btn-sm btn-outline-light">Clear All Filters</a>
                    </div>
                    <?php endif; ?>
                    
                    <div class="card fancy-border" style="background: rgba(255,255,255,0.05);">
                        <div class="card-header">
                            <h6 class="mb-0">Filter by Map Type</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="underwaterCheck" name="underwater" <?php echo $underwater ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="underwaterCheck">
                                            Underwater
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="dungeonCheck" name="dungeon" <?php echo $dungeon ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="dungeonCheck">
                                            Dungeon
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="beginZoneCheck" name="beginZone" <?php echo $beginZone ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="beginZoneCheck">
                                            Beginner
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="redKnightZoneCheck" name="redKnightZone" <?php echo $redKnightZone ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="redKnightZoneCheck">
                                            Red Knight
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="ruunCastleZoneCheck" name="ruunCastleZone" <?php echo $ruunCastleZone ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="ruunCastleZoneCheck">
                                            Ruun Castle
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox" value="1" id="interWarZoneCheck" name="interWarZone" <?php echo $interWarZone ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="interWarZoneCheck">
                                            World Siege
                                        </label>
                                    </div>
                                </div>
                                <div class="col-6 col-md-3 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input filter-checkbox" type="checkbox" value="0" id="interKindCheck" name="interKind" <?php echo $interKind === 0 ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="interKindCheck">
                                            InterServer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Map List Section -->
<section class="container mt-5">
    <h2 class="section-title">Map List</h2>
    
    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): 
                // Get map image path
                $mapImagePath = '/l1jdb-new/assets/img/icons/maps/' . $row['pngId'] . '.jpeg';
                $placeholderPath = '/l1jdb-new/assets/img/placeholders/map-placeholder.png';
                
                // Prepare map type badges
                $badges = [];
                if ($row['underwater'] == 1) $badges[] = ['text' => 'Underwater', 'class' => 'bg-info'];
                if ($row['dungeon'] == 1) $badges[] = ['text' => 'Dungeon', 'class' => 'bg-secondary'];
                if ($row['beginZone'] == 1) $badges[] = ['text' => 'Beginner', 'class' => 'bg-success'];
                if ($row['redKnightZone'] == 1) $badges[] = ['text' => 'Red Knight', 'class' => 'bg-danger'];
                if ($row['ruunCastleZone'] == 1) $badges[] = ['text' => 'Ruun Castle', 'class' => 'bg-warning'];
                if ($row['interWarZone'] == 1) $badges[] = ['text' => 'Inter War', 'class' => 'bg-primary'];
                if ($row['geradBuffZone'] == 1) $badges[] = ['text' => 'Gerad', 'class' => 'bg-accent'];
                if ($row['growBuffZone'] == 1) $badges[] = ['text' => 'Growth', 'class' => 'bg-accent'];
            ?>
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card h-100">
                        <div class="card-header">
                            <h5><?php echo htmlspecialchars($row['locationname'] ?: 'Map #' . $row['mapid']); ?></h5>
                        </div>
                        <img src="<?php echo $mapImagePath; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['locationname']); ?>" 
                             style="height: 180px; object-fit: cover;" onerror="this.src='<?php echo $placeholderPath; ?>'">
                        <div class="card-body">
                            <p class="card-text">
                                <strong>Map ID:</strong> <?php echo $row['mapid']; ?><br>
                                <?php if (!empty($row['desc_kr'])): ?>
                                <strong>Korean Name:</strong> <?php echo htmlspecialchars($row['desc_kr']); ?><br>
                                <?php endif; ?>
                                <strong>Coordinates:</strong> X(<?php echo $row['startX']; ?>-<?php echo $row['endX']; ?>) 
                                                           Y(<?php echo $row['startY']; ?>-<?php echo $row['endY']; ?>)<br>
                                <strong>Monster Rate:</strong> <?php echo $row['monster_amount']; ?><br>
                                <strong>Drop Rate:</strong> <?php echo $row['drop_rate']; ?>
                            </p>
                            
                            <!-- Display badges for map properties -->
                            <div class="mt-2">
                                <?php foreach ($badges as $badge): ?>
                                <span class="badge <?php echo $badge['class']; ?> me-1 mb-1"><?php echo $badge['text']; ?></span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="map_detail.php?id=<?php echo $row['mapid']; ?>" class="btn btn-accent">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
        
        <!-- Pagination -->
        <?php if ($total_pages > 1): ?>
        <div class="text-center mt-4">
            <nav aria-label="Map list pagination">
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
                Showing <?php echo ($offset + 1); ?> to <?php echo min($offset + $items_per_page, $total_items); ?> of <?php echo $total_items; ?> maps
            </p>
        </div>
        <?php endif; ?>
        
    <?php else: ?>
        <div class="alert alert-info">No maps found matching your criteria. Try adjusting your search.</div>
    <?php endif; ?>
</section>

<!-- Add JavaScript to make cards clickable -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Make cards clickable
    const cards = document.querySelectorAll('.card');
    
    cards.forEach(card => {
        const link = card.querySelector('a.btn-accent');
        if (link) {
            const href = link.getAttribute('href');
            
            // Make the entire card clickable except for the button
            card.addEventListener('click', function(e) {
                // Don't trigger if they clicked the button directly
                if (!e.target.closest('.btn-accent')) {
                    window.location.href = href;
                }
            });
            
            // Add hover effect class
            card.classList.add('clickable-row');
        }
    });
    
    // Auto-submit form when checkboxes are changed
    const filterCheckboxes = document.querySelectorAll('.filter-checkbox');
    
    filterCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            document.getElementById('mapFilterForm').submit();
        });
    });
});
</script>

<?php include '../../includes/footer.php'; ?>
