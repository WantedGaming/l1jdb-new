<?php
require_once '../../includes/header.php';

// Get armor ID from URL
$armor_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch armor details
$sql = "SELECT * FROM armor WHERE item_id = $armor_id";
$result = $conn->query($sql);

// Check if armor exists
if ($result->num_rows == 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Armor not found</div></div>';
    include '../../includes/footer.php';
    exit;
}

$armor = $result->fetch_assoc();
// Clean the armor name
$cleanArmorName = cleanItemName($armor['desc_en']);

// Check if armor is part of a set
$set_sql = "SELECT * FROM armor_set WHERE sets LIKE '%$armor_id,%' OR sets LIKE '%,$armor_id,%' OR sets LIKE '%,$armor_id' OR sets = '$armor_id'";
$set_result = $conn->query($set_sql);
$is_part_of_set = ($set_result && $set_result->num_rows > 0);
$set_data = $is_part_of_set ? $set_result->fetch_assoc() : null;

// Function to normalize armor type display
// Function to normalize armor type display
function normalizeArmorType($type) {
    // Replace underscores with spaces
    $type = str_replace('_', ' ', $type);
    
    // Convert to title case (capitalize first letter of each word)
    $type = ucwords(strtolower($type));
    
    return trim($type);
}


// Function to get set item names
function getSetItemNames($conn, $sets) {
    $item_ids = explode(',', $sets);
    $item_names = [];
    
    foreach ($item_ids as $id) {
        $id = trim($id);
        if (!empty($id)) {
            $sql = "SELECT item_id, desc_en FROM armor WHERE item_id = $id";
            $result = $conn->query($sql);
            if ($result && $result->num_rows > 0) {
                $item = $result->fetch_assoc();
                $item_names[$item['item_id']] = cleanItemName($item['desc_en']);
            }
        }
    }
    
    return $item_names;
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Armor <span>Details</span></h1>
            <p class="hero-subtitle"><?php echo htmlspecialchars($cleanArmorName); ?></p>
        </div>
    </div>
</section>

<!-- Armor Detail Section -->
<section class="container mt-5">
    <div class="row">
        <!-- Armor Image Card -->
        <div class="col-md-4 mb-4">
            <div class="card weapon-card">
                <div class="card-header">
                    <h5>Armor Image</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="weapon-image-container flex-grow-1">
                        <img src="/l1jdb-new/assets/img/icons/<?php echo $armor['iconId']; ?>.png" 
                             alt="<?php echo htmlspecialchars($cleanArmorName); ?>" 
                             class="img-fluid weapon-image" 
                             onerror="this.src='/l1jdb-new/assets/img/icons/9175.png';">
                    </div>
                    <div class="weapon-info mt-auto">
                        <h4><?php echo htmlspecialchars($cleanArmorName); ?></h4>
                        <p>
                                                            <span class="badge bg-info">
                                <?php echo htmlspecialchars(normalizeText($armor['type'])); ?>
                            </span>
                            <span class="badge bg-accent ml-2"><?php echo $armor['itemGrade']; ?></span>
                        </p>
                        <p class="card-text">
                            <?php echo !empty($armor['note']) ? htmlspecialchars(cleanItemName($armor['note'])) : 'No description available.'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Basic Stats -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Basic Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Item ID</th>
                                        <td><?php echo $armor['item_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Type</th>
                                        <td><?php echo normalizeArmorType($armor['type']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Material</th>
                                        <td><?php echo normalizeMaterial($armor['material']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Weight</th>
                                        <td><?php echo $armor['weight']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">AC</th>
                                        <td><?php echo $armor['ac']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Safe Enchant</th>
                                        <td><?php echo $armor['safenchant']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Magic Defense</th>
                                        <td><?php echo $armor['m_def']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Damage Reduction</th>
                                        <td><?php echo $armor['damage_reduction']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Min/Max Level</th>
                                        <td><?php echo $armor['min_lvl']; ?> / <?php echo $armor['max_lvl']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Bless</th>
                                        <td><?php echo $armor['bless'] == 1 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Tradeable</th>
                                        <td><?php echo $armor['trade'] == 1 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Can Delete</th>
                                        <td><?php echo $armor['cant_delete'] == 0 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Can Sell</th>
                                        <td><?php echo $armor['cant_sell'] == 0 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Magic Damage Reduction</th>
                                        <td><?php echo $armor['MagicDamageReduction']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Carry Bonus</th>
                                        <td><?php echo $armor['carryBonus']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Max Use Time</th>
                                        <td><?php echo $armor['max_use_time'] > 0 ? $armor['max_use_time'] . ' sec' : 'Unlimited'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Class Restrictions - Only allowed classes -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Class Restrictions</h5>
                </div>
                <div class="card-body">
                    <div class="allowed-classes">
                        <?php
                        $class_restrictions = [
                            'use_royal' => 'Royal',
                            'use_knight' => 'Knight',
                            'use_mage' => 'Mage',
                            'use_elf' => 'Elf',
                            'use_darkelf' => 'Dark Elf',
                            'use_dragonknight' => 'Dragon Knight',
                            'use_illusionist' => 'Illusionist',
                            'use_warrior' => 'Warrior',
                            'use_fencer' => 'Fencer',
                            'use_lancer' => 'Lancer'
                        ];
                        
                        $allowed_classes = [];
                        foreach ($class_restrictions as $field => $class_name) {
                            if ($armor[$field]) {
                                $allowed_classes[] = $class_name;
                            }
                        }
                        
                        if (count($allowed_classes) > 0) {
                            echo '<ul class="class-list">';
                            foreach ($allowed_classes as $class) {
                                // Convert class name to lowercase and remove spaces for image filename
                                $class_img = strtolower(str_replace(' ', '', $class));
                                
                                echo '<li>';
                                echo '<div class="class-item">';
                                // Add the class image with fallback
                                echo '<img src="/l1jdb-new/assets/img/placeholders/class/details/' . $class_img . '.png" 
                                        alt="' . $class . '" 
                                        class="class-icon"
                                        onerror="this.src=\'/assets/img/placeholders/class.png\'">';
                                echo '<span class="badge bg-accent">' . $class . '</span>';
                                echo '</div>';
                                echo '</li>';
                            }
                            echo '</ul>';
                        } else {
                            echo '<p>No class can use this armor.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Stat Bonuses -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Stat Bonuses</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">STR</th>
                                        <td><?php echo $armor['add_str'] > 0 ? '+' . $armor['add_str'] : $armor['add_str']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">DEX</th>
                                        <td><?php echo $armor['add_dex'] > 0 ? '+' . $armor['add_dex'] : $armor['add_dex']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">CON</th>
                                        <td><?php echo $armor['add_con'] > 0 ? '+' . $armor['add_con'] : $armor['add_con']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">INT</th>
                                        <td><?php echo $armor['add_int'] > 0 ? '+' . $armor['add_int'] : $armor['add_int']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">WIS</th>
                                        <td><?php echo $armor['add_wis'] > 0 ? '+' . $armor['add_wis'] : $armor['add_wis']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">CHA</th>
                                        <td><?php echo $armor['add_cha'] > 0 ? '+' . $armor['add_cha'] : $armor['add_cha']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">HP</th>
                                        <td><?php echo $armor['add_hp'] > 0 ? '+' . $armor['add_hp'] : $armor['add_hp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">MP</th>
                                        <td><?php echo $armor['add_mp'] > 0 ? '+' . $armor['add_mp'] : $armor['add_mp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">HP Regen</th>
                                        <td><?php echo $armor['add_hpr'] > 0 ? '+' . $armor['add_hpr'] : $armor['add_hpr']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">MP Regen</th>
                                        <td><?php echo $armor['add_mpr'] > 0 ? '+' . $armor['add_mpr'] : $armor['add_mpr']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">SP</th>
                                        <td><?php echo $armor['add_sp'] > 0 ? '+' . $armor['add_sp'] : $armor['add_sp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Hit Rate</th>
                                        <td><?php echo $armor['hit_rate'] > 0 ? '+' . $armor['hit_rate'] : $armor['hit_rate']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Additional Stats -->
    <div class="row">
        <!-- Elemental Resistances -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Elemental Resistances</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Earth</th>
                                        <td><?php echo $armor['defense_earth']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Fire</th>
                                        <td><?php echo $armor['defense_fire']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Water</th>
                                        <td><?php echo $armor['defense_water']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Wind</th>
                                        <td><?php echo $armor['defense_wind']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">All Elements</th>
                                        <td><?php echo $armor['attr_all']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Stone</th>
                                        <td><?php echo $armor['regist_stone']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Sleep</th>
                                        <td><?php echo $armor['regist_sleep']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Freeze</th>
                                        <td><?php echo $armor['regist_freeze']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Blind</th>
                                        <td><?php echo $armor['regist_blind']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Fear</th>
                                        <td><?php echo $armor['regist_fear']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- PVP Stats -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>PVP & Critical Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">PVP Damage</th>
                                        <td><?php echo $armor['PVPDamage']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">PVP Damage Reduction</th>
                                        <td><?php echo $armor['PVPDamageReduction']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">PVP Magic DR</th>
                                        <td><?php echo $armor['PVPMagicDamageReduction']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">PVP DR %</th>
                                        <td><?php echo $armor['PVPDamageReductionPercent']; ?>%</td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">PVP Damage %</th>
                                        <td><?php echo $armor['PVPDamagePercent']; ?>%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Short Critical</th>
                                        <td><?php echo $armor['shortCritical']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Long Critical</th>
                                        <td><?php echo $armor['longCritical']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Magic Critical</th>
                                        <td><?php echo $armor['magicCritical']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Exp Bonus</th>
                                        <td><?php echo $armor['expBonus']; ?>%</td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Poison Resist</th>
                                        <td><?php echo $armor['poisonRegist'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($is_part_of_set): ?>
    <!-- Armor Set Information -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Armor Set: <?php echo !empty($set_data['note']) ? htmlspecialchars($set_data['note']) : 'Unnamed Set'; ?></h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <h6>Set Items</h6>
                            <ul class="list-group">
                                <?php 
                                $set_items = getSetItemNames($conn, $set_data['sets']);
                                foreach ($set_items as $item_id => $item_name): 
                                    $is_current = ($item_id == $armor_id);
                                ?>
                                <li class="list-group-item bg-dark text-light <?php echo $is_current ? 'border-accent' : ''; ?>" style="<?php echo $is_current ? 'border-color: var(--accent);' : ''; ?>">
                                    <?php if ($is_current): ?>
                                        <strong><?php echo htmlspecialchars($item_name); ?> (Current)</strong>
                                    <?php else: ?>
                                        <a href="armor_detail.php?id=<?php echo $item_id; ?>"><?php echo htmlspecialchars($item_name); ?></a>
                                    <?php endif; ?>
                                </li>
                                <?php endforeach; ?>
                            </ul>
                            
                            <?php if ($set_data['polyid'] > 0): ?>
                            <div class="mt-4">
                                <h6>Polymorph</h6>
                                <p>This set grants polymorph ID: <?php echo $set_data['polyid']; ?></p>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <div class="col-md-6">
                            <h6>Set Bonuses</h6>
                            <table class="table">
                                <tbody>
                                    <?php
                                    $set_bonuses = [
                                        'ac' => 'AC',
                                        'hp' => 'HP',
                                        'mp' => 'MP',
                                        'hpr' => 'HP Regen',
                                        'mpr' => 'MP Regen',
                                        'mr' => 'Magic Resistance',
                                        'str' => 'STR',
                                        'dex' => 'DEX',
                                        'con' => 'CON',
                                        'wis' => 'WIS',
                                        'cha' => 'CHA',
                                        'intl' => 'INT',
                                        'shorthitup' => 'Short Hit',
                                        'shortdmgup' => 'Short Damage',
                                        'shortCritical' => 'Short Critical',
                                        'longhitup' => 'Long Hit',
                                        'longdmgup' => 'Long Damage',
                                        'longCritical' => 'Long Critical',
                                        'sp' => 'SP',
                                        'magichitup' => 'Magic Hit',
                                        'magicCritical' => 'Magic Critical',
                                        'earth' => 'Earth Resistance',
                                        'fire' => 'Fire Resistance',
                                        'wind' => 'Wind Resistance',
                                        'water' => 'Water Resistance',
                                        'reduction' => 'Damage Reduction',
                                        'magicReduction' => 'Magic Damage Reduction',
                                        'PVPDamage' => 'PVP Damage',
                                        'PVPDamageReduction' => 'PVP Damage Reduction',
                                        'expBonus' => 'EXP Bonus'
                                    ];
                                    
                                    $has_bonuses = false;
                                    foreach ($set_bonuses as $field => $label) {
                                        if (!empty($set_data[$field]) && $set_data[$field] != 0) {
                                            $has_bonuses = true;
                                            $value = $set_data[$field];
                                            $display_value = $value > 0 ? '+' . $value : $value;
                                            
                                            echo "<tr>";
                                            echo "<th class='stat-label'>{$label}</th>";
                                            echo "<td>{$display_value}</td>";
                                            echo "</tr>";
                                        }
                                    }
                                    
                                    if (!$has_bonuses) {
                                        echo "<tr><td colspan='2'>No bonuses for this set.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            
                            <?php if (!empty($set_data['min_enchant']) && $set_data['min_enchant'] > 0): ?>
                            <div class="alert alert-info mt-3">
                                <i class="fas fa-info-circle mr-2"></i> Minimum enchant level required: +<?php echo $set_data['min_enchant']; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Monster Drops Section -->
    <?php
    // Fetch monsters that drop this armor
    $drop_sql = "SELECT d.*, n.desc_en as monster_name, n.lvl as monster_level, n.is_bossmonster 
                 FROM droplist d 
                 JOIN npc n ON d.mobId = n.npcid 
                 WHERE d.itemId = $armor_id 
                 ORDER BY d.chance DESC, n.lvl DESC";
    $drop_result = $conn->query($drop_sql);
    
    if ($drop_result && $drop_result->num_rows > 0):
    ?>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Monster Drops</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Monster</th>
                                    <th>Level</th>
                                    <th class="text-center">Min</th>
                                    <th class="text-center">Max</th>
                                    <th class="text-center">Chance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($drop = $drop_result->fetch_assoc()): 
                                    // Calculate drop chance in percentage
                                    $dropChance = ($drop['chance'] / 10000) * 100;
                                    $dropChanceDisplay = ($dropChance < 0.01) ? "< 0.01%" : number_format($dropChance, 2) . "%";
                                    
                                    // Determine color for drop chance
                                    if ($dropChance < 1) {
                                        $chanceColor = '#ff3333'; // Red for very rare
                                    } elseif ($dropChance < 5) {
                                        $chanceColor = '#ff6600'; // Orange for rare
                                    } elseif ($dropChance < 20) {
                                        $chanceColor = '#ffcc00'; // Yellow for uncommon
                                    } elseif ($dropChance < 50) {
                                        $chanceColor = '#99cc00'; // Light green for common
                                    } else {
                                        $chanceColor = '#33cc33'; // Green for very common
                                    }
                                    
                                    // Get the monster sprite ID for its image
                                    $monster_sql = "SELECT spriteId FROM npc WHERE npcid = " . $drop['mobId'];
                                    $monster_result = $conn->query($monster_sql);
                                    $monster_sprite = ($monster_result && $monster_result->num_rows > 0) ? 
                                                      $monster_result->fetch_assoc()['spriteId'] : '';
                                ?>
                                <tr class="clickable-row" data-href="../../pages/monsters/monster_detail.php?id=<?php echo $drop['mobId']; ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($monster_sprite)): ?>
                                            <img src="/l1jdb-new/assets/img/icons/monsters/ms<?php echo $monster_sprite; ?>.gif" 
                                                 alt="<?php echo htmlspecialchars($drop['monster_name']); ?>" 
                                                 style="width: 32px; height: 32px; margin-right: 10px;" 
                                                 onerror="this.onerror=null;this.src='/l1jdb-new/assets/img/icons/monsters/ms<?php echo $monster_sprite; ?>.png';
                                                          this.onerror=function(){this.src='/l1jdb-new/assets/img/placeholders/monster-placeholder.png';}">
                                            <?php endif; ?>
                                            <span><?php echo htmlspecialchars($drop['monster_name']); ?></span>
                                            <?php if ($drop['is_bossmonster'] == 'true'): ?>
                                                <span class="badge bg-accent ml-2">Boss</span>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                    <td><?php echo $drop['monster_level']; ?></td>
                                    <td class="text-center"><?php echo $drop['min']; ?></td>
                                    <td class="text-center"><?php echo $drop['max']; ?></td>
                                    <td class="text-center" style="color: <?php echo $chanceColor; ?>; font-weight: bold;">
                                        <?php echo $dropChanceDisplay; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <div class="text-center mt-4 mb-5">
        <a href="<?php echo getBackUrl('armor_list.php'); ?>" class="btn btn-outline-light"><i class="fas fa-arrow-left mr-2"></i> Back to Armor List</a>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>