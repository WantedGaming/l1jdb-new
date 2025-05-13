<?php
// Database configuration
$hostname = "localhost";
$username = "root";
$password = "";
$database = "l1j_remastered";

// Create database connection
$conn = new mysqli($hostname, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");
