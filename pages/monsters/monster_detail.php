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
    
    <div class="text-center mt-4 mb-5">
        <a href="monsters_list.php" class="btn btn-outline-light"><i class="fas fa-arrow-left mr-2"></i> Back to Monster List</a>
    </div>
</section>

<?php include '../../includes/footer.php'; ?>