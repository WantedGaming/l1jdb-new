<?php

// Add to includes/admin_functions.php
function getAccountMetrics() {
    global $conn;
    return [
        'total' => $conn->query("SELECT COUNT(*) as count FROM accounts")->fetch_assoc()['count'],
        'active' => $conn->query("SELECT COUNT(*) as count FROM accounts WHERE lastactive > DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetch_assoc()['count'],
        'banned' => $conn->query("SELECT COUNT(*) as count FROM accounts WHERE banned > 0")->fetch_assoc()['count'],
        'newToday' => $conn->query("SELECT COUNT(*) as count FROM accounts WHERE lastactive > DATE_SUB(NOW(), INTERVAL 24 HOUR) AND lastQuit IS NULL")->fetch_assoc()['count']
    ];
}

function getServerMetrics() {
    global $conn;
    $uptime = shell_exec('uptime -p');
    return [
        'uptime' => $uptime ? $uptime : "Unknown",
        'peakPlayers' => $conn->query("SELECT maxuser FROM serverinfo LIMIT 1")->fetch_assoc()['maxuser'] ?? 0,
        'memoryUsage' => shell_exec("free | grep Mem | awk '{print int($3/$2 * 100)}'") . '%'
    ];
}

function getEconomyMetrics() {
    global $conn;
    return [
        'adenaInCirculation' => $conn->query("SELECT SUM(adenmake) - SUM(adenconsume) as total FROM serverinfo")->fetch_assoc()['total'] ?? 0,
        'itemTransactions' => $conn->query("SELECT COUNT(*) as count FROM log_shop WHERE type = 'BUY' AND time > DATE_SUB(NOW(), INTERVAL 24 HOUR)")->fetch_assoc()['count'] ?? 0
    ];
}

function getBannedIPs($limit = 5) {
    global $conn;
    $result = $conn->query("SELECT address, reason, registTime FROM ban_ip ORDER BY registTime DESC LIMIT $limit");
    $bannedIPs = [];
    while($row = $result->fetch_assoc()) {
        $bannedIPs[] = $row;
    }
    return $bannedIPs;
}

function getRecentTransactions($limit = 5) {
    global $conn;
    // Fix: Explicit column aliases to match template references
    $sql = "SELECT 
        type, 
        user_name AS user, 
        item_name AS item, 
        total_price AS amount, 
        time 
        FROM log_shop 
        ORDER BY time DESC 
        LIMIT $limit";
    
    $result = $conn->query($sql);
    $transactions = [];
    
    if ($result) {
        while($row = $result->fetch_assoc()) {
            $transactions[] = $row;
        }
    }
    
    return $transactions;
}