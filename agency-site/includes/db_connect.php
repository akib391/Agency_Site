<?php
// Check if the init.php file has already been included
// If not, include the database setup function
if (!function_exists('debug')) {
    require_once __DIR__ . '/db_setup.php';
}

// Database Connection Parameters
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "agency_db";

// Initialize connection status flag
$db_connection_success = false;
$conn = null;

// Try to connect to the database
try {
    // First try to connect directly to the database
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        // If connection fails, it might be because the database doesn't exist
        // Try to set up the database
        $setup_result = setup_database($servername, $username, $password, $dbname);

        if ($setup_result['success']) {
            // If setup was successful, try to connect again
            $conn = new mysqli($servername, $username, $password, $dbname);

            if ($conn->connect_error) {
                throw new Exception("Connection failed after database setup: " . $conn->connect_error);
            }
        } else {
            throw new Exception($setup_result['message']);
        }
    }

    // Set character set
    $conn->set_charset("utf8");
    $db_connection_success = true;

} catch (Exception $e) {
    // Silent fail - we'll handle the connection failure in each page
    $db_connection_success = false;
}
?>