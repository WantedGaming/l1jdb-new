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
            <p class="hero-subtitle"><?php echo htmlspecialchars($weapon['desc_en']); ?></p>
        </div>
    </div>
</section>

<!-- Weapon Detail Section -->
<section class="container mt-5">
    <div class="row">
        <!-- Weapon Image Card -->
        <div class="col-md-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Weapon Image</h5>
                </div>
                <div class="card-body text-center">
                    <img src="../../assets/img/icons/<?php echo $weapon['iconId']; ?>.png" 
                         alt="<?php echo htmlspecialchars($weapon['desc_en']); ?>" 
                         class="img-fluid" style="max-height: 200px; max-width: 200px;"
                         onerror="this.src='../../assets/img/placeholders/weapon.png';">
                    <h4 class="mt-3"><?php echo htmlspecialchars($weapon['desc_en']); ?></h4>
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
                        <?php echo !empty($weapon['note']) ? htmlspecialchars($weapon['note']) : 'No description available.'; ?>
                    </p>
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
                                <tr>
                                    <th>Item ID</th>
                                    <td><?php echo $weapon['item_id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Type</th>
                                    <td><?php echo $weapon['type']; ?></td>
                                </tr>
                                <tr>
                                    <th>Material</th>
                                    <td><?php echo $weapon['material']; ?></td>
                                </tr>
                                <tr>
                                    <th>Weight</th>
                                    <td><?php echo $weapon['weight']; ?></td>
                                </tr>
                                <tr>
                                    <th>Damage (S/L)</th>
                                    <td><?php echo $weapon['dmg_small']; ?> / <?php echo $weapon['dmg_large']; ?></td>
                                </tr>
                                <tr>
                                    <th>Safe Enchant</th>
                                    <td><?php echo $weapon['safenchant']; ?></td>
                                </tr>
                                <tr>
                                    <th>Hit Modifier</th>
                                    <td><?php echo $weapon['hitmodifier']; ?></td>
                                </tr>
                                <tr>
                                    <th>Damage Modifier</th>
                                    <td><?php echo $weapon['dmgmodifier']; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th>Double Damage Chance</th>
                                    <td><?php echo $weapon['double_dmg_chance']; ?>%</td>
                                </tr>
                                <tr>
                                    <th>Magic Damage Modifier</th>
                                    <td><?php echo $weapon['magicdmgmodifier']; ?></td>
                                </tr>
                                <tr>
                                    <th>Min/Max Level</th>
                                    <td><?php echo $weapon['min_lvl']; ?> / <?php echo $weapon['max_lvl']; ?></td>
                                </tr>
                                <tr>
                                    <th>Bless</th>
                                    <td><?php echo $weapon['bless'] == 1 ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th>Tradeable</th>
                                    <td><?php echo $weapon['trade'] == 1 ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th>Can Delete</th>
                                    <td><?php echo $weapon['cant_delete'] == 0 ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th>Can Sell</th>
                                    <td><?php echo $weapon['cant_sell'] == 0 ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th>Max Use Time</th>
                                    <td><?php echo $weapon['max_use_time'] > 0 ? $weapon['max_use_time'] . ' sec' : 'Unlimited'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Class Restrictions -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Class Restrictions</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Royal
                            <span class="badge bg-<?php echo $weapon['use_royal'] ? 'accent' : 'secondary'; ?> rounded-pill">
                                <?php echo $weapon['use_royal'] ? 'Allowed' : 'Restricted'; ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Knight
                            <span class="badge bg-<?php echo $weapon['use_knight'] ? 'accent' : 'secondary'; ?> rounded-pill">
                                <?php echo $weapon['use_knight'] ? 'Allowed' : 'Restricted'; ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Mage
                            <span class="badge bg-<?php echo $weapon['use_mage'] ? 'accent' : 'secondary'; ?> rounded-pill">
                                <?php echo $weapon['use_mage'] ? 'Allowed' : 'Restricted'; ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Elf
                            <span class="badge bg-<?php echo $weapon['use_elf'] ? 'accent' : 'secondary'; ?> rounded-pill">
                                <?php echo $weapon['use_elf'] ? 'Allowed' : 'Restricted'; ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Dark Elf
                            <span class="badge bg-<?php echo $weapon['use_darkelf'] ? 'accent' : 'secondary'; ?> rounded-pill">
                                <?php echo $weapon['use_darkelf'] ? 'Allowed' : 'Restricted'; ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Dragon Knight
                            <span class="badge bg-<?php echo $weapon['use_dragonknight'] ? 'accent' : 'secondary'; ?> rounded-pill">
                                <?php echo $weapon['use_dragonknight'] ? 'Allowed' : 'Restricted'; ?>
                            </span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Illusionist
                            <span class="badge bg-<?php echo $weapon['use_illusionist'] ? 'accent' : 'secondary'; ?> rounded-pill">
                                <?php echo $weapon['use_illusionist'] ? 'Allowed' : 'Restricted'; ?>
                            </span>
                        </li>
                    </ul>
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
                                <tr>
                                    <th>STR</th>
                                    <td><?php echo $weapon['add_str'] > 0 ? '+' . $weapon['add_str'] : $weapon['add_str']; ?></td>
                                </tr>
                                <tr>
                                    <th>DEX</th>
                                    <td><?php echo $weapon['add_dex'] > 0 ? '+' . $weapon['add_dex'] : $weapon['add_dex']; ?></td>
                                </tr>
                                <tr>
                                    <th>CON</th>
                                    <td><?php echo $weapon['add_con'] > 0 ? '+' . $weapon['add_con'] : $weapon['add_con']; ?></td>
                                </tr>
                                <tr>
                                    <th>INT</th>
                                    <td><?php echo $weapon['add_int'] > 0 ? '+' . $weapon['add_int'] : $weapon['add_int']; ?></td>
                                </tr>
                                <tr>
                                    <th>WIS</th>
                                    <td><?php echo $weapon['add_wis'] > 0 ? '+' . $weapon['add_wis'] : $weapon['add_wis']; ?></td>
                                </tr>
                                <tr>
                                    <th>CHA</th>
                                    <td><?php echo $weapon['add_cha'] > 0 ? '+' . $weapon['add_cha'] : $weapon['add_cha']; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tr>
                                    <th>HP</th>
                                    <td><?php echo $weapon['add_hp'] > 0 ? '+' . $weapon['add_hp'] : $weapon['add_hp']; ?></td>
                                </tr>
                                <tr>
                                    <th>MP</th>
                                    <td><?php echo $weapon['add_mp'] > 0 ? '+' . $weapon['add_mp'] : $weapon['add_mp']; ?></td>
                                </tr>
                                <tr>
                                    <th>HP Regen</th>
                                    <td><?php echo $weapon['add_hpr'] > 0 ? '+' . $weapon['add_hpr'] : $weapon['add_hpr']; ?></td>
                                </tr>
                                <tr>
                                    <th>MP Regen</th>
                                    <td><?php echo $weapon['add_mpr'] > 0 ? '+' . $weapon['add_mpr'] : $weapon['add_mpr']; ?></td>
                                </tr>
                                <tr>
                                    <th>SP</th>
                                    <td><?php echo $weapon['add_sp'] > 0 ? '+' . $weapon['add_sp'] : $weapon['add_sp']; ?></td>
                                </tr>
                                <tr>
                                    <th>M. Def</th>
                                    <td><?php echo $weapon['m_def'] > 0 ? '+' . $weapon['m_def'] : $weapon['m_def']; ?></td>
                                </tr>
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
                                <tr>
                                    <th>Skill</th>
                                    <td><?php echo $weapon['regist_skill']; ?></td>
                                </tr>
                                <tr>
                                    <th>Spirit</th>
                                    <td><?php echo $weapon['regist_spirit']; ?></td>
                                </tr>
                                <tr>
                                    <th>Dragon</th>
                                    <td><?php echo $weapon['regist_dragon']; ?></td>
                                </tr>
                                <tr>
                                    <th>Fear</th>
                                    <td><?php echo $weapon['regist_fear']; ?></td>
                                </tr>
                                <tr>
                                    <th>All</th>
                                    <td><?php echo $weapon['regist_all']; ?></td>
                                </tr>
                                <tr>
                                    <th>Poison</th>
                                    <td><?php echo $weapon['poisonRegist'] == 'true' ? 'Yes' : 'No'; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Hit Modifiers</h6>
                            <table class="table">
                                <tr>
                                    <th>Skill</th>
                                    <td><?php echo $weapon['hitup_skill']; ?></td>
                                </tr>
                                <tr>
                                    <th>Spirit</th>
                                    <td><?php echo $weapon['hitup_spirit']; ?></td>
                                </tr>
                                <tr>
                                    <th>Dragon</th>
                                    <td><?php echo $weapon['hitup_dragon']; ?></td>
                                </tr>
                                <tr>
                                    <th>Fear</th>
                                    <td><?php echo $weapon['hitup_fear']; ?></td>
                                </tr>
                                <tr>
                                    <th>All</th>
                                    <td><?php echo $weapon['hitup_all']; ?></td>
                                </tr>
                                <tr>
                                    <th>Magic</th>
                                    <td><?php echo $weapon['hitup_magic']; ?></td>
                                </tr>
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
                                <tr>
                                    <th>PVP Damage</th>
                                    <td><?php echo $weapon['PVPDamage']; ?></td>
                                </tr>
                                <tr>
                                    <th>PVP Damage %</th>
                                    <td><?php echo $weapon['PVPDamagePercent']; ?>%</td>
                                </tr>
                                <tr>
                                    <th>PVP Damage Reduction</th>
                                    <td><?php echo $weapon['PVPDamageReduction']; ?></td>
                                </tr>
                                <tr>
                                    <th>PVP Damage Reduction %</th>
                                    <td><?php echo $weapon['PVPDamageReductionPercent']; ?>%</td>
                                </tr>
                                <tr>
                                    <th>PVP Magic Damage Reduction</th>
                                    <td><?php echo $weapon['PVPMagicDamageReduction']; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h6>Critical Stats</h6>
                            <table class="table">
                                <tr>
                                    <th>Short Critical</th>
                                    <td><?php echo $weapon['shortCritical']; ?></td>
                                </tr>
                                <tr>
                                    <th>Long Critical</th>
                                    <td><?php echo $weapon['longCritical']; ?></td>
                                </tr>
                                <tr>
                                    <th>Magic Critical</th>
                                    <td><?php echo $weapon['magicCritical']; ?></td>
                                </tr>
                                <tr>
                                    <th>Exp Bonus</th>
                                    <td><?php echo $weapon['expBonus']; ?>%</td>
                                </tr>
                                <tr>
                                    <th>Haste</th>
                                    <td><?php echo $weapon['haste_item'] ? 'Yes' : 'No'; ?></td>
                                </tr>
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
    
    <div class="text-center mt-4 mb-5">
        <a href="list.php" class="btn btn-outline-light"><i class="fas fa-arrow-left mr-2"></i> Back to Weapons List</a>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>