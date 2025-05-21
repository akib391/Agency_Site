<?php
require_once 'includes/db_setup.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agency_db";

if (basename($_SERVER['SCRIPT_FILENAME']) == basename(__FILE__)) {
    echo "<h1>Setting up database</h1>";

    $result = setup_database($servername, $username, $password, $dbname);

    echo "<h2>" . ($result['success'] ? "Success" : "Error") . "</h2>";
    echo "<p>" . $result['message'] . "</p>";

    if (!empty($result['details'])) {
        echo "<h3>Details:</h3>";
        echo "<ul>";
        foreach ($result['details'] as $detail) {
            echo "<li>" . $detail . "</li>";
        }
        echo "</ul>";
    }

    echo "<p>Database setup completed. <a href='index.php'>Go to Homepage</a></p>";
}
?>