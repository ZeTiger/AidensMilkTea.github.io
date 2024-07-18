<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function findItemDetails($conn, $name) {
    $tables = ['milktea', 'fruit_tea', 'inf_fruit_tea', 'snacks'];
    foreach ($tables as $table) {
        if ($table === 'snacks') {
            $stmt = $conn->prepare("SELECT code, price FROM $table WHERE name = ?");
        } else {
            $stmt = $conn->prepare("SELECT code, small, medium, large FROM $table WHERE name = ?");
        }
        $stmt->bind_param("s", $name);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $row['table'] = $table; // Add the table name to the returned data
            return $row;
        }
        $stmt->close();
    }
    return null;
}

if (isset($_POST['cart']) && isset($_SESSION['username'])) {
    $cart = $_POST['cart'];
    $username = $_SESSION['username'];
    
    // Fetch user details
    $stmt = $conn->prepare("SELECT email, phone, address1, address2 FROM clients WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $user_result = $stmt->get_result();
    $user = $user_result->fetch_assoc();
    $stmt->close();

    $order_string = "";
    $total = 0;

    foreach ($cart as $item) {
        $details = findItemDetails($conn, $item['name']);
        if ($details) {
            $code = $details['code'];
            if ($details['table'] === 'snacks') {
                $price = $details['price'];
                $size = 'X'; // Use 'X' for snacks
            } else {
                $price = $details[$item['size'] == 'S' ? 'small' : ($item['size'] == 'M' ? 'medium' : 'large')];
                $size = $item['size'];
            }
            $quantity = $item['quantity'];
            $item_total = $price * $quantity;
            $total += $item_total;

            $order_string .= $code . "_" . $size . "_" . $quantity . "&";
        }
    }

    $order_string = rtrim($order_string, "&");
    $status = 'pending';

    $stmt = $conn->prepare("INSERT INTO orderdetails (username, email, phone, address1, address2, `order`, total, date, status) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
    $stmt->bind_param("ssssssds", $username, $user['email'], $user['phone'], $user['address1'], $user['address2'], $order_string, $total, $status);
    $stmt->execute();
    $stmt->close();

    $_SESSION['cart'] = array();

} else {

}

$conn->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmation</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        #loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
        #loading-container {
            width: 300px;
            height: 300px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        #loading-container img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 400px;
            text-align: center;
            border-radius: 5px;
        }
        .modal h2 {
            color: #4CAF50;
            margin-bottom: 10px;
        }
        .modal p {
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div id="loading-screen">
        <div id="loading-container">
            <img src="asset/loading-milktea.gif" alt="Loading...">
        </div>
    </div>

    <div id="orderSentModal" class="modal">
        <div class="modal-content">
            <h2>Order Sent!</h2>
            <p>Please wait for an email confirmation that your order has been received.</p>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const startTime = new Date().getTime();
        const minLoadTime = 2000; // Minimum loading time in milliseconds

        window.addEventListener('load', function() {
            const loadTime = new Date().getTime() - startTime;
            const remainingTime = Math.max(0, minLoadTime - loadTime);

            setTimeout(function() {
                const loadingScreen = document.getElementById('loading-screen');
                loadingScreen.style.display = 'none';

                var modal = document.getElementById("orderSentModal");
                modal.style.display = "block";
                
                setTimeout(function() {
                    modal.style.display = "none";
                    window.location.href = 'menu.php';
                }, 3000); // Display modal for 3 seconds before redirecting
            }, remainingTime);
        });
    });
    </script>
</body>
</html>