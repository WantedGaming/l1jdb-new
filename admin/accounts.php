<?php include '../includes/admin_header.php'; ?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Account <span>Management</span></h1>
                <p class="hero-subtitle">View, edit and manage user accounts.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">User Accounts</h5>
                <div>
                    <a href="add_account.php" class="btn btn-sm btn-accent">Add Account</a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Login</th>
                                <th>Access Level</th>
                                <th>Last Active</th>
                                <th>IP</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $result = $conn->query("SELECT login, access_level, lastactive, ip, banned FROM accounts ORDER BY lastactive DESC LIMIT 20");
                            while($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?=$row['login']?></td>
                                <td><?=$row['access_level']?></td>
                                <td><?=$row['lastactive']?></td>
                                <td><?=$row['ip']?></td>
                                <td>
                                    <?php if($row['banned'] > 0): ?>
                                    <span class="badge bg-danger">Banned</span>
                                    <?php else: ?>
                                    <span class="badge bg-success">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="edit_account.php?login=<?=$row['login']?>" class="btn btn-sm btn-outline-light me-1">Edit</a>
                                    <?php if($row['banned'] > 0): ?>
                                    <a href="unban_account.php?login=<?=$row['login']?>" class="btn btn-sm btn-outline-success">Unban</a>
                                    <?php else: ?>
                                    <a href="ban_account.php?login=<?=$row['login']?>" class="btn btn-sm btn-outline-danger">Ban</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/admin_footer.php'; ?>