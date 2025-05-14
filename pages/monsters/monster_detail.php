<?php
require_once '../../includes/header.php';

// Get monster ID from URL
$monster_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch monster details
$sql = "SELECT * FROM npc WHERE npcid = $monster_id AND (impl = 'L1Monster' OR impl = 'L1Doppelganger')";
$result = $conn->query($sql);

// Check if monster exists
if ($result->num_rows == 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Monster not found</div></div>';
    include '../../includes/footer.php';
    exit;
}

$monster = $result->fetch_assoc();
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Monster <span>Details</span></h1>
            <p class="hero-subtitle"><?php echo htmlspecialchars($monster['desc_en']); ?></p>
        </div>
    </div>
</section>

<!-- Monster Detail Section -->
<section class="container mt-5">
    <div class="row">
        <!-- Monster Image Card -->
        <div class="col-md-4 mb-4">
            <div class="card weapon-card">
                <div class="card-header">
                    <h5>Monster Image</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="weapon-image-container flex-grow-1">
                        <img src="/l1jdb-new/assets/img/icons/ms<?php echo $monster['spriteId']; ?>.gif" 
                             alt="<?php echo htmlspecialchars($monster['desc_en']); ?>" 
                             class="img-fluid weapon-image" 
                             onerror="this.onerror=null;this.src='/l1jdb-new/assets/img/icons/ms<?php echo $monster['spriteId']; ?>.png';
                                      this.onerror=function(){this.src='/l1jdb-new/assets/img/icons/9175.png';}">
                    </div>
                    <div class="weapon-info mt-auto">
                        <h4><?php echo htmlspecialchars($monster['desc_en']); ?></h4>
                        <p>
                            <span class="badge bg-info">Level <?php echo $monster['lvl']; ?></span>
                            <?php if ($monster['is_bossmonster'] == 'true'): ?>
                                <span class="badge bg-accent ml-2">Boss</span>
                            <?php endif; ?>
                        </p>
                        <p class="card-text">
                            <?php echo !empty($monster['note']) ? htmlspecialchars($monster['note']) : 'No description available.'; ?>
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
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">NPC ID</th>
                                        <td><?php echo $monster['npcid']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Level</th>
                                        <td><?php echo $monster['lvl']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">EXP</th>
                                        <td><?php echo $monster['exp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Alignment</th>
                                        <td><?php echo $monster['alignment']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Size</th>
                                        <td><?php echo $monster['big'] == 'true' ? 'Large' : 'Normal'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Family</th>
                                        <td><?php echo !empty($monster['family']) ? $monster['family'] : 'None'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Undead Type</th>
                                        <td><?php echo $monster['undead']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Weakness</th>
                                        <td><?php echo $monster['weakAttr']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Stat Buttons -->
                    <div class="text-center mb-2">
                        <div class="btn-group btn-group-lg" role="group" aria-label="Stat Buttons">
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="hp">
                                <span>HP</span>
                                <span class="d-block"><?php echo $monster['hp']; ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="mp">
                                <span>MP</span>
                                <span class="d-block"><?php echo $monster['mp']; ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="ac">
                                <span>AC</span>
                                <span class="d-block"><?php echo $monster['ac']; ?></span>
                            </button>
							<button type="button" class="btn btn-outline-light stat-btn" data-stat="mr">
                                <span>MR</span>
                                <span class="d-block"><?php echo $monster['mr']; ?></span>
                            </button>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="btn-group btn-group-lg" role="group" aria-label="Attribute Buttons">
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="str">
                                <span>STR</span>
                                <span class="d-block"><?php echo $monster['str']; ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="dex">
                                <span>DEX</span>
                                <span class="d-block"><?php echo $monster['dex']; ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="con">
                                <span>CON</span>
                                <span class="d-block"><?php echo $monster['con']; ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="wis">
                                <span>WIS</span>
                                <span class="d-block"><?php echo $monster['wis']; ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="int">
                                <span>INT</span>
                                <span class="d-block"><?php echo $monster['intel']; ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Attributes -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Attributes</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="stat-label">HP Regen</th>
                                <td><?php echo $monster['hpr']; ?> (<?php echo $monster['hprinterval']; ?> ms)</td>
                            </tr>
                            <tr>
                                <th class="stat-label">MP Regen</th>
                                <td><?php echo $monster['mpr']; ?> (<?php echo $monster['mprinterval']; ?> ms)</td>
                            </tr>
                            <tr>
                                <th class="stat-label">Passive Speed</th>
                                <td><?php echo $monster['passispeed']; ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Attack Speed</th>
                                <td><?php echo $monster['atkspeed']; ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Magic Speed</th>
                                <td><?php echo $monster['atk_magic_speed']; ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Ranged</th>
                                <td><?php echo $monster['ranged']; ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Damage Reduction</th>
                                <td><?php echo $monster['damage_reduction']; ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Hard</th>
                                <td><?php echo $monster['is_hard'] == 'true' ? 'Yes' : 'No'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Aggressive Behavior -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Behavior</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="stat-label">Aggressive</th>
                                <td>
                                    <?php if ($monster['is_agro'] == 'true'): ?>
                                        <span class="badge bg-danger">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Poly Aggressive</th>
                                <td>
                                    <?php if ($monster['is_agro_poly'] == 'true'): ?>
                                        <span class="badge bg-danger">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Invis Aggressive</th>
                                <td>
                                    <?php if ($monster['is_agro_invis'] == 'true'): ?>
                                        <span class="badge bg-danger">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Agro Family</th>
                                <td><?php echo $monster['agrofamily']; ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Poison Attack</th>
                                <td>
                                    <?php if ($monster['poison_atk'] != 'NONE'): ?>
                                        <span class="badge bg-danger"><?php echo $monster['poison_atk']; ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">None</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Boss Status</th>
                                <td>
                                    <?php if ($monster['is_bossmonster'] == 'true'): ?>
                                        <span class="badge bg-accent">Boss</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Normal</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Habits -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Abilities</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="stat-label">Loots Items</th>
                                <td>
                                    <?php if ($monster['is_picupitem'] == 'true'): ?>
                                        <span class="badge bg-accent">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Eats Items</th>
                                <td>
                                    <?php if ($monster['digestitem'] > 0): ?>
                                        <span class="badge bg-accent">Yes (<?php echo $monster['digestitem']; ?>)</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Uses Haste</th>
                                <td>
                                    <?php if ($monster['is_bravespeed'] == 'true'): ?>
                                        <span class="badge bg-accent">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Tameable</th>
                                <td>
                                    <?php if ($monster['is_taming'] == 'true'): ?>
                                        <span class="badge bg-accent">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Turn Undead</th>
                                <td>
                                    <?php if ($monster['can_turnundead'] == 'true'): ?>
                                        <span class="badge bg-accent">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Teleports</th>
                                <td>
                                    <?php if ($monster['is_teleport'] == 'true'): ?>
                                        <span class="badge bg-accent">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Can be Resurrected</th>
                                <td>
                                    <?php if ($monster['cant_resurrect'] == 'false'): ?>
                                        <span class="badge bg-accent">Yes</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">No</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <tr>
                                <th class="stat-label">Random Level</th>
                                <td><?php echo $monster['randomlevel'] > 0 ? $monster['randomlevel'] : 'No'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Item Drops Section -->
    <?php
    // Fetch items dropped by this monster
    $drop_sql = "SELECT d.* FROM droplist d WHERE d.mobId = $monster_id ORDER BY d.chance DESC";
    $drop_result = $conn->query($drop_sql);
    
    if ($drop_result && $drop_result->num_rows > 0):
    ?>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Item Drops</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th class="text-center">Min</th>
                                    <th class="text-center">Max</th>
                                    <th class="text-center">Chance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
                                while ($drop = $drop_result->fetch_assoc()):
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
                                    
                                    // Determine item type and get item details
                                    $itemId = $drop['itemId'];
                                    
                                    // Check if it's a weapon
                                    $weapon_sql = "SELECT item_id, desc_en, type, iconId FROM weapon WHERE item_id = $itemId";
                                    $weapon_result = $conn->query($weapon_sql);
                                    
                                    // Check if it's armor
                                    $armor_sql = "SELECT item_id, desc_en, type, iconId FROM armor WHERE item_id = $itemId";
                                    $armor_result = $conn->query($armor_sql);
                                    
                                    // Check if it's an etc item
                                    $etcitem_sql = "SELECT item_id, desc_en, item_type as type, iconId FROM etcitem WHERE item_id = $itemId";
                                    $etcitem_result = $conn->query($etcitem_sql);
                                    
                                    $item = null;
                                    $item_type = '';
                                    $detail_link = '';
                                    
                                    if ($weapon_result && $weapon_result->num_rows > 0) {
                                        $item = $weapon_result->fetch_assoc();
                                        $item_type = 'Weapon';
                                        $detail_link = '../../pages/weapon/weapon_detail.php?id=' . $item['item_id'];
                                    } elseif ($armor_result && $armor_result->num_rows > 0) {
                                        $item = $armor_result->fetch_assoc();
                                        $item_type = 'Armor';
                                        $detail_link = '../../armor_detail.php?id=' . $item['item_id'];
                                    } elseif ($etcitem_result && $etcitem_result->num_rows > 0) {
                                        $item = $etcitem_result->fetch_assoc();
                                        $item_type = 'Item';
                                        $detail_link = '../../item_detail.php?id=' . $item['item_id'];
                                    } else {
                                        // Skip if item not found
                                        continue;
                                    }
                                    
                                    // Clean item name
                                    $cleanItemName = cleanItemName($item['desc_en']);
                                    
                                    // Normalize type display
                                    $typeDisplay = normalizeText($item['type']);
                                ?>
                                <tr class="clickable-row" data-href="<?php echo $detail_link; ?>">
                                    <td>
                                        <img src="/l1jdb-new/assets/img/icons/icons/<?php echo $item['iconId']; ?>.png" 
                                             alt="<?php echo htmlspecialchars($cleanItemName); ?>" 
                                             style="width: 32px; height: 32px;" 
                                             onerror="this.src='/l1jdb-new/assets/img/placeholders/noiconid.png';">
                                    </td>
                                    <td><?php echo htmlspecialchars($cleanItemName); ?></td>
                                    <td>
                                        <span class="badge bg-info"><?php echo $item_type; ?> - <?php echo htmlspecialchars($typeDisplay); ?></span>
                                    </td>
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
        <a href="<?php echo getBackUrl('monsters_list.php'); ?>" class="btn btn-outline-light"><i class="fas fa-arrow-left mr-2"></i> Back to Monster List</a>
    </div>
</section>

<!-- Add JavaScript for clickable rows -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rows = document.querySelectorAll('.clickable-row');
    
    rows.forEach(function(row) {
        row.addEventListener('click', function() {
            const href = this.getAttribute('data-href');
            if (href && href !== '#') {
                window.location.href = href;
            }
        });
    });
});
</script>

<?php include '../../includes/footer.php'; ?>