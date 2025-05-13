<?php
include '../includes/admin_header.php';

// Process unban request
if(isset($_GET['ip'])) {
    $ip = $_GET['ip'];
    
    // Delete from ban_ip table
    $stmt = $conn->prepare("DELETE FROM ban_ip WHERE address = ?");
    $stmt->bind_param("s", $ip);
    $result = $stmt->execute();
    
    if($result) {
        $message = "IP address $ip has been unbanned successfully.";
        $alert_type = "success";
    } else {
        $message = "Failed to unban IP address $ip.";
        $alert_type = "danger";
    }
}
?>

<section class="hero-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 text-center hero-content">
                <h1 class="hero-title">Unban <span>IP Address</span></h1>
                <p class="hero-subtitle">Remove an IP address from the ban list.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="container">
        <?php if(isset($message)): ?>
        <div class="alert alert-<?=$alert_type?> mb-4">
            <?=$message?>
        </div>
        <?php endif; ?>
        
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Banned IP Addresses</h5>
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
                            <?php 
                            $result = $conn->query("SELECT address, reason, registTime FROM ban_ip ORDER BY registTime DESC");
                            
                            if($result && $result->num_rows > 0):
                                while($row = $result->fetch_assoc()):
                            ?>
                            <tr>
                                <td><?=$row['address']?></td>
                                <td><span class="badge bg-danger"><?=$row['reason']?></span></td>
                                <td><?=$row['registTime']?></td>
                                <td>
                                    <a href="unban_ip.php?ip=<?=$row['address']?>" class="btn btn-sm btn-outline-light">Unban</a>
                                </td>
                            </tr>
                            <?php 
                                endwhile;
                            else:
                            ?>
                            <tr>
                                <td colspan="4" class="text-center">No banned IP addresses found</td>
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