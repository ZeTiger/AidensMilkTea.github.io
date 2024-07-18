<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'client_registration');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    
    $stmt = $conn->prepare("UPDATE orderdetails SET status = 'completed' WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $stmt->error]);
    }
    
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'No order ID provided']);
}

$conn->close();
?>