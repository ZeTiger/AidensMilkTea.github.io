<?php
session_start();

$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
?>
<!DOCTYPE html>
<head>

    <title>Checkout</title>
    <style>
        body {
            background-image: url('img/log in bg.png');
            font-family: "Poppins", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
            color: #333;
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
            transition: opacity 0.5s ease-out;
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
        #content {
            display: none;
            padding: 20px;
        }
        .checkout-container {
            max-width: 1000px;
            margin: 50px auto;
            background-color: #fff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th, table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        table th {
            background-color: #f7f7f7;
            font-weight: bold;
        }
        .item-image {
            width: 90px;
            height: 110px;
            object-fit: cover;
            border-radius: 4px;
        }
        .total {
            text-align: right;
            font-size: 1.5em;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .button-container {
            display: flex;
            justify-content: space-between;
        }
        .button-container button {
            font-size: 16px;
            padding: 15px 30px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            transition: background-color 0.3s;
        }
        .button-container button:hover {
            background-color: #ddd;
        }
        .back-button {
            background-color: #f2f2f2;
        }
        .proceed-button {
            background-color: #007BFF;
            color: white;
        }
        .proceed-button:hover {
            background-color: #0056b3;
        }
        @media (max-width: 600px) {
            .item-image {
                width: 50px;
                height: 50px;
            }
        }
        .title {
            text-align: center;
            margin: 0 auto 80px;
            position: relative;
            line-height: 60px;
            color: whitesmoke;
            font-size: 30px;
            margin-bottom: -20px;
        }
        .title::after {
            content: '';
            background: #ff523b;
            width: 80px;
            height: 5px;
            border-radius: 5px;
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
        }
    </style>
</head>
<body>
<div id="loading-screen">
        <div id="loading-container">
            <img src="asset/loading-milktea.gif" alt="Loading...">
        </div>
    </div>
    <div id="content">
        <h2 class="title">Checkout Page</h2>
        <div class="checkout-container">
            <table>
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Item</th>
                        <th>Size</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($cart)): ?>
                        <?php foreach ($cart as $item): ?>
                            <tr>
                                <td>
                                    <img src="img/<?php echo strtolower($item['name']); ?>.png" alt="<?php echo htmlspecialchars($item['name']); ?>" class="item-image">
                                </td>
                                <td><?php echo htmlspecialchars($item['name']); ?></td>
                                <td><?php echo htmlspecialchars($item['size']); ?></td>
                                <td><?php echo htmlspecialchars($item['quantity']); ?></td>
                                <td>Php <?php echo number_format($item['quantity'] * $item['price'], 2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5">Your cart is empty</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
            <div class="total">
                Total Amount: Php <?php echo calculateTotal($cart); ?>
            </div>
            <div class="button-container">
                <button class="back-button" onclick="window.location.href='menu.php'">Back</button>
                <form action="order_confirm.php" method="POST" style="display: inline;">
                    <?php foreach ($cart as $index => $item): ?>
                        <input type="hidden" name="cart[<?php echo $index; ?>][name]" value="<?php echo htmlspecialchars($item['name']); ?>">
                        <input type="hidden" name="cart[<?php echo $index; ?>][size]" value="<?php echo htmlspecialchars($item['size']); ?>">
                        <input type="hidden" name="cart[<?php echo $index; ?>][quantity]" value="<?php echo htmlspecialchars($item['quantity']); ?>">
                        <input type="hidden" name="cart[<?php echo $index; ?>][price]" value="<?php echo htmlspecialchars($item['price']); ?>">
                    <?php endforeach; ?>
                    <button type="submit" class="proceed-button">Confirm Order</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const startTime = new Date().getTime();
            const minLoadTime = 2000;

            window.addEventListener('load', function() {
                const loadTime = new Date().getTime() - startTime;
                const remainingTime = Math.max(0, minLoadTime - loadTime);

                setTimeout(function() {
                    const loadingScreen = document.getElementById('loading-screen');
                    loadingScreen.style.opacity = '0';

                    setTimeout(function() {
                        loadingScreen.style.display = 'none';
                        document.getElementById('content').style.display = 'block';
                    }, 500);
                }, remainingTime);
            });
        });
    </script>
</body>
</html>

<?php
function calculateTotal($cart) {
    $total = 0;
    foreach ($cart as $item) {
        $total += $item['quantity'] * $item['price'];
    }
    return number_format($total, 2);
}
?>