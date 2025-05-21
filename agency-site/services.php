<?php
include_once 'includes/db_connect.php';

$services = false;
$page_title = "Our Services";

if (isset($db_connection_success) && $db_connection_success) {
    $category = isset($_GET['category']) ? $_GET['category'] : '';

    if (!empty($category)) {
        $sql = "SELECT * FROM services WHERE category = '$category' ORDER BY name ASC";
        $page_title = ucfirst($category) . " Services";
    } else {
        $sql = "SELECT * FROM services ORDER BY name ASC";
        $page_title = "All Services";
    }

    $services = $conn->query($sql);
}

include_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1><?php echo $page_title; ?></h1>
                <p class="lead">Explore our comprehensive range of services designed to meet your business needs.</p>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section class="services py-5">
    <div class="container">
        <!-- Category Filter Buttons -->
        <div class="row mb-5">
            <div class="col-12">
                <div class="d-flex flex-wrap justify-content-center">
                    <a href="services.php"
                        class="btn <?php echo empty($category) ? 'btn-primary' : 'btn-outline-primary'; ?> m-2">All
                        Services</a>
                    <a href="services.php?category=web"
                        class="btn <?php echo $category == 'web' ? 'btn-primary' : 'btn-outline-primary'; ?> m-2">Web
                        Development</a>
                    <a href="services.php?category=mobile"
                        class="btn <?php echo $category == 'mobile' ? 'btn-primary' : 'btn-outline-primary'; ?> m-2">Mobile
                        Apps</a>
                    <a href="services.php?category=ai"
                        class="btn <?php echo $category == 'ai' ? 'btn-primary' : 'btn-outline-primary'; ?> m-2">AI
                        Consulting</a>
                    <a href="services.php?category=cloud"
                        class="btn <?php echo $category == 'cloud' ? 'btn-primary' : 'btn-outline-primary'; ?> m-2">Cloud
                        Solutions</a>
                </div>
            </div>
        </div>

        <!-- Services List -->
        <div class="row">
            <?php if ($services && $services->num_rows > 0): ?>
                <?php while ($service = $services->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="service-card h-100">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $service['name']; ?></h4>
                                <p class="card-text"><?php echo substr($service['description'], 0, 150) . '...'; ?></p>
                                <div class="d-flex justify-content-end mt-4">
                                    <a href="service-details.php?id=<?php echo $service['id']; ?>" class="btn btn-primary">Get
                                        Service</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <?php if (!isset($db_connection_success) || !$db_connection_success): ?>
                        <div class="alert alert-warning">
                            <p>Database connection failed. Services cannot be displayed at this time.</p>
                        </div>
                    <?php else: ?>
                        <p>No services found for this category. <a href="services.php">View all services</a>.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-light py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h2>Can't find what you're looking for?</h2>
                <p class="lead mb-0">Contact us for custom services tailored to your specific business needs.</p>
            </div>
            <div class="col-lg-4 text-lg-end mt-4 mt-lg-0">
                <a href="contact.php" class="btn btn-primary btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php
include_once 'includes/footer.php';
?>