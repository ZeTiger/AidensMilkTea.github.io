<?php
session_start();
$_SESSION['lastpage'] = "Location: login.php";

$conn = mysqli_connect('localhost', 'root', '', 'client_registration');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if user is logged in and is admin
if (isset($_SESSION['username'])) {
    $loggedInUsername = $_SESSION['username'];
    
    $stmt = $conn->prepare("SELECT type FROM clients WHERE username = ?");
    $stmt->bind_param("s", $loggedInUsername);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $userType = $row['type'];
    
        if ($userType == 0) {
            header("Location: index.php");
            exit();
        }
    } else {
        session_destroy();
        header("Location: login.php");
        exit();
    }
    
    $stmt->close();
} else {
    header("Location: login.php");
    exit();
}

// Fetch orders from orderdetails table
$stmt = $conn->prepare("SELECT * FROM orderdetails WHERE status = 'pending' ORDER BY date DESC");
$stmt->execute();
$pendingOrders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

$stmt = $conn->prepare("SELECT * FROM orderdetails WHERE status = 'preparing' ORDER BY date DESC");
$stmt->execute();
$preparingOrders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    <title>Order Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #2c1c5d;
            color: #fff;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
        }
        .sidebar {
            background-color: #65008f;
            width: 15%;
            padding: 20px;
            height: 100vh;
        }
        .sidebar .profile {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }
        .sidebar .profile img {
            border-radius: 50%;
            margin-right: 10px;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            margin: 25px 0;
        }
        .sidebar ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 18px;
        }
        .main-content {
            width: 80%;
            padding: 20px;
        }
        .orders {
            display: flex;
            justify-content: space-between;
        }
        .orders-list {
            width: 30%;
            height: 800px;
            background-color: #fff;
            color: #000;
            margin:10px;
            border-radius: 10px;
            padding: 10px;
        }
        .order-item {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            display: flex;
            justify-content: space-between;
            cursor: pointer;
        }
        .order-item:hover {
            background-color: #f0f0f0;
        }
        .order-details {
            width: 65%;
            background-color: #fff;
            color: #000;
            border-radius: 10px;
            padding: 20px;
            margin: 10px;
            display: flex;
            flex-direction: column;
            height: 800px; /* Match the height of orders-list */
        }

        .order-content {
            flex-grow: 1;
            overflow-y: auto;
        }

        .order-footer {
            margin-top: auto;
        }

        #order-total {
            text-align: right;
            margin-bottom: 10px;
        }

        #order-actions {
            display: flex;
            justify-content: space-around;
        }
        .order-details img {
            width: 50px;
            height: 50px;
        }
        .order-details .item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }
        .order-details h2 {
            font-size: 30px;
            font-weight: bold;
            text-align: center;
        }
        .order-details .item span {
            flex: 1;
            text-align: center;
        }
        .order-details .item img {
            margin-right: 10px;
        }
        .prmry-button {
            display:flex;
            background-color: #65008f;
            color: #fff;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            align-items:center;
            justify-content:center;
        }

        .prmry-button1 {
            display:flex;
            background-color: #65008f;
            color: #fff;
            padding: 14px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            align-items:center;
            justify-content:center;
        }

        .prmry-button2 {
            display:flex;
            background-color: #65008f;
            color: #fff;
            padding: 14px;
            text-align: center;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            align-items:center;
            justify-content:center;
        }

        .scndry-button {
            display: flex;
            background-color: white;
            color: #65008f;
            padding: 10px;
            align-items:center;
            justify-content:center;
            border-radius: 5px;
            border: 4px solid #65008f;
            text-decoration: none;
            margin-top: 20px;
        }

        .info-bar {
            display: flex;
            justify-content: space-around;
            background-color: #f0f0f0;
            color: #000;
            border-radius: 10px;
            margin-bottom: 20px;
        }
        .info-bar div {
            text-align: center;
            padding: 10px 20px;
            border-right: 1px solid #ddd;
        }
        .info-bar div:last-child {
            border-right: none;
        }
        .tabs {
            display: flex;
            justify-content: space-around;
            background-color: #f0f0f0;
            color: #000;
            border-radius: 10px;
            margin-bottom: 20px;
            cursor: pointer;
        }
        .tab {
            text-align: center;
            padding: 10px 20px;
            flex: 1;
        }
        .tab.active {
            background-color: #ddd;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
        }
        .order-item.selected {
            background-color: #e0e0e0;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="sidebar">
            <div class="profile">
                <img src="img/logo_2-removebg-preview (1).png" alt="Profile Image" width="100%">
            </div>
            <ul>
                <li><a href="ordersummary.php"><i class='bx bx-cart'></i> Orders</a></li>
                <li><a href="product_admin.php"><i class='bx bx-plus'></i>Product</a></li>
                <li><a href="report.php"><i class='bx bx-chart'></i> Dashboard</a></li>
                <li><a href="adminmodule.php"><i class='bx bx-cog'></i> Users</a></li>
                <li><a href="logout.php"><i class='bx bx-exit'></i> Logout</a></li>
</ul>
        </div>
        <div class="main-content">
            <div class="orders">
            <div class="orders-list">
                <h2 style="text-align: center;">Orders</h2>
                <div class="tabs">
                    <div class="tab active" data-tab="pending">Pending</div>
                    <div class="tab" data-tab="preparing">Preparing</div>
                </div>
                <div class="tab-content active" id="pending">
                    <?php foreach ($pendingOrders as $order): ?>
                        <div class="order-item" data-order-id="<?php echo $order['id']; ?>">
                            <span>Order <?php echo $order['id']; ?></span>
                            <span>Php <?php echo number_format($order['total'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="tab-content" id="preparing">
                    <?php foreach ($preparingOrders as $order): ?>
                        <div class="order-item" data-order-id="<?php echo $order['id']; ?>">
                            <span>Order <?php echo $order['id']; ?></span>
                            <span>Php <?php echo number_format($order['total'], 2); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="order-details" id="orderDetails" data-current-order-id="">
                <div class="order-content">
                    <h2>Order Details</h2>
                    <div class="info-bar">
                        <!-- JS -->
                    </div>
                    <div id="order-items">
                        <!-- JS -->
                    </div>
                </div>
                <div class="order-footer">
                    <div style="display:flex; justify-content: space-around;"><h3 id="order-total"></h3></div>
                    <div id="order-actions" style="display:flex; justify-content: space-around;">
                        <a href="#" class="scndry-button pending-action" style="width:40%; display: none;">Reject Order</a>
                        <a href="#" class="prmry-button pending-action" style="width:40%; display: none;">Confirm Order</a>
                        <a href="#" class="prmry-button1 preparing-action" style="width:40%; display: none;">Notify Customer</a>
                        <a href="#" class="prmry-button2 preparing-action" style="width:40%; display: none;">Complete Order</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab');
    const pendingActions = document.querySelectorAll('.pending-action');
    const preparingActions = document.querySelectorAll('.preparing-action');
    
    function showActions(type) {
        if (type === 'pending') {
            pendingActions.forEach(action => action.style.display = 'flex');
            preparingActions.forEach(action => action.style.display = 'none');
        } else if (type === 'preparing') {
            pendingActions.forEach(action => action.style.display = 'none');
            preparingActions.forEach(action => action.style.display = 'flex');
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            tabs.forEach(t => t.classList.remove('active'));
            this.classList.add('active');
            
            const tabType = this.getAttribute('data-tab');
            showActions(tabType);

            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.remove('active');
            });
            document.getElementById(tabType).classList.add('active');
        });
    });

    showActions('pending');
    
    document.querySelectorAll('.order-item').forEach(item => {
        item.addEventListener('click', function() {
            const orderId = this.getAttribute('data-order-id');
            selectOrder(orderId);
        });
    });

    document.querySelectorAll('.tab').forEach(tab => {
        tab.addEventListener('click', function() {
            switchTab(this.getAttribute('data-tab'));
        });
    });


    document.querySelector('.scndry-button').addEventListener('click', rejectOrder);
    document.querySelector('.prmry-button').addEventListener('click', confirmOrder);
    document.querySelector('.prmry-button1').addEventListener('click', notifyUser);
    document.querySelector('.prmry-button2').addEventListener('click', completeOrder);
    });


    function switchTab(tabName) {
        document.querySelectorAll('.tab, .tab-content').forEach(el => el.classList.remove('active'));
        
        document.querySelector(`.tab[data-tab="${tabName}"]`).classList.add('active');
        document.getElementById(tabName).classList.add('active');
        resetSelectedOrderId();
        clearOrderDetails();
    }

    function selectOrder(orderId) {
        console.log("Selecting Order ID:", orderId);
        
        fetch('set_selected_order.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'orderId=' + orderId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Order ID stored in session');
            } else {
                console.error('Failed to store Order ID in session');
            }
        })
        .catch(error => console.error('Error:', error));

        document.querySelectorAll('.order-item').forEach(item => {
            item.classList.remove('selected');
        });

        const selectedItem = document.querySelector(`.order-item[data-order-id="${orderId}"]`);
        if (selectedItem) {
            selectedItem.classList.add('selected');
        }

        fetchOrderDetails(orderId);
    }

    function fetchOrderDetails(orderId) {
        fetch(`get_order_details.php?id=${orderId}`)
            .then(response => response.json())
            .then(data => {
                updateOrderDetails(data);
            })
            .catch(error => console.error('Error:', error));
    }

    function updateOrderDetails(orderData) {
        console.log("Updating Order Details for ID:", orderData.id);

        const infoBar = document.querySelector('.info-bar');
        infoBar.innerHTML = `
            <div>Email:<br>${orderData.email}</div>
            <div>Address:<br>${orderData.address1}</div>
            <div>Contact No.:\n${orderData.phone}</div>
        `;

        const orderItems = document.getElementById('order-items');
        orderItems.innerHTML = '';
        orderData.items.forEach(item => {
            orderItems.innerHTML += `
                <div class="item">
                    <img src="img/${item.name.toLowerCase()}.png" alt="${item.name}">
                    <span>${item.name}</span>
                    <span>x${item.quantity}</span>
                    <span>Php ${parseFloat(item.price).toFixed(2)}</span>
                </div>
            `;
        });

        const total = parseFloat(orderData.total);
        document.getElementById('order-total').textContent = `Total: Php ${total.toFixed(2)}`;
    }

    function rejectOrder() {
        fetch('get_selected_order.php')
        .then(response => response.json())
        .then(data => {
            if (data.orderId) {
                const currentOrderId = data.orderId;
                console.log("Reject button clicked. Current Order ID:", currentOrderId);
                
                if (confirm("Are you sure you want to reject this order?\n\nThe customer will be notified of this action.")) {
                    console.log(`Rejecting order with ID: ${currentOrderId}`);
                    fetch(`reject_order.php?id=${currentOrderId}`, { method: 'GET' })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                document.querySelectorAll(`.order-item[data-order-id="${currentOrderId}"]`).forEach(el => el.remove());
                                clearOrderDetails();
                                alert("Order rejected successfully.");

                                resetSelectedOrderId();
                            } else {
                                alert("Failed to reject order. Please try again.");
                            }
                        })
                        .catch(error => console.error('Error:', error));
                }
            } else {
                alert("Please select an order first.");
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function confirmOrder() {
        fetch('get_selected_order.php')
        .then(response => response.json())
        .then(data => {
            if (data.orderId) {
                const currentOrderId = data.orderId;
                console.log("Confirm button clicked. Current Order ID:", currentOrderId);
                
                fetch(`confirm_order.php?id=${currentOrderId}`, { method: 'POST' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const orderItem = document.querySelector(`#pending .order-item[data-order-id="${currentOrderId}"]`);
                            if (orderItem) {
                                document.getElementById('preparing').appendChild(orderItem);
                                clearOrderDetails();
                                alert("Order confirmed and moved to preparing.");

                                resetSelectedOrderId();
                            } else {
                                alert("Order already in preparing state.");
                            }
                        } else {
                            alert("Failed to confirm order. Please try again.");
                        }
                    })
                    .catch(error => console.error('Error:', error));
            } else {
                alert("Please select an order first.");
            }
        })
        .catch(error => console.error('Error:', error));
    }

    function notifyUser() {
        fetch('get_selected_order.php')
            .then(response => response.json())
            .then(data => {
                if (data.orderId) {
                    const currentOrderId = data.orderId;

                    fetch(`notify_user.php?id=${currentOrderId}`, { method: 'POST' })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const orderItem = document.querySelector(`#preparing .order-item[data-order-id="${currentOrderId}"]`);
                                if (orderItem) {
                                    alert("Customer succesfully notified.");
                                    resetSelectedOrderId();
                                } else {
                                    resetSelectedOrderId();
                                    alert("2");
                                }
                            } else {
                                alert("Failed to notify user. Please try again.");
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    alert("Please select an order first.");
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function completeOrder() {
        fetch('get_selected_order.php')
            .then(response => response.json())
            .then(data => {
                if (data.orderId) {
                    const currentOrderId = data.orderId;

                    fetch(`complete_order.php?id=${currentOrderId}`, { method: 'POST' })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const orderItem = document.querySelector(`#preparing .order-item[data-order-id="${currentOrderId}"]`);
                                if (orderItem) {
                                    orderItem.remove(); // Remove the order item from the DOM
                                    clearOrderDetails();
                                    alert("Order completed.");
                                    resetSelectedOrderId();
                                } else {
                                    clearOrderDetails();
                                    alert("Order completed, but the item was not found in the preparing list.");
                                }
                            } else {
                                alert("Failed to complete order. Please try again.");
                            }
                        })
                        .catch(error => console.error('Error:', error));
                } else {
                    alert("Please select an order first.");
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function resetSelectedOrderId() {
        fetch('reset_selected_order.php', { method: 'POST' })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Selected order ID reset successfully');
                } else {
                    console.error('Failed to reset selected order ID');
                }
            })
            .catch(error => console.error('Error:', error));
    }

    function clearOrderDetails() {
        document.querySelector('.info-bar').innerHTML = '';
        document.getElementById('order-items').innerHTML = '';
        document.getElementById('order-total').textContent = '';
        document.querySelectorAll('.order-item').forEach(item => {
            item.classList.remove('selected');
        });

        resetSelectedOrderId();
    }
    </script>
</body>
</html>
</body>
</html>
