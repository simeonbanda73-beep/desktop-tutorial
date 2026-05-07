<?php require "../config.php" ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookings - HD Media</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .alert-success {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #28a745;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border-left: 4px solid #dc3545;
        }
    </style>
</head>
<body>

<?php
// Initialize variables
$show_form = true;
$success_message = "";
$error_message = "";

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get and sanitize form data
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $service = isset($_POST['service']) ? htmlspecialchars(trim($_POST['service'])) : '';
    $message = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
    
    // Validation
    $errors = array();
    
    if (empty($name)) {
        $errors[] = "Name is required";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    if (empty($phone)) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($service)) {
        $errors[] = "Please select a service";
    }
    
    // If no errors, send email
    if (empty($errors)) {
        
        // Owner's email - CHANGE THIS TO YOUR EMAIL
        $owner_email = ADMIN_EMAIL; 
        
        // Email subject
        $email_subject = "NEW BOOKING REQUEST - $name";
        
        // Email body content
        $email_body = "";
        $email_body .= "========================================\n";
        $email_body .= "NEW BOOKING REQUEST\n";
        $email_body .= "========================================\n\n";
        $email_body .= "Client Name: $name\n";
        $email_body .= "Client Email: $email\n";
        $email_body .= "Client Phone: $phone\n";
        $email_body .= "Service Requested: $service\n\n";
        $email_body .= "Additional Details:\n";
        $email_body .= "----------------------------------------\n";
        $email_body .= "$message\n";
        $email_body .= "----------------------------------------\n\n";
        $email_body .= "Request Submitted: " . date("F j, Y, g:i a") . "\n";
        $email_body .= "========================================\n";
        
        // Email headers
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email to owner
        $mail_sent = mail($owner_email, $email_subject, $email_body, $headers);
        
        // Send confirmation email to customer
        if ($mail_sent) {
            $customer_subject = "Booking Request Received - HD Media";
            $customer_message = "";
            $customer_message .= "Dear $name,\n\n";
            $customer_message .= "Thank you for choosing HD Media!\n\n";
            $customer_message .= "We have received your booking request for: $service\n\n";
            $customer_message .= "Here's what you requested:\n";
            $customer_message .= "----------------------------------------\n";
            $customer_message .= "Name: $name\n";
            $customer_message .= "Email: $email\n";
            $customer_message .= "Phone: $phone\n";
            $customer_message .= "Service: $service\n";
            if (!empty($message)) {
                $customer_message .= "Details: $message\n";
            }
            $customer_message .= "----------------------------------------\n\n";
            $customer_message .= "We will review your request and get back to you within 24-48 hours.\n\n";
            $customer_message .= "If you have any urgent questions, please contact us directly.\n\n";
            $customer_message .= "Best regards,\n";
            $customer_message .= "HD Media Team\n";
            $customer_message .= "Phone: [Your Phone Number]\n";
            $customer_message .= "Email: $owner_email\n";
            
            $customer_headers = "From: $owner_email\r\n";
            $customer_headers .= "Reply-To: $owner_email\r\n";
            
            mail($email, $customer_subject, $customer_message, $customer_headers);
            
            // Success - hide form and show success message
            $show_form = false;
            $success_message = "Thank you $name! Your booking request has been sent successfully. We'll contact you within 24-48 hours.";
        } else {
            $error_message = "Sorry, there was a technical error sending your request. Please try again or call us directly.";
        }
    } else {
        // Display validation errors
        $error_message = "Please fix the following errors:<br>";
        foreach ($errors as $error) {
            $error_message .= "- $error<br>";
        }
    }
}
?>

    <nav class="navbar">
        <div class="logo">
            <a href="../index.php"><img src="../images/hd-media-logo.svg" alt="HD Media Logo"></a>
        </div>
        <ul class="nav-links">
            <li><a href="../index.php">Home</a></li>
            <li><a href="Services.php">Services</a></li>
            <li><a href="Portfolio.php">Portfolio</a></li>
            <li><a href="Pricing.php">Pricing</a></li>
            <li><a href="Team.php">Team</a></li>
            <li><a href="Testimonials.php">Testimonials</a></li>
            <li><a href="Process.php">Process</a></li>
            <li><a href="FAQ.php">FAQ</a></li>
            <li><a href="Bookings.php" class="active">Bookings</a></li>
            <li><a href="About.php">About</a></li>
            <li><a href="Contact.php">Contact</a></li>
        </ul>
    </nav>
    
    <section class="booking">
        <div class="booking-container">
            
            <?php if (!$show_form): ?>
                <!-- Success Message -->
                <div class="alert-success">
                    <h2>✓ Booking Request Sent!</h2>
                    <p><?php echo $success_message; ?></p>
                    <p style="margin-top: 15px;">A confirmation email has been sent to <strong><?php echo $email; ?></strong></p>
                    <a href="Bookings.php" class="btn" style="display: inline-block; margin-top: 15px;">Book Another Service</a>
                </div>
            <?php else: ?>
            
                <h1>Book a Photography Session</h1>
                <p>Select a service and schedule your appointment with us.</p>
                
                <?php if (!empty($error_message)): ?>
                    <div class="alert-error">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <h2>Our Services</h2>
                <ul class="services-list">
                    <li>Weddings Photography</li>
                    <li>Portrait Photography</li>
                    <li>Birthday Shoots</li>
                    <li>Graduation Photography</li>
                    <li>Corporate & Private Events</li>
                    <li>Live Church Broadcasts</li>
                    <li>Live Event Streaming</li>
                    <li>Video Production & Editing</li>
                    <li>Drone Photography & Videography</li>
                    <li>Product Photography</li>
                    <li>Real Estate Photography</li>
                    <li>Social Media Content Creation</li>
                    <li>Corporate Photography</li>
                    <li>Family Portrait Sessions</li>
                    <li>Professional Headshots</li>
                    <li>Video Testimonials</li>
                    <li>Documentary Photography</li>
                </ul>
                
                <h2>📅 Schedule Your Session</h2>
                <form method="POST" action="">
                    <input type="text" name="name" placeholder="Your Name" value="<?php echo isset($name) ? $name : ''; ?>" required>
                    <input type="email" name="email" placeholder="Your Email" value="<?php echo isset($email) ? $email : ''; ?>" required>
                    <input type="tel" name="phone" placeholder="Your Phone Number" value="<?php echo isset($phone) ? $phone : ''; ?>" required>
                    <select name="service" required>
                        <option value="">Select a Service</option>
                        <option value="Weddings Photography" <?php echo (isset($service) && $service == 'Weddings Photography') ? 'selected' : ''; ?>>Weddings Photography</option>
                        <option value="Portrait Photography" <?php echo (isset($service) && $service == 'Portrait Photography') ? 'selected' : ''; ?>>Portrait Photography</option>
                        <option value="Birthday Shoots" <?php echo (isset($service) && $service == 'Birthday Shoots') ? 'selected' : ''; ?>>Birthday Shoots</option>
                        <option value="Graduation Photography" <?php echo (isset($service) && $service == 'Graduation Photography') ? 'selected' : ''; ?>>Graduation Photography</option>
                        <option value="Corporate & Private Events" <?php echo (isset($service) && $service == 'Corporate & Private Events') ? 'selected' : ''; ?>>Corporate & Private Events</option>
                        <option value="Live Church Broadcasts" <?php echo (isset($service) && $service == 'Live Church Broadcasts') ? 'selected' : ''; ?>>Live Church Broadcasts</option>
                        <option value="Live Event Streaming" <?php echo (isset($service) && $service == 'Live Event Streaming') ? 'selected' : ''; ?>>Live Event Streaming</option>
                        <option value="Video Production & Editing" <?php echo (isset($service) && $service == 'Video Production & Editing') ? 'selected' : ''; ?>>Video Production & Editing</option>
                        <option value="Drone Photography & Videography" <?php echo (isset($service) && $service == 'Drone Photography & Videography') ? 'selected' : ''; ?>>Drone Photography & Videography</option>
                        <option value="Product Photography" <?php echo (isset($service) && $service == 'Product Photography') ? 'selected' : ''; ?>>Product Photography</option>
                        <option value="Real Estate Photography" <?php echo (isset($service) && $service == 'Real Estate Photography') ? 'selected' : ''; ?>>Real Estate Photography</option>
                        <option value="Social Media Content Creation" <?php echo (isset($service) && $service == 'Social Media Content Creation') ? 'selected' : ''; ?>>Social Media Content Creation</option>
                        <option value="Corporate Photography" <?php echo (isset($service) && $service == 'Corporate Photography') ? 'selected' : ''; ?>>Corporate Photography</option>
                        <option value="Family Portrait Sessions" <?php echo (isset($service) && $service == 'Family Portrait Sessions') ? 'selected' : ''; ?>>Family Portrait Sessions</option>
                        <option value="Professional Headshots" <?php echo (isset($service) && $service == 'Professional Headshots') ? 'selected' : ''; ?>>Professional Headshots</option>
                        <option value="Video Testimonials" <?php echo (isset($service) && $service == 'Video Testimonials') ? 'selected' : ''; ?>>Video Testimonials</option>
                        <option value="Documentary Photography" <?php echo (isset($service) && $service == 'Documentary Photography') ? 'selected' : ''; ?>>Documentary Photography</option>
                    </select>
                    <textarea name="message" placeholder="Additional Details (date preference, location, special requests, etc.)" rows="5"><?php echo isset($message) ? $message : ''; ?></textarea>
                    <button type="submit" class="btn">Book Now</button>
                </form>
            
            <?php endif; ?>
            
        </div>
    </section>
</body>
</html>