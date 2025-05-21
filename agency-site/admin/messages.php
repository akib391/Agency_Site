<?php
// Include authentication check
require_once 'includes/auth_check.php';

// Include database connection
include_once '../includes/db_connect.php';

// Initialize variables
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$success_message = '';
$error_message = '';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'update_status') {
            $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;
            $status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : '';

            if ($message_id > 0 && !empty($status)) {
                $sql = "UPDATE contact_messages SET status = '$status' WHERE id = $message_id";

                if ($conn->query($sql) === TRUE) {
                    $success_message = "Message status updated successfully!";
                    $action = 'view';
                    $id = $message_id;
                } else {
                    $error_message = "Error updating message status: " . $conn->error;
                }
            }
        } elseif ($_POST['action'] === 'delete') {
            $message_id = isset($_POST['message_id']) ? intval($_POST['message_id']) : 0;

            if ($message_id > 0) {
                $sql = "DELETE FROM contact_messages WHERE id = $message_id";

                if ($conn->query($sql) === TRUE) {
                    $success_message = "Message deleted successfully!";
                    $action = 'list';
                } else {
                    $error_message = "Error deleting message: " . $conn->error;
                }
            }
        }
    }
}

// Fetch message for viewing
$message = null;
if ($action === 'view' && $id > 0) {
    $sql = "SELECT * FROM contact_messages WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $message = $result->fetch_assoc();
    } else {
        $error_message = "Message not found.";
        $action = 'list';
    }
}

// Fetch all messages for listing
$messages = [];
if ($action === 'list') {
    $sql = "SELECT * FROM contact_messages ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
}

// Page title based on action
$page_title = ($action === 'view') ? 'Message Details' : 'Contact Messages';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - TechAgency Admin</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>
    <!-- Admin Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <span class="text-primary">Tech</span>Agency Admin
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="adminNavbar">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="messages.php">Messages</a>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                            <i data-lucide="user" class="me-1"></i> <?php echo $_SESSION['admin_username']; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="../index.php" target="_blank">View Website</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Admin Content -->
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?php echo $page_title; ?></h1>
            <?php if ($action === 'view'): ?>
                <a href="messages.php" class="btn btn-secondary">
                    <i data-lucide="arrow-left" class="me-1"></i> Back to Messages
                </a>
            <?php endif; ?>
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" role="alert">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($action === 'list'): ?>
            <!-- Messages List -->
            <div class="card">
                <div class="card-body">
                    <?php if (count($messages) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Subject</th>
                                        <th>Email</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($messages as $item): ?>
                                        <tr>
                                            <td><?php echo $item['id']; ?></td>
                                            <td><?php echo $item['first_name'] . ' ' . $item['last_name']; ?></td>
                                            <td><?php echo $item['subject']; ?></td>
                                            <td><?php echo $item['email']; ?></td>
                                            <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                                            <td>
                                                <span
                                                    class="badge bg-<?php echo ($item['status'] === 'unread') ? 'danger' : 'success'; ?>">
                                                    <?php echo ucfirst($item['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="messages.php?action=view&id=<?php echo $item['id']; ?>"
                                                        class="btn btn-sm btn-primary">
                                                        <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete(<?php echo $item['id']; ?>)">
                                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                                    </button>
                                                </div>

                                                <!-- Hidden form for delete action -->
                                                <form id="delete-form-<?php echo $item['id']; ?>" method="POST"
                                                    action="messages.php" style="display: none;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="message_id" value="<?php echo $item['id']; ?>">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center py-4">No messages found.</p>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($action === 'view' && $message): ?>
            <!-- View Message Details -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Sender Information</h5>
                            <table class="table">
                                <tr>
                                    <th width="30%">Full Name</th>
                                    <td><?php echo $message['first_name'] . ' ' . $message['last_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $message['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?php echo !empty($message['phone']) ? $message['phone'] : 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <th>Date Sent</th>
                                    <td><?php echo date('F d, Y H:i', strtotime($message['created_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Message Details</h5>
                            <table class="table">
                                <tr>
                                    <th width="30%">Subject</th>
                                    <td><?php echo $message['subject']; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <span
                                            class="badge bg-<?php echo ($message['status'] === 'unread') ? 'danger' : 'success'; ?>">
                                            <?php echo ucfirst($message['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td><?php echo nl2br($message['message']); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <form method="POST" action="messages.php" class="mt-4">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="message_id" value="<?php echo $message['id']; ?>">

                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Update Message Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="unread" <?php echo ($message['status'] === 'unread') ? 'selected' : ''; ?>>
                                        Unread</option>
                                    <option value="read" <?php echo ($message['status'] === 'read') ? 'selected' : ''; ?>>
                                        Read</option>
                                    <option value="replied" <?php echo ($message['status'] === 'replied') ? 'selected' : ''; ?>>
                                        Replied</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                    <button type="button" class="btn btn-danger ms-2"
                                        onclick="confirmDelete(<?php echo $message['id']; ?>)">
                                        Delete Message
                                    </button>
                                    <a href="mailto:<?php echo $message['email']; ?>" class="btn btn-success ms-2">
                                        Reply via Email
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-4">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> TechAgency Admin Panel. All rights reserved.</p>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Initialize Lucide icons -->
    <script>
        lucide.createIcons();
    </script>
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this message?')) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        }
    </script>
</body>

</html>