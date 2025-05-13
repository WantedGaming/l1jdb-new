<?php include 'includes/header.php'; ?>

<!-- Hero Section with Search Bar -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 text-center">
                <h1 class="mb-4">L1J Remastered Database</h1>
                <p class="lead mb-4">Explore the comprehensive database of L1J Remastered game data.</p>
                <form class="d-flex search-form" action="search.php" method="GET">
                    <input class="form-control me-2" type="search" name="q" placeholder="Search items, monsters, skills..." aria-label="Search">
                    <button class="btn btn-accent" type="submit">Search</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Main Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="text-center mb-5">Database Categories</h2>
        
        <div class="row">
            <?php 
            $categories = getCategories();
            $counter = 0;
            
            foreach($categories as $category): 
                $counter++;
            ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <img src="<?php echo getCategoryImage($category['id']); ?>" class="card-img-top" alt="<?php echo $category['name']; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $category['name']; ?></h5>
                            <p class="card-text"><?php echo getCategoryDescription($category['id']); ?></p>
                            <a href="<?php echo $category['id']; ?>/list.php" class="btn btn-accent">Explore</a>
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

<?php include 'includes/footer.php'; ?>
