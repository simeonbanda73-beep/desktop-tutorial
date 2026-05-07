<?php 
require '../config.php';
// session_start();

// Check for success/error messages from session
$show_form = true;
$success_message = "";
$error_message = "";
$form_data = array();

if (isset($_SESSION['contact_success']) && $_SESSION['contact_success'] === true) {
    $show_form = false;
    $success_message = $_SESSION['contact_message'];
    $email = $_SESSION['contact_email'];
    $name = $_SESSION['contact_name'];
    // Clear session data
    unset($_SESSION['contact_success']);
    unset($_SESSION['contact_message']);
    unset($_SESSION['contact_email']);
    unset($_SESSION['contact_name']);
}

if (isset($_SESSION['contact_error'])) {
    $error_message = $_SESSION['contact_error'];
    unset($_SESSION['contact_error']);
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
    <title>Contact Us - HD Media</title>
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
            <li><a href="Bookings.php">Bookings</a></li>
            <li><a href="About.php">About</a></li>
            <li><a href="Contact.php" class="active">Contact</a></li>
        </ul>
    </nav>
    <section class="contact">
        <div class="contact-container">
            
            <?php if (!$show_form): ?>
                <!-- Success Message -->
                <div class="alert-success">
                    <h2>✓ Message Sent Successfully!</h2>
                    <p><?php echo htmlspecialchars($success_message); ?></p>
                    <p style="margin-top: 15px;">A confirmation email has been sent to <strong><?php echo htmlspecialchars($email); ?></strong></p>
                    <a href="Contact.php" class="btn" style="display: inline-block; margin-top: 15px; text-decoration: none;">Send Another Message</a>
                </div>
            <?php else: ?>
            
                <h1>Contact Us</h1>
                
                <?php if (!empty($error_message)): ?>
                    <div class="alert-error">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <p>We'd love to hear from you! Whether you have a question about our photography services or want to make a booking, feel free to reach out.</p>
                <h2>📱 Contact Details</h2>
                <p><strong>WhatsApp:</strong> <a href="https://wa.me/260978511273" target="_blank">0978511273</a></p>
                <p><strong>Direct Calls:</strong> <a href="tel:+260772639402">0772639402</a></p>
                <h2>Follow Us</h2>
                <p>
                    <a href="https://facebook.com/hdmedia" target="_blank">Facebook</a> |
                    <a href="https://instagram.com/hdmedia" target="_blank">Instagram</a>
                </p>
                <h2>📩 Send Us a Message</h2>
                <form action="../process_contact.php" method="POST">
                    <input type="text" name="name" placeholder="Your Name" value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''; ?>" required>
                    <input type="email" name="email" placeholder="Your Email" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>" required>
                    <input type="tel" name="phone" placeholder="Your Phone Number" value="<?php echo isset($form_data['phone']) ? htmlspecialchars($form_data['phone']) : ''; ?>" required>
                    <input type="text" name="subject" placeholder="Subject" value="<?php echo isset($form_data['subject']) ? htmlspecialchars($form_data['subject']) : ''; ?>" required>
                    <textarea name="message" placeholder="Your Message" rows="5" required><?php echo isset($form_data['message']) ? htmlspecialchars($form_data['message']) : ''; ?></textarea>
                    <button type="submit" class="btn">Send Message</button>
                </form>
            
            <?php endif; ?>
            
        </div>
    </section>
</body>
</html>