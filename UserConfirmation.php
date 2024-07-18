<?php
session_start();

if (!isset($_SESSION['user_data'])) {
    header('Location: register.php');
    exit();
}

// Function to calculate age from birthdate
function calculate_age($birthdate) {
    $birthDate = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}

$user_data = $_SESSION['user_data'];
// Calculate age based on birthdate
$age = calculate_age($user_data['birthdate']);
$_SESSION['user_data']['age'] = $age; // Store age in session data

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $g1servername = "localhost";
    $g1username = "root";
    $g1password = "";
    $g1dbname = "client_registration";

    $conn = new mysqli($g1servername, $g1username, $g1password, $g1dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $user_data = $_SESSION['user_data'];

    $first_name = $user_data['first_name'];
    $last_name = $user_data['last_name'];
    $email = $user_data['email'];
    $phone = $user_data['phone'];
    $username = $user_data['username'];
    $password = password_hash($user_data['confirm_password'], PASSWORD_DEFAULT);
    $address1 = $user_data['address1'] . ', ' . $user_data['city'] . ', ' . $user_data['postal_code'] ;
    $address2 = $user_data['address2'];
    $gender = $user_data['gender'];
    $birthdate = $user_data['birthdate']; 
    $city = $user_data['city'];
    $postal_code = $user_data['postal_code'];

    $sql = "INSERT INTO clients (first_name, last_name, email, phone, username, password, address1,address2, gender, birthdate,city,postal_code)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?,?,?,?)";

    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param('ssssssssssss', $first_name, $last_name, $email, $phone, $username, $password, $address1, $address2, $gender, $birthdate, $city, $postal_code);

    if ($stmt->execute()) {
        unset($_SESSION['user_data']);
        header('Location: success.php'); 
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<html>
<head>
    <title>Confirm Registration</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/log in bg.png');
            margin: 0;
            padding: 0;
            position: relative;
            min-height: 100vh;
            overflow-y: auto;
            background-size: auto;
            background-position: center;
            background-repeat: no-repeat;
            color: white;
        }

        .M6Container {
            width: 50%;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.112);
            border-radius: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            position: relative;
            z-index: 10;
            box-sizing: border-box;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            width: 150px;
            height: auto;
        }

        h2 {
            text-align: center;
        }

        form {
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            z-index: 2;
        }

        .container {
            padding: 20px;
            background-color: rgba(255, 255, 255, 0.112);
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
        }

        label {
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="tel"],
        input[type="password"],
        input[type="date"],
        input[type="postal_code"],
        textarea {
            width: 100%;
            padding: 10px;
            margin: 5px 0 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .flex-container {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
        }

        .flex-container div {
            flex: 1;
            box-sizing: border-box;
        }

        .flex-container .postal_code-container {
            flex: 1;
        }

        .flex-container  {
            flex: 3;
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: rgba(62, 5, 62, 0.868);
            font-size: 15px;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: rgba(96, 7, 96, 0.868);
        }

        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 20px;
            border-radius: 10px;
            z-index: 1000;
            text-align: center;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
        }

        .popup p {
            margin-bottom: 10px;
        }

        .popup button {
            padding: 10px 20px;
            background-color: rgba(62, 5, 62, 0.868);
            font-size: 15px;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup button:hover {
            background-color: rgba(96, 7, 96, 0.868);
        }

        .button-container {
            display: flex;
            justify-content: space-between;
            align-items: center; 
            margin-top: 50px; 
        }

        input[type="submit"],
        .cancel-button {
            flex: 1; 
            padding: 10px 20px; 
            background-color: rgba(62, 5, 62, 0.868);
            font-size: 18px; 
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none; 
            transition: background-color 0.3s ease; 
            text-align: center;
            margin-right: 15px;
        }

        input[type="submit"]:hover,
        .cancel-button:hover {
            background-color: rgba(96, 7, 96, 0.868);
        }
    </style>
</head>
<body>

<div class="M6Container">
    <div class="logo">
        <img src="img/logo_2-removebg-preview (1).png">
    </div>
    <h2>Confirm Your Information</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <p>First Name: <?php echo $_SESSION['user_data']['first_name']; ?></p>
        <p>Last Name: <?php echo $_SESSION['user_data']['last_name']; ?></p>
        <p>Email: <?php echo $_SESSION['user_data']['email']; ?></p>
        <p>Phone: <?php echo $_SESSION['user_data']['phone']; ?></p>
        <p>Gender: <?php echo $_SESSION['user_data']['gender']; ?></p>
        <p>Birthdate: <?php echo $_SESSION['user_data']['birthdate']; ?></p>
        <p>Age: <?php echo $_SESSION['user_data']['age']; ?></p> 
        <p>Address 1: <?php echo $_SESSION['user_data']['address1']; ?>, <?php echo $_SESSION['user_data']['city']; ?>, <?php echo $_SESSION['user_data']['postal_code']; ?></p>
        <p>Address 2: <?php echo $_SESSION['user_data']['address2']; ?></p>
        <p>Username: <?php echo $_SESSION['user_data']['username']; ?></p>
        
        <div class="button-container">
            <input type="submit" name="confirm" value="Confirm">
            <a href="sign_up.php" class="cancel-button">Cancel</a>
        </div>
    </form>
</div>

</body>
</html>