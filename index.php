<?php
session_start();
$_SESSION['lastpage'] = "Location: index.php";

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


<!DOCTYPE html>
<html lang="en">
<head>
    <script>
        function TermsOfService() {
        <?php
            if (isset($_SESSION['tos'])){
                
            }
            else {
                echo "document.getElementById('overlay').style.display = 'block';"; 
            }
        ?>
        }
        
        function acceptTerms() {
            document.getElementById('overlay').style.display = 'none';
            <?php $_SESSION['tos'] = "checked" ?>
        }

        function declineTerms() {
            window.location.href = 'https://www.google.com'; 
        }

    </script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TEST</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Lilita+One&display=swap" rel="stylesheet">
    <style>
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 9999;
        }

        .popup {
            position: fixed;
            background-color: white;
            color: black;
            width: 60%;
            max-width: 600px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            z-index: 10000;
            text-align: center;
        }

        .footer {
            background-color:#8009c5e5;
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

        .container {
            max-width: 1200px; 
            margin: 0 auto; 
            padding: 20px; 
            box-sizing: border-box; 
        }


        .G1 {
            transform: translate(20px, 85px) rotate(-20deg);
        }

        .e1 {
            transform: translate(13px, 60px) rotate(-15deg);
        }

        .e2 {
            transform: translate(6px, 40px) rotate(-13deg);
        }

        .k1 {
            transform: translate(3px, 23px) rotate(-15deg);
        }

        .s1 {
            transform: translate(2px, 14px) rotate(-6deg);
        }

        .f {
            transform: translate(1px, 8px) rotate(-5deg);
        }

        .o {
            transform: translate(0px, 5px) rotate(0deg);
        }

        .r {
            transform: translate(-1px, 8px) rotate(5deg);
        }

        .G2 {
            transform: translate(-2px, 14px) rotate(5deg);
        }

        .e3 {
            transform: translate(-3px, 25px) rotate(10deg);
        }

        .e4 {
            transform: translate(-6px, 40px) rotate(15deg);
        }

        .k2 {
            transform: translate(-14px, 60px) rotate(20deg);
        }

        .s2 {
            transform: translate(-20px, 80px) rotate(20deg);
        }

        span {
            display: inline-block;
        }
        
        .arc_text {
            text-align: center; 
            padding-top: 50px;
            font-size: 90px; 
            color: black; 
            font-weight:bold; 
            font-family:'Poppins';
            animation: bounce 2s infinite;
        }

        @media (max-width: 768px) {
            .arc_text {
                font-size: 60px; 
            }
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-20px); 
            }
        }


        .flip-card-cont{
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .flip-card {
            background-color: transparent;
            width: 250px;
            height: 500px;
            border: 1px solid #f1f1f1;
            perspective: 1000px;
            margin-top: 70px;
            margin-bottom: 50px;
            border-radius: 5px;

        }

        @media (max-width: 768px) {
            .flip-card {
                width: 100%; 
                max-width: 250px; 
            }
        }

        .flip-card-inner {
            position: relative;
            width: 100%;
            height: 100%;
            text-align: center;
            transition: transform 0.8s;
            transform-style: preserve-3d;
            border-radius: 5px;

        }

        .flip-card:hover .flip-card-inner {
            transform: rotateY(180deg);
        }

        .flip-card-front, .flip-card-back {
            position: absolute;
            width: 100%;
            height: 100%;
            -webkit-backface-visibility: hidden; 
            backface-visibility: hidden;
        }

            .flip-card-front {
            background-color: transparent;
            color: black;
        }

        .flip-card-back {
            background-color: transparent;
            color: white;
            transform: rotateY(180deg);
        }

        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropbtn {
            background-color: white;
            color: black;
            border: none;
            cursor: pointer;
            font-size: 20px;
            padding: 10px;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .dropdown:hover .dropbtn {
            background-color: #ddd;
        }

    </style>

</head>
<body onload="TermsOfService()">
    <div class="scroll-watcher"></div>
    <div class="grid">

        <div class="main-1">
        </div>

        <div class="milktea-splash"> 
            <img class="outside-image" width="100%" src="asset/milkteasplash.png">
        </div>

        <div class="boba1-div"> 
            <img width="40px" src="asset/star.png">
        </div>
        <div class="boba2-div"> 
            <img width="40px" src="asset/star.png">
        </div>
        <div class="boba3-div"> 
            <img width="40px" src="asset/star.png">
        </div>
        <div class="boba4-div"> 
            <img width="40px" src="asset/star.png">
        </div>
        <div class="boba5-div"> 
            <img width="40px" src="asset/star.png">
        </div>

        <div class="milktea-div"> 
            <img class="outside-image" width="1000px" src="asset/milktea.png">
        </div>
        <div class="thirstea-div"> 
            THIRS<div class="-tea-div">-TEA?</div>
        </div>
        <div class="thirstea-div2"> 
            Or perhaps peckish? Grab a drink and a bite of our masharap na food! Yum, Yum, YUM!
        </div>
        <div class="thirstea-div3"> 
            <a href="menu.php" class="order-btn">M E N U >></a>
        </div>
        <div class="navbarr">
            <ul>
            <?php
                if (isset($_SESSION['username'])) {
                    $firstLetter = strtoupper(substr($customername, 0, 1)); 
                    echo "<a href='user-setting.php'><li style='position:absolute; top: 10px; left: 20px; align-items: center;'><div class='user-logo'>$firstLetter</div></li></a>";
                    echo "<span style='animation: none; position:absolute; top: 18px; left: 50px; margin-left:30px; font-size:18px; font-family:'Poppins';'>Welcome, <b>" . $customername . "!</b></span>";  
                }
                ?>

                <li><a href="index.php" style="margin-left:20px;"><span class="selected-page">Home</span></a></li>
                <li><a href="about_us.php" style="margin-left:20px;"><span>About Us</span></a></li>
                <li><a href="menu.php" style="margin-left:20px;"><span>Menu</span></a></li>
                <li><a href="location.php" style="margin-left:20px;"><span>Location</span></a></li>
                <li><a href="contact_us.php" style="margin-left:20px;"><span>Contact Us</span></a></li>
                <?php
                    if (isset($_SESSION['username'])) {
                        echo "<li><a href='logout.php' class='secondary-btn' style='margin-left:20px;margin-right:20px;'>Log Out</a></li>";
                    } else {
                        echo "<li><a href='sign_up.php' class='primary-btn' style='margin-left:20px;'>Sign Up</a></li>";
                        echo "<li><a href='login.php' class='secondary-btn' style='margin-left:10px;margin-right:20px;'>Log In</a></li>";
                    }
                ?>
            </ul>
        </div>
    </div>

    <div class="container">
    <!-- Arc Text -->
    <div class="arc_text">

        <span class="G1"></span>
        <span class="e1">B</span>
        <span class="e2">E</span>
        <span class="k1">S</span>
        <span class="s1">T</span>
        <span class="f"></span>
        <span class="o">S</span>
        <span class="r">E</span>
        <span class="G2">L</span>
        <span class="e3">L</span>
        <span class="e4">E</span>
        <span class="k2">R</span>
        <span class="s2"></span>

    </div>

    <!-- Flip Cards - Best Sellers Pictures -->
    <div class="flip-card-cont">
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img src="asset/Wintermelon Milktea (1).png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;">
                </div>
                <div class="flip-card-back">
                    <a href="menu.php#milktea"><img src="asset/Wintermelon Milktea.png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;" title="Wintermelon Milktea"></a>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img src="asset/Brookies.png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;">
                </div>
                <div class="flip-card-back">
                    <a href="menu.php#snacks"><img src="asset/Brookies (1).png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;" title="Brookies"></a>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img src="asset/Blueberry Fruit Tea.png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;">
                </div>
                <div class="flip-card-back">
                    <a href="menu.php#fruittea"><img src="asset/Blueberry Fruit Tea (1).png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;border: 2px;" title="Blueberry Fruit Tea"></a>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img src="asset/Hawaiian Pizza.png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;">
                </div>
                <div class="flip-card-back">
                    <a href="menu.php#snacks"><img src="asset/Hawaiian Pizza (1).png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;" title="Hawaiian Pizza"></a>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img src="asset/Lemon Kiwi Infusion Fruit Tea.png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;">
                </div>
                <div class="flip-card-back">
                    <a href="menu.php#infusion-fruit-tea"><img src="asset/Lemon Kiwi Infusion Fruit Tea (1).png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;" title="Lemon Kiwi Infusion Fruit Tea"></a>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img src="asset/Hawaiian Pizza.png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;">
                </div>
                <div class="flip-card-back">
                    <a href="menu.php#snacks"><img src="asset/Cheese Overload Pizza.png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;" title="Hawaiian Pizza"></a>
                </div>
            </div>
        </div>
        <div class="flip-card">
            <div class="flip-card-inner">
                <div class="flip-card-front">
                    <img src="asset/Wintermelon Milktea (1).png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;">
                </div>
                <div class="flip-card-back">
                    <a href="menu.php#milktea"><img src="asset/Okinawa Milktea.png" alt="Avatar" style="width:250px;height:500px;border-radius:10px;" title="Wintermelon Milktea"></a>
                </div>
            </div>
        </div>
    </div>
</div>


    <!-- modal of terms and condi -->
    <div class="overlay" id="overlay">
        <div class="popup">
            <h2>Terms and Conditions</h2>
            <p>Welcome to Aiden's Milktea House website. Please read these terms and conditions carefully before using this website.
                By accessing and using this website, you agree to be bound by these terms and conditions. If you do not agree with any of these terms, you are prohibited from using or accessing this site.
            </p>
            <p>By continuing, you agree to our terms.</p>
            <button onclick="acceptTerms()">Accept</button>
            <button onclick="declineTerms()">Decline</button>
        </div>
    </div>

    <div class="footer">
        <p>&copy; 2024 Aiden's Milk Tea House. All rights reserved.</p>
        <p>
            <a href="about_us.php">About</a> |
            <a href="menu.php">Menu</a> |
            <a href="location.php">Location</a> |
            <a href="contact_us.php">Contact Us</a>

        </p>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>

    <script>

    $(window).scroll(function() {
        const scrollTop = $(window).scrollTop();

        if (scrollTop > 0) {
            $(".navbarr").addClass("purple");
        } else {
            $(".navbarr").removeClass("purple");
        }

        if (scrollTop > 920) {
            $(".navbarr").addClass("white");
        } else {
            $(".navbarr").removeClass("white");
        }
          
        
        });

    </script>
</body>
</html>

<!-- Gizmos and Shit -->
