<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $subject = $conn->real_escape_string($_POST['subject'] ?? 'Contact Inquiry');
    $message = $conn->real_escape_string($_POST['message']);

    $sql = "INSERT INTO contact_inquiries (name, email, phone, subject, message) 
            VALUES ('$name', '$email', '$phone', '$subject', '$message')";

    if ($conn->query($sql) === TRUE) {
        $inquiry_id = $conn->insert_id;

        // Send confirmation email to customer
        $customer_email_body = "
        <html>
            <head><style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
                .header { background-color: #007bff; color: white; padding: 20px; border-radius: 5px 5px 0 0; text-align: center; }
                .content { background-color: white; padding: 20px; }
                .footer { background-color: #f0f0f0; padding: 10px; text-align: center; font-size: 12px; }
            </style></head>
            <body>
                <div class='container'>
                    <div class='header'><h2>Thank You for Contacting HD Media</h2></div>
                    <div class='content'>
                        <p>Hi <strong>$name</strong>,</p>
                        <p>Thank you for reaching out to us! We have received your message and will get back to you shortly.</p>
                        <h3>Your Message Details:</h3>
                        <p><strong>Subject:</strong> $subject</p>
                        <p><strong>Message:</strong><br>" . nl2br($message) . "</p>
                        <p><strong>Inquiry Reference ID:</strong> #$inquiry_id</p>
                        <hr>
                        <p>We typically respond within 24 hours. If your matter is urgent, please call us directly.</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; 2024 HD Media. All rights reserved.</p>
                    </div>
                </div>
            </body>
        </html>";

        // Send notification email to admin
        $admin_email_body = "
        <html>
            <head><style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
                .header { background-color: #28a745; color: white; padding: 20px; border-radius: 5px 5px 0 0; text-align: center; }
                .content { background-color: white; padding: 20px; }
            </style></head>
            <body>
                <div class='container'>
                    <div class='header'><h2>New Contact Inquiry</h2></div>
                    <div class='content'>
                        <p><strong>Inquiry Reference ID:</strong> #$inquiry_id</p>
                        <p><strong>Name:</strong> $name</p>
                        <p><strong>Email:</strong> <a href='mailto:$email'>$email</a></p>
                        <p><strong>Phone:</strong> $phone</p>
                        <p><strong>Subject:</strong> $subject</p>
                        <hr>
                        <p><strong>Message:</strong></p>
                        <p>" . nl2br($message) . "</p>
                        <hr>
                        <p><a href='http://localhost/Website%20Photo-guru/Home/admin.php' style='background-color: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>View in Admin Panel</a></p>
                    </div>
                </div>
            </body>
        </html>";

        // Send emails
        sendEmail($email, "Thank You for Contacting HD Media", $customer_email_body);
        sendEmail(ADMIN_EMAIL, "New Contact Inquiry from $name - #$inquiry_id", $admin_email_body);

        $_SESSION['success_message'] = "Thank you! Your message has been received. We'll contact you shortly.";
        header("Location: Pages/Contact.html");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
        header("Location: Pages/Contact.html");
        exit();
    }
}
$conn->close();
