<?php
/**
 * Application Initialization File
 * 
 * This file is included at the beginning of every page to ensure 
 * the database and necessary resources are set up automatically.
 */

// Start session if not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Database Connection Parameters
$servername = "localhost";
$username = "root"; // Default XAMPP username
$password = ""; // Default XAMPP password
$dbname = "agency_db";

// Include the database setup function
require_once __DIR__ . '/includes/db_setup.php';

// Try to set up the database automatically
if (!isset($db_connection_success) || !$db_connection_success) {
    $setup_result = setup_database($servername, $username, $password, $dbname);

    // If setup was successful, try to connect again
    if ($setup_result['success']) {
        // Include the database connection file to establish the connection
        include_once __DIR__ . '/includes/db_connect.php';
    }
}

// Set global site variables
$site_name = "TechAgency";
$site_tagline = "Innovative Digital Solutions";
$site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);

/**
 * Debug function - only use during development
 */
function debug($data)
{
    echo '<pre>';
    print_r($data);
    echo '</pre>';
}
?>