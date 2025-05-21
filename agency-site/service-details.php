<?php
include_once 'includes/db_connect.php';

$service = false;

if (isset($db_connection_success) && $db_connection_success) {
    $service_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

    if ($service_id > 0) {
        $sql = "SELECT * FROM services WHERE id = $service_id";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $service = $result->fetch_assoc();
        } else {
            header("Location: services.php");
            exit();
        }
    } else {
        header("Location: services.php");
        exit();
    }
}

// If database connection failed or service not found, we'll still include header and show error message
include_once 'includes/header.php';

// Show error message if database connection failed
if (!isset($db_connection_success) || !$db_connection_success) {
    echo '<div class="container py-5">';
    echo '<div class="alert alert-warning">';
    echo '<h4>Database Connection Error</h4>';
    echo '<p>We are unable to display service details at this time due to a database connection issue.</p>';
    echo '<p><a href="index.php" class="btn btn-primary">Return to Home</a></p>';
    echo '</div>';
    echo '</div>';
    include_once 'includes/footer.php';
    exit();
}

// If we got here but don't have a service, it means the ID was not found
// This shouldn't happen as we would have redirected, but just in case
if (!$service) {
    echo '<div class="container py-5">';
    echo '<div class="alert alert-warning">';
    echo '<h4>Service Not Found</h4>';
    echo '<p>The requested service could not be found.</p>';
    echo '<p><a href="services.php" class="btn btn-primary">Browse All Services</a></p>';
    echo '</div>';
    echo '</div>';
    // Include footer and exit
    include_once 'includes/footer.php';
    exit();
}
?>

<!-- Service Details Section -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item"><a href="services.php">Services</a></li>
                        <li class="breadcrumb-item active" aria-current="page"><?php echo $service['name']; ?></li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-12">
                <h1 class="mb-3"><?php echo $service['name']; ?></h1>
                <div class="d-flex align-items-center mb-4">
                    <span class="badge bg-primary"><?php echo ucfirst($service['category']); ?></span>
                </div>
                <p class="lead"><?php echo $service['description']; ?></p>

                <div class="mt-4">
                    <h4 class="mb-3">Key Features</h4>
                    <div class="row">
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i data-lucide="check-circle" class="text-primary me-2"></i>
                                    <span>Professional Implementation</span>
                                </li>
                                <li class="mb-2">
                                    <i data-lucide="check-circle" class="text-primary me-2"></i>
                                    <span>Expert Consultation</span>
                                </li>
                                <li class="mb-2">
                                    <i data-lucide="check-circle" class="text-primary me-2"></i>
                                    <span>Dedicated Support</span>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <i data-lucide="check-circle" class="text-primary me-2"></i>
                                    <span>Timely Delivery</span>
                                </li>
                                <li class="mb-2">
                                    <i data-lucide="check-circle" class="text-primary me-2"></i>
                                    <span>Quality Assurance</span>
                                </li>
                                <li class="mb-2">
                                    <i data-lucide="check-circle" class="text-primary me-2"></i>
                                    <span>After-Service Support</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <a href="get-service.php?service_id=<?php echo $service['id']; ?>"
                        class="btn btn-primary btn-lg">Get This Service</a>
                    <a href="contact.php" class="btn btn-outline-primary btn-lg ms-2">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Related Services Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-12">
                <h2>Related Services</h2>
            </div>
        </div>

        <div class="row">
            <?php
            // Fetch related services in the same category
            $related_services = false;
            if (isset($db_connection_success) && $db_connection_success) {
                $sql = "SELECT * FROM services WHERE category = '{$service['category']}' AND id != {$service['id']} LIMIT 3";
                $related_services = $conn->query($sql);
            }

            if ($related_services && $related_services->num_rows > 0):
                while ($related = $related_services->fetch_assoc()):
                    ?>
                    <div class="col-md-4 mb-4">
                        <div class="service-card h-100">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $related['name']; ?></h4>
                                <p class="card-text"><?php echo substr($related['description'], 0, 100) . '...'; ?></p>
                                <div class="d-flex justify-content-end mt-4">
                                    <a href="service-details.php?id=<?php echo $related['id']; ?>"
                                        class="btn btn-outline-primary">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
            else:
                ?>
                <div class="col-12 text-center">
                    <?php if (!isset($db_connection_success) || !$db_connection_success): ?>
                        <div class="alert alert-warning">
                            <p>Database connection failed. Related services cannot be displayed at this time.</p>
                        </div>
                    <?php else: ?>
                        <p>No related services found. <a href="services.php">View all services</a>.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2 class="mb-2">Ready to Get Started?</h2>
                <p class="lead mb-0">Contact our team to discuss your project requirements and get a personalized
                    solution.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="buy-service.php?service_id=<?php echo $service['id']; ?>" class="btn btn-light btn-lg">Buy
                    Now</a>
                <a href="contact.php" class="btn btn-outline-light btn-lg ms-2">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php
include_once 'includes/footer.php';
?>