<!-- Footer -->
<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <h5 class="mb-4"><span class="text-primary">Tech</span>Agency</h5>
                <p>We provide innovative digital solutions to help businesses thrive in the digital era.</p>
                <div class="d-flex mt-4">
                    <a href="#" class="text-white me-3"><i data-lucide="facebook"></i></a>
                    <a href="#" class="text-white me-3"><i data-lucide="twitter"></i></a>
                    <a href="#" class="text-white me-3"><i data-lucide="instagram"></i></a>
                    <a href="#" class="text-white me-3"><i data-lucide="linkedin"></i></a>
                </div>
            </div>
            <div class="col-md-2 mb-4 mb-md-0">
                <h5 class="mb-4">Services</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="services.php?category=web" class="text-white">Web Development</a></li>
                    <li class="mb-2"><a href="services.php?category=ai" class="text-white">AI Consulting</a></li>
                    <li class="mb-2"><a href="services.php?category=mobile" class="text-white">Mobile Apps</a></li>
                    <li class="mb-2"><a href="services.php?category=cloud" class="text-white">Cloud Solutions</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4 mb-md-0">
                <h5 class="mb-4">Quick Links</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php" class="text-white">Home</a></li>
                    <li class="mb-2"><a href="about.php" class="text-white">About Us</a></li>
                    <li class="mb-2"><a href="portfolio.php" class="text-white">Portfolio</a></li>
                    <li class="mb-2"><a href="contact.php" class="text-white">Contact</a></li>
                </ul>
            </div>
            <div class="col-md-4">
                <h5 class="mb-4">Contact Us</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i data-lucide="map-pin" class="me-2"></i> 123 Agency Street, Tech City</li>
                    <li class="mb-2"><i data-lucide="phone" class="me-2"></i> +1 234 567 8901</li>
                    <li class="mb-2"><i data-lucide="mail" class="me-2"></i> info@techagency.com</li>
                    <li class="mb-2"><i data-lucide="user" class="me-2"></i> Creator: Mahmudul
                        Haque Akib</li>
                    <li class="mb-2"><i data-lucide="book" class="me-2"></i> Instructor: Maryam Vatankhah</li>
                </ul>
            </div>
        </div>
        <hr class="my-4">
        <div class="row">
            <div class="col-md-6 text-center text-md-start">
                <p class="mb-0">&copy; <?php echo date('Y'); ?> TechAgency. All rights reserved. </p>
            </div>
            <div class="col-md-6 text-center text-md-end">
                <a href="#" class="text-white me-3">Privacy Policy</a>
                <a href="#" class="text-white me-3">Terms of Service</a>
                <a href="#" class="text-white">Sitemap</a>
            </div>
        </div>
    </div>
</footer>

<!-- Bootstrap JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Initialize Lucide icons -->
<script>
    lucide.createIcons();
</script>
<!-- Custom JavaScript -->
<script
    src="<?php echo (strpos($_SERVER['PHP_SELF'], 'admin/') !== false) ? '../js/script.js' : 'js/script.js'; ?>"></script>
</body>

</html>