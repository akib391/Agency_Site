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
    // Check for form action
    if (isset($_POST['form_action'])) {
        if ($_POST['form_action'] === 'add' || $_POST['form_action'] === 'edit') {
            // Validate required fields
            if (!isset($_POST['name']) || empty(trim($_POST['name']))) {
                $error_message = "Service name is required.";
            } elseif (!isset($_POST['category']) || empty(trim($_POST['category']))) {
                $error_message = "Category is required.";
            } elseif (!isset($_POST['description']) || empty(trim($_POST['description']))) {
                $error_message = "Description is required.";
            } else {
                // All required fields are present, proceed with the operation
                $name = trim($_POST['name']);
                $category = trim($_POST['category']);
                $description = trim($_POST['description']);
                $featured = isset($_POST['featured']) ? 1 : 0;

                try {
                    if ($_POST['form_action'] === 'add') {
                        $stmt = $conn->prepare("INSERT INTO services (name, category, description, featured) VALUES (?, ?, ?, ?)");
                        $stmt->bind_param("sssi", $name, $category, $description, $featured);
                    } else {
                        $service_id = intval($_POST['id']);
                        $stmt = $conn->prepare("UPDATE services SET name = ?, category = ?, description = ?, featured = ? WHERE id = ?");
                        $stmt->bind_param("sssii", $name, $category, $description, $featured, $service_id);
                    }

                    if ($stmt->execute()) {
                        $success_message = ($_POST['form_action'] === 'add') ? "Service added successfully!" : "Service updated successfully!";
                        // Reset action to list only on success
                        $action = 'list';
                    } else {
                        $error_message = "Error: " . $conn->error;
                    }
                } catch (Exception $e) {
                    $error_message = "Database error: " . $e->getMessage();
                }
            }
        } elseif ($_POST['form_action'] === 'delete' && isset($_POST['id'])) {
            try {
                $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
                $stmt->bind_param("i", $_POST['id']);

                if ($stmt->execute()) {
                    $success_message = "Service deleted successfully!";
                    $action = 'list';
                } else {
                    $error_message = "Error: " . $conn->error;
                }
            } catch (Exception $e) {
                $error_message = "Database error: " . $e->getMessage();
            }
        }
    }
}

// Fetch service for editing or viewing
if (($action === 'edit' || $action === 'view') && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT id, name, category, description, featured, created_at, updated_at FROM services WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $service = $result->fetch_assoc();

    if (!$service) {
        $error_message = "Service not found.";
        $action = 'list';
    }
}

// Fetch all services for listing
$services = [];
if ($action === 'list') {
    $sql = "SELECT id, name, category, description, featured FROM services ORDER BY id DESC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $services[] = $row;
        }
    }
}

// Page title based on action
$page_title = 'Services';
if ($action === 'add') {
    $page_title = 'Add New Service';
} elseif ($action === 'edit') {
    $page_title = 'Edit Service';
} elseif ($action === 'view') {
    $page_title = 'View Service';
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
                        <a class="nav-link active" href="services.php">Services</a>
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
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1><?php echo $page_title; ?></h1>
            <?php if ($action === 'list'): ?>
                <a href="services.php?action=add" class="btn btn-primary">
                    <i data-lucide="plus" class="me-1"></i> Add New Service
                </a>
            <?php else: ?>
                <a href="services.php" class="btn btn-secondary">
                    <i data-lucide="arrow-left" class="me-1"></i> Back to Services
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
            <!-- Services List -->
            <div class="card">
                <div class="card-body">
                    <?php if (!empty($services)): ?>
                        <div class="table-responsive">
                            <table class="table table-striped admin-table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Category</th>
                                        <th>Featured</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($services as $item): ?>
                                        <tr>
                                            <td><?php echo $item['id']; ?></td>
                                            <td><?php echo $item['name']; ?></td>
                                            <td><?php echo ucfirst($item['category']); ?></td>
                                            <td>
                                                <?php if ($item['featured']): ?>
                                                    <span class="badge bg-success">Yes</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">No</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="services.php?action=view&id=<?php echo $item['id']; ?>"
                                                        class="btn btn-sm btn-info">
                                                        <i data-lucide="eye" style="width: 16px; height: 16px;"></i>
                                                    </a>
                                                    <a href="services.php?action=edit&id=<?php echo $item['id']; ?>"
                                                        class="btn btn-sm btn-primary">
                                                        <i data-lucide="edit" style="width: 16px; height: 16px;"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-danger"
                                                        onclick="confirmDelete(<?php echo $item['id']; ?>, '<?php echo $item['name']; ?>')">
                                                        <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                                    </button>
                                                </div>

                                                <!-- Hidden form for delete action -->
                                                <form id="delete-form-<?php echo $item['id']; ?>" method="POST"
                                                    action="services.php" style="display: none;">
                                                    <input type="hidden" name="form_action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-center py-4">No services found. <a href="services.php?action=add">Add a new service</a>.
                        </p>
                    <?php endif; ?>
                </div>
            </div>

        <?php elseif ($action === 'view' && $service): ?>
            <!-- View Service -->
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <h5>Service Details</h5>
                            <table class="table">
                                <tr>
                                    <th>ID</th>
                                    <td><?php echo $service['id']; ?></td>
                                </tr>
                                <tr>
                                    <th>Name</th>
                                    <td><?php echo $service['name']; ?></td>
                                </tr>
                                <tr>
                                    <th>Category</th>
                                    <td><?php echo ucfirst($service['category']); ?></td>
                                </tr>
                                <tr>
                                    <th>Featured</th>
                                    <td><?php echo $service['featured'] ? 'Yes' : 'No'; ?></td>
                                </tr>
                                <tr>
                                    <th>Description</th>
                                    <td><?php echo $service['description']; ?></td>
                                </tr>
                                <tr>
                                    <th>Created</th>
                                    <td><?php echo date('M d, Y', strtotime($service['created_at'])); ?></td>
                                </tr>
                                <tr>
                                    <th>Last Updated</th>
                                    <td><?php echo date('M d, Y', strtotime($service['updated_at'])); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="mt-4">
                        <a href="services.php?action=edit&id=<?php echo $service['id']; ?>" class="btn btn-primary">
                            <i data-lucide="edit" class="me-1"></i> Edit Service
                        </a>
                        <button type="button" class="btn btn-danger"
                            onclick="confirmDelete(<?php echo $service['id']; ?>, '<?php echo $service['name']; ?>')">
                            <i data-lucide="trash-2" class="me-1"></i> Delete Service
                        </button>
                    </div>
                </div>
            </div>

        <?php elseif ($action === 'add' || ($action === 'edit' && $service)): ?>
            <!-- Add/Edit Service Form -->
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="services.php">
                        <input type="hidden" name="form_action" value="<?php echo $action; ?>">
                        <?php if ($action === 'edit'): ?>
                            <input type="hidden" name="id" value="<?php echo $service['id']; ?>">
                        <?php endif; ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Service Name *</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="<?php echo ($action === 'edit') ? htmlspecialchars($service['name']) : ''; ?>"
                                        required>
                                </div>

                                <div class="mb-3">
                                    <label for="category" class="form-label">Category *</label>
                                    <select class="form-select" id="category" name="category" required>
                                        <option value="">-- Select Category --</option>
                                        <option value="web" <?php echo ($action === 'edit' && $service['category'] === 'web') ? 'selected' : ''; ?>>Web Development</option>
                                        <option value="mobile" <?php echo ($action === 'edit' && $service['category'] === 'mobile') ? 'selected' : ''; ?>>Mobile Apps</option>
                                        <option value="ai" <?php echo ($action === 'edit' && $service['category'] === 'ai') ? 'selected' : ''; ?>>AI Consulting</option>
                                        <option value="cloud" <?php echo ($action === 'edit' && $service['category'] === 'cloud') ? 'selected' : ''; ?>>Cloud Solutions</option>
                                    </select>
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="featured" name="featured" <?php echo ($action === 'edit' && $service['featured']) ? 'checked' : ''; ?>>
                                    <label class="form-check-label" for="featured">Featured Service</label>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="10"
                                        required><?php echo ($action === 'edit') ? htmlspecialchars($service['description']) : ''; ?></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">
                                <?php echo ($action === 'edit') ? 'Update Service' : 'Add Service'; ?>
                            </button>
                            <a href="services.php" class="btn btn-secondary ms-2">Cancel</a>
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
        function confirmDelete(id, name) {
            if (confirm(`Are you sure you want to delete the service "${name}"?`)) {
                document.getElementById(`delete-form-${id}`).submit();
            }
        }
    </script>
</body>

</html>