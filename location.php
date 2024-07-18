<?php
    session_start();
    $_SESSION['lastpage'] = "Location: location.php";

$hostname = "localhost";  
$username = "root";      
$password = "";          
$dbname = "client_registration";  

$con = new mysqli($hostname, $username, $password, $dbname);

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT first_name FROM clients WHERE username = '$username'";
    $result = $con->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customername = $row['first_name'];
    }
}

$con->close();
?>

<html>
<head>

    <title>Our Location</title> 
    <link rel="stylesheet" href="index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900&display=swap" rel="stylesheet">  
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <style>

        body {
            font-family: Arial, sans-serif;
            background-image: url('img/log in bg.png');
            margin: 0;
            padding: 0;
        }
        .container-custom {
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }
        .map-container {
            height: 500px;
            border-radius: 10px;
            overflow: hidden;
        }
        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 10px;
            font-weight: 500;
        }
        .info-container p {
            margin: 10px 0;
            font-size: 17px;
        }
        .info-container p i {
            margin-right: 10px;
            color: #8009c5e5;
        }
        .info-container img {
            width: 100%;
            height: auto;
            border-radius: 10px;
            margin-top: 20px;
        }
        .logo {
            display: block;
            margin: 20px auto;
            max-width: 100%;
            height: auto;
        }
        .footer {
            background-color: #333;
            color: #fff;
            padding: 20px 0;
        }
        .footer a {
            color: #fff;
        }
        .box {
            background: url('img/log in bg.png');
            border: 1px solid #e0c1f3;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            color: white;
        }
        .team-box {
            background: url('img/log in bg.png');
            border: 1px solid #e0c1f3;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            color: white;
        }
        .footer {
            background-color: #8009c5e5;
            box-shadow: 0px 6px 8px 0px rgba(0, 0, 0, 0.3);
            color: white;
            padding: 20px 0;
            text-align: center;
            width: 100%;
            position: relative;
            bottom: 0;
        }

        .footer p {
            margin: 0;
            font-size: 14px;
        }

        .footer a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }

        .footer a:hover {
            color: #ddd;
        }

        @media (max-width: 768px) {
            .footer {
                padding: 15px 0;
            }

            .footer p {
                font-size: 12px;
            }

            .footer a {
                margin: 0 5px;
            }
        }

        @media (max-width: 480px) {
            .footer {
                padding: 10px 0;
            }

            .footer p {
                font-size: 10px;
            }

            .footer a {
                margin: 0 2px;
            }
        }


        .user-logo {
            width: 40px;
            height: 40px;
            background-color: black; 
            color: white; 
            border-radius: 50%; 
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-family: 'Poppins', sans-serif;
            font-weight: bold;
            border: 3px solid white;
        }

    </style>
</head>
<body>
    <div class="navbarr" style="background-color:white; position: relative;">
            <ul>
            <?php
                if (isset($_SESSION['username'])) {
                    $firstLetter = strtoupper(substr($customername, 0, 1)); 
                    echo "<a href='user-setting.php'><li style='position:absolute; top: 10px; left: 20px; align-items: center;'><div class='user-logo'>$firstLetter</div></li></a>";
                    echo "<span style='animation: none; position:absolute; top: 18px; left: 50px; margin-left:30px; font-size:18px; font-family:'Poppins';'>Welcome, <b>" . $customername . "!</b></span>";  
                }
                ?>
                
                <li><a href="index.php" style="margin-left:20px; text-decoration:none;"><span>Home</span></a></li>
                <li><a href="about_us.php" style="margin-left:20px; text-decoration:none;"><span>About Us</span></a></li>
                <li><a href="menu.php" style="margin-left:20px;text-decoration:none;"><span>Menu</span></a></li>
                <li><a href="location.php" style="margin-left:20px;text-decoration:none;"><span class="selected-page">Location</span></a></li>
                <li><a href="contact_us.php" style="margin-left:20px;text-decoration:none;"><span>Contact Us</span></a></li>
                <?php
                if (isset($_SESSION['username'])) {
                    echo "<li><a href='logout.php' class='secondary-btn' style='margin-left:20px;margin-right:20px;'>Log Out</a></li>";
                  }
                else{
                    echo "<li><a href='sign_up.php' class='primary-btn' style='margin-left:20px;'>Sign Up</a></li>";
                    echo "<li><a href='login.php' class='secondary-btn' style='margin-left:10px;margin-right:20px;'>Log In</a></li>";
                }
            ?>
        </ul>
    </div>
    <div class="container container-custom" style="margin-top:30px;">
        <div class="row">
            <div class="col-md-8 col-sm-12 mb-4">
                <div class="map-container">
                    <iframe
                        width="100%"
                        height="100%"
                        frameborder="0" style="border:0"
                        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3859.9076295601108!2d120.9475297105791!3d14.661183185773298!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3397b453cd0bb305%3A0xb51b1933419f9c0e!2s37%20C.%20Arellano%20St%2C%20Malabon%2C%201470%20Metro%20Manila!5e0!3m2!1sen!2sph!4v1720099545833!5m2!1sen!2sph"
                        allowfullscreen>
                    </iframe>
                </div>
            </div>
            <div class="col-md-4 col-sm-12">
                <div class="info-container">
                <img src="img/calling.png" alt="Business Image">
                    <p><i class="fas fa-store"></i><strong>Business Name:</strong> Aiden's Milk Tea House</p>
                    <p><i class="fas fa-map-marker-alt"></i><strong>Address:</strong> 37 C. Arellano St. San Agustin Malabon City</p>
                    <p><i class="fas fa-phone"></i><strong>Phone:</strong> 09123456789</p>
                    <p><i class="fas fa-envelope"></i><strong>Email:</strong> AidenRichards.com</p>
                    <p><i class="fas fa-clock"></i><strong>Working Hours:</strong> Monday - Friday, 9:00 AM - 5:00 PM</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer">
        <p>&copy; 2024 Aiden's Milk Tea House. All rights reserved.</p>
        <p>
            <a href="#">About</a> |
            <a href="#">Menu</a> |
            <a href="#">Contact Us</a>
        </p>
    </div>
</body>
</html>