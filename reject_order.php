<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'client_registration');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    
    $stmt = $conn->prepare("UPDATE orderdetails SET status = 'rejected' WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    
    if ($stmt->execute()) {
        $emailStmt = $conn->prepare("SELECT email FROM orderdetails WHERE id = ?");
        $emailStmt->bind_param("i", $orderId);
        $emailStmt->execute();
        $result = $emailStmt->get_result();
        
        if ($row = $result->fetch_assoc()) {
            $to = $row['email'];
            $subject = "Order Rejected - Aiden's Milktea";
            $message = "Dear Valued Customer,

We hope this message finds you well. We're writing to provide an update on your recent order with Aiden's Milktea.

Order Details:
- Order Number: #" . sprintf('%04d', $orderId) . "
- Status: Unable to Process

We regret to inform you that we are unable to process your order at this time. This could be due to a variety of reasons, such as inventory shortages, technical issues, or other unforeseen circumstances.

We sincerely apologize for any inconvenience this may cause. We value your business and would like to make this right.

Next Steps:
1. If you have any questions or would like more information about why your order couldn't be processed, please don't hesitate to contact our customer service team.
2. We encourage you to try placing your order again at a later time, as the issue may be temporary.
3. If you paid for this order, a full refund will be processed within 3-5 business days.

We appreciate your understanding and thank you for your continued support of Aiden's Milktea. We hope to have the opportunity to serve you again soon.

Best regards,
The Aiden's Milktea Team

Note: This is an automated message. For immediate assistance, please contact our customer service team directly.";

            $headers = "From: orders@aidensmilktea.com\r\n";
            $headers .= "Reply-To: support@aidensmilktea.com\r\n";
            $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
            
            if (mail($to, $subject, $message, $headers)) {
                echo json_encode(['success' => true, 'message' => 'Order rejected and email sent']);
            } else {
                echo json_encode(['success' => true, 'message' => 'Order rejected but failed to send email']);
            }
        } else {
            echo json_encode(['success' => true, 'message' => 'Order rejected but email not found']);
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
?>