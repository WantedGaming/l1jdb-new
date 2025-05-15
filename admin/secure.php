<?php
/**
 * Admin security check - Include this at the top of all admin pages
 * Uses relative path to find the security file based on current directory depth
 */

// Determine relative path to admin_security.php based on current file path
$current_path = $_SERVER['SCRIPT_FILENAME'];
$admin_pos = strpos($current_path, 'admin');
$path_depth = substr_count(substr($current_path, $admin_pos), '/');

// Build the relative path based on directory depth
$relative_path = str_repeat('../', $path_depth);
$security_path = $relative_path . 'includes/admin_security.php';

// Include security file and require authentication
require_once $security_path;
requireAdminAuth();
?>