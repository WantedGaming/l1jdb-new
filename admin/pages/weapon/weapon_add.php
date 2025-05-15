<?php include '../../../includes/admin_header.php'; ?>

<?php
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect all form fields
    $item_id = isset($_POST['item_id']) ? intval($_POST['item_id']) : 0;
    $desc_en = sanitizeInput($_POST['desc_en']);
    $type = sanitizeInput($_POST['type']);
    $material = sanitizeInput($_POST['material']);
    $weight = intval($_POST['weight']);
    $dmg_small = intval($_POST['dmg_small']);
    $dmg_large = intval($_POST['dmg_large']);
    $safenchant = intval($_POST['safenchant']);
    $hitmodifier = intval($_POST['hitmodifier']);
    $dmgmodifier = intval($_POST['dmgmodifier']);
    $double_dmg_chance = intval($_POST['double_dmg_chance']);
    $min_lvl = intval($_POST['min_lvl']);
    $max_lvl = intval($_POST['max_lvl']);
    $magicdmgmodifier = intval($_POST['magicdmgmodifier']);
    $add_str = intval($_POST['add_str']);
    $add_dex = intval($_POST['add_dex']);
    $add_con = intval($_POST['add_con']);
    $add_int = intval($_POST['add_int']);
    $add_wis = intval($_POST['add_wis']);
    $add_cha = intval($_POST['add_cha']);
    $add_hp = intval($_POST['add_hp']);
    $add_mp = intval($_POST['add_mp']);
    $add_hpr = intval($_POST['add_hpr']);
    $add_mpr = intval($_POST['add_mpr']);
    $add_sp = intval($_POST['add_sp']);
    $m_def = intval($_POST['m_def']);
    $bless = isset($_POST['bless']) ? 1 : 0;
    $trade = isset($_POST['trade']) ? 1 : 0;
    $cant_delete = isset($_POST['cant_delete']) ? 1 : 0;
    $cant_sell = isset($_POST['cant_sell']) ? 1 : 0;
    $max_use_time = intval($_POST['max_use_time']);
    $regist_skill = intval($_POST['regist_skill']);
    $regist_spirit = intval($_POST['regist_spirit']);
    $regist_dragon = intval($_POST['regist_dragon']);
    $regist_fear = intval($_POST['regist_fear']);
    $regist_all = intval($_POST['regist_all']);
    $hitup_skill = intval($_POST['hitup_skill']);
    $hitup_spirit = intval($_POST['hitup_spirit']);
    $hitup_dragon = intval($_POST['hitup_dragon']);
    $hitup_fear = intval($_POST['hitup_fear']);
    $hitup_all = intval($_POST['hitup_all']);
    $hitup_magic = intval($_POST['hitup_magic']);
    $iconId = intval($_POST['iconId']);
    $poisonRegist = isset($_POST['poisonRegist']) ? 'true' : 'false';
    $haste_item = isset($_POST['haste_item']) ? 1 : 0;
    $shortCritical = intval($_POST['shortCritical']);
    $longCritical = intval($_POST['longCritical']);
    $magicCritical = intval($_POST['magicCritical']);
    $expBonus = intval($_POST['expBonus']);
    $PVPDamage = intval($_POST['PVPDamage']);
    $PVPDamagePercent = intval($_POST['PVPDamagePercent']);
    $PVPDamageReduction = intval($_POST['PVPDamageReduction']);
    $PVPDamageReductionPercent = intval($_POST['PVPDamageReductionPercent']);
    $PVPMagicDamageReduction = intval($_POST['PVPMagicDamageReduction']);
    
    // Added new fields
    $spriteId = intval($_POST['spriteId']);
    $itemGrade = sanitizeInput($_POST['itemGrade']);
    $note = sanitizeInput($_POST['note']);
    
    // Class restrictions
    $use_royal = isset($_POST['use_royal']) ? 1 : 0;
    $use_knight = isset($_POST['use_knight']) ? 1 : 0;
    $use_mage = isset($_POST['use_mage']) ? 1 : 0;
    $use_elf = isset($_POST['use_elf']) ? 1 : 0;
    $use_darkelf = isset($_POST['use_darkelf']) ? 1 : 0;
    $use_dragonknight = isset($_POST['use_dragonknight']) ? 1 : 0;
    $use_illusionist = isset($_POST['use_illusionist']) ? 1 : 0;
    $use_warrior = isset($_POST['use_warrior']) ? 1 : 0;
    $use_fencer = isset($_POST['use_fencer']) ? 1 : 0;
    $use_lancer = isset($_POST['use_lancer']) ? 1 : 0;
    
    // Check if ID already exists
    if ($item_id > 0) {
        $check_sql = "SELECT COUNT(*) as count FROM weapon WHERE item_id = $item_id";
        $check_result = $conn->query($check_sql);
        $check_row = $check_result->fetch_assoc();
        
        if ($check_row['count'] > 0) {
            $_SESSION['message'] = "A weapon with ID $item_id already exists. Please choose a different ID.";
            $_SESSION['message_type'] = "danger";
        } else {
            // Build the insert query with many fields
            $insert_sql = "INSERT INTO weapon (
                item_id, desc_en, type, material, weight, dmg_small, dmg_large,
                safenchant, hitmodifier, dmgmodifier, double_dmg_chance,
                min_lvl, max_lvl, magicdmgmodifier, add_str, add_dex,
                add_con, add_int, add_wis, add_cha, add_hp, add_mp,
                add_hpr, add_mpr, add_sp, m_def, bless, trade,
                cant_delete, cant_sell, max_use_time, regist_skill, regist_spirit,
                regist_dragon, regist_fear, regist_all, hitup_skill, hitup_spirit,
                hitup_dragon, hitup_fear, hitup_all, hitup_magic, iconId, 
                poisonRegist, haste_item, shortCritical, longCritical, magicCritical,
                expBonus, PVPDamage, PVPDamagePercent, PVPDamageReduction, 
                PVPDamageReductionPercent, PVPMagicDamageReduction, spriteId, itemGrade, note,
                use_royal, use_knight, use_mage, use_elf, use_darkelf,
                use_dragonknight, use_illusionist, use_warrior, use_fencer, use_lancer
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?,
                ?, ?, ?, ?, ?,
                ?, ?, ?, ?, ?
            )";
                
            $stmt = $conn->prepare($insert_sql);
            
            if ($stmt) {
                $stmt->bind_param(
                    "isssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiisiiiiiiiiiisiissiiiiiiiii",
                    $item_id, $desc_en, $type, $material, $weight, $dmg_small, $dmg_large,
                    $safenchant, $hitmodifier, $dmgmodifier, $double_dmg_chance,
                    $min_lvl, $max_lvl, $magicdmgmodifier, $add_str, $add_dex,
                    $add_con, $add_int, $add_wis, $add_cha, $add_hp, $add_mp,
                    $add_hpr, $add_mpr, $add_sp, $m_def, $bless, $trade,
                    $cant_delete, $cant_sell, $max_use_time, $regist_skill, $regist_spirit,
                    $regist_dragon, $regist_fear, $regist_all, $hitup_skill, $hitup_spirit,
                    $hitup_dragon, $hitup_fear, $hitup_all, $hitup_magic, $iconId,
                    $poisonRegist, $haste_item, $shortCritical, $longCritical, $magicCritical,
                    $expBonus, $PVPDamage, $PVPDamagePercent, $PVPDamageReduction,
                    $PVPDamageReductionPercent, $PVPMagicDamageReduction, $spriteId, $itemGrade, $note,
                    $use_royal, $use_knight, $use_mage, $use_elf, $use_darkelf,
                    $use_dragonknight, $use_illusionist, $use_warrior, $use_fencer, $use_lancer
                );
                
                $stmt->execute();
                
                if ($stmt->affected_rows > 0) {
                    $_SESSION['message'] = "Weapon added successfully!";
                    $_SESSION['message_type'] = "success";
                    header("Location: weapon_list.php");
                    exit;
                } else {
                    $_SESSION['message'] = "Failed to add weapon: " . $conn->error;
                    $_SESSION['message_type'] = "danger";
                }
                
                $stmt->close();
            } else {
                $_SESSION['message'] = "Error preparing statement: " . $conn->error;
                $_SESSION['message_type'] = "danger";
            }
        }
    } else {
        $_SESSION['message'] = "Please enter a valid item ID";
        $_SESSION['message_type'] = "danger";
    }
}

// Generate a suggested new item ID (highest existing + 1)
$id_sql = "SELECT MAX(item_id) as max_id FROM weapon";
$id_result = $conn->query($id_sql);
$id_row = $id_result->fetch_assoc();
$suggested_id = ($id_row['max_id'] ?? 0) + 1;
?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Add <span>New Weapon</span></h1>
                <p class="hero-subtitle">Create a new weapon in the database</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">New Weapon Details</h5>
                <div>
                    <a href="weapon_list.php" class="btn btn-sm btn-outline-light">Back to List</a>
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
                
                <form action="" method="POST">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="mb-3">
                                <label for="item_id" class="form-label">Item ID</label>
                                <input type="number" class="form-control filter-select" id="item_id" name="item_id" value="<?php echo $suggested_id; ?>" required>
                                <small class="text-muted">Suggested ID: <?php echo $suggested_id; ?></small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="desc_en" class="form-label">Weapon Name</label>
                                <input type="text" class="form-control filter-select" id="desc_en" name="desc_en" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="note" class="form-label">Weapon Description/Note</label>
                                <textarea class="form-control filter-select" id="note" name="note" rows="3"></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="itemGrade" class="form-label">Item Grade</label>
                                <select class="form-control filter-select" id="itemGrade" name="itemGrade" required>
                                    <option value="NORMAL">Normal</option>
                                    <option value="ADVANC">Advanced</option>
                                    <option value="RARE">Rare</option>
                                    <option value="HERO">Hero</option>
                                    <option value="LEGEND">Legend</option>
                                    <option value="MYTH">Myth</option>
                                    <option value="ONLY">Only</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="type" class="form-label">Weapon Type</label>
                                <select class="form-control filter-select" id="type" name="type" required>
                                    <option value="SWORD">Sword</option>
                                    <option value="DAGGER">Dagger</option>
                                    <option value="TOHAND_SWORD">Two-Handed Sword</option>
                                    <option value="BOW">Bow</option>
                                    <option value="SPEAR">Spear</option>
                                    <option value="BLUNT">Blunt</option>
                                    <option value="STAFF">Staff</option>
                                    <option value="EDORYU">Edoryu</option>
                                    <option value="CLAW">Claw</option>
                                    <option value="STING">Sting</option>
                                    <option value="CHAINSWORD">Chain Sword</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="material" class="form-label">Material</label>
                                <select class="form-control filter-select" id="material" name="material" required>
                                    <option value="IRON(철)">Iron</option>
                                    <option value="STEEL(강철)">Steel</option>
                                    <option value="WOOD(나무)">Wood</option>
                                    <option value="SILVER(은)">Silver</option>
                                    <option value="GOLD(금)">Gold</option>
                                    <option value="COPPER(구리)">Copper</option>
                                    <option value="PLATINUM(백금)">Platinum</option>
                                    <option value="MITHRIL(미스릴)">Mithril</option>
                                    <option value="PLASTIC(블랙미스릴)">Black Mithril</option>
                                    <option value="GEMSTONE(보석)">Gemstone</option>
                                    <option value="ORIHARUKON(오리하루콘)">Oriharukon</option>
                                    <option value="DRANIUM(드라니움)">Dranium</option>
                                    <option value="NONE(-)">None</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="weight" class="form-label">Weight</label>
                                <input type="number" class="form-control filter-select" id="weight" name="weight" value="0" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="iconId" class="form-label">Icon ID</label>
                                    <input type="number" class="form-control filter-select" id="iconId" name="iconId" value="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="spriteId" class="form-label">Sprite ID</label>
                                    <input type="number" class="form-control filter-select" id="spriteId" name="spriteId" value="0" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="dmg_small" class="form-label">Damage (Small)</label>
                                    <input type="number" class="form-control filter-select" id="dmg_small" name="dmg_small" value="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dmg_large" class="form-label">Damage (Large)</label>
                                    <input type="number" class="form-control filter-select" id="dmg_large" name="dmg_large" value="0" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="safenchant" class="form-label">Safe Enchant</label>
                                <input type="number" class="form-control filter-select" id="safenchant" name="safenchant" value="0" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="hitmodifier" class="form-label">Hit Modifier</label>
                                    <input type="number" class="form-control filter-select" id="hitmodifier" name="hitmodifier" value="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dmgmodifier" class="form-label">Damage Modifier</label>
                                    <input type="number" class="form-control filter-select" id="dmgmodifier" name="dmgmodifier" value="0" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="double_dmg_chance" class="form-label">Double Damage Chance (%)</label>
                                <input type="number" class="form-control filter-select" id="double_dmg_chance" name="double_dmg_chance" value="0" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="min_lvl" class="form-label">Min Level</label>
                                    <input type="number" class="form-control filter-select" id="min_lvl" name="min_lvl" value="0" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="max_lvl" class="form-label">Max Level</label>
                                    <input type="number" class="form-control filter-select" id="max_lvl" name="max_lvl" value="0" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="magicdmgmodifier" class="form-label">Magic Damage Modifier</label>
                                <input type="number" class="form-control filter-select" id="magicdmgmodifier" name="magicdmgmodifier" value="0" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="max_use_time" class="form-label">Max Use Time (seconds, 0 for unlimited)</label>
                                <input type="number" class="form-control filter-select" id="max_use_time" name="max_use_time" value="0" required>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="bless" name="bless">
                                        <label class="form-check-label" for="bless">
                                            Bless
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="trade" name="trade" checked>
                                        <label class="form-check-label" for="trade">
                                            Tradeable
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="cant_delete" name="cant_delete">
                                        <label class="form-check-label" for="cant_delete">
                                            Can't Delete
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="cant_sell" name="cant_sell">
                                        <label class="form-check-label" for="cant_sell">
                                            Can't Sell
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-4">
                            <h5 class="mb-3">Stat Bonuses</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="add_str" class="form-label">STR</label>
                                    <input type="number" class="form-control filter-select" id="add_str" name="add_str" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_dex" class="form-label">DEX</label>
                                    <input type="number" class="form-control filter-select" id="add_dex" name="add_dex" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_con" class="form-label">CON</label>
                                    <input type="number" class="form-control filter-select" id="add_con" name="add_con" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_int" class="form-label">INT</label>
                                    <input type="number" class="form-control filter-select" id="add_int" name="add_int" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_wis" class="form-label">WIS</label>
                                    <input type="number" class="form-control filter-select" id="add_wis" name="add_wis" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_cha" class="form-label">CHA</label>
                                    <input type="number" class="form-control filter-select" id="add_cha" name="add_cha" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_hp" class="form-label">HP</label>
                                    <input type="number" class="form-control filter-select" id="add_hp" name="add_hp" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_mp" class="form-label">MP</label>
                                    <input type="number" class="form-control filter-select" id="add_mp" name="add_mp" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_hpr" class="form-label">HP Regen</label>
                                    <input type="number" class="form-control filter-select" id="add_hpr" name="add_hpr" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_mpr" class="form-label">MP Regen</label>
                                    <input type="number" class="form-control filter-select" id="add_mpr" name="add_mpr" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_sp" class="form-label">SP</label>
                                    <input type="number" class="form-control filter-select" id="add_sp" name="add_sp" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="m_def" class="form-label">M. Def</label>
                                    <input type="number" class="form-control filter-select" id="m_def" name="m_def" value="0" required>
                                </div>
                            </div>
                            
                            <h5 class="mb-3">Resistances</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="regist_skill" class="form-label">Skill</label>
                                    <input type="number" class="form-control filter-select" id="regist_skill" name="regist_skill" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="regist_spirit" class="form-label">Spirit</label>
                                    <input type="number" class="form-control filter-select" id="regist_spirit" name="regist_spirit" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="regist_dragon" class="form-label">Dragon</label>
                                    <input type="number" class="form-control filter-select" id="regist_dragon" name="regist_dragon" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="regist_fear" class="form-label">Fear</label>
                                    <input type="number" class="form-control filter-select" id="regist_fear" name="regist_fear" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="regist_all" class="form-label">All</label>
                                    <input type="number" class="form-control filter-select" id="regist_all" name="regist_all" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" value="true" id="poisonRegist" name="poisonRegist">
                                        <label class="form-check-label" for="poisonRegist">
                                            Poison Resist
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <h5 class="mb-3">Hit Modifiers</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_skill" class="form-label">Skill</label>
                                    <input type="number" class="form-control filter-select" id="hitup_skill" name="hitup_skill" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_spirit" class="form-label">Spirit</label>
                                    <input type="number" class="form-control filter-select" id="hitup_spirit" name="hitup_spirit" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_dragon" class="form-label">Dragon</label>
                                    <input type="number" class="form-control filter-select" id="hitup_dragon" name="hitup_dragon" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_fear" class="form-label">Fear</label>
                                    <input type="number" class="form-control filter-select" id="hitup_fear" name="hitup_fear" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_all" class="form-label">All</label>
                                    <input type="number" class="form-control filter-select" id="hitup_all" name="hitup_all" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_magic" class="form-label">Magic</label>
                                    <input type="number" class="form-control filter-select" id="hitup_magic" name="hitup_magic" value="0" required>
                                </div>
                            </div>
                            
                            <h5 class="mb-3">PVP & Critical Stats</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="shortCritical" class="form-label">Short Critical</label>
                                    <input type="number" class="form-control filter-select" id="shortCritical" name="shortCritical" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="longCritical" class="form-label">Long Critical</label>
                                    <input type="number" class="form-control filter-select" id="longCritical" name="longCritical" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="magicCritical" class="form-label">Magic Critical</label>
                                    <input type="number" class="form-control filter-select" id="magicCritical" name="magicCritical" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="expBonus" class="form-label">Exp Bonus (%)</label>
                                    <input type="number" class="form-control filter-select" id="expBonus" name="expBonus" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPDamage" class="form-label">PVP Damage</label>
                                    <input type="number" class="form-control filter-select" id="PVPDamage" name="PVPDamage" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPDamagePercent" class="form-label">PVP Damage %</label>
                                    <input type="number" class="form-control filter-select" id="PVPDamagePercent" name="PVPDamagePercent" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPDamageReduction" class="form-label">PVP DMG Reduction</label>
                                    <input type="number" class="form-control filter-select" id="PVPDamageReduction" name="PVPDamageReduction" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPDamageReductionPercent" class="form-label">PVP DMG Reduction %</label>
                                    <input type="number" class="form-control filter-select" id="PVPDamageReductionPercent" name="PVPDamageReductionPercent" value="0" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPMagicDamageReduction" class="form-label">PVP Magic DMG Reduction</label>
                                    <input type="number" class="form-control filter-select" id="PVPMagicDamageReduction" name="PVPMagicDamageReduction" value="0" required>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="haste_item" name="haste_item">
                                <label class="form-check-label" for="haste_item">
                                    Haste Item
                                </label>
                            </div>
                            
                            <h5 class="mb-3">Class Restrictions</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_royal" name="use_royal" checked>
                                        <label class="form-check-label" for="use_royal">
                                            Royal
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_knight" name="use_knight" checked>
                                        <label class="form-check-label" for="use_knight">
                                            Knight
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_mage" name="use_mage" checked>
                                        <label class="form-check-label" for="use_mage">
                                            Mage
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_elf" name="use_elf" checked>
                                        <label class="form-check-label" for="use_elf">
                                            Elf
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_darkelf" name="use_darkelf" checked>
                                        <label class="form-check-label" for="use_darkelf">
                                            Dark Elf
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_dragonknight" name="use_dragonknight" checked>
                                        <label class="form-check-label" for="use_dragonknight">
                                            Dragon Knight
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_illusionist" name="use_illusionist" checked>
                                        <label class="form-check-label" for="use_illusionist">
                                            Illusionist
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_warrior" name="use_warrior" checked>
                                        <label class="form-check-label" for="use_warrior">
                                            Warrior
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_fencer" name="use_fencer" checked>
                                        <label class="form-check-label" for="use_fencer">
                                            Fencer
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_lancer" name="use_lancer" checked>
                                        <label class="form-check-label" for="use_lancer">
                                            Lancer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-accent">Add Weapon</button>
                        <a href="weapon_list.php" class="btn btn-outline-light ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include '../../../includes/admin_footer.php'; ?>