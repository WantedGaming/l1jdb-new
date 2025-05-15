<?php
// Include security check with automatic path detection
require_once '../../../admin/secure.php';

// Then include the regular header
include '../../../includes/admin_header.php'; 

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['message'] = "No weapon ID specified";
    $_SESSION['message_type'] = "danger";
    header("Location: weapon_list.php");
    exit;
}

$weapon_id = intval($_GET['id']);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect all form fields
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
    
    // Build the update query with many fields
    $update_sql = "UPDATE weapon SET 
        desc_en = ?, type = ?, material = ?, weight = ?, dmg_small = ?, dmg_large = ?,
        safenchant = ?, hitmodifier = ?, dmgmodifier = ?, double_dmg_chance = ?,
        min_lvl = ?, max_lvl = ?, magicdmgmodifier = ?, add_str = ?, add_dex = ?,
        add_con = ?, add_int = ?, add_wis = ?, add_cha = ?, add_hp = ?, add_mp = ?,
        add_hpr = ?, add_mpr = ?, add_sp = ?, m_def = ?, bless = ?, trade = ?,
        cant_delete = ?, cant_sell = ?, max_use_time = ?, regist_skill = ?, regist_spirit = ?,
        regist_dragon = ?, regist_fear = ?, regist_all = ?, hitup_skill = ?, hitup_spirit = ?,
        hitup_dragon = ?, hitup_fear = ?, hitup_all = ?, hitup_magic = ?, iconId = ?, 
        poisonRegist = ?, haste_item = ?, shortCritical = ?, longCritical = ?, magicCritical = ?,
        expBonus = ?, PVPDamage = ?, PVPDamagePercent = ?, PVPDamageReduction = ?, 
        PVPDamageReductionPercent = ?, PVPMagicDamageReduction = ?, spriteId = ?, itemGrade = ?, note = ?,
        use_royal = ?, use_knight = ?, use_mage = ?, use_elf = ?, use_darkelf = ?,
        use_dragonknight = ?, use_illusionist = ?, use_warrior = ?, use_fencer = ?, use_lancer = ?
        WHERE item_id = ?";
        
    $stmt = $conn->prepare($update_sql);
    
    if ($stmt) {
        $stmt->bind_param(
            "sssiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiiisiiiiiiiiiisiissiiiiiiiii",
            $desc_en, $type, $material, $weight, $dmg_small, $dmg_large,
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
            $use_dragonknight, $use_illusionist, $use_warrior, $use_fencer, $use_lancer,
            $weapon_id
        );
        
        $stmt->execute();
        
        if ($stmt->affected_rows > 0) {
            $_SESSION['message'] = "Weapon updated successfully!";
            $_SESSION['message_type'] = "success";
        } else {
            $_SESSION['message'] = "No changes made or update failed";
            $_SESSION['message_type'] = "warning";
        }
        
        $stmt->close();
        
        header("Location: weapon_list.php");
        exit;
    } else {
        $_SESSION['message'] = "Error preparing statement: " . $conn->error;
        $_SESSION['message_type'] = "danger";
    }
}

// Fetch the weapon data
$sql = "SELECT * FROM weapon WHERE item_id = $weapon_id";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    $_SESSION['message'] = "Weapon not found";
    $_SESSION['message_type'] = "danger";
    header("Location: weapon_list.php");
    exit;
}

$weapon = $result->fetch_assoc();
$cleanWeaponName = cleanItemName($weapon['desc_en']);
?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Edit <span>Weapon</span></h1>
                <p class="hero-subtitle">Editing: <?php echo htmlspecialchars($cleanWeaponName); ?> (ID: <?php echo $weapon_id; ?>)</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Weapon Details</h5>
                <div>
                    <a href="weapon_list.php" class="btn btn-sm btn-outline-light">Back to List</a>
                    <a href="../../pages/weapon/weapon_detail.php?id=<?php echo $weapon_id; ?>" class="btn btn-sm btn-accent" target="_blank">View Weapon Details</a>
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
                            <div class="text-center mb-4">
                                <img src="/l1jdb-new/assets/img/icons/<?php echo $weapon['iconId']; ?>.png" 
                                     alt="<?php echo htmlspecialchars($cleanWeaponName); ?>" 
                                     class="weapon-image" 
                                     onerror="this.src='/l1jdb-new/assets/img/icons/9175.png';">
                            </div>
                            
                            <div class="mb-3">
                                <label for="desc_en" class="form-label">Weapon Name</label>
                                <input type="text" class="form-control filter-select" id="desc_en" name="desc_en" value="<?php echo htmlspecialchars($weapon['desc_en']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="note" class="form-label">Weapon Description/Note</label>
                                <textarea class="form-control filter-select" id="note" name="note" rows="3"><?php echo htmlspecialchars($weapon['note']); ?></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="itemGrade" class="form-label">Item Grade</label>
                                <select class="form-control filter-select" id="itemGrade" name="itemGrade" required>
                                    <option value="NORMAL" <?php echo ($weapon['itemGrade'] == 'NORMAL') ? 'selected' : ''; ?>>Normal</option>
                                    <option value="ADVANC" <?php echo ($weapon['itemGrade'] == 'ADVANC') ? 'selected' : ''; ?>>Advanced</option>
                                    <option value="RARE" <?php echo ($weapon['itemGrade'] == 'RARE') ? 'selected' : ''; ?>>Rare</option>
                                    <option value="HERO" <?php echo ($weapon['itemGrade'] == 'HERO') ? 'selected' : ''; ?>>Hero</option>
                                    <option value="LEGEND" <?php echo ($weapon['itemGrade'] == 'LEGEND') ? 'selected' : ''; ?>>Legend</option>
                                    <option value="MYTH" <?php echo ($weapon['itemGrade'] == 'MYTH') ? 'selected' : ''; ?>>Myth</option>
                                    <option value="ONLY" <?php echo ($weapon['itemGrade'] == 'ONLY') ? 'selected' : ''; ?>>Only</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="type" class="form-label">Weapon Type</label>
                                <select class="form-control filter-select" id="type" name="type" required>
                                    <option value="SWORD" <?php echo ($weapon['type'] == 'SWORD') ? 'selected' : ''; ?>>Sword</option>
                                    <option value="DAGGER" <?php echo ($weapon['type'] == 'DAGGER') ? 'selected' : ''; ?>>Dagger</option>
                                    <option value="TOHAND_SWORD" <?php echo ($weapon['type'] == 'TOHAND_SWORD') ? 'selected' : ''; ?>>Two-Handed Sword</option>
                                    <option value="BOW" <?php echo ($weapon['type'] == 'BOW') ? 'selected' : ''; ?>>Bow</option>
                                    <option value="SPEAR" <?php echo ($weapon['type'] == 'SPEAR') ? 'selected' : ''; ?>>Spear</option>
                                    <option value="BLUNT" <?php echo ($weapon['type'] == 'BLUNT') ? 'selected' : ''; ?>>Blunt</option>
                                    <option value="STAFF" <?php echo ($weapon['type'] == 'STAFF') ? 'selected' : ''; ?>>Staff</option>
                                    <option value="EDORYU" <?php echo ($weapon['type'] == 'EDORYU') ? 'selected' : ''; ?>>Edoryu</option>
                                    <option value="CLAW" <?php echo ($weapon['type'] == 'CLAW') ? 'selected' : ''; ?>>Claw</option>
                                    <option value="STING" <?php echo ($weapon['type'] == 'STING') ? 'selected' : ''; ?>>Sting</option>
                                    <option value="CHAINSWORD" <?php echo ($weapon['type'] == 'CHAINSWORD') ? 'selected' : ''; ?>>Chain Sword</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="material" class="form-label">Material</label>
                                <select class="form-control filter-select" id="material" name="material" required>
                                    <option value="IRON(철)" <?php echo ($weapon['material'] == 'IRON(철)') ? 'selected' : ''; ?>>Iron</option>
                                    <option value="STEEL(강철)" <?php echo ($weapon['material'] == 'STEEL(강철)') ? 'selected' : ''; ?>>Steel</option>
                                    <option value="WOOD(나무)" <?php echo ($weapon['material'] == 'WOOD(나무)') ? 'selected' : ''; ?>>Wood</option>
                                    <option value="SILVER(은)" <?php echo ($weapon['material'] == 'SILVER(은)') ? 'selected' : ''; ?>>Silver</option>
                                    <option value="GOLD(금)" <?php echo ($weapon['material'] == 'GOLD(금)') ? 'selected' : ''; ?>>Gold</option>
                                    <option value="COPPER(구리)" <?php echo ($weapon['material'] == 'COPPER(구리)') ? 'selected' : ''; ?>>Copper</option>
                                    <option value="PLATINUM(백금)" <?php echo ($weapon['material'] == 'PLATINUM(백금)') ? 'selected' : ''; ?>>Platinum</option>
                                    <option value="MITHRIL(미스릴)" <?php echo ($weapon['material'] == 'MITHRIL(미스릴)') ? 'selected' : ''; ?>>Mithril</option>
                                    <option value="PLASTIC(블랙미스릴)" <?php echo ($weapon['material'] == 'PLASTIC(블랙미스릴)') ? 'selected' : ''; ?>>Black Mithril</option>
                                    <option value="GEMSTONE(보석)" <?php echo ($weapon['material'] == 'GEMSTONE(보석)') ? 'selected' : ''; ?>>Gemstone</option>
                                    <option value="ORIHARUKON(오리하루콘)" <?php echo ($weapon['material'] == 'ORIHARUKON(오리하루콘)') ? 'selected' : ''; ?>>Oriharukon</option>
                                    <option value="DRANIUM(드라니움)" <?php echo ($weapon['material'] == 'DRANIUM(드라니움)') ? 'selected' : ''; ?>>Dranium</option>
                                    <option value="NONE(-)" <?php echo ($weapon['material'] == 'NONE(-)') ? 'selected' : ''; ?>>None</option>
                                </select>
                            </div>
                            
                            <div class="mb-3">
                                <label for="weight" class="form-label">Weight</label>
                                <input type="number" class="form-control filter-select" id="weight" name="weight" value="<?php echo $weapon['weight']; ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="iconId" class="form-label">Icon ID</label>
                                    <input type="number" class="form-control filter-select" id="iconId" name="iconId" value="<?php echo $weapon['iconId']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="spriteId" class="form-label">Sprite ID</label>
                                    <input type="number" class="form-control filter-select" id="spriteId" name="spriteId" value="<?php echo $weapon['spriteId']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="dmg_small" class="form-label">Damage (Small)</label>
                                    <input type="number" class="form-control filter-select" id="dmg_small" name="dmg_small" value="<?php echo $weapon['dmg_small']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dmg_large" class="form-label">Damage (Large)</label>
                                    <input type="number" class="form-control filter-select" id="dmg_large" name="dmg_large" value="<?php echo $weapon['dmg_large']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="safenchant" class="form-label">Safe Enchant</label>
                                <input type="number" class="form-control filter-select" id="safenchant" name="safenchant" value="<?php echo $weapon['safenchant']; ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="hitmodifier" class="form-label">Hit Modifier</label>
                                    <input type="number" class="form-control filter-select" id="hitmodifier" name="hitmodifier" value="<?php echo $weapon['hitmodifier']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="dmgmodifier" class="form-label">Damage Modifier</label>
                                    <input type="number" class="form-control filter-select" id="dmgmodifier" name="dmgmodifier" value="<?php echo $weapon['dmgmodifier']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="double_dmg_chance" class="form-label">Double Damage Chance (%)</label>
                                <input type="number" class="form-control filter-select" id="double_dmg_chance" name="double_dmg_chance" value="<?php echo $weapon['double_dmg_chance']; ?>" required>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="min_lvl" class="form-label">Min Level</label>
                                    <input type="number" class="form-control filter-select" id="min_lvl" name="min_lvl" value="<?php echo $weapon['min_lvl']; ?>" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="max_lvl" class="form-label">Max Level</label>
                                    <input type="number" class="form-control filter-select" id="max_lvl" name="max_lvl" value="<?php echo $weapon['max_lvl']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="magicdmgmodifier" class="form-label">Magic Damage Modifier</label>
                                <input type="number" class="form-control filter-select" id="magicdmgmodifier" name="magicdmgmodifier" value="<?php echo $weapon['magicdmgmodifier']; ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="max_use_time" class="form-label">Max Use Time (seconds, 0 for unlimited)</label>
                                <input type="number" class="form-control filter-select" id="max_use_time" name="max_use_time" value="<?php echo $weapon['max_use_time']; ?>" required>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="bless" name="bless" <?php echo ($weapon['bless'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="bless">
                                            Bless
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="trade" name="trade" <?php echo ($weapon['trade'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="trade">
                                            Tradeable
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="cant_delete" name="cant_delete" <?php echo ($weapon['cant_delete'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="cant_delete">
                                            Can't Delete
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="cant_sell" name="cant_sell" <?php echo ($weapon['cant_sell'] == 1) ? 'checked' : ''; ?>>
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
                                    <input type="number" class="form-control filter-select" id="add_str" name="add_str" value="<?php echo $weapon['add_str']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_dex" class="form-label">DEX</label>
                                    <input type="number" class="form-control filter-select" id="add_dex" name="add_dex" value="<?php echo $weapon['add_dex']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_con" class="form-label">CON</label>
                                    <input type="number" class="form-control filter-select" id="add_con" name="add_con" value="<?php echo $weapon['add_con']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_int" class="form-label">INT</label>
                                    <input type="number" class="form-control filter-select" id="add_int" name="add_int" value="<?php echo $weapon['add_int']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_wis" class="form-label">WIS</label>
                                    <input type="number" class="form-control filter-select" id="add_wis" name="add_wis" value="<?php echo $weapon['add_wis']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_cha" class="form-label">CHA</label>
                                    <input type="number" class="form-control filter-select" id="add_cha" name="add_cha" value="<?php echo $weapon['add_cha']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_hp" class="form-label">HP</label>
                                    <input type="number" class="form-control filter-select" id="add_hp" name="add_hp" value="<?php echo $weapon['add_hp']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_mp" class="form-label">MP</label>
                                    <input type="number" class="form-control filter-select" id="add_mp" name="add_mp" value="<?php echo $weapon['add_mp']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_hpr" class="form-label">HP Regen</label>
                                    <input type="number" class="form-control filter-select" id="add_hpr" name="add_hpr" value="<?php echo $weapon['add_hpr']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_mpr" class="form-label">MP Regen</label>
                                    <input type="number" class="form-control filter-select" id="add_mpr" name="add_mpr" value="<?php echo $weapon['add_mpr']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="add_sp" class="form-label">SP</label>
                                    <input type="number" class="form-control filter-select" id="add_sp" name="add_sp" value="<?php echo $weapon['add_sp']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="m_def" class="form-label">M. Def</label>
                                    <input type="number" class="form-control filter-select" id="m_def" name="m_def" value="<?php echo $weapon['m_def']; ?>" required>
                                </div>
                            </div>
                            
                            <h5 class="mb-3">Resistances</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="regist_skill" class="form-label">Skill</label>
                                    <input type="number" class="form-control filter-select" id="regist_skill" name="regist_skill" value="<?php echo $weapon['regist_skill']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="regist_spirit" class="form-label">Spirit</label>
                                    <input type="number" class="form-control filter-select" id="regist_spirit" name="regist_spirit" value="<?php echo $weapon['regist_spirit']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="regist_dragon" class="form-label">Dragon</label>
                                    <input type="number" class="form-control filter-select" id="regist_dragon" name="regist_dragon" value="<?php echo $weapon['regist_dragon']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="regist_fear" class="form-label">Fear</label>
                                    <input type="number" class="form-control filter-select" id="regist_fear" name="regist_fear" value="<?php echo $weapon['regist_fear']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="regist_all" class="form-label">All</label>
                                    <input type="number" class="form-control filter-select" id="regist_all" name="regist_all" value="<?php echo $weapon['regist_all']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check mt-4">
                                        <input class="form-check-input" type="checkbox" value="true" id="poisonRegist" name="poisonRegist" <?php echo ($weapon['poisonRegist'] == 'true') ? 'checked' : ''; ?>>
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
                                    <input type="number" class="form-control filter-select" id="hitup_skill" name="hitup_skill" value="<?php echo $weapon['hitup_skill']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_spirit" class="form-label">Spirit</label>
                                    <input type="number" class="form-control filter-select" id="hitup_spirit" name="hitup_spirit" value="<?php echo $weapon['hitup_spirit']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_dragon" class="form-label">Dragon</label>
                                    <input type="number" class="form-control filter-select" id="hitup_dragon" name="hitup_dragon" value="<?php echo $weapon['hitup_dragon']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_fear" class="form-label">Fear</label>
                                    <input type="number" class="form-control filter-select" id="hitup_fear" name="hitup_fear" value="<?php echo $weapon['hitup_fear']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_all" class="form-label">All</label>
                                    <input type="number" class="form-control filter-select" id="hitup_all" name="hitup_all" value="<?php echo $weapon['hitup_all']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="hitup_magic" class="form-label">Magic</label>
                                    <input type="number" class="form-control filter-select" id="hitup_magic" name="hitup_magic" value="<?php echo $weapon['hitup_magic']; ?>" required>
                                </div>
                            </div>
                            
                            <h5 class="mb-3">PVP & Critical Stats</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="shortCritical" class="form-label">Short Critical</label>
                                    <input type="number" class="form-control filter-select" id="shortCritical" name="shortCritical" value="<?php echo $weapon['shortCritical']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="longCritical" class="form-label">Long Critical</label>
                                    <input type="number" class="form-control filter-select" id="longCritical" name="longCritical" value="<?php echo $weapon['longCritical']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="magicCritical" class="form-label">Magic Critical</label>
                                    <input type="number" class="form-control filter-select" id="magicCritical" name="magicCritical" value="<?php echo $weapon['magicCritical']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="expBonus" class="form-label">Exp Bonus (%)</label>
                                    <input type="number" class="form-control filter-select" id="expBonus" name="expBonus" value="<?php echo $weapon['expBonus']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPDamage" class="form-label">PVP Damage</label>
                                    <input type="number" class="form-control filter-select" id="PVPDamage" name="PVPDamage" value="<?php echo $weapon['PVPDamage']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPDamagePercent" class="form-label">PVP Damage %</label>
                                    <input type="number" class="form-control filter-select" id="PVPDamagePercent" name="PVPDamagePercent" value="<?php echo $weapon['PVPDamagePercent']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPDamageReduction" class="form-label">PVP DMG Reduction</label>
                                    <input type="number" class="form-control filter-select" id="PVPDamageReduction" name="PVPDamageReduction" value="<?php echo $weapon['PVPDamageReduction']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPDamageReductionPercent" class="form-label">PVP DMG Reduction %</label>
                                    <input type="number" class="form-control filter-select" id="PVPDamageReductionPercent" name="PVPDamageReductionPercent" value="<?php echo $weapon['PVPDamageReductionPercent']; ?>" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="PVPMagicDamageReduction" class="form-label">PVP Magic DMG Reduction</label>
                                    <input type="number" class="form-control filter-select" id="PVPMagicDamageReduction" name="PVPMagicDamageReduction" value="<?php echo $weapon['PVPMagicDamageReduction']; ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" value="1" id="haste_item" name="haste_item" <?php echo ($weapon['haste_item'] == 1) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="haste_item">
                                    Haste Item
                                </label>
                            </div>
                            
                            <h5 class="mb-3">Class Restrictions</h5>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_royal" name="use_royal" <?php echo ($weapon['use_royal'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_royal">
                                            Royal
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_knight" name="use_knight" <?php echo ($weapon['use_knight'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_knight">
                                            Knight
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_mage" name="use_mage" <?php echo ($weapon['use_mage'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_mage">
                                            Mage
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_elf" name="use_elf" <?php echo ($weapon['use_elf'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_elf">
                                            Elf
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_darkelf" name="use_darkelf" <?php echo ($weapon['use_darkelf'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_darkelf">
                                            Dark Elf
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_dragonknight" name="use_dragonknight" <?php echo ($weapon['use_dragonknight'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_dragonknight">
                                            Dragon Knight
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_illusionist" name="use_illusionist" <?php echo ($weapon['use_illusionist'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_illusionist">
                                            Illusionist
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_warrior" name="use_warrior" <?php echo ($weapon['use_warrior'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_warrior">
                                            Warrior
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_fencer" name="use_fencer" <?php echo ($weapon['use_fencer'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_fencer">
                                            Fencer
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" value="1" id="use_lancer" name="use_lancer" <?php echo ($weapon['use_lancer'] == 1) ? 'checked' : ''; ?>>
                                        <label class="form-check-label" for="use_lancer">
                                            Lancer
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-accent">Update Weapon</button>
                        <a href="weapon_list.php" class="btn btn-outline-light ms-2">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<?php include '../../../includes/admin_footer.php'; ?>