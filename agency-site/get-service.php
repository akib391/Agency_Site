<?php 
// Include initialization file
require_once 'init.php';

// Include database connection
include_once 'includes/db_connect.php';

// Initialize variables
$service_id = isset($_GET['service_id']) ? intval($_GET['service_id']) : 0;
$selected_service = null;
$services = [];
$success_message = '';
$error_message = '';

// Fetch all services for the dropdown
$services = []; // Initialize empty array
if (isset($db_connection_success) && $db_connection_success) {
    $sql = "SELECT id, name, category FROM services ORDER BY name ASC";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $services[] = $row;
            
            // If service_id is provided in URL, get the selected service details
            if ($service_id > 0 && $service_id == $row['id']) {
                $selected_service = $row;
            }
        }
    }
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $service_id = isset($_POST['service_id']) ? intval($_POST['service_id']) : 0;
    $service_name = isset($_POST['service_name']) ? ($conn ? $conn->real_escape_string($_POST['service_name']) : $_POST['service_name']) : '';
    $full_name = isset($_POST['full_name']) ? ($conn ? $conn->real_escape_string($_POST['full_name']) : $_POST['full_name']) : '';
    $email = isset($_POST['email']) ? ($conn ? $conn->real_escape_string($_POST['email']) : $_POST['email']) : '';
    $phone = isset($_POST['phone']) ? ($conn ? $conn->real_escape_string($_POST['phone']) : $_POST['phone']) : '';
    $address = isset($_POST['address']) ? ($conn ? $conn->real_escape_string($_POST['address']) : $_POST['address']) : '';
    $message = isset($_POST['message']) ? ($conn ? $conn->real_escape_string($_POST['message']) : $_POST['message']) : '';
    
    // Validate required fields
    if (empty($service_id) || empty($service_name) || empty($full_name) || empty($email) || empty($phone)) {
        $error_message = "Please fill in all required fields.";
    } else if (!isset($db_connection_success) || !$db_connection_success) {
        $error_message = "Database connection error. Cannot process your request at this time.";
    } else {
        // Insert request into database
        $sql = "INSERT INTO orders (service_id, full_name, email, phone, address, service_name, message) 
                VALUES ($service_id, '$full_name', '$email', '$phone', '$address', '$service_name', '$message')";
        
        if ($conn->query($sql) === TRUE) {
            $success_message = "Your request has been submitted successfully. We will contact you shortly.";
            // Reset form
            $service_id = 0;
            $selected_service = null;
        } else {
            $error_message = "Error: " . $conn->error;
        }
    }
}

// Include header
include_once 'includes/header.php';
?>

<!-- Page Header -->
<section class="bg-primary text-white py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h1>Get Our Services</h1>
                <p class="lead">Fill out the form below to request our services.</p>
            </div>
        </div>
    </div>
</section>

<!-- Service Request Form Section -->
<section class="py-5">
    <div class="container">
        <?php if(!empty($success_message)): ?>
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="success-message">
                        <h4><i data-lucide="check-circle" class="me-2"></i> Request Submitted!</h4>
                        <p><?php echo $success_message; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if(!empty($error_message)): ?>
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="alert alert-danger">
                        <h4><i data-lucide="alert-circle" class="me-2"></i> Error</h4>
                        <p><?php echo $error_message; ?></p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <?php if(!isset($db_connection_success) || !$db_connection_success): ?>
            <div class="row mb-4">
                <div class="col-md-8 mx-auto">
                    <div class="alert alert-warning">
                        <h4><i data-lucide="alert-triangle" class="me-2"></i> Database Connection Error</h4>
                        <p>We are currently experiencing technical difficulties with our database connection. Please try again later.</p>
                    </div>
                </div>
            </div>
        <?php else: ?>
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="buy-service-form">
                    <form method="POST" action="get-service.php" class="needs-validation" novalidate>
                        <div class="service-selection mb-4">
                            <h4 class="mb-3">Select Service</h4>
                            <div class="form-group mb-3">
                                <label for="service_id" class="form-label">Service *</label>
                                <select class="form-select" id="service_id" name="service_id" required onchange="updateServiceName(this)">
                                    <option value="">-- Select a Service --</option>
                                    <?php foreach($services as $service): ?>
                                        <option value="<?php echo $service['id']; ?>"
                                                <?php echo ($service_id == $service['id']) ? 'selected' : ''; ?>>
                                            <?php echo $service['name']; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback">
                                    Please select a service.
                                </div>
                            </div>
                            
                            <!-- Hidden field for service name -->
                            <input type="hidden" name="service_name" id="service_name" value="<?php echo ($selected_service) ? $selected_service['name'] : ''; ?>">
                        </div>
                        
                        <div class="personal-info mb-4">
                            <h4 class="mb-3">Personal Information</h4>
                            <div class="form-group mb-3">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" required>
                                <div class="invalid-feedback">
                                    Please provide your full name.
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                                <div class="invalid-feedback">
                                    Please provide a valid email address.
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                                <div class="invalid-feedback">
                                    Please provide a phone number.
                                </div>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="3"></textarea>
                            </div>
                        </div>
                        
                        <div class="additional-info mb-4">
                            <h4 class="mb-3">Additional Information</h4>
                            <div class="form-group mb-3">
                                <label for="message" class="form-label">Message (Optional)</label>
                                <textarea class="form-control" id="message" name="message" rows="4" placeholder="Any specific requirements or questions?"></textarea>
                            </div>
                        </div>
                        
                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" id="terms" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#">Terms and Conditions</a> and <a href="#">Privacy Policy</a>.
                            </label>
                            <div class="invalid-feedback">
                                You must agree to the terms and conditions.
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">Submit Request</button>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- JavaScript to update form when service is selected -->
<script>
function updateServiceName(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    if (selectedOption) {
        const serviceName = selectedOption.text;
        document.getElementById('service_name').value = serviceName;
    } else {
        document.getElementById('service_name').value = '';
    }
}
</script>

<?php
// Include footer
include_once 'includes/footer.php';
?> 