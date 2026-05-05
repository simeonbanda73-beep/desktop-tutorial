<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client_name = $conn->real_escape_string($_POST['client_name']);
    $service_used = $conn->real_escape_string($_POST['service_used'] ?? '');
    $rating = (int)$_POST['rating'];
    $testimonial_text = $conn->real_escape_string($_POST['testimonial_text']);

    // Validate rating
    if ($rating < 1 || $rating > 5) {
        $_SESSION['error_message'] = "Rating must be between 1 and 5.";
        header("Location: Pages/Testimonials.html");
        exit();
    }

    $sql = "INSERT INTO testimonials (client_name, service_used, rating, testimonial_text, approved) 
            VALUES ('$client_name', '$service_used', $rating, '$testimonial_text', FALSE)";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Thank you for your testimonial! It will be reviewed and published soon.";
        header("Location: Pages/Testimonials.html");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
        header("Location: Pages/Testimonials.html");
        exit();
    }
}
$conn->close();
?>
