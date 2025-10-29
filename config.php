<?php
// Database configuration
$db_host = 'localhost';
$db_port = 3306;        // 👈 Add this line for custom MySQL port
$db_name = 'rentrdb';   // Your database name in lowercase
$db_user = 'root';      // Default XAMPP username
$db_pass = '';          // Default XAMPP password is empty

// Function to establish database connection using MySQLi
function getDBConnection() {
    global $db_host, $db_port, $db_name, $db_user, $db_pass;
    
    // Create connection (include port)
    $conn = new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
    
    // Check connection
    if ($conn->connect_error) {
        error_log("Connection failed: " . $conn->connect_error);
        die("Database connection failed. Please try again later.");
    }
    
    // Set charset to utf8mb4 for full Unicode support
    $conn->set_charset("utf8mb4");
    
    return $conn;
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper function to redirect with a message
function redirect($url, $message = '') {
    if (!empty($message)) {
        $_SESSION['flash_message'] = $message;
    }
    header("Location: $url");
    exit();
}

// Helper function to display flash messages
function getFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message'];
        unset($_SESSION['flash_message']);
        return $message;
    }
    return '';
}

// Set default timezone
date_default_timezone_set('Asia/Kuala_Lumpur');
?>
