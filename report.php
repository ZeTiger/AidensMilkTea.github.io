<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_registration"; // Replace with your actual database name

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Example Query 1: Count of fruit tea orders
    $sql_fruit_tea = "SELECT COUNT(*) as count FROM fruit_tea";
    $stmt_fruit_tea = $conn->prepare($sql_fruit_tea);
    $stmt_fruit_tea->execute();
    $data_fruit_tea = $stmt_fruit_tea->fetch(PDO::FETCH_ASSOC);
    $fruit_tea_count = $data_fruit_tea['count'];

    // Example Query 2: Count of infused fruit tea orders
    $sql_inf_fruit_tea = "SELECT COUNT(*) as count FROM inf_fruit_tea";
    $stmt_inf_fruit_tea = $conn->prepare($sql_inf_fruit_tea);
    $stmt_inf_fruit_tea->execute();
    $data_inf_fruit_tea = $stmt_inf_fruit_tea->fetch(PDO::FETCH_ASSOC);
    $inf_fruit_tea_count = $data_inf_fruit_tea['count'];

    // Example Query 3: Count of milk tea orders
    $sql_milktea = "SELECT COUNT(*) as count FROM milktea";
    $stmt_milktea = $conn->prepare($sql_milktea);
    $stmt_milktea->execute();
    $data_milktea = $stmt_milktea->fetch(PDO::FETCH_ASSOC);
    $milktea_count = $data_milktea['count'];

} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Tea Orders Report</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .charts-wrapper {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
        }
        .chart-container {
            width: 30%;
            padding: 20px;
            box-sizing: border-box;
            border-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .btn-default {
            border-color: rgba(24, 28, 33, 0.1);
            background  : rgba(0, 0, 0, 0);
            color       : #4E5155;
        }
    </style>
</head>
<body>
    <div class="charts-wrapper">
        <div class="chart-container">
            <canvas id="fruitTeaChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="infFruitTeaChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="milkTeaChart"></canvas>
        </div>
    </div>

    <div id="password_message" style="color: red;"></div>
<button type="submit" class="btn btn-primary" style="background-color: #007bff; color: white; border: none; padding: 10px 15px; border-radius: 5px;">
    <a href="product_admin.php" style="color: white; text-decoration: none;">Return</a>
</button>

    <script>
        // Data fetched from PHP
        var fruitTeaCount = <?php echo $fruit_tea_count; ?>;
        var infFruitTeaCount = <?php echo $inf_fruit_tea_count; ?>;
        var milkTeaCount = <?php echo $milktea_count; ?>;

        // Doughnut Charts
        var ctx1 = document.getElementById('fruitTeaChart').getContext('2d');
        var fruitTeaChart = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Fruit Tea', 'Infused Fruit Tea', 'Milk Tea'],
                datasets: [{
                    label: 'Tea Orders',
                    data: [fruitTeaCount, infFruitTeaCount, milkTeaCount],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)', // Red
                        'rgba(54, 162, 235, 0.8)', // Blue
                        'rgba(255, 206, 86, 0.8)', // Yellow
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Tea Orders Breakdown'
                    }
                }
            }
        });

        var ctx2 = document.getElementById('infFruitTeaChart').getContext('2d');
        var infFruitTeaChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Fruit Tea', 'Infused Fruit Tea', 'Milk Tea'],
                datasets: [{
                    label: 'Tea Orders',
                    data: [fruitTeaCount, infFruitTeaCount, milkTeaCount],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8', // Red
                        'rgba(54, 162, 235, 0.8', // Blue
                        'rgba(255, 206, 86, 0.8', // Yellow
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Infused Fruit Tea Orders Breakdown'
                    }
                }
            }
        });

        var ctx3 = document.getElementById('milkTeaChart').getContext('2d');
        var milkTeaChart = new Chart(ctx3, {
            type: 'doughnut',
            data: {
                labels: ['Fruit Tea', 'Infused Fruit Tea', 'Milk Tea'],
                datasets: [{
                    label: 'Tea Orders',
                    data: [fruitTeaCount, infFruitTeaCount, milkTeaCount],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.8)', // Red
                        'rgba(54, 162, 235, 0.8)', // Blue
                        'rgba(255, 206, 86, 0.8)', // Yellow
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Milk Tea Orders Breakdown'
                    }
                }
            }
        });
    </script>
</body>
</html>