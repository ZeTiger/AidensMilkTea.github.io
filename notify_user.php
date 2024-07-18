<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'client_registration');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    
    $emailStmt = $conn->prepare("SELECT email FROM orderdetails WHERE id = ?");
    $emailStmt->bind_param("i", $orderId);
    $emailStmt->execute();
    $result = $emailStmt->get_result();
    
    if ($row = $result->fetch_assoc()) {
        $to = $row['email'];
        $subject = "Your Order is Ready for Pickup - Aiden's Milktea";
        $message = "Dear Valued Customer,

Great news! Your order from Aiden's Milktea is now ready for pickup.

Order Details:
- Order Number: #" . sprintf('%04d', $orderId) . "
- Status: Ready for Pickup

Your delicious milktea is freshly prepared and waiting for you at our store. Here's what you need to know:

1. Pickup Location: 37 C. Arellano St. San Agustin Malabon City
2. Pickup Hours: Monday to Friday, 9:00 AM - 5:00 PM
3. Order Validity: Please pick up your order within the next hour to ensure optimal freshness.

When you arrive:
- Please have your order number ready.
- If you can't find your order number, our staff can look it up using your name or phone number.
- Let our staff know if you have any special requests or need assistance.

We've taken extra care to prepare your order just the way you like it. If you have any questions or need to make changes, please contact us immediately.

Thank you for choosing Aiden's Milktea. We hope you enjoy your drink and look forward to serving you again soon!

Best regards,
The Aiden's Milktea Team

Note: This is an automated message. For immediate assistance, please contact our store directly at +63 9123456789.";

        $headers = "From: orders@aidensmilktea.com\r\n";
        $headers .= "Reply-To: support@aidensmilktea.com\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        if (mail($to, $subject, $message, $headers)) {
            echo json_encode(['success' => true, 'message' => 'Pickup notification email sent']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to send pickup notification email']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Email not found for this order']);
    }
    
    $emailStmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No order ID provided']);
}

$conn->close();
?>