<?php
// Include initialization file
require_once 'init.php';

// Check if the reset parameter was provided
$reset = isset($_GET['reset']) && $_GET['reset'] == 'true';

// HTML Head
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Setup - ' . $site_name . '</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h3 class="mb-0">Database Setup Tool</h3>
                    </div>
                    <div class="card-body">';

// If reset was requested and confirmed, drop the database first
if ($reset && isset($_GET['confirm']) && $_GET['confirm'] == 'true') {
    try {
        // Connect without selecting database
        $temp_conn = new mysqli($servername, $username, $password);

        // Drop the database if it exists
        $sql = "DROP DATABASE IF EXISTS $dbname";
        if ($temp_conn->query($sql) === TRUE) {
            echo '<div class="alert alert-warning">Database ' . $dbname . ' has been dropped successfully.</div>';
        } else {
            echo '<div class="alert alert-danger">Error dropping database: ' . $temp_conn->error . '</div>';
        }

        $temp_conn->close();
    } catch (Exception $e) {
        echo '<div class="alert alert-danger">Error: ' . $e->getMessage() . '</div>';
    }
} else if ($reset) {
    // Show confirmation screen for reset
    echo '<div class="alert alert-danger">
            <h4>Warning: Database Reset</h4>
            <p>You are about to delete the entire database and all its data. This action cannot be undone.</p>
            <p>Are you sure you want to proceed?</p>
            <div class="mt-3">
                <a href="database_setup.php?reset=true&confirm=true" class="btn btn-danger me-2">Yes, Reset Database</a>
                <a href="database_setup.php" class="btn btn-secondary">Cancel</a>
            </div>
          </div>';

    // End the page early
    echo '</div>
        <div class="card-footer text-muted">
            <a href="index.php" class="btn btn-primary">Return to Homepage</a>
        </div>
    </div>
    </div>
    </div>
    </div>
    </body>
    </html>';
    exit;
}

// Run the database setup
$result = setup_database($servername, $username, $password, $dbname);

// Display result
if ($result['success']) {
    echo '<div class="alert alert-success">
            <h4><i class="bi bi-check-circle"></i> Success!</h4>
            <p>' . $result['message'] . '</p>
          </div>';
} else {
    echo '<div class="alert alert-danger">
            <h4><i class="bi bi-exclamation-triangle"></i> Error</h4>
            <p>' . $result['message'] . '</p>
          </div>';
}

// Display details
if (!empty($result['details'])) {
    echo '<h5 class="mt-4">Details:</h5>
          <ul class="list-group">';

    foreach ($result['details'] as $detail) {
        echo '<li class="list-group-item">' . $detail . '</li>';
    }

    echo '</ul>';
}

// Show reset option
echo '<div class="mt-4 pt-3 border-top">
        <h5>Additional Options</h5>
        <a href="database_setup.php?reset=true" class="btn btn-warning">Reset Database</a>
        <small class="d-block mt-2 text-muted">This will delete the entire database and recreate it from scratch.</small>
      </div>';

echo '</div>
        <div class="card-footer text-muted">
            <a href="index.php" class="btn btn-primary">Return to Homepage</a>
        </div>
    </div>
    </div>
    </div>
    </div>
    </body>
    </html>';
?>