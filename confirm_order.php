<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'client_registration');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    
    $stmt = $conn->prepare("UPDATE orderdetails SET status = 'preparing' WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    
    if ($stmt->execute()) {
        $emailStmt = $conn->prepare("SELECT email FROM orderdetails WHERE id = ?");
        $emailStmt->bind_param("i", $orderId);
        $emailStmt->execute();
        $result = $emailStmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $to = $row['email'];
            $subject = "Order Confirmation - Aiden's Milktea";
            $message = "Dear Valued Customer,

Thank you for choosing Aiden's Milktea! We're delighted to confirm that we've received your order.

Order Details:
- Order Number: #" . sprintf('%04d', $orderId) . "
- Status: Preparing

We're excited to start preparing your delicious milktea. Our team is working diligently to ensure your order is made to perfection.

You'll receive another notification when your order is ready for pickup.

If you have any questions or need to make changes to your order, please don't hesitate to reply to this email or contact our customer service team.

We appreciate your business and look forward to serving you!

Best regards,
The Aiden's Milktea Team

Note: This is an automated message. Please do not reply directly to this email.";

            $headers = "From: orders@aidensmilktea.com\r\n";
            $headers .= "Reply-To: support@aidensmilktea.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            if (mail($to, $subject, $message, $headers)) {
                echo json_encode(['success' => true, 'message' => 'Order confirmed and email sent']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Order confirmed but failed to send email']);
            }
        } else {
            echo json_encode(['success' => true, 'message' => 'Order confirmed but email not found']);
        }
        
        $emailStmt->close();
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No order ID provided']);
}

$conn->close();
?>