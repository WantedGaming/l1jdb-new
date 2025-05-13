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
                        <img src="/l1jdb-new/assets/img/icons/monsters/ms<?php echo $monster['spriteId']; ?>.gif" 
                             alt="<?php echo htmlspecialchars($monster['desc_en']); ?>" 
                             class="img-fluid weapon-image" 
                             onerror="this.onerror=null;this.src='/l1jdb-new/assets/img/icons/monsters/ms<?php echo $monster['spriteId']; ?>.png';
                                      this.onerror=function(){this.src='/l1jdb-new/assets/img/placeholders/monster-placeholder.png';}">
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
                    <div class="row">
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
                                        <th class="stat-label">HP</th>
                                        <td><?php echo $monster['hp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">MP</th>
                                        <td><?php echo $monster['mp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">AC</th>
                                        <td><?php echo $monster['ac']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">EXP</th>
                                        <td><?php echo $monster['exp']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Alignment</th>
                                        <td><?php echo $monster['alignment']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Size</th>
                                        <td><?php echo $monster['big'] == 'true' ? 'Large' : 'Normal'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
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
                                    <tr>
                                        <th class="stat-label">Is Boss</th>
                                        <td><?php echo $monster['is_bossmonster'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Tameable</th>
                                        <td><?php echo $monster['is_taming'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Aggressive</th>
                                        <td><?php echo $monster['is_agro'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Teleport</th>
                                        <td><?php echo $monster['is_teleport'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Picks Up Items</th>
                                        <td><?php echo $monster['is_picupitem'] == 'true' ? 'Yes' : 'No'; ?></td>
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
        <!-- Stats -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Attributes</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">STR</th>
                                        <td><?php echo $monster['str']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">DEX</th>
                                        <td><?php echo $monster['dex']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">CON</th>
                                        <td><?php echo $monster['con']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">WIS</th>
                                        <td><?php echo $monster['wis']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">INT</th>
                                        <td><?php echo $monster['intel']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">MR</th>
                                        <td><?php echo $monster['mr']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
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
            </div>
        </div>
        
        <!-- Special Abilities -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Special Abilities</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Poison Attack</th>
                                        <td><?php echo $monster['poison_atk']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Aggro Invis</th>
                                        <td><?php echo $monster['is_agro_invis'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Aggro Poly</th>
                                        <td><?php echo $monster['is_agro_poly'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Brave Speed</th>
                                        <td><?php echo $monster['is_bravespeed'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Digestible</th>
                                        <td><?php echo $monster['digestitem'] > 0 ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
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
                                        <th class="stat-label">Random Level</th>
                                        <td><?php echo $monster['randomlevel']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Can be Resurrected</th>
                                        <td><?php echo $monster['cant_resurrect'] == 'false' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Can be Turn Undead</th>
                                        <td><?php echo $monster['can_turnundead'] == 'true' ? 'Yes' : 'No'; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="text-center mt-4 mb-5">
        <a href="monster_list.php" class="btn btn-outline-light"><i class="fas fa-arrow-left mr-2"></i> Back to Monster List</a>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>