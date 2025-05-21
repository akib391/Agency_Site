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
    // Update Order Status
    if (isset($_POST['action']) && $_POST['action'] === 'update_status') {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;
        $status = isset($_POST['status']) ? $conn->real_escape_string($_POST['status']) : '';

        if ($order_id > 0 && !empty($status)) {
            $sql = "UPDATE orders SET status = '$status' WHERE id = $order_id";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Order status updated successfully!";
                // Reset action to view
                $action = 'view';
                $id = $order_id;
            } else {
                $error_message = "Error updating order status: " . $conn->error;
            }
        } else {
            $error_message = "Invalid order ID or status.";
        }
    }
    // Delete Order
    elseif (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $order_id = isset($_POST['order_id']) ? intval($_POST['order_id']) : 0;

        if ($order_id > 0) {
            $sql = "DELETE FROM orders WHERE id = $order_id";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Order deleted successfully!";
                // Reset action to list
                $action = 'list';
            } else {
                $error_message = "Error deleting order: " . $conn->error;
            }
        } else {
            $error_message = "Invalid order ID.";
        }
    }
}

// Fetch order details if viewing
$order = null;
if ($action === 'view' && $id > 0) {
    $sql = "SELECT * FROM orders WHERE id = $id";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $order = $result->fetch_assoc();
    } else {
        $error_message = "Order not found.";
        $action = 'list';
    }
}

// Fetch all orders for listing
$orders = [];
if ($action === 'list') {
    $sql = "SELECT * FROM orders ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $orders[] = $row;
        }
    }
}

// Page title based on action
$page_title = 'Orders';
if ($action === 'view') {
    $page_title = 'Order Details';
}
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
                        <a class="nav-link active" href="orders.php">Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="messages.php">Messages</a>
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
                <a href="orders.php" class="btn btn-secondary">
                    <i data-lucide="arrow-left" class="me-1"></i> Back to Orders
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
            <!-- Orders List -->
            <div class="card">
                <div class="card-body">
                    <?php if (count($orders) > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-striped admin-table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer</th>
                                        <th>Service</th>
                                        <th>Price</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($orders as $item): ?>
                                        <tr>
                                            <td>#<?php echo $item['id']; ?></td>
                                            <td><?php echo $item['full_name']; ?></td>
                                            <td><?php echo $item['service_name']; ?></td>
                                            <td>$<?php echo number_format($item['service_price'], 2); ?></td>
                                            <td><?php echo date('M d, Y', strtotime($item['created_at'])); ?></td>
                                            <td>
                                                <span
                                                    class="badge bg-<?php echo ($item['status'] == 'pending') ? 'warning' : 'success'; ?>">
                                                    <?php echo ucfirst($item['status']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="orders.php?action=view&id=<?php echo $item['id']; ?>"
                                                        class="btn btn-sm btn-primary">
                                                        <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete(<?php echo $item['id']; ?>)">
                                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                                    </button>
                                                </div>

                                                <!-- Hidden form for delete action -->
                                                <form id="delete-form-<?php echo $item['id']; ?>" method="POST" action="orders.php"
                                                    style="display: none;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="order_id" value="<?php echo $item['id']; ?>">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center py-4">No orders found.</p>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($action === 'view' && $order): ?>
            <!-- View Order Details -->
            <div class="card mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Order #<?php echo $order['id']; ?> Details</h5>
                        <span class="badge bg-<?php echo ($order['status'] == 'pending') ? 'warning' : 'success'; ?> fs-6">
                            <?php echo ucfirst($order['status']); ?>
                        </span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Customer Information</h5>
                            <table class="table">
                                <tr>
                                    <th width="30%">Full Name</th>
                                    <td><?php echo $order['full_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $order['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>Phone</th>
                                    <td><?php echo $order['phone']; ?></td>
                                </tr>
                                <tr>
                                    <th>Address</th>
                                    <td><?php echo !empty($order['address']) ? $order['address'] : 'N/A'; ?></td>
                                </tr>
                                <tr>
                                    <th>Order Date</th>
                                    <td><?php echo date('F d, Y H:i', strtotime($order['created_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Order Details</h5>
                            <table class="table">
                                <tr>
                                    <th width="30%">Service</th>
                                    <td><?php echo $order['service_name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Price</th>
                                    <td>$<?php echo number_format($order['service_price'], 2); ?></td>
                                </tr>
                                <tr>
                                    <th>Payment Method</th>
                                    <td><?php echo ucwords(str_replace('_', ' ', $order['payment_method'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Message</th>
                                    <td><?php echo !empty($order['message']) ? $order['message'] : 'N/A'; ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- Update Status Form -->
                    <form method="POST" action="orders.php" class="mt-4">
                        <input type="hidden" name="action" value="update_status">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <label for="status" class="form-label">Update Order Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="pending" <?php echo ($order['status'] === 'pending') ? 'selected' : ''; ?>>
                                        Pending</option>
                                    <option value="processing" <?php echo ($order['status'] === 'processing') ? 'selected' : ''; ?>>Processing</option>
                                    <option value="completed" <?php echo ($order['status'] === 'completed') ? 'selected' : ''; ?>>Completed</option>
                                    <option value="cancelled" <?php echo ($order['status'] === 'cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="d-flex">
                                    <button type="submit" class="btn btn-primary">Update Status</button>
                                    <button type="button" class="btn btn-danger ms-2"
                                        onclick="confirmDelete(<?php echo $order['id']; ?>)">
                                        Delete Order
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Hidden form for delete action -->
                    <form id="delete-form-<?php echo $order['id']; ?>" method="POST" action="orders.php"
                        style="display: none;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                    </form>
                </div>
            </div>

            <!-- Back Button -->
            <div class="text-center">
                <a href="orders.php" class="btn btn-secondary">
                    <i data-lucide="arrow-left" class="me-1"></i> Back to Orders List
                </a>
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
        function confirmDelete(orderId) {
            if (confirm(`Are you sure you want to delete Order #${orderId}?`)) {
                document.getElementById(`delete-form-${orderId}`).submit();
            }
        }
    </script>
</body>

</html>