<?php
require 'config.php';

// Handle view/close modals
$view_inquiry = isset($_GET['view']) ? (int)$_GET['view'] : null;
$inquiry_details = null;

if ($view_inquiry) {
    $result = $conn->query("SELECT * FROM contact_inquiries WHERE id = $view_inquiry");
    if ($result && $result->num_rows > 0) {
        $inquiry_details = $result->fetch_assoc();
    }
}

$bookings = $conn->query("SELECT * FROM bookings ORDER BY booking_date DESC LIMIT 10");
$contacts = $conn->query("SELECT * FROM contact_inquiries ORDER BY inquiry_date DESC LIMIT 10");
$testimonials = $conn->query("SELECT * FROM testimonials ORDER BY date_submitted DESC LIMIT 10");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - HD Media</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
        }

        .top-bar {
            background-color: #007bff;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .top-bar h1 {
            font-size: 24px;
        }

        .admin-email {
            font-size: 14px;
            opacity: 0.9;
        }

        .container {
            max-width: 1400px;
            margin: 20px auto;
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .messages {
            margin-bottom: 20px;
        }

        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .message.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        h2 {
            color: #007bff;
            margin-top: 30px;
            margin-bottom: 15px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 10px;
        }

        h2:first-of-type {
            margin-top: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #007bff;
            color: white;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f0f0f0;
        }

        .status-pending {
            background-color: #fff3cd;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        .status-replied {
            background-color: #d4edda;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        .status-approved {
            background-color: #d4edda;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 12px;
            display: inline-block;
        }

        .btn {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #545b62;
        }

        .no-data {
            color: #999;
            font-style: italic;
            text-align: center;
            padding: 20px;
        }

        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
        }

        .modal.show {
            display: block;
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 20px;
            border-radius: 8px;
            width: 90%;
            max-width: 700px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .modal-header {
            background-color: #007bff;
            color: white;
            padding: 15px;
            margin: -20px -20px 20px -20px;
            border-radius: 8px 8px 0 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            margin: 0;
            border: none;
            color: white;
            font-size: 20px;
        }

        .close-btn {
            color: white;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 0;
            line-height: 1;
        }

        .close-btn:hover {
            color: #ddd;
        }

        .inquiry-details {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .inquiry-details p {
            margin-bottom: 10px;
            line-height: 1.6;
        }

        .inquiry-details strong {
            display: inline-block;
            min-width: 120px;
            color: #007bff;
        }

        .reply-form {
            margin-top: 20px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }

        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            resize: vertical;
            min-height: 150px;
        }

        .form-actions {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
            margin-top: 15px;
        }

        .inquiry-row:hover {
            cursor: pointer;
            background-color: #e7f3ff !important;
        }

        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
</head>

<body>
    <div class="top-bar">
        <div>
            <h1>📊 HD Media Admin Panel</h1>
            <p style="margin-top: 5px; font-size: 14px;">Welcome! Manage your inquiries and customer feedback.</p>
        </div>
        <div class="admin-email">
            <p>Admin Email: <strong><?php echo htmlspecialchars(ADMIN_EMAIL); ?></strong></p>
        </div>
    </div>

    <div class="container">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="messages">
                <div class="message success">
                    ✓ <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                </div>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error_message'])): ?>
            <div class="messages">
                <div class="message error">
                    ✗ <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                </div>
            </div>
            <?php unset($_SESSION['error_message']); ?>
        <?php endif; ?>

        <h2>📅 Recent Bookings</h2>
        <?php if ($bookings->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Service</th>
                    <th>Event Date</th>
                    <th>Status</th>
                    <th>Date Received</th>
                </tr>
                <?php while ($row = $bookings->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['service_type']); ?></td>
                        <td><?php echo htmlspecialchars($row['event_date']); ?></td>
                        <td><span class="status-pending"><?php echo ucfirst($row['status']); ?></span></td>
                        <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No bookings yet.</p>
        <?php endif; ?>

        <h2>📧 Contact Inquiries & Feedback</h2>
        <p style="margin-bottom: 15px; color: #666;">Click on any inquiry to view details and send a reply.</p>
        <?php if ($contacts->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Subject</th>
                    <th>Status</th>
                    <th>Date Received</th>
                    <th style="width: 100px;">Actions</th>
                </tr>
                <?php while ($row = $contacts->fetch_assoc()): ?>
                    <tr class="inquiry-row">
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td>
                            <span class="status-<?php echo $row['status']; ?>">
                                <?php echo ucfirst($row['status']); ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($row['inquiry_date']); ?></td>
                        <td>
                            <div class="action-buttons">
                                <button class="btn btn-sm" onclick="openInquiry(<?php echo $row['id']; ?>)">Reply</button>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No contact inquiries yet.</p>
        <?php endif; ?>

        <h2>⭐ Recent Testimonials</h2>
        <?php if ($testimonials->num_rows > 0): ?>
            <table>
                <tr>
                    <th>Client Name</th>
                    <th>Service Used</th>
                    <th>Rating</th>
                    <th>Approved</th>
                    <th>Date Submitted</th>
                </tr>
                <?php while ($row = $testimonials->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['service_used']); ?></td>
                        <td><?php echo str_repeat('⭐', $row['rating']); ?></td>
                        <td>
                            <span class="<?php echo $row['approved'] ? 'status-approved' : 'status-pending'; ?>">
                                <?php echo $row['approved'] ? 'Yes' : 'No'; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars($row['date_submitted']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </table>
        <?php else: ?>
            <p class="no-data">No testimonials yet.</p>
        <?php endif; ?>
    </div>

    <!-- Reply Modal -->
    <div id="replyModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Send Reply to Customer</h2>
                <button class="close-btn" onclick="closeModal()">&times;</button>
            </div>

            <?php if ($inquiry_details): ?>
                <div class="inquiry-details">
                    <h3 style="margin-bottom: 15px; color: #007bff;">Original Inquiry</h3>
                    <p><strong>Inquiry ID:</strong> #<?php echo $inquiry_details['id']; ?></p>
                    <p><strong>From:</strong> <?php echo htmlspecialchars($inquiry_details['name']); ?></p>
                    <p><strong>Email:</strong> <a href="mailto:<?php echo $inquiry_details['email']; ?>"><?php echo htmlspecialchars($inquiry_details['email']); ?></a></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($inquiry_details['phone']); ?></p>
                    <p><strong>Subject:</strong> <?php echo htmlspecialchars($inquiry_details['subject']); ?></p>
                    <p><strong>Message:</strong></p>
                    <div style="background-color: #fff; padding: 10px; border-left: 4px solid #007bff; margin-top: 10px;">
                        <?php echo nl2br(htmlspecialchars($inquiry_details['message'])); ?>
                    </div>
                </div>

                <form method="POST" action="send_reply.php" class="reply-form">
                    <input type="hidden" name="inquiry_id" value="<?php echo $inquiry_details['id']; ?>">
                    <div class="form-group">
                        <label for="reply_message">Your Reply:</label>
                        <textarea id="reply_message" name="reply_message" required placeholder="Type your response here..."></textarea>
                    </div>
                    <div class="form-actions">
                        <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                        <button type="submit" class="btn">Send Reply</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function openInquiry(inquiryId) {
            window.location.href = '?view=' + inquiryId;
        }

        function closeModal() {
            window.location.href = 'admin.php';
        }

        // Show modal if viewing an inquiry
        <?php if ($view_inquiry): ?>
            document.getElementById('replyModal').classList.add('show');
        <?php endif; ?>
    </script>
</body>

</html>

<?php $conn->close(); ?>