<?php
session_start();

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'HD_Media');

// Email Configuration - CHANGE THESE TO YOUR EMAIL SETTINGS
define('ADMIN_EMAIL', 'simeonbanda73@gmail.com'); // Your Gmail address
define('ADMIN_NAME', 'HD Media Admin');
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'simeonbanda73@gmail.com'); // Your Gmail address
define('SMTP_PASSWORD', ''); // Your Gmail app password (16 characters)
define('USE_SMTP', false); // Set to false until you add your app password

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8");

// Function to send emails
function sendEmail($to, $subject, $body, $isHtml = true)
{
    if (USE_SMTP) {
        // Using SMTP (requires additional setup)
        return mail($to, $subject, $body, "MIME-Version: 1.0\r\nContent-type: text/html; charset=UTF-8\r\nFrom: " . ADMIN_EMAIL);
    } else {
        // Using PHP mail() function
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: " . ADMIN_NAME . " <" . ADMIN_EMAIL . ">\r\n";
        return mail($to, $subject, $body, $headers);
    }
}
