<?php
require "config.php";

// Initialize variables
$show_form = true;
$success_message = "";
$error_message = "";
$form_data = array();

// Check if form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Get and sanitize form data
    $form_data['name'] = isset($_POST['name']) ? htmlspecialchars(trim($_POST['name'])) : '';
    $form_data['email'] = isset($_POST['email']) ? htmlspecialchars(trim($_POST['email'])) : '';
    $form_data['phone'] = isset($_POST['phone']) ? htmlspecialchars(trim($_POST['phone'])) : '';
    $form_data['service'] = isset($_POST['service']) ? htmlspecialchars(trim($_POST['service'])) : '';
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
    
    if (empty($form_data['service'])) {
        $errors[] = "Please select a service";
    }
    
    // If no errors, send email
    if (empty($errors)) {
        
        // Owner's email from config
        $owner_email = ADMIN_EMAIL; 
        
        // Email subject
        $email_subject = "NEW BOOKING REQUEST - " . $form_data['name'];
        
        // Email body content
        $email_body = "";
        $email_body .= "========================================\n";
        $email_body .= "NEW BOOKING REQUEST\n";
        $email_body .= "========================================\n\n";
        $email_body .= "Client Name: " . $form_data['name'] . "\n";
        $email_body .= "Client Email: " . $form_data['email'] . "\n";
        $email_body .= "Client Phone: " . $form_data['phone'] . "\n";
        $email_body .= "Service Requested: " . $form_data['service'] . "\n\n";
        $email_body .= "Additional Details:\n";
        $email_body .= "----------------------------------------\n";
        $email_body .= $form_data['message'] . "\n";
        $email_body .= "----------------------------------------\n\n";
        $email_body .= "Request Submitted: " . date("F j, Y, g:i a") . "\n";
        $email_body .= "========================================\n";
        
        // Email headers
        $headers = "From: " . $form_data['email'] . "\r\n";
        $headers .= "Reply-To: " . $form_data['email'] . "\r\n";
        $headers .= "X-Mailer: PHP/" . phpversion();
        
        // Send email to owner
        $mail_sent = mail($owner_email, $email_subject, $email_body, $headers);
        
        // Send confirmation email to customer
        if ($mail_sent) {
            $customer_subject = "Booking Request Received - HD Media";
            $customer_message = "";
            $customer_message .= "Dear " . $form_data['name'] . ",\n\n";
            $customer_message .= "Thank you for choosing HD Media!\n\n";
            $customer_message .= "We have received your booking request for: " . $form_data['service'] . "\n\n";
            $customer_message .= "Here's what you requested:\n";
            $customer_message .= "----------------------------------------\n";
            $customer_message .= "Name: " . $form_data['name'] . "\n";
            $customer_message .= "Email: " . $form_data['email'] . "\n";
            $customer_message .= "Phone: " . $form_data['phone'] . "\n";
            $customer_message .= "Service: " . $form_data['service'] . "\n";
            if (!empty($form_data['message'])) {
                $customer_message .= "Details: " . $form_data['message'] . "\n";
            }
            $customer_message .= "----------------------------------------\n\n";
            $customer_message .= "We will review your request and get back to you within 24-48 hours.\n\n";
            $customer_message .= "If you have any urgent questions, please contact us directly.\n\n";
            $customer_message .= "Best regards,\n";
            $customer_message .= "HD Media Team\n";
            $customer_message .= "Phone: [Your Phone Number]\n";
            $customer_message .= "Email: " . $owner_email . "\n";
            
            $customer_headers = "From: $owner_email\r\n";
            $customer_headers .= "Reply-To: $owner_email\r\n";
            
            mail($form_data['email'], $customer_subject, $customer_message, $customer_headers);
            
            // Store success data in session to display after redirect
            session_start();
            $_SESSION['booking_success'] = true;
            $_SESSION['booking_name'] = $form_data['name'];
            $_SESSION['booking_email'] = $form_data['email'];
            $_SESSION['booking_message'] = "Thank you " . $form_data['name'] . "! Your booking request has been sent successfully. We'll contact you within 24-48 hours.";
            
            // Redirect back to Bookings.php to avoid form resubmission
            header("Location: Bookings.php");
            exit();
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

// If we got here with errors, redirect back with error message
if (!empty($error_message)) {
    session_start();
    $_SESSION['booking_error'] = $error_message;
    $_SESSION['form_data'] = $form_data;
    header("Location: pages/Bookings.php");
    exit();
}
?>