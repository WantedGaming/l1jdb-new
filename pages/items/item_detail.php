<?php
require_once '../../includes/header.php';

// Get item ID from URL
$item_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch item details
$sql = "SELECT * FROM etcitem WHERE item_id = $item_id";
$result = $conn->query($sql);

// Check if item exists
if ($result->num_rows == 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Item not found</div></div>';
    include '../../includes/footer.php';
    exit;
}

$item = $result->fetch_assoc();
// Clean the item name
$cleanItemName = cleanItemName($item['desc_en']);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Item <span>Details</span></h1>
            <p class="hero-subtitle"><?php echo htmlspecialchars($cleanItemName); ?></p>
        </div>
    </div>
</section>

<!-- Item Detail Section -->
<section class="container mt-5">
    <div class="row">
        <!-- Item Image Card -->
        <div class="col-md-4 mb-4">
            <div class="card weapon-card">
                <div class="card-header">
                    <h5>Item Image</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="weapon-image-container flex-grow-1">
                        <img src="/l1jdb-new/assets/img/icons/icons/<?php echo $item['iconId']; ?>.png" 
                             alt="<?php echo htmlspecialchars($cleanItemName); ?>" 
                             class="img-fluid weapon-image" 
                             onerror="this.src='/l1jdb-new/assets/img/placeholders/item.png';">
                    </div>
                    <div class="weapon-info mt-auto">
                        <h4><?php echo htmlspecialchars($cleanItemName); ?></h4>
                        <p>
                            <span class="badge bg-info">
                                <?php echo htmlspecialchars(normalizeText($item['item_type'])); ?>
                            </span>
                            <?php if ($item['use_type'] != 'NONE'): ?>
                            <span class="badge bg-secondary ml-2">
                                <?php echo htmlspecialchars(normalizeText($item['use_type'])); ?>
                            </span>
                            <?php endif; ?>
                            <span class="badge bg-accent ml-2"><?php echo $item['itemGrade']; ?></span>
                        </p>
                        <p class="card-text">
                            <?php echo !empty($item['note']) ? htmlspecialchars(cleanItemName($item['note'])) : 'No description available.'; ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Basic Stats -->
        <div class="col-md-8 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Item ID</th>
                                        <td><?php echo $item['item_id']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Type</th>
                                        <td><?php echo normalizeText($item['item_type']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Use Type</th>
                                        <td><?php echo normalizeText($item['use_type']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Material</th>
                                        <td><?php echo normalizeMaterial($item['material']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Weight</th>
                                        <td><?php echo $item['weight']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Stackable</th>
                                        <td><?php echo $item['merge'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <?php if ($item['max_charge_count'] > 0): ?>
                                    <tr>
                                        <th class="stat-label">Max Charges</th>
                                        <td><?php echo $item['max_charge_count']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Min/Max Level</th>
                                        <td><?php echo $item['min_lvl'] > 0 || $item['max_lvl'] > 0 ? $item['min_lvl'] . ' / ' . $item['max_lvl'] : 'No Level Restriction'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Bless</th>
                                        <td><?php echo $item['bless'] == 1 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Tradeable</th>
                                        <td><?php echo $item['trade'] == 1 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Can Delete</th>
                                        <td><?php echo $item['cant_delete'] == 0 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Can Sell</th>
                                        <td><?php echo $item['cant_sell'] == 0 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <?php if ($item['delay_time'] > 0): ?>
                                    <tr>
                                        <th class="stat-label">Delay Time</th>
                                        <td><?php echo $item['delay_time']; ?> ms</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['food_volume'] > 0): ?>
                                    <tr>
                                        <th class="stat-label">Food Volume</th>
                                        <td><?php echo $item['food_volume']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if (
        $item['dmg_small'] > 0 || $item['dmg_large'] > 0 || 
        $item['shortHit'] > 0 || $item['shortDmg'] > 0 || 
        $item['longHit'] > 0 || $item['longDmg'] > 0 || 
        $item['ac_bonus'] > 0
    ): ?>
    <div class="row">
        <!-- Combat Stats -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Combat Stats</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <?php if ($item['dmg_small'] > 0 || $item['dmg_large'] > 0): ?>
                            <tr>
                                <th class="stat-label">Damage (S/L)</th>
                                <td><?php echo $item['dmg_small']; ?> / <?php echo $item['dmg_large']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($item['ac_bonus'] != 0): ?>
                            <tr>
                                <th class="stat-label">AC Bonus</th>
                                <td><?php echo $item['ac_bonus']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($item['shortHit'] > 0): ?>
                            <tr>
                                <th class="stat-label">Short Hit</th>
                                <td><?php echo $item['shortHit']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($item['shortDmg'] > 0): ?>
                            <tr>
                                <th class="stat-label">Short Damage</th>
                                <td><?php echo $item['shortDmg']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($item['longHit'] > 0): ?>
                            <tr>
                                <th class="stat-label">Long Hit</th>
                                <td><?php echo $item['longHit']; ?></td>
                            </tr>
                            <?php endif; ?>
                            <?php if ($item['longDmg'] > 0): ?>
                            <tr>
                                <th class="stat-label">Long Damage</th>
                                <td><?php echo $item['longDmg']; ?></td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <?php if (
            $item['add_str'] != 0 || $item['add_con'] != 0 || $item['add_dex'] != 0 || 
            $item['add_int'] != 0 || $item['add_wis'] != 0 || $item['add_cha'] != 0 || 
            $item['add_hp'] != 0 || $item['add_mp'] != 0 || $item['add_hpr'] != 0 || 
            $item['add_mpr'] != 0 || $item['add_sp'] != 0
        ): ?>
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
                                    <?php if ($item['add_str'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">STR</th>
                                        <td><?php echo $item['add_str'] > 0 ? '+' . $item['add_str'] : $item['add_str']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['add_dex'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">DEX</th>
                                        <td><?php echo $item['add_dex'] > 0 ? '+' . $item['add_dex'] : $item['add_dex']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['add_con'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">CON</th>
                                        <td><?php echo $item['add_con'] > 0 ? '+' . $item['add_con'] : $item['add_con']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['add_int'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">INT</th>
                                        <td><?php echo $item['add_int'] > 0 ? '+' . $item['add_int'] : $item['add_int']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['add_wis'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">WIS</th>
                                        <td><?php echo $item['add_wis'] > 0 ? '+' . $item['add_wis'] : $item['add_wis']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['add_cha'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">CHA</th>
                                        <td><?php echo $item['add_cha'] > 0 ? '+' . $item['add_cha'] : $item['add_cha']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <?php if ($item['add_hp'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">HP</th>
                                        <td><?php echo $item['add_hp'] > 0 ? '+' . $item['add_hp'] : $item['add_hp']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['add_mp'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">MP</th>
                                        <td><?php echo $item['add_mp'] > 0 ? '+' . $item['add_mp'] : $item['add_mp']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['add_hpr'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">HP Regen</th>
                                        <td><?php echo $item['add_hpr'] > 0 ? '+' . $item['add_hpr'] : $item['add_hpr']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['add_mpr'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">MP Regen</th>
                                        <td><?php echo $item['add_mpr'] > 0 ? '+' . $item['add_mpr'] : $item['add_mpr']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['add_sp'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">SP</th>
                                        <td><?php echo $item['add_sp'] > 0 ? '+' . $item['add_sp'] : $item['add_sp']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['m_def'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">M. Def</th>
                                        <td><?php echo $item['m_def'] > 0 ? '+' . $item['m_def'] : $item['m_def']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if (
        $item['defense_water'] != 0 || $item['defense_wind'] != 0 || 
        $item['defense_fire'] != 0 || $item['defense_earth'] != 0 || 
        $item['attr_all'] != 0 || $item['regist_stone'] != 0 || 
        $item['regist_sleep'] != 0 || $item['regist_freeze'] != 0 || 
        $item['regist_blind'] != 0
    ): ?>
    <!-- Additional Stats -->
    <div class="row">
        <!-- Elemental Resistances -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Resistances</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Elemental</h6>
                            <table class="table">
                                <tbody>
                                    <?php if ($item['defense_earth'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Earth</th>
                                        <td><?php echo $item['defense_earth']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['defense_fire'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Fire</th>
                                        <td><?php echo $item['defense_fire']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['defense_water'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Water</th>
                                        <td><?php echo $item['defense_water']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['defense_wind'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Wind</th>
                                        <td><?php echo $item['defense_wind']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['attr_all'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">All Elements</th>
                                        <td><?php echo $item['attr_all']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Status</h6>
                            <table class="table">
                                <tbody>
                                    <?php if ($item['regist_stone'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Stone</th>
                                        <td><?php echo $item['regist_stone']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['regist_sleep'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Sleep</th>
                                        <td><?php echo $item['regist_sleep']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['regist_freeze'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Freeze</th>
                                        <td><?php echo $item['regist_freeze']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['regist_blind'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Blind</th>
                                        <td><?php echo $item['regist_blind']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['poisonRegist'] == 'true'): ?>
                                    <tr>
                                        <th class="stat-label">Poison</th>
                                        <td>Yes</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php if (
            $item['shortCritical'] != 0 || $item['longCritical'] != 0 || 
            $item['magicCritical'] != 0 || $item['expBonus'] != 0 || 
            $item['PVPDamage'] != 0 || $item['PVPDamageReduction'] != 0
        ): ?>
        <!-- PVP Stats -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>PVP & Critical Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>PVP Stats</h6>
                            <table class="table">
                                <tbody>
                                    <?php if ($item['PVPDamage'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">PVP Damage</th>
                                        <td><?php echo $item['PVPDamage']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['PVPDamagePercent'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">PVP Damage %</th>
                                        <td><?php echo $item['PVPDamagePercent']; ?>%</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['PVPDamageReduction'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">PVP Damage Reduction</th>
                                        <td><?php echo $item['PVPDamageReduction']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['PVPDamageReductionPercent'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">PVP Damage Reduction %</th>
                                        <td><?php echo $item['PVPDamageReductionPercent']; ?>%</td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['PVPMagicDamageReduction'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">PVP Magic Damage Reduction</th>
                                        <td><?php echo $item['PVPMagicDamageReduction']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Critical Stats</h6>
                            <table class="table">
                                <tbody>
                                    <?php if ($item['shortCritical'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Short Critical</th>
                                        <td><?php echo $item['shortCritical']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['longCritical'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Long Critical</th>
                                        <td><?php echo $item['longCritical']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['magicCritical'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Magic Critical</th>
                                        <td><?php echo $item['magicCritical']; ?></td>
                                    </tr>
                                    <?php endif; ?>
                                    <?php if ($item['expBonus'] != 0): ?>
                                    <tr>
                                        <th class="stat-label">Exp Bonus</th>
                                        <td><?php echo $item['expBonus']; ?>%</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>
    
    <?php if (
        $item['use_royal'] != 0 || $item['use_knight'] != 0 || 
        $item['use_mage'] != 0 || $item['use_elf'] != 0 || 
        $item['use_darkelf'] != 0 || $item['use_dragonknight'] != 0 || 
        $item['use_illusionist'] != 0 || $item['use_warrior'] != 0 || 
        $item['use_fencer'] != 0 || $item['use_lancer'] != 0
    ): ?>
    <div class="row">
        <!-- Class Restrictions - Only allowed classes -->
        <div class="col-md-12 mb-4">
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
                            if ($item[$field]) {
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
                            echo '<p>All classes can use this item.</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Monster Drops Section -->
    <?php
    // Fetch monsters that drop this item
    $drop_sql = "SELECT d.*, n.desc_en as monster_name, n.lvl as monster_level, n.is_bossmonster, n.spriteId 
                 FROM droplist d 
                 JOIN npc n ON d.mobId = n.npcid 
                 WHERE d.itemId = $item_id 
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
                                ?>
                                <tr class="clickable-row" data-href="../../pages/monsters/monster_detail.php?id=<?php echo $drop['mobId']; ?>">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <?php if (!empty($drop['spriteId'])): ?>
                                            <img src="/l1jdb-new/assets/img/icons/monsters/ms<?php echo $drop['spriteId']; ?>.gif" 
                                                 alt="<?php echo htmlspecialchars($drop['monster_name']); ?>" 
                                                 style="width: 32px; height: 32px; margin-right: 10px;" 
                                                 onerror="this.onerror=null;this.src='/l1jdb-new/assets/img/icons/monsters/ms<?php echo $drop['spriteId']; ?>.png';
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
        <a href="<?php echo getBackUrl('items_list.php'); ?>" class="btn btn-outline-light"><i class="fas fa-arrow-left mr-2"></i> Back to Items List</a>
    </div>
</section>

<!-- Add JavaScript for clickable rows -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rows = document.querySelectorAll('.clickable-row');
    
    rows.forEach(function(row) {
        row.addEventListener('click', function() {
            window.location.href = this.getAttribute('data-href');
        });
    });
});
</script>

<?php include '../../includes/footer.php'; ?>