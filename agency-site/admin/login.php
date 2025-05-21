<?php
// Include initialization file
require_once __DIR__ . '/../init.php';

// Start session
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, redirect to admin dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: index.php");
    exit();
}

// Include database connection
include_once '../includes/db_connect.php';

// Initialize variables
$error_message = '';

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = isset($_POST['username']) ? ($conn ? $conn->real_escape_string($_POST['username']) : $_POST['username']) : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // Validate input
    if (empty($username) || empty($password)) {
        $error_message = "Please provide both username and password.";
    } elseif (!isset($db_connection_success) || !$db_connection_success) {
        $error_message = "Database connection error. Please try again later.";
    } else {
        // Query the database
        $sql = "SELECT * FROM admin_users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Password is correct, set session variables
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $user['id'];
                $_SESSION['admin_username'] = $user['username'];

                // Update last login timestamp
                $update_sql = "UPDATE admin_users SET last_login = NOW() WHERE id = {$user['id']}";
                $conn->query($update_sql);

                // Redirect to admin dashboard
                header("Location: index.php");
                exit();
            } else {
                // Invalid password
                $error_message = "Invalid username or password.";
            }
        } else {
            // User not found
            $error_message = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - <?php echo $site_name; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body class="bg-light">
    <div class="container">
        <div class="login-form">
            <div class="text-center mb-4">
                <a href="../index.php">
                    <h2><span class="text-primary">Tech</span>Agency</h2>
                </a>
                <p>Admin Panel</p>
            </div>

            <?php if (!empty($error_message)): ?>
                <div class="alert alert-danger" role="alert">
                    <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <?php if (!isset($db_connection_success) || !$db_connection_success): ?>
                <div class="alert alert-warning" role="alert">
                    <h4><i data-lucide="alert-triangle" class="me-2"></i> Database Connection Error</h4>
                    <p>We are currently experiencing technical difficulties with our database connection. Please try again
                        later.</p>
                </div>
            <?php else: ?>
                <form method="POST" action="login.php">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-lucide="user"></i></span>
                            <input type="text" class="form-control" id="username" name="username"
                                placeholder="Enter your username" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i data-lucide="lock"></i></span>
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Enter your password" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility()">
                                <i data-lucide="eye" id="togglePassword"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2">Login</button>
                </form>
            <?php endif; ?>

            <div class="text-center mt-4">
                <p><a href="../index.php">Return to Website</a></p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Initialize Lucide icons -->
    <script>
        lucide.createIcons();
    </script>
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePassword');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordInput.type = 'password';
                toggleIcon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>

</html>