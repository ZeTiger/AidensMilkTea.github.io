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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Aiden's Milk Tea House</title>
    <link rel="stylesheet" href="index.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Varela+Round&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.7.1/ScrollTrigger.min.js"></script>
 
    <style>
        .smaller-img {
            max-width: 100%;
            height: auto;
            max-height: 300px;
        }
        .header {
            height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            text-align: center;
            position: relative;
            overflow: hidden;
        }   
        .header video {
            min-width: 100%;
            min-height: 100%;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            object-fit: cover;
        }
        .header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5); 
        }
        .about-us {
            padding: 50px 0;
        }
        .about-us img {
            max-width: 100%;
            height: auto;
        }
        .parallax {
            background: url('asset/Parallax_History.png') no-repeat center center/cover;
            height: 300px;
            position: relative;
        }
        .team img {
            border-radius: 50%;
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
            color:white;
        }
        .team-box {
            background:url('img/log in bg.png');
            border: 1px solid #e0c1f3;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            text-align: center;
            color:white;
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

<div class="navbarr" style="background-color:white;">
            <ul>

            <?php
                if (isset($_SESSION['username'])) {
                    $firstLetter = strtoupper(substr($customername, 0, 1)); 
                    echo "<a href='user-setting.php'><li style='position:absolute; top: 10px; left: 20px; align-items: center;'><div class='user-logo'>$firstLetter</div></li></a>";
                    echo "<span style='animation: none; position:absolute; top: 18px; left: 50px; margin-left:30px; font-size:18px; font-family:'Poppins';'>Welcome, <b>" . $customername . "!</b></span>";  
                }
                ?>

                <li><a href="index.php" style="margin-left:20px;text-decoration:none;"><span>Home</span></a></li>
                <li><a href="about_us.php" style="margin-left:20px;text-decoration:none;"><span class="selected-page">About Us</span></a></li>
                <li><a href="menu.php" style="margin-left:20px;text-decoration:none;"><span>Menu</span></a></li>
                <li><a href="location.php" style="margin-left:20px;text-decoration:none;"><span>Location</span></a></li>
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
    <!-- Header -->
    <header class="header">
        <div class="container">
            <video src="asset/About Us Header.mp4" autoplay muted loop></video>
        </div>
    </header>

    <!-- About Us  -->
    <section class="about-us container mt-5 slide-up">
        <div class="row">
            <div class="col-md-6">
                <img src="asset/place1.jpg" alt="About Us">
            </div>
            <div class="col-md-6">
                <h1>Welcome to Aiden's Milk Tea House</h1>
                <p>
                    In the vibrant and bustling landscape of the food and beverage industry in the Philippines, milk tea 
                    shops have emerged as one of the most profitable and beloved ventures. Recognizing this growing trend 
                    and the community's love for unique, flavorful beverages, we were inspired to establish our very own 
                    milk tea haven. Thus, Aiden's Milk Tea House was born.
                    <br><br>
                    From the very beginning, our mission has been clear: to create a warm, 
                    welcoming space where every cup of tea tells a story. We envisioned a place where customers could 
                    escape the hustle and bustle of daily life, relax, and enjoy a meticulously crafted drink that brings 
                    joy and comfort.
                </p>
            </div>
        </div>
    </section>

    <!-- Our Story -->
    <section class="parallax"></section>
    <section class="container mt-5 slide-up">
        <div class="row">
            <div class="col-12">
                <h2>Our Story</h2>
                <p>
                    Aiden's Milk Tea House is not just another spot to grab a quick beverage. It's a destination—a place 
                    where quality, creativity, and community converge. Each cup of tea we serve is a testament to our 
                    commitment to excellence and our passion for the craft. We take pride in sourcing the finest ingredients, 
                    experimenting with innovative flavors, and perfecting classic recipes to deliver an unforgettable taste 
                    experience.
                    <br><br>
                    Aiden's Milk Tea House is more than just a business; it's a labor of love, a dream realized, and a 
                    community cherished. We look forward to welcoming you to our home, where every sip is a celebration 
                    of flavor, comfort, and connection. Join us as we continue to brew happiness, one delicious cup at 
                    a time.
                </p>
            </div>
        </div>
    </section>

    <!-- Mission and Vision -->
    <section class="container mt-5 slide-up">
        <div class="row">
            <div class="col-md-6">
                <div class="box">
                    <h3>Mission</h3>
                    <p>At Aiden's Milk Tea House our mission is to provide good quality of products and experiences to our valued customers and maintain an excellent service to the people around us.
                    </p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="box">
                    <h3>Vision</h3>
                    <p>At Aiden's Milk Tea House, our vision is to become the leading destination for high-quality milk tea, creating a welcoming space where customers can relax, and indulge in our diverse flavors. 
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Team -->
    <section class="container mt-5 slide-up">
        <h2 class="text-center">Meet Our Team</h2>
        <div class="row">
            <div class="col-md-3">
                <div class="team-box">
                    <img src="asset/MAYANN(OWNER).png" alt="Team Member" class="img-fluid mb-3">
                    <h4>May Ann Ganalon</h4>
                    <p>Founder</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="team-box">
                    <img src="asset/LAURA(PREPARINGOFFOODS).png" alt="Team Member" class="img-fluid mb-3">
                    <h4>Laura Danganan</h4>
                    <p>Head Barista</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="team-box">
                    <img src="asset/ERLENE(RECEIVINGORDERS).png" alt="Team Member" class="img-fluid mb-3">
                    <h4>Erlene Sorello</h4>
                    <p>Customer Service Staff</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="team-box">
                    <img src="asset/RAMON(PREPARINGOFFOODS).png" alt="Team Member" class="img-fluid mb-3">
                    <h4>Ramon Danganan</h4>
                    <p>Delivery Service Staff</p>
                </div>
            </div>
        </div>
    </section>

    <div class="footer">
        <p>&copy; 2024 Aiden's Milk Tea House. All rights reserved.</p>
        <p>
            <a href="#">About</a> |
            <a href="#">Menu</a> |
            <a href="#">Contact Us</a>
        </p>
    </div>

    <script>
        gsap.registerPlugin(ScrollTrigger);

        gsap.utils.toArray(".slide-up").forEach(function(element) {
            gsap.from(element, {
                duration: 1.5,
                opacity: 0,
                y: 50,
                ease: "power3.out",
                scrollTrigger: {
                    trigger: element,
                    start: "top 80%",
                    end: "bottom 60%",
                    toggleActions: "play none none none"
                }
            });
        });

        gsap.fromTo(".parallax", {
            backgroundPosition: "50% 0%"
        }, {
            backgroundPosition: "50% 100%",
            ease: "none",
            scrollTrigger: {
                trigger: ".parallax",
                start: "top bottom",
                end: "bottom top",
                scrub: true
            }
        });
    </script>

</body>
</html>