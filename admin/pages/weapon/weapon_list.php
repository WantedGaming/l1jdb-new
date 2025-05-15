<?php include '../../../includes/admin_header.php'; ?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Weapons <span>Management</span></h1>
                <p class="hero-subtitle">View, edit, and manage weapons in the game database.</p>
                
                <!-- Search Form -->
                <form class="search-form mt-4" method="GET" action="">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control search-input" name="search" placeholder="Search weapons..." 
                               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
                        <button class="btn btn-accent" type="submit">Search</button>
                    </div>
                    
                    <div class="filter-options">
                        <div class="row">
                            <div class="col-md-12 mb-2">
                                <select class="form-control filter-select" name="type" onchange="this.form.submit()">
                                    <option value="">All Types</option>
                                    <option value="SWORD" <?php echo (isset($_GET['type']) && $_GET['type'] == 'SWORD') ? 'selected' : ''; ?>>Sword</option>
                                    <option value="DAGGER" <?php echo (isset($_GET['type']) && $_GET['type'] == 'DAGGER') ? 'selected' : ''; ?>>Dagger</option>
                                    <option value="TOHAND_SWORD" <?php echo (isset($_GET['type']) && $_GET['type'] == 'TOHAND_SWORD') ? 'selected' : ''; ?>>Two-Handed Sword</option>
                                    <option value="BOW" <?php echo (isset($_GET['type']) && $_GET['type'] == 'BOW') ? 'selected' : ''; ?>>Bow</option>
                                    <option value="SPEAR" <?php echo (isset($_GET['type']) && $_GET['type'] == 'SPEAR') ? 'selected' : ''; ?>>Spear</option>
                                    <option value="BLUNT" <?php echo (isset($_GET['type']) && $_GET['type'] == 'BLUNT') ? 'selected' : ''; ?>>Blunt</option>
                                    <option value="STAFF" <?php echo (isset($_GET['type']) && $_GET['type'] == 'STAFF') ? 'selected' : ''; ?>>Staff</option>
                                    <option value="EDORYU" <?php echo (isset($_GET['type']) && $_GET['type'] == 'EDORYU') ? 'selected' : ''; ?>>Edoryu</option>
                                    <option value="CLAW" <?php echo (isset($_GET['type']) && $_GET['type'] == 'CLAW') ? 'selected' : ''; ?>>Claw</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php
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
        
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Weapons List</h5>
                <div>
                    <a href="weapon_add.php" class="btn btn-sm btn-accent">Add New Weapon</a>
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
                
                <?php if ($result && $result->num_rows > 0): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Type</th>
                                <th class="text-center">Damage (S/L)</th>
                                <th class="text-center">Safe Enchant</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $result->fetch_assoc()): 
                                // Clean the item name
                                $cleanName = cleanItemName($row['desc_en']);
                            ?>
                                <tr class="clickable-row" data-href="../../pages/weapon/weapon_detail.php?id=<?php echo $row['item_id']; ?>">
                                    <td><?php echo $row['item_id']; ?></td>
                                    <td>
                                        <img src="/l1jdb-new/assets/img/icons/<?php echo $row['iconId']; ?>.png" 
                                             alt="<?php echo htmlspecialchars($cleanName); ?>" 
                                             style="width: 32px; height: 32px;" 
                                             onerror="this.src='/l1jdb-new/assets/img/icons/9175.png';">
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
                                    <td class="text-center"><?php echo $row['dmg_small']; ?> / <?php echo $row['dmg_large']; ?></td>
                                    <td class="text-center"><?php echo $row['safenchant']; ?></td>
                                    <td class="text-center">
                                        <a href="weapon_edit.php?id=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-outline-light">Edit</a>
                                        <a href="weapon_delete.php?id=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-outline-danger btn-delete" 
                                           onclick="return confirm('Are you sure you want to delete this weapon? This action cannot be undone.')">Delete</a>
                                        <a href="../../pages/weapon/weapon_detail.php?id=<?php echo $row['item_id']; ?>" class="btn btn-sm btn-outline-accent" target="_blank">View</a>
                                    </td>
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
            </div>
        </div>
    </div>
</section>

<!-- Add JavaScript for clickable rows -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rows = document.querySelectorAll('.clickable-row');
    
    rows.forEach(function(row) {
        row.addEventListener('click', function(e) {
            // Don't navigate if clicked on a button
            if (e.target.tagName === 'A' || e.target.closest('a')) {
                return;
            }
            window.location.href = this.getAttribute('data-href');
        });
    });
});
</script>

<?php include '../../../includes/admin_footer.php'; ?>