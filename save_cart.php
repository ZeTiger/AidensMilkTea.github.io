<?php
session_start();

$json = file_get_contents('php://input');

$data = json_decode($json, true);


$_SESSION['cart'] = $data['cart'];
echo json_encode(['success' => true]);
?>

<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>
    <script>
        window.location.href = 'checkout.php';
    </script>
</body>
</html>
