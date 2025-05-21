<?php
// Include initialization file
require_once 'init.php';

// Include database connection
include_once 'includes/db_connect.php';

// Fetch featured services only if database connection is successful
$featured_services = false;
if (isset($db_connection_success) && $db_connection_success) {
    $sql = "SELECT * FROM services WHERE featured = 1 LIMIT 3";
    $featured_services = $conn->query($sql);
}

// Include header
include_once 'includes/header.php';
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1><?php echo $site_tagline; ?></h1>
                <p class="lead">We help businesses transform and thrive in the digital era with cutting-edge
                    technologies and expert consultancy.</p>
                <div class="d-flex justify-content-center">
                    <a href="services.php" class="btn btn-light btn-lg me-3">Our Services</a>
                    <a href="contact.php" class="btn btn-outline-light btn-lg">Contact Us</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Featured Services Section -->
<section class="services">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2>Our Premium Services</h2>
                <p class="lead">Explore our most popular services tailored to drive your business forward.</p>
            </div>
        </div>
        <div class="row">
            <?php if ($featured_services && $featured_services->num_rows > 0): ?>
                <?php while ($service = $featured_services->fetch_assoc()): ?>
                    <div class="col-md-4 mb-4">
                        <div class="service-card h-100">
                            <div class="card-body">
                                <h4 class="card-title"><?php echo $service['name']; ?></h4>
                                <p class="card-text"><?php echo substr($service['description'], 0, 150) . '...'; ?></p>
                                <div class="d-flex justify-content-end mt-4">
                                    <a href="service-details.php?id=<?php echo $service['id']; ?>"
                                        class="btn btn-outline-primary">Learn More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center">
                    <p><?php echo (!isset($db_connection_success) || !$db_connection_success) ? "Database connection failed. Featured services cannot be displayed." : "No featured services found."; ?>
                        <a href="services.php">View all services</a>.
                    </p>
                </div>
            <?php endif; ?>
        </div>
        <div class="text-center mt-5">
            <a href="services.php" class="btn btn-lg btn-primary">View All Services</a>
        </div>
    </div>
</section>

<!-- About Us Section -->
<section class="about-section bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-image">
                    <img src="https://assetdigitalcom.com/wp-content/uploads/2023/05/B2B-Digital-Marketing-Agency-scaled.jpeg"
                        alt="About TechAgency" class="img-fluid">
                </div>
            </div>
            <div class="col-lg-6 mt-4 mt-lg-0">
                <h2>About TechAgency</h2>
                <p class="lead">We are a digital transformation agency dedicated to helping businesses thrive in the
                    digital era.</p>
                <p>Founded in 2015, TechAgency has helped over 200 businesses across various industries to implement
                    digital solutions that drive growth, improve efficiency, and enhance customer experience. Our team
                    of expert consultants and developers are passionate about technology and innovation.</p>
                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i data-lucide="check-circle" class="text-primary me-2"></i>
                            <span>Expert Consultants</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i data-lucide="check-circle" class="text-primary me-2"></i>
                            <span>Innovative Solutions</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center mb-3">
                            <i data-lucide="check-circle" class="text-primary me-2"></i>
                            <span>Proven Results</span>
                        </div>
                        <div class="d-flex align-items-center mb-3">
                            <i data-lucide="check-circle" class="text-primary me-2"></i>
                            <span>Dedicated Support</span>
                        </div>
                    </div>
                </div>
                <a href="about.php" class="btn btn-primary mt-4">Learn More About Us</a>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number">200+</div>
                    <div class="stat-title">Clients</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number">350+</div>
                    <div class="stat-title">Projects</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number">50+</div>
                    <div class="stat-title">Experts</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-item">
                    <div class="stat-number">8+</div>
                    <div class="stat-title">Years</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section class="testimonials">
    <div class="container">
        <div class="row text-center mb-5">
            <div class="col-lg-8 mx-auto">
                <h2>What Our Clients Say</h2>
                <p class="lead">Don't just take our word for it. Here's what our clients have to say about working with
                    us.</p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mb-4">
                <div class="testimonial-card h-100">
                    <div class="quote"><i data-lucide="quote"></i></div>
                    <p class="mt-3">TechAgency transformed our business with their innovative web development solutions.
                        The team was professional and delivered beyond our expectations.</p>
                    <div class="client-info">
                        <!-- <img src="https://via.placeholder.com/60x60?text=Client" alt="Client" class="rounded-circle"> -->
                        <div>
                            <h5 class="client-name">Mahmudul Haque Akib</h5>
                            <p class="client-position">Student & CEO, XYZ</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="testimonial-card h-100">
                    <div class="quote"><i data-lucide="quote"></i></div>
                    <p class="mt-3">The AI consulting services from TechAgency helped us automate our operations and
                        significantly reduce costs. Highly recommended!</p>
                    <div class="client-info">
                        <!-- <img src="https://via.placeholder.com/60x60?text=Client" alt="Client" class="rounded-circle"> -->
                        <div>
                            <h5 class="client-name">Maryam Vatankhah</h5>
                            <p class="client-position">Instructor, Software Development</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-4">
                <div class="testimonial-card h-100">
                    <div class="quote"><i data-lucide="quote"></i></div>
                    <p class="mt-3">Our mobile app developed by TechAgency has received fantastic user feedback and
                        significantly improved our customer engagement.</p>
                    <div class="client-info">
                        <!-- <img src="https://via.placeholder.com/60x60?text=Client" alt="Client" class="rounded-circle"> -->
                        <div>
                            <h5 class="client-name">Michael Brown</h5>
                            <p class="client-position">Marketing Director, 123 Industries</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-9">
                <h2 class="mb-2">Ready to Transform Your Business?</h2>
                <p class="lead mb-0">Get in touch with our experts to discuss your digital transformation needs.</p>
            </div>
            <div class="col-lg-3 text-lg-end mt-4 mt-lg-0">
                <a href="contact.php" class="btn btn-light btn-lg">Contact Us</a>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once 'includes/footer.php';
?>