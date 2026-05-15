<?php 
require "../config.php";
// session_start();

// Check for success/error messages from session
$show_form = true;
$success_message = "";
$error_message = "";
$form_data = array();

if (isset($_SESSION['booking_success']) && $_SESSION['booking_success'] === true) {
    $show_form = false;
    $success_message = $_SESSION['booking_message'];
    $email = $_SESSION['booking_email'];
    // Clear session data
    unset($_SESSION['booking_success']);
    unset($_SESSION['booking_message']);
    unset($_SESSION['booking_email']);
}

if (isset($_SESSION['booking_error'])) {
    $error_message = $_SESSION['booking_error'];
    unset($_SESSION['booking_error']);
}

if (isset($_SESSION['form_data'])) {
    $form_data = $_SESSION['form_data'];
    unset($_SESSION['form_data']);
}
?>

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
                    <p><?php echo htmlspecialchars($success_message); ?></p>
                    <p style="margin-top: 15px;">A confirmation email has been sent to <strong><?php echo htmlspecialchars($email); ?></strong></p>
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
                <form method="POST" action="../process_booking.php">
                    <input type="text" name="name" placeholder="Your Name" value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''; ?>" required>
                    <input type="email" name="email" placeholder="Your Email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>" required>
                    <input type="tel" name="phone" placeholder="Your Phone Number" value="<?php echo isset($form_data['phone']) ? htmlspecialchars($form_data['phone']) : ''; ?>" required>
                    <select name="service" required>
                        <option value="">Select a Service</option>
                        <option value="Weddings Photography" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Weddings Photography') ? 'selected' : ''; ?>>Weddings Photography</option>
                        <option value="Portrait Photography" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Portrait Photography') ? 'selected' : ''; ?>>Portrait Photography</option>
                        <option value="Birthday Shoots" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Birthday Shoots') ? 'selected' : ''; ?>>Birthday Shoots</option>
                        <option value="Graduation Photography" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Graduation Photography') ? 'selected' : ''; ?>>Graduation Photography</option>
                        <option value="Corporate & Private Events" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Corporate & Private Events') ? 'selected' : ''; ?>>Corporate & Private Events</option>
                        <option value="Live Church Broadcasts" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Live Church Broadcasts') ? 'selected' : ''; ?>>Live Church Broadcasts</option>
                        <option value="Live Event Streaming" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Live Event Streaming') ? 'selected' : ''; ?>>Live Event Streaming</option>
                        <option value="Video Production & Editing" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Video Production & Editing') ? 'selected' : ''; ?>>Video Production & Editing</option>
                        <option value="Drone Photography & Videography" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Drone Photography & Videography') ? 'selected' : ''; ?>>Drone Photography & Videography</option>
                        <option value="Product Photography" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Product Photography') ? 'selected' : ''; ?>>Product Photography</option>
                        <option value="Real Estate Photography" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Real Estate Photography') ? 'selected' : ''; ?>>Real Estate Photography</option>
                        <option value="Social Media Content Creation" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Social Media Content Creation') ? 'selected' : ''; ?>>Social Media Content Creation</option>
                        <option value="Corporate Photography" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Corporate Photography') ? 'selected' : ''; ?>>Corporate Photography</option>
                        <option value="Family Portrait Sessions" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Family Portrait Sessions') ? 'selected' : ''; ?>>Family Portrait Sessions</option>
                        <option value="Professional Headshots" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Professional Headshots') ? 'selected' : ''; ?>>Professional Headshots</option>
                        <option value="Video Testimonials" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Video Testimonials') ? 'selected' : ''; ?>>Video Testimonials</option>
                        <option value="Documentary Photography" <?php echo (isset($form_data['service']) && $form_data['service'] == 'Documentary Photography') ? 'selected' : ''; ?>>Documentary Photography</option>
                    </select>
                    <textarea name="message" placeholder="Additional Details (date preference, location, special requests, etc.)" rows="5"><?php echo isset($form_data['message']) ? htmlspecialchars($form_data['message']) : ''; ?></textarea>
                    <button type="submit" class="btn">Book Now</button>
                </form>
            
            <?php endif; ?>
            
        </div>
    </section>
</body>
</html>