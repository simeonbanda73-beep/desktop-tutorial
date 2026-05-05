<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone'] ?? '');
    $service_type = $conn->real_escape_string($_POST['service_type'] ?? '');
    $event_date = $conn->real_escape_string($_POST['event_date'] ?? '');
    $location = $conn->real_escape_string($_POST['location'] ?? '');
    $budget = $conn->real_escape_string($_POST['budget'] ?? '');
    $additional_notes = $conn->real_escape_string($_POST['additional_notes'] ?? '');

    $sql = "INSERT INTO bookings (name, email, phone, service_type, event_date, location, budget, additional_notes) 
            VALUES ('$name', '$email', '$phone', '$service_type', '$event_date', '$location', '$budget', '$additional_notes')";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['success_message'] = "Your booking has been received! We'll contact you soon.";
        header("Location: Pages/Bookings.html");
        exit();
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
        header("Location: Pages/Bookings.html");
        exit();
    }
}
$conn->close();
?>
