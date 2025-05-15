<?php
/**
 * security.php - Admin security functions
 * 
 * This file contains functions for admin authentication and security.
 */

/**
 * Checks if a user is authenticated and has admin privileges
 * 
 * @return boolean True if user is authenticated and has admin access, false otherwise
 */
function isAdminAuthenticated() {
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // Check if admin is logged in and has admin access level
    return (
        isset($_SESSION['admin_logged_in']) && 
        $_SESSION['admin_logged_in'] === true && 
        isset($_SESSION['admin_access_level']) && 
        $_SESSION['admin_access_level'] == 1
    );
}

/**
 * Ensures the user is authenticated, redirects to login page if not
 * 
 * @param string $redirect_url URL to redirect to after login (optional)
 * @return void
 */
function requireAdminAuth($redirect_url = '') {
    if (!isAdminAuthenticated()) {
        // Set redirect URL if provided
        if (!empty($redirect_url)) {
            $_SESSION['redirect_after_login'] = $redirect_url;
        }
        
        // Redirect to login page
        header("Location: /l1jdb-new/admin/login.php");
        exit;
    }
}

/**
 * Logs activity for audit purposes
 * 
 * @param string $action The action performed
 * @param string $target The target of the action (e.g., table name, ID)
 * @param string $details Additional details about the action
 * @return boolean True if log was successful, false otherwise
 */
function logAdminActivity($action, $target, $details = '') {
    global $conn;
    
    // Get admin username from session
    $admin_username = $_SESSION['admin_username'] ?? 'unknown';
    
    // Prepare statement
    $stmt = $conn->prepare("INSERT INTO admin_activity_log (admin_username, action, target, details, ip_address) VALUES (?, ?, ?, ?, ?)");
    
    // Check if table exists, if not, create it
    $tableCheckResult = $conn->query("SHOW TABLES LIKE 'admin_activity_log'");
    if ($tableCheckResult->num_rows == 0) {
        // Create table
        $createTableSQL = "CREATE TABLE `admin_activity_log` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `admin_username` varchar(50) NOT NULL,
            `action` varchar(50) NOT NULL,
            `target` varchar(50) NOT NULL,
            `details` text,
            `ip_address` varchar(45) NOT NULL,
            `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        
        $conn->query($createTableSQL);
    }
    
    if ($stmt) {
        $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        $stmt->bind_param("sssss", $admin_username, $action, $target, $details, $ip_address);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    
    return false;
}
