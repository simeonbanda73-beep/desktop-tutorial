<?php
require "config.php";
// session_start();

// Check if inquiry form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_inquiry'])) {
    
    // Get and sanitize form data
    $form_data = array();
    $form_data['name'] = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $form_data['email'] = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $form_data['phone'] = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $form_data['subject'] = isset($_POST['subject']) ? htmlspecialchars(trim($_POST['subject'])) : '';
    $form_data['message'] = isset($_POST['message']) ? htmlspecialchars(trim($_POST['message'])) : '';
    
    // Validation
    $errors = array();
    
    if (empty($form_data['name'])) {
        $errors[] = "Name is required";
    }
    
    if (empty($form_data['email'])) {
        $errors[] = "Email is required";
    } elseif (!filter_var($form_data['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Please enter a valid email address";
    }
    
    if (empty($form_data['phone'])) {
        $errors[] = "Phone number is required";
    }
    
    if (empty($form_data['subject'])) {
        $errors[] = "Please select a subject";
    }
    
    if (empty($form_data['message'])) {
        $errors[] = "Message is required";
    }
    
    // If no errors, send email
    if (empty($errors)) {
        
        // Owner's email from config.php
        $owner_email = ADMIN_EMAIL;
        
        // Email subject
        $email_subject = "NEW INQUIRY from About Page - " . $form_data['subject'];
        
        // Email body content
        $email_body = "";
        $email_body .= "========================================\n";
        $email_body .= "NEW CONTACT INQUIRY\n";
        $email_body .= "========================================\n\n";
        $email_body .= "Name: " . $form_data['name'] . "\n";
        $email_body .= "Email: " . $form_data['email'] . "\n";
        $email_body .= "Phone: " . $form_data['phone'] . "\n";
        $email_body .= "Subject: " . $form_data['subject'] . "\n\n";
        $email_body .= "Message:\n";
        $email_body .= "----------------------------------------\n";
        $email_body .= $form_data['message'] . "\n";
        $email_body .= "----------------------------------------\n\n";
        $email_body .= "Submitted: " . date("F j, Y, g:i a") . "\n";
        $email_body .= "IP Address: " . $_SERVER['REMOTE_ADDR'] . "\n";
        $email_body .= "========================================\n";
        
        // Email headers
        $headers = "From: " . $form_data['email'] . "\r\n";
        $headers .= "Reply-To: " . $form_data['email'] . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email to owner
        $mail_sent = mail($owner_email, $email_subject, $email_body, $headers);
        
        // Send confirmation email to customer
        if ($mail_sent) {
            $customer_subject = "We received your inquiry - HD Media";
            $customer_message = "";
            $customer_message .= "Dear " . $form_data['name'] . ",\n\n";
            $customer_message .= "Thank you for contacting HD Media!\n\n";
            $customer_message .= "We have received your inquiry regarding: " . $form_data['subject'] . "\n\n";
            $customer_message .= "Our team will review your message and get back to you within 24 hours.\n\n";
            $customer_message .= "Here's a copy of your message:\n";
            $customer_message .= "----------------------------------------\n";
            $customer_message .= $form_data['message'] . "\n";
            $customer_message .= "----------------------------------------\n\n";
            $customer_message .= "Best regards,\n";
            $customer_message .= "HD Media Team\n";
            $customer_message .= "www.hdmedia.com\n";
            
            $customer_headers = "From: $owner_email\r\n";
            $customer_headers .= "Reply-To: $owner_email\r\n";
            
            mail($form_data['email'], $customer_subject, $customer_message, $customer_headers);
            
            // Store success data in session
            $_SESSION['inquiry_success'] = true;
            $_SESSION['inquiry_name'] = $form_data['name'];
            $_SESSION['inquiry_email'] = $form_data['email'];
            $_SESSION['inquiry_message'] = "Thank you " . $form_data['name'] . "! Your inquiry has been sent successfully. We'll get back to you within 24 hours.";
            
            // Redirect back to About.php
            header("Location: About.php");
            exit();
        } else {
            // Store error message
            $_SESSION['inquiry_error'] = "Sorry, there was a technical error sending your inquiry. Please try again or call us directly.";
            $_SESSION['form_data'] = $form_data;
            header("Location: About.php");
            exit();
        }
    } else {
        // Display validation errors
        $error_message = "Please fix the following errors:<br>";
        foreach ($errors as $error) {
            $error_message .= "• $error<br>";
        }
        $_SESSION['inquiry_error'] = $error_message;
        $_SESSION['form_data'] = $form_data;
        header("Location: About.php");
        exit();
    }
} else {
    // If someone tries to access this file directly, redirect to About page
    header("Location: pages/About.php");
    exit();
}
?>