<?php
// Start session for potential user login (only if not already started)
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the current page has a hero section
$hasHeroSection = (basename($_SERVER['PHP_SELF']) === 'index.php');
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tech Agency Solutions</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet"
        href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../css/style.css' : 'css/style.css'; ?>">
    <!-- Hero CSS (only on pages with hero section) -->
    <?php if ($hasHeroSection): ?>
        <link rel="stylesheet"
            href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../css/hero-placeholder.css' : 'css/hero-placeholder.css'; ?>">
    <?php endif; ?>
</head>

<body>
    <!-- Navigation Bar -->
    <nav
        class="navbar navbar-expand-lg navbar-dark sticky-top <?php echo $hasHeroSection ? 'bg-transparent' : 'bg-dark'; ?>">
        <div class="container">
            <a class="navbar-brand"
                href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../index.php' : 'index.php'; ?>">
                <span class="text-primary">Tech</span>Agency
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link"
                            href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../index.php' : 'index.php'; ?>">Home</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            Services
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item"
                                    href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../services.php' : 'services.php'; ?>">All
                                    Services</a></li>
                            <li><a class="dropdown-item"
                                    href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../services.php?category=web' : 'services.php?category=web'; ?>">Web
                                    Development</a></li>
                            <li><a class="dropdown-item"
                                    href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../services.php?category=ai' : 'services.php?category=ai'; ?>">AI
                                    Consulting</a></li>
                            <li><a class="dropdown-item"
                                    href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../services.php?category=mobile' : 'services.php?category=mobile'; ?>">Mobile
                                    Apps</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                            href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../about.php' : 'about.php'; ?>">About
                            Us</a>
                    </li>
                    <!-- <li class="nav-item">
                        <a class="nav-link"
                            href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../portfolio.php' : 'portfolio.php'; ?>">Portfolio</a>
                    </li> -->
                    <li class="nav-item">
                        <a class="nav-link"
                            href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../contact.php' : 'contact.php'; ?>">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-primary text-white px-3 ms-lg-2"
                            href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../get-service.php' : 'get-service.php'; ?>">Get
                            Service</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-success text-white px-3 ms-lg-2"
                            href="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? 'index.php' : 'admin/login.php'; ?>">
                            <?php echo (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) ? 'Admin Dashboard' : 'Admin Login'; ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</body>

</html>