<?php
// Include authentication check
require_once 'includes/auth_check.php';

// Include database connection
include_once '../includes/db_connect.php';

// Get counts for dashboard
$services_count = 0;
$orders_count = 0;
$messages_count = 0;

// Count services
$sql = "SELECT COUNT(*) as count FROM services";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $services_count = $row['count'];
}

// Count orders
$sql = "SELECT COUNT(*) as count FROM orders";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $orders_count = $row['count'];
}

// Count messages
$sql = "SELECT COUNT(*) as count FROM contact_messages";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $messages_count = $row['count'];
}

// Get recent orders
$recent_orders = [];
$sql = "SELECT * FROM orders ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recent_orders[] = $row;
    }
}

// Get recent messages
$recent_messages = [];
$sql = "SELECT * FROM contact_messages ORDER BY created_at DESC LIMIT 5";
$result = $conn->query($sql);
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $recent_messages[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TechAgency</title>
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
                        <a class="nav-link active" href="index.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="services.php">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="orders.php">Orders</a>
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
        <h1 class="mb-4">Dashboard</h1>

        <!-- Dashboard Stats -->
        <div class="row mb-4">
            <div class="col-md-3 mb-4">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Total Services</h5>
                                <h2 class="display-4"><?php echo $services_count; ?></h2>
                            </div>
                            <i data-lucide="briefcase" style="width: 48px; height: 48px;"></i>
                        </div>
                        <a href="services.php" class="text-white">Manage Services <i data-lucide="arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Total Orders</h5>
                                <h2 class="display-4"><?php echo $orders_count; ?></h2>
                            </div>
                            <i data-lucide="shopping-cart" style="width: 48px; height: 48px;"></i>
                        </div>
                        <a href="orders.php" class="text-white">View Orders <i data-lucide="arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Total Messages</h5>
                                <h2 class="display-4"><?php echo $messages_count; ?></h2>
                            </div>
                            <i data-lucide="mail" style="width: 48px; height: 48px;"></i>
                        </div>
                        <a href="messages.php" class="text-white">View Messages <i data-lucide="arrow-right"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-4">
                <div class="card bg-secondary text-white h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h5 class="card-title">Quick Links</h5>
                                <p>Access common tasks</p>
                            </div>
                            <i data-lucide="link" style="width: 48px; height: 48px;"></i>
                        </div>
                        <div class="d-flex flex-column">
                            <a href="services.php?action=add" class="text-white mb-2">Add New Service <i
                                    data-lucide="plus"></i></a>
                            <a href="../index.php" target="_blank" class="text-white">View Website <i
                                    data-lucide="external-link"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Orders</h5>
            </div>
            <div class="card-body">
                <?php if (count($recent_orders) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Order ID</th>
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Price</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_orders as $order): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td><?php echo $order['full_name']; ?></td>
                                        <td><?php echo $order['service_name']; ?></td>
                                        <td>$<?php echo number_format($order['service_price'], 2); ?></td>
                                        <td><?php echo date('M d, Y', strtotime($order['created_at'])); ?></td>
                                        <td>
                                            <span
                                                class="badge bg-<?php echo ($order['status'] == 'pending') ? 'warning' : 'success'; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="orders.php?action=view&id=<?php echo $order['id']; ?>"
                                                class="btn btn-sm btn-primary">
                                                <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="orders.php" class="btn btn-outline-primary">View All Orders</a>
                    </div>
                <?php else: ?>
                    <p class="text-center py-4">No orders found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Recent Messages -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Messages</h5>
            </div>
            <div class="card-body">
                <?php if (count($recent_messages) > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Subject</th>
                                    <th>Email</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_messages as $message): ?>
                                    <tr>
                                        <td>#<?php echo $message['id']; ?></td>
                                        <td><?php echo $message['first_name'] . ' ' . $message['last_name']; ?></td>
                                        <td><?php echo $message['subject']; ?></td>
                                        <td><?php echo $message['email']; ?></td>
                                        <td><?php echo date('M d, Y', strtotime($message['created_at'])); ?></td>
                                        <td>
                                            <span
                                                class="badge bg-<?php echo ($message['status'] === 'unread') ? 'danger' : 'success'; ?>">
                                                <?php echo ucfirst($message['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="messages.php?action=view&id=<?php echo $message['id']; ?>"
                                                class="btn btn-sm btn-primary">
                                                <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="text-end mt-3">
                        <a href="messages.php" class="btn btn-outline-primary">View All Messages</a>
                    </div>
                <?php else: ?>
                    <p class="text-center py-4">No messages found.</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- System Info -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">System Information</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center">
                            <i data-lucide="calendar" class="text-primary me-2"></i>
                            <div>
                                <h6 class="mb-0">Current Date</h6>
                                <p class="mb-0"><?php echo date('F d, Y'); ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center">
                            <i data-lucide="user" class="text-primary me-2"></i>
                            <div>
                                <h6 class="mb-0">Logged in as</h6>
                                <p class="mb-0"><?php echo $_SESSION['admin_username']; ?></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="d-flex align-items-center">
                            <i data-lucide="server" class="text-primary me-2"></i>
                            <div>
                                <h6 class="mb-0">PHP Version</h6>
                                <p class="mb-0"><?php echo phpversion(); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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
</body>

</html>