<?php
require '../config.php';
// session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // Get and sanitize form data
    $name = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $email = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $subject = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : 'Contact Inquiry';
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
    
    if (empty($subject)) {
        $errors[] = "Subject is required";
    }
    
    if (empty($message)) {
        $errors[] = "Message is required";
    }
    
    // If no errors, send email
    if (empty($errors)) {
        
        // Owner's email from config.php
        $owner_email = ADMIN_EMAIL;
        
        // Email subject
        $email_subject = "NEW CONTACT MESSAGE - $subject from $name";
        
        // Email body content (plain text for better compatibility)
        $email_body = "";
        $email_body .= "========================================\n";
        $email_body .= "NEW CONTACT FORM SUBMISSION\n";
        $email_body .= "========================================\n\n";
        $email_body .= "Name: $name\n";
        $email_body .= "Email: $email\n";
        $email_body .= "Phone: $phone\n";
        $email_body .= "Subject: $subject\n\n";
        $email_body .= "Message:\n";
        $email_body .= "----------------------------------------\n";
        $email_body .= "$message\n";
        $email_body .= "----------------------------------------\n\n";
        $email_body .= "Submitted: " . date("F j, Y, g:i a") . "\n";
        $email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $email_body .= "========================================\n";
        
        // Email headers
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email to owner
        $mail_sent = mail($owner_email, $email_subject, $email_body, $headers);
        
        // Send confirmation email to customer
        if ($mail_sent) {
            $customer_subject = "We received your message - HD Media";
            $customer_message = "";
            $customer_message .= "Dear $name,\n\n";
            $customer_message .= "Thank you for contacting HD Media!\n\n";
            $customer_message .= "We have received your message regarding: $subject\n\n";
            $customer_message .= "Our team will review your message and get back to you within 24 hours.\n\n";
            $customer_message .= "Here's a copy of your message:\n";
            $customer_message .= "----------------------------------------\n";
            $customer_message .= "$message\n";
            $customer_message .= "----------------------------------------\n\n";
            $customer_message .= "Best regards,\n";
            $customer_message .= "HD Media Team\n";
            $customer_message .= "Phone: 0978511273 / 0772639402\n";
            $customer_message .= "www.hdmedia.com\n";
            
            $customer_headers = "From: $owner_email\r\n";
            $customer_headers .= "Reply-To: $owner_email\r\n";
            
            mail($email, $customer_subject, $customer_message, $customer_headers);
            
            // Store success data in session
            $_SESSION['contact_success'] = true;
            $_SESSION['contact_name'] = $name;
            $_SESSION['contact_email'] = $email;
            $_SESSION['contact_message'] = "Thank you $name! Your message has been sent successfully. We'll get back to you within 24 hours.";
            
            // Redirect back to Contact.php
            header("Location: Contact.php");
            exit();
        } else {
            // Store error message
            $_SESSION['contact_error'] = "Sorry, there was a technical error sending your message. Please try again or call us directly.";
            $_SESSION['form_data'] = array(
                'name' => $name,
                'email' => $email,
                'phone' => $phone,
                'subject' => $subject,
                'message' => $message
            );
            header("Location: Contact.php");
            exit();
        }
    } else {
        // Display validation errors
        $error_message = "Please fix the following errors:<br>";
        foreach ($errors as $error) {
            $error_message .= "• $error<br>";
        }
        $_SESSION['contact_error'] = $error_message;
        $_SESSION['form_data'] = array(
            'name' => $name,
            'email' => $email,
            'phone' => $phone,
            'subject' => $subject,
            'message' => $message
        );
        header("Location: Contact.php");
        exit();
    }
} else {
    // If someone tries to access this file directly, redirect to contact page
    header("Location: pages/Contact.php");
    exit();
}
?>