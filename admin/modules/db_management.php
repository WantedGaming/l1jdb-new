<?php
// The session check is now handled in admin_header.php
// We don't need a duplicate check here, but we'll keep it safe
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: /l1jdb-new/admin/login.php");
    exit;
}
?>

<div class="row g-4">
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Weapons Management</h5>
                <span class="badge bg-accent">CRUD</span>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="card-text">Manage all weapons in the game database. Add, edit, and delete weapons with all their properties and stats.</p>
                <div class="mt-auto d-flex">
                    <a href="pages/weapon/weapon_list.php" class="btn btn-accent me-2">Manage</a>
                    <a href="pages/weapon/weapon_add.php" class="btn btn-outline-light">Add New</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Armor Management</h5>
                <span class="badge bg-accent">CRUD</span>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="card-text">Manage all armor pieces in the game database. Add, edit, and delete armor with all their properties and stats.</p>
                <div class="mt-auto d-flex">
                    <a href="pages/armor/armor_list.php" class="btn btn-accent me-2">Manage</a>
                    <a href="pages/armor/armor_add.php" class="btn btn-outline-light">Add New</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Items Management</h5>
                <span class="badge bg-accent">CRUD</span>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="card-text">Manage all items in the game database. Add, edit, and delete items with all their properties and stats.</p>
                <div class="mt-auto d-flex">
                    <a href="pages/items/items_list.php" class="btn btn-accent me-2">Manage</a>
                    <a href="pages/items/items_add.php" class="btn btn-outline-light">Add New</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Monsters Management</h5>
                <span class="badge bg-accent">CRUD</span>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="card-text">Manage all monsters in the game database. Add, edit, and delete monsters with all their properties and behaviors.</p>
                <div class="mt-auto d-flex">
                    <a href="pages/monsters/monsters_list.php" class="btn btn-accent me-2">Manage</a>
                    <a href="pages/monsters/monsters_add.php" class="btn btn-outline-light">Add New</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Maps Management</h5>
                <span class="badge bg-accent">CRUD</span>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="card-text">Manage all maps in the game database. Add, edit, and delete maps with all their properties and spawn points.</p>
                <div class="mt-auto d-flex">
                    <a href="pages/maps/maps_list.php" class="btn btn-accent me-2">Manage</a>
                    <a href="pages/maps/maps_add.php" class="btn btn-outline-light">Add New</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4 col-md-6">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Drop Lists</h5>
                <span class="badge bg-accent">CRUD</span>
            </div>
            <div class="card-body d-flex flex-column">
                <p class="card-text">Manage the item drop lists for monsters. Add, edit, and delete drops from monsters with customizable drop rates.</p>
                <div class="mt-auto d-flex">
                    <a href="pages/drops/drops_list.php" class="btn btn-accent me-2">Manage</a>
                    <a href="pages/drops/drops_add.php" class="btn btn-outline-light">Add New</a>
                </div>
            </div>
        </div>
    </div>
</div>