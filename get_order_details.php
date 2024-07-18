<?php
session_start();
$conn = mysqli_connect('localhost', 'root', '', 'client_registration');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

function getItemDetails($conn, $code) {
    $tables = ['milktea', 'fruit_tea', 'inf_fruit_tea', 'snacks'];
    foreach ($tables as $table) {
        if ($table == 'snacks') {
            $stmt = $conn->prepare("SELECT name, price FROM $table WHERE code = ?");
        } else {
            $stmt = $conn->prepare("SELECT name, small, medium, large FROM $table WHERE code = ?");
        }
        $stmt->bind_param("s", $code);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $data = $result->fetch_assoc();
            $data['table'] = $table;
            return $data;
        }
        $stmt->close();
    }
    return null;
}

function parseOrderString($orderString) {
    $items = explode("&", $orderString);
    $parsedItems = [];
    foreach ($items as $item) {
        list($code, $size, $quantity) = explode("_", $item);
        $parsedItems[] = [
            'code' => $code,
            'size' => $size,
            'quantity' => $quantity
        ];
    }
    return $parsedItems;
}

if (isset($_GET['id'])) {
    $orderId = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT * FROM orderdetails WHERE id = ?");
    $stmt->bind_param("i", $orderId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $order = $result->fetch_assoc();
        $parsedItems = parseOrderString($order['order']);
        
        $itemDetails = [];
        foreach ($parsedItems as $item) {
            $details = getItemDetails($conn, $item['code']);
            if ($details) {
                if ($details['table'] == 'snacks' || $item['size'] == 'X') {
                    $price = $details['price'];
                    $size = 'Regular';
                } else {
                    $price = $details[$item['size'] == 'S' ? 'small' : ($item['size'] == 'M' ? 'medium' : 'large')];
                    $size = $item['size'];
                }
                $itemDetails[] = [
                    'name' => $details['name'],
                    'size' => $size,
                    'quantity' => $item['quantity'],
                    'price' => $price * $item['quantity']
                ];
            }
        }
        
        $responseData = [
            'email' => $order['email'],
            'address1' => $order['address1'],
            'phone' => $order['phone'],
            'items' => $itemDetails,
            'total' => floatval($order['total']) 
        ];
        
        echo json_encode($responseData);
    } else {
        echo json_encode(['error' => 'Order not found']);
    }
    
    $stmt->close();
} else {
    echo json_encode(['error' => 'No order ID provided']);
}

$conn->close();
?>