<?php include '../includes/admin_header.php'; ?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Server <span>Logs</span></h1>
                <p class="hero-subtitle">Monitor server performance and activity.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Server Status</h5>
                        <div>
                            <button class="btn btn-sm btn-accent" onclick="window.location.reload()">Refresh</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php $serverMetrics = getServerMetrics(); ?>
                        <div class="row">
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
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header">
                        <h5 class="mb-0">System Logs</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <select class="form-select bg-dark text-white border-secondary">
                                <option value="system">System Log</option>
                                <option value="errors">Error Log</option>
                                <option value="login">Login Log</option>
                            </select>
                        </div>
                        <div class="log-content p-3 rounded" style="height:200px; overflow-y:auto; background-color:rgba(0,0,0,0.3); font-family:monospace; font-size:12px;">
                            <!-- Sample log content - would be loaded from server logs -->
                            [2025-05-13 10:23:15] Server started<br>
                            [2025-05-13 10:25:32] Database connection established<br>
                            [2025-05-13 10:31:47] Player login: AdminUser<br>
                            [2025-05-13 10:35:19] Player logout: AdminUser<br>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Active Connections</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Account</th>
                                <th>Character</th>
                                <th>IP Address</th>
                                <th>Login Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT a.login, c.char_name, a.ip, a.lastactive FROM accounts a LEFT JOIN characters c ON a.login = c.account_name WHERE a.OnlineStatus = 1 ORDER BY a.lastactive DESC LIMIT 20");
                            
                            if($result && $result->num_rows > 0):
                                while($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?=$row['login']?></td>
                                <td><?=$row['char_name'] ?? 'N/A'?></td>
                                <td><?=$row['ip']?></td>
                                <td><?=$row['lastactive']?></td>
                                <td>
                                    <a href="kick_player.php?account=<?=$row['login']?>" class="btn btn-sm btn-outline-danger">Disconnect</a>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="5" class="text-center">No active connections</td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/admin_footer.php'; ?>