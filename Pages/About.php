<?php 
require "../config.php";
// session_start();

// Check for success/error messages from session
$show_inquiry_form = true;
$success_message = "";
$error_message = "";
$form_data = array();

if (isset($_SESSION['inquiry_success']) && $_SESSION['inquiry_success'] === true) {
    $show_inquiry_form = false;
    $success_message = $_SESSION['inquiry_message'];
    $email = $_SESSION['inquiry_email'];
    $name = $_SESSION['inquiry_name'];
    // Clear session data
    unset($_SESSION['inquiry_success']);
    unset($_SESSION['inquiry_message']);
    unset($_SESSION['inquiry_email']);
    unset($_SESSION['inquiry_name']);
}

if (isset($_SESSION['inquiry_error'])) {
    $error_message = $_SESSION['inquiry_error'];
    unset($_SESSION['inquiry_error']);
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
    <title>About Us - HD Media</title>
    <link rel="stylesheet" href="../style.css">
    <style>
        .contact-form-container {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 10px;
            margin-top: 40px;
        }
        .inquiry-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        .inquiry-form input,
        .inquiry-form select,
        .inquiry-form textarea {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .btn-submit {
            background: #333;
            color: white;
            padding: 12px 25px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background: #555;
        }
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
        .testimonial-form {
            margin-top: 30px;
            padding-top: 30px;
            border-top: 2px solid #eee;
        }
    </style>
</head>
<body>

    <!-- Navigation Bar -->
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
            <li><a href="About.php" class="active">About</a></li>
            <li><a href="Contact.php">Contact</a></li>
        </ul>
    </nav>

    <!-- About Us Section -->
    <section class="about">
        <div class="about-container">
            
            <?php if (!$show_inquiry_form): ?>
                <!-- Success Message -->
                <div class="alert-success">
                    <h2>✓ Message Sent Successfully!</h2>
                    <p><?php echo htmlspecialchars($success_message); ?></p>
                    <p style="margin-top: 15px;">A confirmation email has been sent to <strong><?php echo htmlspecialchars($email); ?></strong></p>
                    <a href="About.php" class="btn-submit" style="display: inline-block; margin-top: 15px; text-decoration: none;">Send Another Message</a>
                </div>
            <?php else: ?>
            
                <h1>About HD Media</h1>
                
                <?php if (!empty($error_message)): ?>
                    <div class="alert-error">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <p>Welcome to HD Media! We are passionate about capturing moments that last a lifetime. Our mission is to provide top-quality photography services tailored to your needs.</p>
                
                <p>At <strong>HD Media</strong>, our passion for photography drives us to create stunning, high-quality images that tell a story. Whether it's a wedding, a graduation, or a special event, we are dedicated to making your memories unforgettable.</p>
                
                <h2>Our Vision</h2>
                <p>To be the leading photography service provider, delivering creativity and excellence in every shot.</p>
                
                <h2>Why Choose Us?</h2>
                <p>With years of experience, professional equipment, and an eye for detail, we ensure every photograph tells a story.</p>
                
                <h2>What to Expect?</h2>
                <p>When you hire us, expect professionalism, creativity, and stunning photography that exceeds your expectations.</p>
                
                <ul>
                    <li>Professional and High-Quality Photography – We use top-tier equipment and techniques to ensure your photos are perfect.</li>
                    <li>Creative and Personalized Approach – Every session is tailored to match your style and personality.</li>
                    <li>Timely Delivery – Expect your photos to be edited and delivered within the agreed timeframe.</li>
                    <li>Friendly and Comfortable Sessions – We create a relaxed environment, making sure you feel comfortable and confident.</li>
                    <li>Affordable and Transparent Pricing – No hidden fees, just top-quality photography at great value.</li>
                </ul>
                
                <p style="margin-top: 20px;">
                    <strong>Ready to create unforgettable memories? Get in touch today and let's make magic happen!</strong>
                </p>
                
                <!-- Contact/Inquiry Form -->
                <div class="contact-form-container">
                    <h2>📧 Send Us a Message</h2>
                    <p>Have questions? Want to book a session? Fill out the form below and we'll get back to you shortly.</p>
                    
                    <form method="POST" action="../process_inquiry.php" class="inquiry-form">
                        <input type="text" name="name" placeholder="Your Full Name" value="<?php echo isset($form_data['name']) ? htmlspecialchars($form_data['name']) : ''; ?>" required>
                        <input type="email" name="email" placeholder="Your Email Address" value="<?php echo isset($form_data['email']) ? htmlspecialchars($form_data['email']) : ''; ?>" required>
                        <input type="tel" name="phone" placeholder="Your Phone Number" value="<?php echo isset($form_data['phone']) ? htmlspecialchars($form_data['phone']) : ''; ?>" required>
                        
                        <select name="subject" required>
                            <option value="">Select Subject</option>
                            <option value="General Inquiry" <?php echo (isset($form_data['subject']) && $form_data['subject'] == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                            <option value="Booking Question" <?php echo (isset($form_data['subject']) && $form_data['subject'] == 'Booking Question') ? 'selected' : ''; ?>>Booking Question</option>
                            <option value="Pricing Request" <?php echo (isset($form_data['subject']) && $form_data['subject'] == 'Pricing Request') ? 'selected' : ''; ?>>Pricing Request</option>
                            <option value="Collaboration" <?php echo (isset($form_data['subject']) && $form_data['subject'] == 'Collaboration') ? 'selected' : ''; ?>>Collaboration Opportunity</option>
                            <option value="Feedback" <?php echo (isset($form_data['subject']) && $form_data['subject'] == 'Feedback') ? 'selected' : ''; ?>>Feedback/Suggestion</option>
                            <option value="Other" <?php echo (isset($form_data['subject']) && $form_data['subject'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                        
                        <textarea name="message" placeholder="Your Message" rows="6" required><?php echo isset($form_data['message']) ? htmlspecialchars($form_data['message']) : ''; ?></textarea>
                        
                        <button type="submit" name="submit_inquiry" class="btn-submit">Send Message</button>
                    </form>
                </div>
                
            <?php endif; ?>
            
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>© 2025 HD Media | Follow us on 
            <a href="https://instagram.com/hdmedia" target="_blank">Instagram</a> | 
            <a href="https://facebook.com/hdmedia" target="_blank">Facebook</a>
        </p>
    </footer>

</body>
</html>