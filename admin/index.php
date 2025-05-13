<?php include '../includes/admin_header.php'; ?>

<!-- Modern Admin Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Admin <span>Dashboard</span></h1>
                <p class="hero-subtitle">Manage and modify the L1J Remastered database content through this administrative interface.</p>
                <form class="search-form" action="search.php" method="GET">
                    <div class="position-relative">
                        <input class="form-control" type="search" name="q" placeholder="Search database items..." aria-label="Search">
                        <button class="btn btn-accent" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Stats Overview -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-3 mb-4">
                <div class="admin-stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-sword"></i>
                    </div>
                    <div class="stats-number">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) as count FROM weapon");
                        $row = $result->fetch_assoc();
                        echo number_format($row['count']);
                        ?>
                    </div>
                    <div class="stats-label">Weapons</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="admin-stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-shield"></i>
                    </div>
                    <div class="stats-number">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) as count FROM armor");
                        $row = $result->fetch_assoc();
                        echo number_format($row['count']);
                        ?>
                    </div>
                    <div class="stats-label">Armor Items</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="admin-stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-dragon"></i>
                    </div>
                    <div class="stats-number">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) as count FROM npc");
                        $row = $result->fetch_assoc();
                        echo number_format($row['count']);
                        ?>
                    </div>
                    <div class="stats-label">NPCs & Monsters</div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="admin-stats-card">
                    <div class="stats-icon">
                        <i class="fas fa-magic"></i>
                    </div>
                    <div class="stats-number">
                        <?php
                        $result = $conn->query("SELECT COUNT(*) as count FROM skills");
                        $row = $result->fetch_assoc();
                        echo number_format($row['count']);
                        ?>
                    </div>
                    <div class="stats-label">Skills</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="py-4">
    <div class="container">
        <h2 class="section-title mb-4">Quick Actions</h2>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="backup.php" class="card text-decoration-none h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <div class="mb-3" style="width: 50px; height: 50px; background-color: var(--accent-transparent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-database" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="card-title">Backup Database</h5>
                        <p class="card-text small text-white-50">Create a full backup of the database</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="logs.php" class="card text-decoration-none h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <div class="mb-3" style="width: 50px; height: 50px; background-color: var(--accent-transparent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-list" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="card-title">View Logs</h5>
                        <p class="card-text small text-white-50">Check system and error logs</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="users.php" class="card text-decoration-none h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <div class="mb-3" style="width: 50px; height: 50px; background-color: var(--accent-transparent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="card-title">Manage Users</h5>
                        <p class="card-text small text-white-50">Add or edit admin users</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="settings.php" class="card text-decoration-none h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <div class="mb-3" style="width: 50px; height: 50px; background-color: var(--accent-transparent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-cog" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="card-title">Settings</h5>
                        <p class="card-text small text-white-50">Configure system settings</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Admin Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title mb-4">Database Management</h2>
        
        <div class="row g-4">
            <?php 
            $categories = getCategories();
            
            foreach($categories as $category): 
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo $category['name']; ?></h5>
                            <span class="badge bg-accent"><?php echo count($category['sql']); ?> tables</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="card-text"><?php echo getCategoryDescription($category['id']); ?></p>
                            <p class="small text-muted mb-3">SQL Tables: <?php echo implode(', ', $category['sql']); ?></p>
                            <div class="mt-auto d-flex">
                                <a href="<?php echo $category['id']; ?>/list.php" class="btn btn-accent me-2">Manage</a>
                                <a href="<?php echo $category['id']; ?>/create.php" class="btn btn-outline-light">Add New</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Recent Activity -->
<section class="py-5" style="background-color: var(--primary);">
    <div class="container">
        <h2 class="section-title mb-4">Recent Activity</h2>
        
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Action</th>
                            <th>Table</th>
                            <th>Item</th>
                            <th>User</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- This would be populated from a database table that tracks admin actions -->
                        <tr>
                            <td><span class="badge bg-success">Added</span></td>
                            <td>weapons</td>
                            <td>Mythril Sword</td>
                            <td>admin</td>
                            <td><?php echo date('Y-m-d H:i', time() - 3600); ?></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-warning">Updated</span></td>
                            <td>armor</td>
                            <td>Dragon Scale Armor</td>
                            <td>admin</td>
                            <td><?php echo date('Y-m-d H:i', time() - 7200); ?></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-danger">Deleted</span></td>
                            <td>items</td>
                            <td>Damaged Potion</td>
                            <td>admin</td>
                            <td><?php echo date('Y-m-d H:i', time() - 10800); ?></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-accent">Viewed</span></td>
                            <td>monsters</td>
                            <td>Ancient Dragon</td>
                            <td>admin</td>
                            <td><?php echo date('Y-m-d H:i', time() - 14400); ?></td>
                        </tr>
                        <tr>
                            <td><span class="badge bg-success">Added</span></td>
                            <td>skills</td>
                            <td>Flame Strike</td>
                            <td>admin</td>
                            <td><?php echo date('Y-m-d H:i', time() - 18000); ?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/admin_footer.php'; ?>
