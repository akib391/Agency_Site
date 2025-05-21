<?php
// Include initialization file
require_once 'init.php';

// Include database connection
include_once 'includes/db_connect.php';

$success_message = '';
$error_message = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate required fields
    if (empty($_POST['firstName']) || empty($_POST['lastName']) || empty($_POST['email']) || empty($_POST['subject']) || empty($_POST['message'])) {
        $error_message = "Please fill in all required fields.";
    } else {
        // Prepare and sanitize data
        $first_name = $conn->real_escape_string(trim($_POST['firstName']));
        $last_name = $conn->real_escape_string(trim($_POST['lastName']));
        $email = $conn->real_escape_string(trim($_POST['email']));
        $phone = $conn->real_escape_string(trim($_POST['phone'] ?? ''));
        $subject = $conn->real_escape_string(trim($_POST['subject']));
        $message = $conn->real_escape_string(trim($_POST['message']));

        // Insert into database
        $sql = "INSERT INTO contact_messages (first_name, last_name, email, phone, subject, message) 
                VALUES ('$first_name', '$last_name', '$email', '$phone', '$subject', '$message')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Thank you for your message! We'll get back to you soon.";
        } else {
            $error_message = "Sorry, there was an error sending your message. Please try again later.";
        }
    }
}

// Include header
include_once 'includes/header.php';
?>

<!-- Contact Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1>Contact Us</h1>
                <p class="lead">Get in touch with our team for any inquiries or to discuss your project.</p>
            </div>
        </div>
    </div>
</section>

<!-- Contact Form Section -->
<section class="py-5">
    <div class="container">
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

        <div class="row">
            <div class="col-lg-6 mb-5 mb-lg-0">
                <div class="contact-info">
                    <h2 class="mb-4">Get In Touch</h2>
                    <p class="lead mb-4">We'd love to hear from you. Fill out the form, and we'll get back to you as
                        soon as possible.</p>

                    <div class="contact-info-item">
                        <i data-lucide="map-pin" class="me-3 text-primary"></i>
                        <div>
                            <h5 class="mb-1">Our Office</h5>
                            <p>123 Tech Street, Digital City, 12345</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i data-lucide="phone" class="me-3 text-primary"></i>
                        <div>
                            <h5 class="mb-1">Phone</h5>
                            <p>+1 234 567 8901</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i data-lucide="mail" class="me-3 text-primary"></i>
                        <div>
                            <h5 class="mb-1">Email</h5>
                            <p>info@techagency.com</p>
                        </div>
                    </div>

                    <div class="contact-info-item">
                        <i data-lucide="clock" class="me-3 text-primary"></i>
                        <div>
                            <h5 class="mb-1">Business Hours</h5>
                            <p>Monday - Friday: 9:00 AM - 5:00 PM</p>
                            <p>Saturday & Sunday: Closed</p>
                        </div>
                    </div>

                    <div class="social-links mt-4">
                        <h5 class="mb-3">Follow Us</h5>
                        <a href="#" class="btn btn-outline-primary me-2"><i data-lucide="facebook"></i></a>
                        <a href="#" class="btn btn-outline-primary me-2"><i data-lucide="twitter"></i></a>
                        <a href="#" class="btn btn-outline-primary me-2"><i data-lucide="linkedin"></i></a>
                        <a href="#" class="btn btn-outline-primary me-2"><i data-lucide="instagram"></i></a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="contact-form">
                    <h2 class="mb-4">Send Us a Message</h2>

                    <!-- Contact Form -->
                    <form method="POST" action="contact.php" class="needs-validation" novalidate>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" required>
                                <div class="invalid-feedback">
                                    Please provide your first name.
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="lastName" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" required>
                                <div class="invalid-feedback">
                                    Please provide your last name.
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone">
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" class="form-control" id="subject" name="subject" required>
                            <div class="invalid-feedback">
                                Please provide a subject.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
                            <div class="invalid-feedback">
                                Please provide your message.
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="policy" required>
                            <label class="form-check-label" for="policy">
                                I agree to the <a href="#">Privacy Policy</a> and the processing of my personal data.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to our privacy policy.
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg">Send Message</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

<?php
// Include footer
include_once 'includes/footer.php';
?>