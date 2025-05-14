<?php
require_once '../../includes/header.php';

// Get weapon ID from URL
$weapon_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch weapon details
$sql = "SELECT * FROM weapon WHERE item_id = $weapon_id";
$result = $conn->query($sql);

// Check if weapon exists
if ($result->num_rows == 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Weapon not found</div></div>';
    include '../../includes/footer.php';
    exit;
}

$weapon = $result->fetch_assoc();
// Clean the weapon name
$cleanWeaponName = cleanItemName($weapon['desc_en']);

// Fetch weapon skills if available
$skill_sql = "SELECT * FROM weapon_skill WHERE weapon_id = $weapon_id";
$skill_result = $conn->query($skill_sql);

// Fetch weapon skill model if available
$model_sql = "SELECT * FROM weapon_skill_model WHERE item_id = $weapon_id";
$model_result = $conn->query($model_sql);
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Weapon <span>Details</span></h1>
            <p class="hero-subtitle"><?php echo htmlspecialchars($cleanWeaponName); ?></p>
        </div>
    </div>
</section>

<!-- Weapon Detail Section -->
<section class="container mt-5">
    <div class="row">
        <!-- Weapon Image Card -->
        <div class="col-md-4 mb-4">
            <div class="card weapon-card">
                <div class="card-header">
                    <h5>Weapon Image</h5>
                </div>
                <div class="card-body d-flex flex-column">
                    <div class="weapon-image-container flex-grow-1">
                        <img src="/l1jdb-new/assets/img/icons/icons/<?php echo $weapon['iconId']; ?>.png" 
                             alt="<?php echo htmlspecialchars($cleanWeaponName); ?>" 
                             class="img-fluid weapon-image" 
                             onerror="this.src='/l1jdb-new/assets/img/placeholders/weapon.png';">
                    </div>
                    <div class="weapon-info mt-auto">
                        <h4><?php echo htmlspecialchars($cleanWeaponName); ?></h4>
                        <p>
                            <span class="badge bg-info">
                                <?php 
                                    $type_display = str_replace('_', ' ', $weapon['type']);
                                    $type_display = str_replace('TOHAND', 'Two-Handed', $type_display);
                                    echo htmlspecialchars($type_display); 
                                ?>
                            </span>
                            <span class="badge bg-accent ml-2"><?php echo $weapon['itemGrade']; ?></span>
                        </p>
                        <p class="card-text">
                            <?php echo !empty($weapon['note']) ? htmlspecialchars(cleanItemName($weapon['note'])) : 'No description available.'; ?>
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
                                        <td><?php echo $weapon['item_id']; ?></td>
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
                                        <th class="stat-label">Weight</th>
                                        <td><?php echo $weapon['weight']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Damage (S/L)</th>
                                        <td><?php echo $weapon['dmg_small']; ?> / <?php echo $weapon['dmg_large']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Safe Enchant</th>
                                        <td><?php echo $weapon['safenchant']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Hit Modifier</th>
                                        <td><?php echo $weapon['hitmodifier']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Damage Modifier</th>
                                        <td><?php echo $weapon['dmgmodifier']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Double Damage Chance</th>
                                        <td><?php echo $weapon['double_dmg_chance']; ?>%</td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Magic Damage Modifier</th>
                                        <td><?php echo $weapon['magicdmgmodifier']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Min/Max Level</th>
                                        <td><?php echo $weapon['min_lvl']; ?> / <?php echo $weapon['max_lvl']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Bless</th>
                                        <td><?php echo $weapon['bless'] == 1 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Tradeable</th>
                                        <td><?php echo $weapon['trade'] == 1 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Can Delete</th>
                                        <td><?php echo $weapon['cant_delete'] == 0 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Can Sell</th>
                                        <td><?php echo $weapon['cant_sell'] == 0 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Max Use Time</th>
                                        <td><?php echo $weapon['max_use_time'] > 0 ? $weapon['max_use_time'] . ' sec' : 'Unlimited'; ?></td>
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
        <!-- Class Restrictions with Icons - Only allowed classes -->
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
						if ($weapon[$field]) {
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
						echo '<p>No class can use this weapon.</p>';
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
                                        <td><?php echo $weapon['add_str'] > 0 ? '+' . $weapon['add_str'] : $weapon['add_str']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">DEX</th>
                                        <td><?php echo $weapon['add_dex'] > 0 ? '+' . $weapon['add_dex'] : $weapon['add_dex']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">CON</th>
                                        <td><?php echo $weapon['add_con'] > 0 ? '+' . $weapon['add_con'] : $weapon['add_con']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">INT</th>
                                        <td><?php echo $weapon['add_int'] > 0 ? '+' . $weapon['add_int'] : $weapon['add_int']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">WIS</th>
                                        <td><?php echo $weapon['add_wis'] > 0 ? '+' . $weapon['add_wis'] : $weapon['add_wis']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">CHA</th>
                                        <td><?php echo $weapon['add_cha'] > 0 ? '+' . $weapon['add_cha'] : $weapon['add_cha']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">HP</th>
                                        <td><?php echo $weapon['add_hp'] > 0 ? '+' . $weapon['add_hp'] : $weapon['add_hp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">MP</th>
                                        <td><?php echo $weapon['add_mp'] > 0 ? '+' . $weapon['add_mp'] : $weapon['add_mp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">HP Regen</th>
                                        <td><?php echo $weapon['add_hpr'] > 0 ? '+' . $weapon['add_hpr'] : $weapon['add_hpr']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">MP Regen</th>
                                        <td><?php echo $weapon['add_mpr'] > 0 ? '+' . $weapon['add_mpr'] : $weapon['add_mpr']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">SP</th>
                                        <td><?php echo $weapon['add_sp'] > 0 ? '+' . $weapon['add_sp'] : $weapon['add_sp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">M. Def</th>
                                        <td><?php echo $weapon['m_def'] > 0 ? '+' . $weapon['m_def'] : $weapon['m_def']; ?></td>
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
        <!-- Resistance Stats -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Resistances & Combat Stats</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Resistances</h6>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Skill</th>
                                        <td><?php echo $weapon['regist_skill']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Spirit</th>
                                        <td><?php echo $weapon['regist_spirit']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Dragon</th>
                                        <td><?php echo $weapon['regist_dragon']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Fear</th>
                                        <td><?php echo $weapon['regist_fear']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">All</th>
                                        <td><?php echo $weapon['regist_all']; ?></td>
                                    </tr>
                                                                                <tr>
                                        <th class="stat-label">Poison</th>
                                        <td><?php echo $weapon['poisonRegist'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Hit Modifiers</h6>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Skill</th>
                                        <td><?php echo $weapon['hitup_skill']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Spirit</th>
                                        <td><?php echo $weapon['hitup_spirit']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Dragon</th>
                                        <td><?php echo $weapon['hitup_dragon']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Fear</th>
                                        <td><?php echo $weapon['hitup_fear']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">All</th>
                                        <td><?php echo $weapon['hitup_all']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Magic</th>
                                        <td><?php echo $weapon['hitup_magic']; ?></td>
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
                            <h6>PVP Stats</h6>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">PVP Damage</th>
                                        <td><?php echo $weapon['PVPDamage']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">PVP Damage %</th>
                                        <td><?php echo $weapon['PVPDamagePercent']; ?>%</td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">PVP Damage Reduction</th>
                                        <td><?php echo $weapon['PVPDamageReduction']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">PVP Damage Reduction %</th>
                                        <td><?php echo $weapon['PVPDamageReductionPercent']; ?>%</td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">PVP Magic Damage Reduction</th>
                                        <td><?php echo $weapon['PVPMagicDamageReduction']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Critical Stats</h6>
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Short Critical</th>
                                        <td><?php echo $weapon['shortCritical']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Long Critical</th>
                                        <td><?php echo $weapon['longCritical']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Magic Critical</th>
                                        <td><?php echo $weapon['magicCritical']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Exp Bonus</th>
                                        <td><?php echo $weapon['expBonus']; ?>%</td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Haste</th>
                                        <td><?php echo $weapon['haste_item'] ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php if ($skill_result && $skill_result->num_rows > 0): ?>
    <!-- Weapon Skills -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Weapon Skills</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Attack Type</th>
                                    <th>Probability</th>
                                    <th>Damage</th>
                                    <th>Area</th>
                                    <th>Skill ID</th>
                                    <th>Duration</th>
                                    <th>Attribute</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($skill = $skill_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $skill['attackType']; ?></td>
                                    <td><?php echo $skill['probability']; ?>%</td>
                                    <td><?php echo $skill['fix_damage']; ?> + <?php echo $skill['random_damage']; ?></td>
                                    <td><?php echo $skill['area']; ?></td>
                                    <td><?php echo $skill['skill_id']; ?></td>
                                    <td><?php echo $skill['skill_time']; ?> sec</td>
                                    <td><?php echo $skill['attr']; ?></td>
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
    
    <!-- Monster Drops Section -->
    <?php
    // Fetch monsters that drop this weapon
    $drop_sql = "SELECT d.*, n.desc_en as monster_name, n.lvl as monster_level, n.is_bossmonster 
                 FROM droplist d 
                 JOIN npc n ON d.mobId = n.npcid 
                 WHERE d.itemId = $weapon_id 
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
        <a href="<?php echo getBackUrl('weapon_list.php'); ?>" class="btn btn-outline-light"><i class="fas fa-arrow-left mr-2"></i> Back to Weapons List</a>
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