<?php include '../includes/admin_header.php'; ?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Economy <span>Dashboard</span></h1>
                <p class="hero-subtitle">Monitor game economy metrics and transactions.</p>
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
                        <h5 class="mb-0">Economy Overview</h5>
                        <i class="fas fa-dollar-sign" style="color: var(--accent);"></i>
                    </div>
                    <div class="card-body">
                        <?php $economyMetrics = getEconomyMetrics(); ?>
                        <div class="row">
                            <div class="col-6 mb-3">
                                <div class="small text-white-50">Adena in Circulation</div>
                                <div class="h4"><?=number_format($economyMetrics['adenaInCirculation'])?></div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="small text-white-50">Item Transactions</div>
                                <div class="h4"><?=number_format($economyMetrics['itemTransactions'])?></div>
                            </div>
                        </div>
                        
                        <h6 class="mt-4 mb-3">Economy Status</h6>
                        <div class="progress mb-3" style="height: 10px;">
                            <div class="progress-bar bg-accent" role="progressbar" style="width: 65%;" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">65% Healthy</div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Top Selling Items</h5>
                        <i class="fas fa-chart-line" style="color: var(--accent);"></i>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Transactions</th>
                                        <th>Total Value</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $result = $conn->query("SELECT item_name, COUNT(*) as num_transactions, SUM(total_price) as total_value FROM log_shop GROUP BY item_name ORDER BY num_transactions DESC LIMIT 5");
                                    
                                    if($result && $result->num_rows > 0):
                                        while($row = $result->fetch_assoc()):
                                    ?>
                                    <tr>
                                        <td><?=$row['item_name']?></td>
                                        <td><?=number_format($row['num_transactions'])?></td>
                                        <td><?=number_format($row['total_value'])?></td>
                                    </tr>
                                    <?php 
                                        endwhile;
                                    else:
                                    ?>
                                    <tr>
                                        <td colspan="3" class="text-center">No transaction data available</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Transactions</h5>
            </div>
            <div class="card-body">
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
                            <?php foreach(getRecentTransactions(10) as $tx): ?>
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
    </div>
</section>

<?php include '../includes/admin_footer.php'; ?>