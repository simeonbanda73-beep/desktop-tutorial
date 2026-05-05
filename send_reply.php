<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inquiry_id = (int)$_POST['inquiry_id'];
    $reply_message = $conn->real_escape_string($_POST['reply_message']);

    // Get the inquiry details
    $result = $conn->query("SELECT * FROM contact_inquiries WHERE id = $inquiry_id");

    if ($result && $result->num_rows > 0) {
        $inquiry = $result->fetch_assoc();
        $customer_email = $inquiry['email'];
        $customer_name = $inquiry['name'];
        $inquiry_subject = $inquiry['subject'];

        // Update inquiry status
        $conn->query("UPDATE contact_inquiries SET status = 'replied' WHERE id = $inquiry_id");

        // Send reply email to customer
        $reply_email_body = "
        <html>
            <head><style>
                body { font-family: Arial, sans-serif; color: #333; }
                .container { max-width: 600px; margin: 0 auto; background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
                .header { background-color: #007bff; color: white; padding: 20px; border-radius: 5px 5px 0 0; text-align: center; }
                .content { background-color: white; padding: 20px; }
                .footer { background-color: #f0f0f0; padding: 10px; text-align: center; font-size: 12px; }
                .original-message { background-color: #f5f5f5; border-left: 4px solid #007bff; padding: 15px; margin-top: 20px; }
            </style></head>
            <body>
                <div class='container'>
                    <div class='header'><h2>Reply to Your Inquiry - HD Media</h2></div>
                    <div class='content'>
                        <p>Hi <strong>$customer_name</strong>,</p>
                        <p>Thank you for your patience. Here's our response to your inquiry:</p>
                        <hr>
                        <p>" . nl2br($reply_message) . "</p>
                        <hr>
                        <div class='original-message'>
                            <p><strong>Your Original Message (Inquiry #$inquiry_id):</strong></p>
                            <p><strong>Subject:</strong> $inquiry_subject</p>
                            <p>" . nl2br($inquiry['message']) . "</p>
                        </div>
                        <p>If you have any further questions, feel free to reply to this email or contact us directly.</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; 2024 HD Media. All rights reserved.</p>
                    </div>
                </div>
            </body>
        </html>";

        // Send the reply email
        if (sendEmail($customer_email, "Re: " . $inquiry_subject . " - HD Media Response", $reply_email_body)) {
            $_SESSION['success_message'] = "Reply sent successfully to $customer_name!";
        } else {
            $_SESSION['error_message'] = "Reply could not be sent. Please check your email configuration.";
        }
    } else {
        $_SESSION['error_message'] = "Inquiry not found!";
    }

    header("Location: admin.php");
    exit();
}
