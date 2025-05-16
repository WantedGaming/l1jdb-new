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

// Check for dependencies in droplist
$droplist_sql = "SELECT d.*, n.desc_en as monster_name, n.lvl as monster_level, n.is_bossmonster 
                FROM droplist d 
                LEFT JOIN npc n ON d.mobId = n.npcid 
                WHERE d.itemId = $weapon_id 
                ORDER BY d.chance DESC";
$droplist_result = $conn->query($droplist_sql);
$monster_drops = [];

if ($droplist_result && $droplist_result->num_rows > 0) {
    while ($drop = $droplist_result->fetch_assoc()) {
        $monster_drops[] = $drop;
    }
}

// Check for weapon skills
$skills_sql = "SELECT ws.* 
              FROM weapon_skill ws 
              WHERE ws.weapon_id = $weapon_id";
$skills_result = $conn->query($skills_sql);
$weapon_skills = [];

if ($skills_result && $skills_result->num_rows > 0) {
    while ($skill = $skills_result->fetch_assoc()) {
        $weapon_skills[] = $skill;
    }
}
?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Weapon <span>Details</span></h1>
                <p class="hero-subtitle"><?php echo htmlspecialchars($cleanWeaponName); ?> (ID: <?php echo $weapon_id; ?>)</p>
                
                <div class="mt-3">
                    <?php if (count($monster_drops) > 0): ?>
                        <span class="badge bg-accent"><?php echo count($monster_drops); ?> Monster Drops</span>
                    <?php endif; ?>
                    
                    <?php if (count($weapon_skills) > 0): ?>
                        <span class="badge bg-info ms-2"><?php echo count($weapon_skills); ?> Weapon Skills</span>
                    <?php endif; ?>
                    
                    <span class="badge bg-<?php echo $weapon['itemGrade'] === 'LEGEND' ? 'warning' : ($weapon['itemGrade'] === 'RARE' ? 'primary' : 'secondary'); ?> ms-2"><?php echo $weapon['itemGrade']; ?> Grade</span>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php if (isset($_SESSION['message'])): ?>
        <div class="alert alert-<?php echo $_SESSION['message_type']; ?> alert-dismissible fade show mb-4" role="alert">
            <?php 
            echo $_SESSION['message'];
            unset($_SESSION['message']);
            unset($_SESSION['message_type']);
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php endif; ?>
        
        <div class="mb-4 d-flex justify-content-between">
            <a href="weapon_list.php" class="btn btn-outline-light">
                <i class="fas fa-arrow-left me-2"></i> Back to List
            </a>
            
            <div>
                <a href="weapon_edit.php?id=<?php echo $weapon_id; ?>" class="btn btn-accent">
                    <i class="fas fa-edit me-2"></i> Edit Weapon
                </a>
                <a href="weapon_delete.php?id=<?php echo $weapon_id; ?>" class="btn btn-outline-danger ms-2">
                    <i class="fas fa-trash-alt me-2"></i> Delete
                </a>
                <a href="../../../pages/weapon/weapon_detail.php?id=<?php echo $weapon_id; ?>" class="btn btn-outline-light ms-2" target="_blank">
                    <i class="fas fa-external-link-alt me-2"></i> Public View
                </a>
            </div>
        </div>
        
        <div class="row mb-4">
            <!-- Weapon Image Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Weapon Image</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="weapon-image-container">
                            <img src="/l1jdb-new/assets/img/icons/<?php echo $weapon['iconId']; ?>.png" 
                                 alt="<?php echo htmlspecialchars($cleanWeaponName); ?>" 
                                 class="weapon-image" 
                                 id="weapon-preview"
                                 onerror="this.src='/l1jdb-new/assets/img/icons/9175.png';">
                        </div>
                        <div class="mt-3">
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-1"><strong>Icon ID:</strong></p>
                                    <p class="mb-0"><?php echo $weapon['iconId']; ?></p>
                                </div>
                                <div class="col-6">
                                    <p class="mb-1"><strong>Sprite ID:</strong></p>
                                    <p class="mb-0"><?php echo $weapon['spriteId']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Class Restrictions Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Class Restrictions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-4 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="/l1jdb-new/assets/img/classes/royal<?php echo $weapon['use_royal'] ? '' : '_gray'; ?>.png" 
                                         class="mb-2" style="width: 40px; height: 40px;" alt="Royal">
                                    <span class="small <?php echo $weapon['use_royal'] ? 'text-white' : 'text-muted'; ?>">Royal</span>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="/l1jdb-new/assets/img/classes/knight<?php echo $weapon['use_knight'] ? '' : '_gray'; ?>.png" 
                                         class="mb-2" style="width: 40px; height: 40px;" alt="Knight">
                                    <span class="small <?php echo $weapon['use_knight'] ? 'text-white' : 'text-muted'; ?>">Knight</span>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="/l1jdb-new/assets/img/classes/elf<?php echo $weapon['use_elf'] ? '' : '_gray'; ?>.png" 
                                         class="mb-2" style="width: 40px; height: 40px;" alt="Elf">
                                    <span class="small <?php echo $weapon['use_elf'] ? 'text-white' : 'text-muted'; ?>">Elf</span>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="/l1jdb-new/assets/img/classes/mage<?php echo $weapon['use_mage'] ? '' : '_gray'; ?>.png" 
                                         class="mb-2" style="width: 40px; height: 40px;" alt="Mage">
                                    <span class="small <?php echo $weapon['use_mage'] ? 'text-white' : 'text-muted'; ?>">Mage</span>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="/l1jdb-new/assets/img/classes/darkelf<?php echo $weapon['use_darkelf'] ? '' : '_gray'; ?>.png" 
                                         class="mb-2" style="width: 40px; height: 40px;" alt="Dark Elf">
                                    <span class="small <?php echo $weapon['use_darkelf'] ? 'text-white' : 'text-muted'; ?>">Dark Elf</span>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="/l1jdb-new/assets/img/classes/dragonknight<?php echo $weapon['use_dragonknight'] ? '' : '_gray'; ?>.png" 
                                         class="mb-2" style="width: 40px; height: 40px;" alt="Dragon Knight">
                                    <span class="small <?php echo $weapon['use_dragonknight'] ? 'text-white' : 'text-muted'; ?>">Dragon Knight</span>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="/l1jdb-new/assets/img/classes/illusionist<?php echo $weapon['use_illusionist'] ? '' : '_gray'; ?>.png" 
                                         class="mb-2" style="width: 40px; height: 40px;" alt="Illusionist">
                                    <span class="small <?php echo $weapon['use_illusionist'] ? 'text-white' : 'text-muted'; ?>">Illusionist</span>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="/l1jdb-new/assets/img/classes/warrior<?php echo $weapon['use_warrior'] ? '' : '_gray'; ?>.png" 
                                         class="mb-2" style="width: 40px; height: 40px;" alt="Warrior">
                                    <span class="small <?php echo $weapon['use_warrior'] ? 'text-white' : 'text-muted'; ?>">Warrior</span>
                                </div>
                            </div>
                            <div class="col-4 mb-3">
                                <div class="d-flex flex-column align-items-center">
                                    <img src="/l1jdb-new/assets/img/classes/fencer<?php echo $weapon['use_fencer'] ? '' : '_gray'; ?>.png" 
                                         class="mb-2" style="width: 40px; height: 40px;" alt="Fencer">
                                    <span class="small <?php echo $weapon['use_fencer'] ? 'text-white' : 'text-muted'; ?>">Fencer</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Basic Information Card -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Basic Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-dark table-hover">
                                    <tbody>
                                        <tr>
                                            <th class="stat-label">Item ID</th>
                                            <td><?php echo $weapon['item_id']; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Name</th>
                                            <td><?php echo htmlspecialchars($cleanWeaponName); ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Type</th>
                                            <td><?php echo normalizeText($weapon['type']); ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Material</th>
                                            <td><?php echo normalizeMaterial($weapon['material']); ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Grade</th>
                                            <td><?php echo $weapon['itemGrade']; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Weight</th>
                                            <td><?php echo $weapon['weight']; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-dark table-hover">
                                    <tbody>
                                        <tr>
                                            <th class="stat-label">Safe Enchant</th>
                                            <td><?php echo $weapon['safenchant']; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Min Level</th>
                                            <td><?php echo $weapon['min_lvl']; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Max Level</th>
                                            <td><?php echo $weapon['max_lvl']; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Max Use Time</th>
                                            <td><?php echo ($weapon['max_use_time'] > 0) ? $weapon['max_use_time'] . 's' : 'Unlimited'; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Tradeable</th>
                                            <td><?php echo $weapon['trade'] ? 'Yes' : 'No'; ?></td>
                                        </tr>
                                        <tr>
                                            <th class="stat-label">Bless</th>
                                            <td><?php echo $weapon['bless'] ? 'Yes' : 'No'; ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                        <?php if (!empty($weapon['note'])): ?>
                        <div class="mt-3">
                            <h6 class="mb-2">Description / Note:</h6>
                            <p class="mb-0"><?php echo nl2br(htmlspecialchars($weapon['note'])); ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <div class="mt-3 row">
                            <div class="col-md-6 d-flex">
                                <div class="form-check me-4">
                                    <input class="form-check-input" type="checkbox" disabled <?php echo $weapon['cant_delete'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Can't Delete</label>
                                </div>
                            </div>
                            <div class="col-md-6 d-flex">
                                <div class="form-check me-4">
                                    <input class="form-check-input" type="checkbox" disabled <?php echo $weapon['cant_sell'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Can't Sell</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" disabled <?php echo $weapon['haste_item'] ? 'checked' : ''; ?>>
                                    <label class="form-check-label">Haste Item</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Combat Stats Card -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Combat Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stat-btn card">
                                    <div class="card-body text-center p-3">
                                        <span>Small Damage</span>
                                        <span class="d-block fs-4 mt-1"><?php echo $weapon['dmg_small']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-btn card">
                                    <div class="card-body text-center p-3">
                                        <span>Large Damage</span>
                                        <span class="d-block fs-4 mt-1"><?php echo $weapon['dmg_large']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-btn card">
                                    <div class="card-body text-center p-3">
                                        <span>Double Damage</span>
                                        <span class="d-block fs-4 mt-1"><?php echo $weapon['double_dmg_chance']; ?>%</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-md-4">
                                <div class="stat-btn card">
                                    <div class="card-body text-center p-3">
                                        <span>Hit Modifier</span>
                                        <span class="d-block fs-4 mt-1"><?php echo ($weapon['hitmodifier'] >= 0 ? '+' : '') . $weapon['hitmodifier']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-btn card">
                                    <div class="card-body text-center p-3">
                                        <span>Damage Modifier</span>
                                        <span class="d-block fs-4 mt-1"><?php echo ($weapon['dmgmodifier'] >= 0 ? '+' : '') . $weapon['dmgmodifier']; ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-btn card">
                                    <div class="card-body text-center p-3">
                                        <span>Magic Damage</span>
                                        <span class="d-block fs-4 mt-1"><?php echo ($weapon['magicdmgmodifier'] >= 0 ? '+' : '') . $weapon['magicdmgmodifier']; ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mb-4">
            <!-- Stat Bonuses Card -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">Stat Bonuses</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/str.png" class="me-2" style="width: 24px; height: 24px;" alt="STR">
                                    <span>STR: <strong><?php echo ($weapon['add_str'] >= 0 ? '+' : '') . $weapon['add_str']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/dex.png" class="me-2" style="width: 24px; height: 24px;" alt="DEX">
                                    <span>DEX: <strong><?php echo ($weapon['add_dex'] >= 0 ? '+' : '') . $weapon['add_dex']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/con.png" class="me-2" style="width: 24px; height: 24px;" alt="CON">
                                    <span>CON: <strong><?php echo ($weapon['add_con'] >= 0 ? '+' : '') . $weapon['add_con']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/int.png" class="me-2" style="width: 24px; height: 24px;" alt="INT">
                                    <span>INT: <strong><?php echo ($weapon['add_int'] >= 0 ? '+' : '') . $weapon['add_int']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/wis.png" class="me-2" style="width: 24px; height: 24px;" alt="WIS">
                                    <span>WIS: <strong><?php echo ($weapon['add_wis'] >= 0 ? '+' : '') . $weapon['add_wis']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/cha.png" class="me-2" style="width: 24px; height: 24px;" alt="CHA">
                                    <span>CHA: <strong><?php echo ($weapon['add_cha'] >= 0 ? '+' : '') . $weapon['add_cha']; ?></strong></span>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/hp.png" class="me-2" style="width: 24px; height: 24px;" alt="HP">
                                    <span>HP: <strong><?php echo ($weapon['add_hp'] >= 0 ? '+' : '') . $weapon['add_hp']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/mp.png" class="me-2" style="width: 24px; height: 24px;" alt="MP">
                                    <span>MP: <strong><?php echo ($weapon['add_mp'] >= 0 ? '+' : '') . $weapon['add_mp']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/sp.png" class="me-2" style="width: 24px; height: 24px;" alt="SP">
                                    <span>SP: <strong><?php echo ($weapon['add_sp'] >= 0 ? '+' : '') . $weapon['add_sp']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/hpr.png" class="me-2" style="width: 24px; height: 24px;" alt="HP Regen">
                                    <span>HP Regen: <strong><?php echo ($weapon['add_hpr'] >= 0 ? '+' : '') . $weapon['add_hpr']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/mpr.png" class="me-2" style="width: 24px; height: 24px;" alt="MP Regen">
                                    <span>MP Regen: <strong><?php echo ($weapon['add_mpr'] >= 0 ? '+' : '') . $weapon['add_mpr']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/mdef.png" class="me-2" style="width: 24px; height: 24px;" alt="M.Def">
                                    <span>M.Def: <strong><?php echo ($weapon['m_def'] >= 0 ? '+' : '') . $weapon['m_def']; ?></strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Resistances -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Resistances</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/resist_skill.png" class="me-2" style="width: 24px; height: 24px;" alt="Skill Resist">
                                    <span>Skill: <strong><?php echo ($weapon['regist_skill'] >= 0 ? '+' : '') . $weapon['regist_skill']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/resist_spirit.png" class="me-2" style="width: 24px; height: 24px;" alt="Spirit Resist">
                                    <span>Spirit: <strong><?php echo ($weapon['regist_spirit'] >= 0 ? '+' : '') . $weapon['regist_spirit']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/resist_dragon.png" class="me-2" style="width: 24px; height: 24px;" alt="Dragon Resist">
                                    <span>Dragon: <strong><?php echo ($weapon['regist_dragon'] >= 0 ? '+' : '') . $weapon['regist_dragon']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/resist_fear.png" class="me-2" style="width: 24px; height: 24px;" alt="Fear Resist">
                                    <span>Fear: <strong><?php echo ($weapon['regist_fear'] >= 0 ? '+' : '') . $weapon['regist_fear']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/resist_all.png" class="me-2" style="width: 24px; height: 24px;" alt="All Resist">
                                    <span>All: <strong><?php echo ($weapon['regist_all'] >= 0 ? '+' : '') . $weapon['regist_all']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/resist_poison.png" class="me-2" style="width: 24px; height: 24px;" alt="Poison Resist">
                                    <span>Poison: <strong><?php echo ($weapon['poisonRegist'] == 'true') ? 'Yes' : 'No'; ?></strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- PVP, Critical, Hit -->
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">PVP & Critical Stats</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/short_crit.png" class="me-2" style="width: 24px; height: 24px;" alt="Short Critical">
                                    <span>Short Crit: <strong><?php echo ($weapon['shortCritical'] >= 0 ? '+' : '') . $weapon['shortCritical']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/long_crit.png" class="me-2" style="width: 24px; height: 24px;" alt="Long Critical">
                                    <span>Long Crit: <strong><?php echo ($weapon['longCritical'] >= 0 ? '+' : '') . $weapon['longCritical']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/magic_crit.png" class="me-2" style="width: 24px; height: 24px;" alt="Magic Critical">
                                    <span>Magic Crit: <strong><?php echo ($weapon['magicCritical'] >= 0 ? '+' : '') . $weapon['magicCritical']; ?></strong></span>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/exp_bonus.png" class="me-2" style="width: 24px; height: 24px;" alt="EXP Bonus">
                                    <span>EXP Bonus: <strong><?php echo ($weapon['expBonus'] >= 0 ? '+' : '') . $weapon['expBonus']; ?>%</strong></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <div class="card bg-dark">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0">PVP Damage</h6>
                                    </div>
                                    <div class="card-body py-2">
                                        <div class="mb-2">
                                            <span>Flat: <strong><?php echo ($weapon['PVPDamage'] >= 0 ? '+' : '') . $weapon['PVPDamage']; ?></strong></span>
                                        </div>
                                        <div>
                                            <span>Percent: <strong><?php echo ($weapon['PVPDamagePercent'] >= 0 ? '+' : '') . $weapon['PVPDamagePercent']; ?>%</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6 col-12 mb-3">
                                <div class="card bg-dark">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0">PVP Damage Reduction</h6>
                                    </div>
                                    <div class="card-body py-2">
                                        <div class="mb-2">
                                            <span>Flat: <strong><?php echo ($weapon['PVPDamageReduction'] >= 0 ? '+' : '') . $weapon['PVPDamageReduction']; ?></strong></span>
                                        </div>
                                        <div>
                                            <span>Percent: <strong><?php echo ($weapon['PVPDamageReductionPercent'] >= 0 ? '+' : '') . $weapon['PVPDamageReductionPercent']; ?>%</strong></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 col-12 mb-3">
                                <div class="card bg-dark">
                                    <div class="card-header py-2">
                                        <h6 class="mb-0">PVP Magic Damage Reduction</h6>
                                    </div>
                                    <div class="card-body py-2">
                                        <span>Amount: <strong><?php echo ($weapon['PVPMagicDamageReduction'] >= 0 ? '+' : '') . $weapon['PVPMagicDamageReduction']; ?></strong></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Hit Modifiers -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h5 class="mb-0">Hit Modifiers</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/hit_skill.png" class="me-2" style="width: 24px; height: 24px;" alt="Skill Hit">
                                    <span>Skill: <strong><?php echo ($weapon['hitup_skill'] >= 0 ? '+' : '') . $weapon['hitup_skill']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/hit_spirit.png" class="me-2" style="width: 24px; height: 24px;" alt="Spirit Hit">
                                    <span>Spirit: <strong><?php echo ($weapon['hitup_spirit'] >= 0 ? '+' : '') . $weapon['hitup_spirit']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/hit_dragon.png" class="me-2" style="width: 24px; height: 24px;" alt="Dragon Hit">
                                    <span>Dragon: <strong><?php echo ($weapon['hitup_dragon'] >= 0 ? '+' : '') . $weapon['hitup_dragon']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/hit_fear.png" class="me-2" style="width: 24px; height: 24px;" alt="Fear Hit">
                                    <span>Fear: <strong><?php echo ($weapon['hitup_fear'] >= 0 ? '+' : '') . $weapon['hitup_fear']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/hit_all.png" class="me-2" style="width: 24px; height: 24px;" alt="All Hit">
                                    <span>All: <strong><?php echo ($weapon['hitup_all'] >= 0 ? '+' : '') . $weapon['hitup_all']; ?></strong></span>
                                </div>
                            </div>
                            <div class="col-md-4 col-6 mb-3">
                                <div class="d-flex align-items-center">
                                    <img src="/l1jdb-new/assets/img/stats/hit_magic.png" class="me-2" style="width: 24px; height: 24px;" alt="Magic Hit">
                                    <span>Magic: <strong><?php echo ($weapon['hitup_magic'] >= 0 ? '+' : '') . $weapon['hitup_magic']; ?></strong></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (count($monster_drops) > 0): ?>
        <!-- Monster Drops -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Monster Drops</h5>
                <span class="badge bg-accent"><?php echo count($monster_drops); ?> Monsters</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Monster ID</th>
                                <th>Monster Name</th>
                                <th>Level</th>
                                <th>Min</th>
                                <th>Max</th>
                                <th>Chance</th>
                                <th>Enchant</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($monster_drops as $drop): ?>
                            <tr class="clickable-row" onclick="window.location.href='../../pages/monsters/monster_detail.php?id=<?php echo $drop['mobId']; ?>'">
                                <td><?php echo $drop['mobId']; ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php echo htmlspecialchars($drop['monster_name'] ?? $drop['mobname_en']); ?>
                                        <?php if (isset($drop['is_bossmonster']) && $drop['is_bossmonster'] == 'true'): ?>
                                            <span class="badge bg-danger ms-2">Boss</span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td><?php echo $drop['monster_level'] ?? $drop['moblevel']; ?></td>
                                <td><?php echo $drop['min']; ?></td>
                                <td><?php echo $drop['max']; ?></td>
                                <td>
                                    <?php 
                                    $dropChance = ($drop['chance'] / 10000);
                                    $chanceFormatted = ($dropChance < 0.0001) ? "< 0.0001%" : number_format($dropChance, 4) . "%";
                                    
                                    // Determine color for drop chance
                                    if ($dropChance < 0.01) {
                                        $chanceClass = 'text-danger fw-bold'; // Red for very rare
                                    } elseif ($dropChance < 0.05) {
                                        $chanceClass = 'text-warning fw-bold'; // Orange for rare
                                    } elseif ($dropChance < 0.2) {
                                        $chanceClass = 'text-info fw-bold'; // Yellow for uncommon
                                    } else {
                                        $chanceClass = 'text-success fw-bold'; // Green for common
                                    }
                                    
                                    echo '<span class="' . $chanceClass . '">' . $chanceFormatted . '</span>';
                                    ?>
                                </td>
                                <td><?php echo ($drop['Enchant'] > 0) ? '+' . $drop['Enchant'] : '-'; ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (count($weapon_skills) > 0): ?>
        <!-- Weapon Skills -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Weapon Skills</h5>
                <span class="badge bg-info"><?php echo count($weapon_skills); ?> Skills</span>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-dark table-hover">
                        <thead>
                            <tr>
                                <th>Skill ID</th>
                                <th>Skill Name</th>
                                <th>Activation Chance</th>
                                <th>Description</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($weapon_skills as $skill): ?>
                            <tr class="clickable-row" onclick="window.location.href='../../pages/skills/skill_detail.php?id=<?php echo $skill['skill_id']; ?>'">
                                <td><?php echo $skill['skill_id']; ?></td>
                                <td><?php echo isset($skill['note']) && !empty($skill['note']) ? htmlspecialchars($skill['note']) : 'Skill #' . $skill['skill_id']; ?></td>
                                <td><?php echo isset($skill['probability']) ? ($skill['probability'] / 100) . '%' : 'N/A'; ?></td>
                                <td><?php 
                                    $description = "";
                                    if (isset($skill['note']) && !empty($skill['note'])) {
                                        $description .= htmlspecialchars($skill['note']) . ". ";
                                    }
                                    if (isset($skill['fix_damage']) && $skill['fix_damage'] > 0) {
                                        $description .= "Fixed damage: " . $skill['fix_damage'] . ". ";
                                    }
                                    if (isset($skill['random_damage']) && $skill['random_damage'] > 0) {
                                        $description .= "Random damage: " . $skill['random_damage'] . ". ";
                                    }
                                    if (isset($skill['skill_time']) && $skill['skill_time'] > 0) {
                                        $description .= "Duration: " . ($skill['skill_time'] / 1000) . "s. ";
                                    }
                                    if (isset($skill['area']) && $skill['area'] > 0) {
                                        $description .= "Area: " . $skill['area'] . ". ";
                                    }
                                    if (isset($skill['attackType']) && $skill['attackType'] != 'ALL') {
                                        $description .= "Type: " . $skill['attackType'] . ". ";
                                    }
                                    echo !empty($description) ? $description : 'No description available';
                                ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../../../includes/admin_footer.php'; ?>
