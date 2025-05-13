<?php include '../includes/admin_header.php'; ?>

<!-- Modern Admin Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Admin <span>Dashboard</span></h1>
                <p class="hero-subtitle">Manage and modify the L1J Remastered database content through this administrative interface.</p>
                <form class="search-form" action="search.php" method="GET">
                    <div class="position-relative">
                        <input class="form-control" type="search" name="q" placeholder="Search database items..." aria-label="Search">
                        <button class="btn btn-accent" type="submit">Search</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Enhanced Stats Overview -->
<section class="py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">User Accounts</h5>
                        <i class="fas fa-user-circle" style="color: var(--accent);"></i>
                    </div>
                    <div class="card-body d-flex flex-column" style="min-height: 240px;">
                        <?php $accountMetrics = getAccountMetrics(); ?>
                        <div class="row mb-auto">
                            <div class="col-6 mb-3">
                                <div class="small text-white-50">Total Accounts</div>
                                <div class="h4"><?=number_format($accountMetrics['total'])?></div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="small text-white-50">Active Today</div>
                                <div class="h4"><?=number_format($accountMetrics['active'])?></div>
                            </div>
                            <div class="col-6">
                                <div class="small text-white-50">Banned Accounts</div>
                                <div class="h4"><?=number_format($accountMetrics['banned'])?></div>
                            </div>
                            <div class="col-6">
                                <div class="small text-white-50">New Today</div>
                                <div class="h4">+<?=number_format($accountMetrics['newToday'])?></div>
                            </div>
                        </div>
                        <a href="accounts.php" class="btn btn-sm btn-accent w-100 mt-3">View Account Details</a>
                    </div>
                </div>
            </div>

            <!-- Server Stats -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Server Status</h5>
                        <i class="fas fa-server" style="color: var(--accent);"></i>
                    </div>
                    <div class="card-body d-flex flex-column" style="min-height: 240px;">
                        <?php $serverMetrics = getServerMetrics(); ?>
                        <div class="row mb-auto">
                            <div class="col-6 mb-3">
                                <div class="small text-white-50">Uptime</div>
                                <div class="h4"><?=$serverMetrics['uptime']?></div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="small text-white-50">Peak Players</div>
                                <div class="h4"><?=number_format($serverMetrics['peakPlayers'])?></div>
                            </div>
                            <div class="col-6">
                                <div class="small text-white-50">Memory Usage</div>
                                <div class="h4"><?=$serverMetrics['memoryUsage']?></div>
                            </div>
                        </div>
                        <a href="server_logs.php" class="btn btn-sm btn-accent w-100 mt-3">View Server Logs</a>
                    </div>
                </div>
            </div>

            <!-- Economy Stats -->
            <div class="col-lg-4 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Economy Status</h5>
                        <i class="fas fa-dollar-sign" style="color: var(--accent);"></i>
                    </div>
                    <div class="card-body d-flex flex-column" style="min-height: 240px;">
                        <?php $economyMetrics = getEconomyMetrics(); ?>
                        <div class="row mb-auto">
                            <div class="col-6 mb-3">
                                <div class="small text-white-50">Adena Circulation</div>
                                <div class="h4"><?=number_format($economyMetrics['adenaInCirculation']/1000000, 1)?>M</div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="small text-white-50">Transactions</div>
                                <div class="h4"><?=number_format($economyMetrics['itemTransactions'])?></div>
                            </div>
                        </div>
                        <a href="economy.php" class="btn btn-sm btn-accent w-100 mt-3">View Economy Report</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Actions -->
<section class="py-4">
    <div class="container">
        <h2 class="section-title mb-4">Quick Actions</h2>
        <div class="row g-3">
            <div class="col-md-3">
                <a href="backup.php" class="card text-decoration-none h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <div class="mb-3" style="width: 50px; height: 50px; background-color: var(--accent-transparent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-database" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="card-title">Backup Database</h5>
                        <p class="card-text small text-white-50">Create a full backup of the database</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="logs.php" class="card text-decoration-none h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <div class="mb-3" style="width: 50px; height: 50px; background-color: var(--accent-transparent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-list" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="card-title">View Logs</h5>
                        <p class="card-text small text-white-50">Check system and error logs</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="users.php" class="card text-decoration-none h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <div class="mb-3" style="width: 50px; height: 50px; background-color: var(--accent-transparent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-users" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="card-title">Manage Users</h5>
                        <p class="card-text small text-white-50">Add or edit admin users</p>
                    </div>
                </a>
            </div>
            <div class="col-md-3">
                <a href="settings.php" class="card text-decoration-none h-100">
                    <div class="card-body d-flex flex-column align-items-center text-center">
                        <div class="mb-3" style="width: 50px; height: 50px; background-color: var(--accent-transparent); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-cog" style="color: var(--accent);"></i>
                        </div>
                        <h5 class="card-title">Settings</h5>
                        <p class="card-text small text-white-50">Configure system settings</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Security Section -->
<section class="py-4">
    <div class="container">
        <h2 class="section-title mb-4">Security</h2>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Banned IPs</h5>
                        <i class="fas fa-shield-alt" style="color: var(--accent);"></i>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>IP Address</th>
                                        <th>Reason</th>
                                        <th>Ban Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach(getBannedIPs() as $ip): ?>
                                    <tr>
                                        <td><?=$ip['address']?></td>
                                        <td><span class="badge bg-danger"><?=$ip['reason']?></span></td>
                                        <td><?=$ip['registTime']?></td>
                                        <td>
                                            <a href="unban_ip.php?ip=<?=$ip['address']?>" class="btn btn-sm btn-outline-light">Unban</a>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Admin Categories -->
<section class="py-5">
    <div class="container">
        <h2 class="section-title mb-4">Database Management</h2>
        
        <div class="row g-4">
            <?php 
            $categories = getCategories();
            
            foreach($categories as $category): 
            ?>
                <div class="col-lg-4 col-md-6">
                    <div class="card h-100">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0"><?php echo $category['name']; ?></h5>
                            <span class="badge bg-accent"><?php echo count($category['sql']); ?> tables</span>
                        </div>
                        <div class="card-body d-flex flex-column">
                            <p class="card-text"><?php echo getCategoryDescription($category['id']); ?></p>
                            <p class="small text-muted mb-3">SQL Tables: <?php echo implode(', ', $category['sql']); ?></p>
                            <div class="mt-auto d-flex">
                                <a href="<?php echo $category['id']; ?>/list.php" class="btn btn-accent me-2">Manage</a>
                                <a href="<?php echo $category['id']; ?>/create.php" class="btn btn-outline-light">Add New</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Recent Transactions -->
<section class="py-4">
    <div class="container">
        <h2 class="section-title mb-4">Recent Transactions</h2>
        <div class="card">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Type</th>
                            <th>User</th>
                            <th>Item</th>
                            <th>Amount</th>
                            <th>Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach(getRecentTransactions() as $tx): ?>
                        <tr>
                            <td><span class="badge bg-accent"><?=$tx['type']?></span></td>
                            <td><?=$tx['user']?></td>
                            <td><?=$tx['item']?></td>
                            <td><?=number_format($tx['amount'])?></td>
                            <td><?=$tx['time']?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/admin_footer.php'; ?>