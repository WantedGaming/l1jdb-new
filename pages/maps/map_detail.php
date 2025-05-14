<?php
require_once '../../includes/header.php';

// Get map ID from URL
$map_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Fetch map details
$sql = "SELECT * FROM mapids WHERE mapid = $map_id";
$result = $conn->query($sql);

// Check if map exists
if ($result->num_rows == 0) {
    echo '<div class="container mt-5"><div class="alert alert-danger">Map not found</div></div>';
    include '../../includes/footer.php';
    exit;
}

$map = $result->fetch_assoc();

// Helper function to convert boolean integer to Yes/No text
function boolToText($value) {
    return ($value == 1) ? 'Yes' : 'No';
}

// Helper function to convert boolean to badge
function boolToBadge($value, $positive_class = 'bg-accent', $negative_class = 'bg-secondary') {
    if ($value == 1) {
        return '<span class="badge ' . $positive_class . '">Yes</span>';
    } else {
        return '<span class="badge ' . $negative_class . '">No</span>';
    }
}
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="hero-content text-center">
            <h1 class="hero-title">Map <span>Details</span></h1>
            <p class="hero-subtitle"><?php echo htmlspecialchars($map['locationname'] ?: 'Map #' . $map['mapid']); ?></p>
        </div>
    </div>
</section>

<!-- Map Detail Section -->
<section class="container mt-5">
    <!-- Full-width Map Image Card -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card weapon-card">
                <div class="card-header">
                    <h5><?php echo htmlspecialchars($map['locationname'] ?: 'Map #' . $map['mapid']); ?></h5>
                </div>
                <div class="card-body p-0 d-flex flex-column">
                    <div class="weapon-image-container flex-grow-1" style="min-height: 450px; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                        <?php
                        $mapImagePath = '/l1jdb-new/assets/img/icons/maps/' . $map['pngId'] . '.jpeg';
                        $placeholderPath = '/l1jdb-new/assets/img/placeholders/map-placeholder.png';
                        ?>
                        <img src="<?php echo $mapImagePath; ?>" 
                             alt="<?php echo htmlspecialchars($map['locationname']); ?>" 
                             class="img-fluid weapon-image" style="width: 100%; max-height: 100%; object-fit: cover;"
                             onerror="this.src='<?php echo $placeholderPath; ?>'">
                    </div>
                    <div class="weapon-info p-3 mt-auto text-center" style="background-color: rgba(8, 8, 8, 0.95);">
                        <div class="badges mb-3">
                            <span class="badge bg-info">ID: <?php echo $map['mapid']; ?></span>
                            <?php if ($map['dungeon'] == 1): ?>
                                <span class="badge bg-secondary ml-2">Dungeon</span>
                            <?php endif; ?>
                            <?php if ($map['underwater'] == 1): ?>
                                <span class="badge bg-info ml-2">Underwater</span>
                            <?php endif; ?>
                            <?php if ($map['beginZone'] == 1): ?>
                                <span class="badge bg-success ml-2">Beginner Zone</span>
                            <?php endif; ?>
                            <?php if ($map['redKnightZone'] == 1): ?>
                                <span class="badge bg-danger ml-2">Red Knight Zone</span>
                            <?php endif; ?>
                            <?php if ($map['ruunCastleZone'] == 1): ?>
                                <span class="badge bg-warning ml-2">Ruun Castle Zone</span>
                            <?php endif; ?>
                            <?php if ($map['interWarZone'] == 1): ?>
                                <span class="badge bg-primary ml-2">Inter War Zone</span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($map['desc_kr'])): ?>
                            <p class="card-text">
                                Korean Name: <?php echo htmlspecialchars($map['desc_kr']); ?>
                            </p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Basic Information -->
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Basic Information</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Map ID</th>
                                        <td><?php echo $map['mapid']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Location Name</th>
                                        <td><?php echo htmlspecialchars($map['locationname'] ?: 'Not specified'); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Korean Name</th>
                                        <td><?php echo htmlspecialchars($map['desc_kr']); ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Coordinates X</th>
                                        <td><?php echo $map['startX']; ?> - <?php echo $map['endX']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Coordinates Y</th>
                                        <td><?php echo $map['startY']; ?> - <?php echo $map['endY']; ?></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th class="stat-label">Monster Amount</th>
                                        <td><?php echo $map['monster_amount']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Drop Rate</th>
                                        <td><?php echo $map['drop_rate']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">Inter Kind</th>
                                        <td><?php echo $map['interKind']; ?></td>
                                    </tr>
                                    <tr>
                                        <th class="stat-label">PNG ID</th>
                                        <td><?php echo $map['pngId']; ?></td>
                                    </tr>
                                    <?php if (!empty($map['script'])): ?>
                                    <tr>
                                        <th class="stat-label">Script</th>
                                        <td><?php echo htmlspecialchars($map['script']); ?></td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Special Area Indicators -->
                    <div class="text-center mb-2">
                        <div class="btn-group btn-group-lg" role="group" aria-label="Area Types">
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="underwater">
                                <span>Underwater</span>
                                <span class="d-block"><?php echo boolToText($map['underwater']); ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="dungeon">
                                <span>Dungeon</span>
                                <span class="d-block"><?php echo boolToText($map['dungeon']); ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="beginZone">
                                <span>Beginner</span>
                                <span class="d-block"><?php echo boolToText($map['beginZone']); ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="redKnight">
                                <span>Red Knight</span>
                                <span class="d-block"><?php echo boolToText($map['redKnightZone']); ?></span>
                            </button>
                        </div>
                    </div>
                    <div class="text-center">
                        <div class="btn-group btn-group-lg" role="group" aria-label="Additional Area Types">
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="ruunCastle">
                                <span>Ruun Castle</span>
                                <span class="d-block"><?php echo boolToText($map['ruunCastleZone']); ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="interWar">
                                <span>Inter War</span>
                                <span class="d-block"><?php echo boolToText($map['interWarZone']); ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="geradBuff">
                                <span>Gerad Buff</span>
                                <span class="d-block"><?php echo boolToText($map['geradBuffZone']); ?></span>
                            </button>
                            <button type="button" class="btn btn-outline-light stat-btn" data-stat="growBuff">
                                <span>Grow Buff</span>
                                <span class="d-block"><?php echo boolToText($map['growBuffZone']); ?></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        <!-- Map Properties -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Map Properties</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="stat-label">Teleportable</th>
                                <td><?php echo boolToBadge($map['teleportable']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Escapable</th>
                                <td><?php echo boolToBadge($map['escapable']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Resurrection</th>
                                <td><?php echo boolToBadge($map['resurrection']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Pain Wand</th>
                                <td><?php echo boolToBadge($map['painwand']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Penalty</th>
                                <td><?php echo boolToBadge($map['penalty']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Take Pets</th>
                                <td><?php echo boolToBadge($map['take_pets']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Recall Pets</th>
                                <td><?php echo boolToBadge($map['recall_pets']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        <!-- Additional Properties -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Usage Properties</h5>
                </div>
                <div class="card-body">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th class="stat-label">Usable Items</th>
                                <td><?php echo boolToBadge($map['usable_item']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Usable Skills</th>
                                <td><?php echo boolToBadge($map['usable_skill']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Markable</th>
                                <td><?php echo boolToBadge($map['markable']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Decrease HP</th>
                                <td><?php echo boolToBadge($map['decreaseHp'], 'bg-danger'); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">Domination Teleport</th>
                                <td><?php echo boolToBadge($map['dominationTeleport']); ?></td>
                            </tr>
                            <tr>
                                <th class="stat-label">PC→NPC Damage Modifier</th>
                                <td><?php echo $map['dmgModiPc2Npc']; ?>%</td>
                            </tr>
                            <tr>
                                <th class="stat-label">NPC→PC Damage Modifier</th>
                                <td><?php echo $map['dmgModiNpc2Pc']; ?>%</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monster Spawns Section -->
    <?php
    // Fetch monsters that spawn on this map from various tables
    
    // Regular spawnlist
    $spawn_sql = "SELECT s.*, n.desc_en, n.lvl, n.is_bossmonster 
                  FROM spawnlist s 
                  JOIN npc n ON s.npc_templateid = n.npcid 
                  WHERE s.mapid = $map_id 
                  ORDER BY n.lvl DESC, n.desc_en ASC";
    $spawn_result = $conn->query($spawn_sql);
    
    // Boss spawnlist
    $boss_sql = "SELECT b.*, n.desc_en, n.lvl 
                 FROM spawnlist_boss b 
                 JOIN npc n ON b.npcid = n.npcid 
                 WHERE b.spawnMapId = $map_id 
                 ORDER BY n.lvl DESC, n.desc_en ASC";
    $boss_result = $conn->query($boss_sql);
    
    // Additional spawnlists
    $additional_spawns = [];
    
    // spawnlist_ruun
    $ruun_sql = "SELECT r.*, n.desc_en, n.lvl 
                 FROM spawnlist_ruun r 
                 JOIN npc n ON r.npcId = n.npcid 
                 WHERE r.mapId = $map_id";
    $additional_spawns['Ruun Castle'] = $conn->query($ruun_sql);
    
    // spawnlist_unicorntemple
    $unicorn_sql = "SELECT u.*, n.desc_en, n.lvl 
                   FROM spawnlist_unicorntemple u 
                   JOIN npc n ON u.npc_id = n.npcid 
                   WHERE u.mapId = $map_id";
    $additional_spawns['Unicorn Temple'] = $conn->query($unicorn_sql);
    
    // spawnlist_worldwar
    $worldwar_sql = "SELECT w.*, n.desc_en, n.lvl 
                     FROM spawnlist_worldwar w 
                     JOIN npc n ON w.npc_id = n.npcid 
                     WHERE w.mapid = $map_id";
    $additional_spawns['World War'] = $conn->query($worldwar_sql);
    
    // spawnlist_other
    $other_sql = "SELECT o.*, n.desc_en, n.lvl 
                  FROM spawnlist_other o 
                  JOIN npc n ON o.npc_id = n.npcid 
                  WHERE o.mapId = $map_id";
    $additional_spawns['Other'] = $conn->query($other_sql);
    
    // Determine if we have any spawns to display
    $has_spawns = ($spawn_result && $spawn_result->num_rows > 0) || 
                  ($boss_result && $boss_result->num_rows > 0);
                  
    foreach ($additional_spawns as $result) {
        if ($result && $result->num_rows > 0) {
            $has_spawns = true;
            break;
        }
    }
    
    if ($has_spawns):
    ?>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Monster Spawns</h5>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs mb-3" id="spawnsTab" role="tablist">
                        <?php if ($spawn_result && $spawn_result->num_rows > 0): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="regular-tab" data-bs-toggle="tab" data-bs-target="#regular" type="button" role="tab" aria-controls="regular" aria-selected="true">Regular</button>
                        </li>
                        <?php endif; ?>
                        
                        <?php if ($boss_result && $boss_result->num_rows > 0): ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo (!$spawn_result || $spawn_result->num_rows == 0) ? 'active' : ''; ?>" id="boss-tab" data-bs-toggle="tab" data-bs-target="#boss" type="button" role="tab" aria-controls="boss" aria-selected="false">Boss</button>
                        </li>
                        <?php endif; ?>
                        
                        <?php 
                        $active_set = ($spawn_result && $spawn_result->num_rows > 0) || 
                                      ($boss_result && $boss_result->num_rows > 0);
                                      
                        foreach ($additional_spawns as $type => $result):
                            if ($result && $result->num_rows > 0):
                                $tab_id = strtolower(str_replace(' ', '-', $type));
                        ?>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link <?php echo (!$active_set) ? 'active' : ''; $active_set = true; ?>" 
                                    id="<?php echo $tab_id; ?>-tab" 
                                    data-bs-toggle="tab" 
                                    data-bs-target="#<?php echo $tab_id; ?>" 
                                    type="button" role="tab" 
                                    aria-controls="<?php echo $tab_id; ?>" 
                                    aria-selected="false"><?php echo $type; ?></button>
                        </li>
                        <?php endif; endforeach; ?>
                    </ul>
                    
                    <div class="tab-content" id="spawnsTabContent">
                        <!-- Regular Spawns Tab -->
                        <?php if ($spawn_result && $spawn_result->num_rows > 0): ?>
                        <div class="tab-pane fade show active" id="regular" role="tabpanel" aria-labelledby="regular-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="48"></th>
                                            <th>Name</th>
                                            <th class="text-center">Level</th>
                                            <th class="text-center">Count</th>
                                            <th class="text-center">Location</th>
                                            <th class="text-center">Respawn Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Reset result pointer to first row
                                        mysqli_data_seek($spawn_result, 0);
                                        
                                        // Create an array to collect npc IDs for later drops query
                                        $map_monster_ids = [];
                                        
                                        while ($spawn = $spawn_result->fetch_assoc()):
                                            // Add monster ID to our collection
                                            $map_monster_ids[] = $spawn['npc_templateid'];
                                            
                                            // Fetch sprite ID for this monster
                                            $sprite_sql = "SELECT spriteId FROM npc WHERE npcid = " . $spawn['npc_templateid'];
                                            $sprite_result = $conn->query($sprite_sql);
                                            $spriteId = ($sprite_result && $sprite_result->num_rows > 0) ? 
                                                       $sprite_result->fetch_assoc()['spriteId'] : 0;
                                        ?>
                                        <tr class="clickable-row" data-href="../monsters/monster_detail.php?id=<?php echo $spawn['npc_templateid']; ?>">
                                            <td width="48">
                                                <img src="/l1jdb-new/assets/img/icons/monsters/ms<?php echo $spriteId; ?>.gif" 
                                                     alt="<?php echo htmlspecialchars($spawn['desc_en']); ?>" 
                                                     style="width: 32px; height: 32px;"
                                                     onerror="this.onerror=null;this.src='/l1jdb-new/assets/img/icons/monsters/ms<?php echo $spriteId; ?>.png';
                                                           this.onerror=function(){this.src='/l1jdb-new/assets/img/placeholders/monster-placeholder.png';}">
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($spawn['desc_en']); ?>
                                                <?php if ($spawn['is_bossmonster'] == 'true'): ?>
                                                <span class="badge bg-danger ms-2">Boss</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-center"><?php echo $spawn['lvl']; ?></td>
                                            <td class="text-center"><?php echo $spawn['count']; ?></td>
                                            <td class="text-center">
                                                <?php 
                                                if ($spawn['locx'] > 0 && $spawn['locy'] > 0) {
                                                    echo "(" . $spawn['locx'] . ", " . $spawn['locy'] . ")";
                                                    if ($spawn['randomx'] > 0 || $spawn['randomy'] > 0) {
                                                        echo " ±(" . $spawn['randomx'] . ", " . $spawn['randomy'] . ")";
                                                    }
                                                } elseif ($spawn['locx1'] > 0 && $spawn['locy1'] > 0 && $spawn['locx2'] > 0 && $spawn['locy2'] > 0) {
                                                    echo "Area: (" . $spawn['locx1'] . ", " . $spawn['locy1'] . ") to (" . $spawn['locx2'] . ", " . $spawn['locy2'] . ")";
                                                } else {
                                                    echo "Random";
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php 
                                                if ($spawn['min_respawn_delay'] > 0 || $spawn['max_respawn_delay'] > 0) {
                                                    // Convert seconds to minutes
                                                    $min_minutes = floor($spawn['min_respawn_delay'] / 60);
                                                    $max_minutes = floor($spawn['max_respawn_delay'] / 60);
                                                    
                                                    if ($min_minutes == $max_minutes) {
                                                        echo $min_minutes . " min";
                                                    } else {
                                                        echo $min_minutes . "-" . $max_minutes . " min";
                                                    }
                                                } else {
                                                    echo "Immediate";
                                                }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Boss Spawns Tab -->
                        <?php if ($boss_result && $boss_result->num_rows > 0): ?>
                        <div class="tab-pane fade <?php echo (!$spawn_result || $spawn_result->num_rows == 0) ? 'show active' : ''; ?>" id="boss" role="tabpanel" aria-labelledby="boss-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th width="48"></th>
                                            <th>Name</th>
                                            <th class="text-center">Level</th>
                                            <th class="text-center">Location</th>
                                            <th class="text-center">Spawn Time</th>
                                            <th class="text-center">Group</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        // Reset result pointer to first row
                                        mysqli_data_seek($boss_result, 0);
                                        
                                        while ($boss = $boss_result->fetch_assoc()): 
                                            // Add monster ID to our collection
                                            $map_monster_ids[] = $boss['npcid'];
                                            
                                            // Fetch sprite ID for this monster
                                            $sprite_sql = "SELECT spriteId FROM npc WHERE npcid = " . $boss['npcid'];
                                            $sprite_result = $conn->query($sprite_sql);
                                            $spriteId = ($sprite_result && $sprite_result->num_rows > 0) ? 
                                                       $sprite_result->fetch_assoc()['spriteId'] : 0;
                                        ?>
                                        <tr class="clickable-row" data-href="../monsters/monster_detail.php?id=<?php echo $boss['npcid']; ?>">
                                            <td width="48">
                                                <img src="/l1jdb-new/assets/img/icons/monsters/ms<?php echo $spriteId; ?>.gif" 
                                                     alt="<?php echo htmlspecialchars($boss['desc_en']); ?>" 
                                                     style="width: 32px; height: 32px;"
                                                     onerror="this.onerror=null;this.src='/l1jdb-new/assets/img/icons/monsters/ms<?php echo $spriteId; ?>.png';
                                                           this.onerror=function(){this.src='/l1jdb-new/assets/img/placeholders/monster-placeholder.png';}">
                                            </td>
                                            <td>
                                                <?php echo htmlspecialchars($boss['desc_en']); ?>
                                                <span class="badge bg-danger ms-2">Boss</span>
                                            </td>
                                            <td class="text-center"><?php echo $boss['lvl']; ?></td>
                                            <td class="text-center">(<?php echo $boss['spawnX']; ?>, <?php echo $boss['spawnY']; ?>)</td>
                                            <td class="text-center">
                                                <?php 
                                                echo !empty($boss['spawnDay']) ? htmlspecialchars($boss['spawnDay']) . ', ' : '';
                                                echo !empty($boss['spawnTime']) ? htmlspecialchars($boss['spawnTime']) : 'Random';
                                                if ($boss['rndMinut'] > 0) {
                                                    echo ' ±' . $boss['rndMinut'] . ' min';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center"><?php echo $boss['groupid'] > 0 ? $boss['groupid'] : '-'; ?></td>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Additional Spawn Tabs -->
                        <?php 
                        $active_set = ($spawn_result && $spawn_result->num_rows > 0) || 
                                      ($boss_result && $boss_result->num_rows > 0);
                                      
                        foreach ($additional_spawns as $type => $result):
                            if ($result && $result->num_rows > 0):
                                $tab_id = strtolower(str_replace(' ', '-', $type));
                        ?>
                        <div class="tab-pane fade <?php echo (!$active_set) ? 'show active' : ''; $active_set = true; ?>" 
                             id="<?php echo $tab_id; ?>" role="tabpanel" aria-labelledby="<?php echo $tab_id; ?>-tab">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th class="text-center">Level</th>
                                            <?php if ($type == 'Ruun Castle'): ?>
                                            <th class="text-center">Stage</th>
                                            <th class="text-center">Count</th>
                                            <?php else: ?>
                                            <th class="text-center">Location</th>
                                            <th class="text-center">Type</th>
                                            <?php endif; ?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php while ($spawn = $result->fetch_assoc()): ?>
                                        <tr class="clickable-row" data-href="../monsters/monster_detail.php?id=<?php echo 
                                            $type == 'Ruun Castle' ? $spawn['npcId'] : 
                                            $spawn['npc_id']; ?>">
                                            <td><?php echo htmlspecialchars($spawn['desc_en']); ?></td>
                                            <td class="text-center"><?php echo $spawn['lvl']; ?></td>
                                            <?php if ($type == 'Ruun Castle'): ?>
                                            <td class="text-center"><?php echo $spawn['stage']; ?></td>
                                            <td class="text-center"><?php echo $spawn['count']; ?></td>
                                            <?php else: ?>
                                            <td class="text-center">
                                                <?php 
                                                if (isset($spawn['locx']) && isset($spawn['locy'])) {
                                                    echo "(" . $spawn['locx'] . ", " . $spawn['locy'] . ")";
                                                } elseif (isset($spawn['locX']) && isset($spawn['locY'])) {
                                                    echo "(" . $spawn['locX'] . ", " . $spawn['locY'] . ")";
                                                } else {
                                                    echo "Location varies";
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <?php 
                                                if (isset($spawn['type'])) {
                                                    echo $spawn['type'];
                                                } elseif (isset($spawn['spawnType'])) {
                                                    echo $spawn['spawnType'];
                                                } else {
                                                    echo "-";
                                                }
                                                ?>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                        <?php endwhile; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <?php endif; endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- Items Dropped on this Map -->
    <?php
    // Process the monster IDs we've collected to query possible drops
    if (!empty($map_monster_ids)):
        $monster_ids_str = implode(',', $map_monster_ids);
        
        // Get drops from all monsters that spawn in this map
        $drops_sql = "SELECT d.*, n.desc_en AS monster_name, n.lvl AS monster_level 
                     FROM droplist d 
                     JOIN npc n ON d.mobId = n.npcid 
                     WHERE d.mobId IN ($monster_ids_str) 
                     ORDER BY d.chance DESC 
                     LIMIT 50"; // Limit to top 50 items to avoid overwhelming display
        
        $drops_result = $conn->query($drops_sql);
        
        // Only show the section if we have drops
        if ($drops_result && $drops_result->num_rows > 0):
    ?>
    <div class="row">
        <div class="col-12 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5>Notable Items Dropped in this Area</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="48">Image</th>
                                    <th>Item</th>
                                    <th>Type</th>
                                    <th>Monster</th>
                                    <th class="text-center">Amount</th>
                                    <th class="text-center">Chance</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($drop = $drops_result->fetch_assoc()):
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
                                    $typeDisplay = isset($item['type']) ? normalizeText($item['type']) : 'Unknown';
                                ?>
                                <tr class="<?php echo !empty($detail_link) ? 'clickable-row' : ''; ?>" data-href="<?php echo $detail_link; ?>">
                                    <td>
                                        <img src="/l1jdb-new/assets/img/icons/icons/<?php echo $item['iconId']; ?>.png" 
                                             alt="<?php echo htmlspecialchars($cleanItemName); ?>" 
                                             style="width: 32px; height: 32px;" 
                                             onerror="this.src='/l1jdb-new/assets/img/placeholders/noiconid.png';">
                                    </td>
                                    <td><?php echo htmlspecialchars($cleanItemName); ?></td>
                                    <td>
                                        <span class="badge bg-info"><?php echo $item_type; ?><?php echo $typeDisplay != 'Unknown' ? ' - ' . htmlspecialchars($typeDisplay) : ''; ?></span>
                                    </td>
                                    <td>
                                        <a href="../monsters/monster_detail.php?id=<?php echo $drop['mobId']; ?>">
                                            <?php echo htmlspecialchars($drop['monster_name']); ?> (Lv. <?php echo $drop['monster_level']; ?>)
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                        if ($drop['min'] == $drop['max']) {
                                            echo $drop['min'];
                                        } else {
                                            echo $drop['min'] . '-' . $drop['max']; 
                                        }
                                        ?>
                                    </td>
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
    <?php endif; endif; ?>
    
    <div class="text-center mt-4 mb-5">
        <a href="<?php echo getBackUrl('maps_list.php'); ?>" class="btn btn-outline-light"><i class="fas fa-arrow-left mr-2"></i> Back to Maps List</a>
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
