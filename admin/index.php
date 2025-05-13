<?php include '../includes/admin_header.php'; ?>

<!-- Admin Hero Section with Search Bar -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="mb-4">Admin Dashboard</h1>
                <p class="lead mb-4">Manage and modify the L1J Remastered database content.</p>
                <form class="d-flex search-form" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search database items..." aria-label="Search">
                    <button class="btn btn-accent" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Admin Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Database Management</h2>
        
        <div class="row">
            <?php 
            $categories = getCategories();
            $counter = 0;
            
            foreach($categories as $category): 
                $counter++;
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card admin-card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo $category['name']; ?></h5>
                            <span class="badge bg-accent"><?php echo count($category['sql']); ?> tables</span>
                        </div>
                        <div class="card-body">
                            <p class="card-text"><?php echo getCategoryDescription($category['id']); ?></p>
                            <small class="text-muted">SQL Tables: <?php echo implode(', ', $category['sql']); ?></small>
                            <div class="mt-3 d-flex">
                                <a href="<?php echo $category['id']; ?>/list.php" class="btn btn-accent me-2">Manage</a>
                                <a href="<?php echo $category['id']; ?>/create.php" class="btn btn-outline-light">Add New</a>
                            </div>
                        </div>
                    </div>
                </div>
                
                <?php
                // Start a new row after every 3 cards
                if($counter % 3 == 0 && $counter < count($categories)): 
                ?>
                    </div><div class="row">
                <?php endif; ?>
                
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Database Statistics -->
<section class="py-5 bg-dark">
    <div class="container">
        <h2 class="text-center mb-5">Database Statistics</h2>
        
        <div class="row text-center">
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="display-4 text-accent">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as count FROM weapon");
                            $row = $result->fetch_assoc();
                            echo number_format($row['count']);
                            ?>
                        </h3>
                        <p class="card-text">Weapons</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="display-4 text-accent">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as count FROM armor");
                            $row = $result->fetch_assoc();
                            echo number_format($row['count']);
                            ?>
                        </h3>
                        <p class="card-text">Armor Items</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="display-4 text-accent">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as count FROM npc");
                            $row = $result->fetch_assoc();
                            echo number_format($row['count']);
                            ?>
                        </h3>
                        <p class="card-text">NPCs & Monsters</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="display-4 text-accent">
                            <?php
                            $result = $conn->query("SELECT COUNT(*) as count FROM skills");
                            $row = $result->fetch_assoc();
                            echo number_format($row['count']);
                            ?>
                        </h3>
                        <p class="card-text">Skills</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/admin_footer.php'; ?>
