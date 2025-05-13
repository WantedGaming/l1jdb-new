<?php include 'includes/header.php'; ?>

<!-- Modern Hero Section with Search Bar -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">L1J <span>Remastered</span> Database</h1>
                <p class="hero-subtitle">Explore the comprehensive database of L1J Remastered game data, including weapons, armor, items, monsters and more.</p>
                <form class="search-form" action="search.php" method="GET">
                    <div class="position-relative">
                        <input class="form-control" type="search" name="q" placeholder="Search items, monsters, skills..." aria-label="Search">
                        <button class="btn btn-accent" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Main Categories -->
<section class="category-section py-5">
    <div class="container">
        <h2 class="section-title text-center mb-5">Database Categories</h2>
        
        <div class="row g-4">
            <?php 
            $categories = getCategories();
            
            foreach($categories as $category): 
            ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="<?php echo getCategoryImage($category['id']); ?>" class="card-img-top" alt="<?php echo $category['name']; ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $category['name']; ?></h5>
                            <p class="card-text"><?php echo getCategoryDescription($category['id']); ?></p>
                            <div class="mt-auto">
                                <a href="<?php echo $category['id']; ?>_list.php" class="btn btn-accent">Explore</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="py-5" style="background-color: var(--primary);">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2 class="mb-4">What is L1J Remastered?</h2>
                <p>L1J Remastered is a modern implementation of the classic Lineage 1 MMORPG game server. This database browser provides comprehensive information about all game elements, helping players explore and understand the game mechanics.</p>
                <p>Use this resource to discover weapons, armor, items, monsters, skills, and more from the game world. Whether you're a new player learning the ropes or a veteran seeking specific information, this database has you covered.</p>
                <a href="#" class="btn btn-accent mt-2">Learn More</a>
            </div>
            <div class="col-lg-6">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">Getting Started</h5>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <span class="badge bg-accent" style="font-size: 1.2rem;">1</span>
                            </div>
                            <div>
                                <h6 class="mb-0">Browse Categories</h6>
                                <small class="text-muted">Explore different sections of the database</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <div class="me-3">
                                <span class="badge bg-accent" style="font-size: 1.2rem;">2</span>
                            </div>
                            <div>
                                <h6 class="mb-0">Search</h6>
                                <small class="text-muted">Find specific items using the search feature</small>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <span class="badge bg-accent" style="font-size: 1.2rem;">3</span>
                            </div>
                            <div>
                                <h6 class="mb-0">View Details</h6>
                                <small class="text-muted">Get comprehensive information about each item</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recent Updates Section -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title mb-4">Recent Updates</h2>
        <div class="row">
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Database Updates</h5>
                            <span class="badge bg-accent">New</span>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-circle me-2" style="font-size: 8px; color: var(--accent);"></i>
                                <span>Added 50+ new weapons with detailed statistics</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-circle me-2" style="font-size: 8px; color: var(--accent);"></i>
                                <span>Updated armor sets with correct bonuses</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-circle me-2" style="font-size: 8px; color: var(--accent);"></i>
                                <span>Improved monster drop rate information</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="fas fa-circle me-2" style="font-size: 8px; color: var(--accent);"></i>
                                <span>Fixed skill descriptions and effects</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="card-title mb-0">Game Updates</h5>
                            <span class="badge bg-accent">Latest</span>
                        </div>
                        <ul class="list-unstyled">
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-circle me-2" style="font-size: 8px; color: var(--accent);"></i>
                                <span>New dungeon area with exclusive rewards</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-circle me-2" style="font-size: 8px; color: var(--accent);"></i>
                                <span>Balance adjustments for several classes</span>
                            </li>
                            <li class="mb-2 d-flex align-items-center">
                                <i class="fas fa-circle me-2" style="font-size: 8px; color: var(--accent);"></i>
                                <span>Added new quest lines for mid-level players</span>
                            </li>
                            <li class="d-flex align-items-center">
                                <i class="fas fa-circle me-2" style="font-size: 8px; color: var(--accent);"></i>
                                <span>Updated PvP system with improved rewards</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>