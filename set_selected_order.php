<?php
session_start();
header('Content-Type: application/json');

if(isset($_POST['orderId'])) {
    $_SESSION['selected_order_id'] = $_POST['orderId'];
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>