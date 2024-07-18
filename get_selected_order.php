<?php
session_start();
header('Content-Type: application/json');

if(isset($_SESSION['selected_order_id'])) {
    echo json_encode(['orderId' => $_SESSION['selected_order_id']]);
} else {
    echo json_encode(['orderId' => null]);
}
?>