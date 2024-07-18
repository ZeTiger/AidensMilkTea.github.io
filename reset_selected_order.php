<?php
session_start();
header('Content-Type: application/json');

unset($_SESSION['selected_order_id']);
echo json_encode(['success' => true]);
?>