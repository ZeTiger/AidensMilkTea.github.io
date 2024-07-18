<?php
session_start();
$_SESSION['lastpage'] = "Location: contact_us.php";

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_valid_name($name) {
    return preg_match("/^[a-zA-Z .]*$/", $name);
}

function contains_vulgar_words($comment) {
    $vulgar_words = [
        'nigga', 'shit', 'NIGGA', 'fuck', 'FUCK', 'bastard', 'SHIT', 'DUMB', 'dumb', 
        'mothferfucker', 'mothafucka', 'MOTHAFUCKA', 'MOTHERFUCKER', 'bitch', 'BITCH', 
        'bitches', 'BITCHES', 'HOE', 'hoes', 'HOES', 'hoe', 'fucker', 'FUCKER', 'gago', 
        'GAGO', 'TANGA', 'tanga', 'TANGINAMO', 'tanginamo', 'tarantado', 'TARANTADO', 
        'siraulo', 'SIRAULO', 'inutil', 'INUTIL', 'putanginamo', 'PUTANGINAMO', 'deputa', 
        'DEPUTA', 'adik', 'ADIK', 'kupal', 'KUPAL', 'epal', 'EPAL', 'bulok', 'BULOK', 
        'useless', 'USELESS', 'asshole', 'ASSHOLE', 'retard', 'RETARD', 'piss', 'PISS', 
        'idiot', 'IDIOT', 'DICK', 'dick', 'penis', 'PENIS', 'ass', 'ASS', 'tits', 'TITS', 
        'TITIES', 'tities', 'pussy', 'PUSSY', 'VAGINA', 'vagina', 'swat', 'SWAT', 'drugs', 
        'DRUG', 'marijuana', 'MARIJUANA', 'weed', 'WEED', 'WEEDS', 'puta', 'Puta', 'PUTA', 'Fuck',
        'Shit', 'Nigga', 'MOtherfucker', 'Dumb', 'Bitch', 'Tanginamo', 'Siraulo'
    ]; // Add more words as needed
    
    foreach ($vulgar_words as $word) {
        if (stripos($comment, $word) !== false) {
            return true;
        }
    }
    return false;
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (isset($_SESSION['username'])) {
    $username = $_SESSION['username'];
    $query = "SELECT first_name FROM clients WHERE username = '$username'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $customername = $row['first_name'];
    }
}

$errors = [];
$user_data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['name'], $_POST['email'], $_POST['message'])) {
    $name = sanitize_input($_POST["name"]);
    $email = sanitize_input($_POST["email"]);
    $message = sanitize_input($_POST["message"]);

    if (!is_valid_name($name)) {
        $errors['name'] = "Please input a valid name!";
    }

    if (!is_valid_email($email)) {
        $errors['email'] = "Invalid email format. Please enter a valid email address.";
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, message) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $email, $message);
        $stmt->execute();
        $stmt->close();

        mail("You@me.com", "Inquiry from $name", "$message\nFrom: $email");

        $success_message = "Thank you for your feedback!";
    } else {
        $user_data = [
            'name' => $name,
            'email' => $email,
            'message' => $message
        ];
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['comment']) && isset($_SESSION['username'])) {
    $comment = sanitize_input($_POST["comment"]);
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id']; // Assuming user_id is stored in the session

    if (contains_vulgar_words($comment)) {
        $errors['comment'] = "Your comment contains inappropriate language.";
    } else {
        $stmt = $conn->prepare("INSERT INTO comments (user_id, username, message) VALUES (?, ?, ?)");
        $stmt->bind_param("iss", $user_id, $username, $comment);
        $stmt->execute();
        $stmt->close();
    }
}

$comments = [];
$result = $conn->query("SELECT username, message, date_of_comment FROM comments ORDER BY date_of_comment DESC");
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
}
?>

<html>
<head>
    <title>Comments, Suggestions, and Inquiry</title>
    <link rel="stylesheet" href="index.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <style>
        
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-image: url('img/log in bg.png');
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            background-size: cover;
        }
        .container {
            display: flex;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            width: 800px;
            max-width: 100%;
            margin-top: 80px;
        }
        .left-side {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 50%;
        }
        .left-side img {
            width: 100%;
            height: auto;
        }
        .right-side {
            padding: 20px;
            width: 50%;
            box-sizing: border-box;
        }
        .right-side h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #333333;
        }
        .right-side .form-group {
            margin-bottom: 15px;
        }
        .right-side .form-group input, .right-side .form-group textarea {
            width: calc(100% - 40px);
            padding: 10px;
            margin-left: 20px;
            margin-right: 20px;
            font-size: 14px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .right-side .form-group textarea {
            resize: vertical;
            height: 100px;
        }
        .right-side .btn {
            display: block;
            width: calc(100% - 40px);
            padding: 10px;
            margin-left: 20px;
            margin-right: 20px;
            font-size: 16px;
            color: #ffffff;
            background-color: #6c63ff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-align: center;
        }
        .right-side .btn:hover {
            background-color: #5147b0;
        }
        .alert {
            text-align: center;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
        }
        .alert-success {
            color: #155724;
            background-color: #d4edda;
            border-color: #c3e6cb;
        }
        .text-danger {
            color: #dc3545;
            font-size: 12px;
            margin-top: 5px;
        }
        .social-media {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
        }
        .social-media a {
            margin: 0 10px;
            font-size: 24px;
            color: #6c63ff;
            text-decoration: none;
        }
        .social-media a:hover {
            color: #5147b0;
        }
        .faq-container {
            width: 800px;
            max-width: 100%;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            padding: 20px;
            box-sizing: border-box;
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .faq-container h2 {
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
            color: #333333;
        }
        .accordion {
            cursor: pointer;
            padding: 10px;
            border: 1px solid #cccccc;
            border-radius: 4px;
            margin-bottom: 5px;
            background-color: #f7f7f7;
        }
        .accordion:hover {
            background-color: #e7e7e7;
        }
        .panel {
            padding: 10px;
            display: none;
            background-color: white;
            overflow: hidden;
            border: 1px solid #cccccc;
            border-top: none;
        }
        .star-rating {
            direction: rtl;
            unicode-bidi: bidi-override;
            display: inline-block;
        }

        .star-rating input {
            display: none;
        }

        .star-rating label {
            display: inline-block;
            width: 50px;
            height: 50px;
            background: url('star-empty.png') no-repeat;
            background-size: cover;
            cursor: pointer;
        }

        .star-rating input:checked ~ label {
            background: url('star.png') no-repeat;
            background-size: cover;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label {
            background: url('star.png') no-repeat;
            background-size: cover;
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
        .comments-section {
        width: 800px;
        max-width: 100%;
        background: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 20px;
        box-sizing: border-box;
        margin-top: 20px;
        margin-bottom: 20px;
    }

    .comments-section h2 {
        margin-bottom: 20px;
        font-size: 24px;
        text-align: center;
        color: white; /* White font color */
    }

    .comments-section form {
        margin-top: 10px;
    }

    .comments-section .form-group {
        margin-bottom: 15px;
    }

    .comments-section textarea {
        width: calc(100% - 40px);
        padding: 10px;
        font-size: 14px;
        border: 1px solid #cccccc;
        border-radius: 4px;
        box-sizing: border-box;
        resize: vertical;
        height: 100px;
        color: #333; 
    }

    .comments-section textarea::placeholder {
        color: #999; 
    }

    .comments-section .btn {
        display: block;
        width: calc(100% - 40px);
        padding: 10px;
        font-size: 16px;
        color: #ffffff;
        background-color: #6c63ff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        text-align: center;
        margin-top: 10px;
        transition: background-color 0.3s ease;
    }

    .comments-section .btn:hover {
        background-color: #5147b0; 
    }
    .comment-box {
            background-color: #f8f9fa;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            position: relative;
        }

        .comment-box strong {
            font-size: 16px;
            color: #333;
        }

        .comment-box em {
            font-size: 12px;
            color: #777;
            position: absolute;
            bottom: 10px;
            right: 15px;
        }

        .comment-box p {
            margin-top: 10px;
            font-size: 14px;
            color: #555;
        }

        .comments-section ul {
            list-style-type: none;
            padding: 0;
        }

        .comments-section ul li {
            margin-bottom: 15px;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body style="color:black;">
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
            <li><a href="location.php" style="margin-left:20px;text-decoration:none;"><span>Location</span></a></li>
            <li><a href="contact_us.php" style="margin-left:20px;text-decoration:none;"><span class="selected-page">Contact Us</span></a></li>
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

    <div class="container">
        <div class="left-side">
            <img src="img/email.jpg" alt="Contact Image"> 
        </div>
        <div class="right-side">
            <h2>Get Closer – Reach Out Now!</h2>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            <div class="social-media">
                <a href="https://www.facebook.com/MeiAn30" target="_blank"><i class="fab fa-facebook-f"></i></a>
                <a href="https://www.instagram.com/engr.agan" target="_blank"><i class="fab fa-instagram"></i></a>
                <a href="https://www.tiktok.com/@mskoliii/video/7371002520866540801?q=baby%20hindi%20oobra&t=1720172541613" target="_blank"><i class="fab fa-tiktok"></i></a>
            </div>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <input type="text" id="name" name="name" placeholder="Name" value="<?php echo isset($user_data['name']) ? $user_data['name'] : ''; ?>" required>
                    <?php if (isset($errors['name'])): ?>
                        <div class="text-danger"><?php echo $errors['name']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <input type="email" id="email" name="email" placeholder="Email" value="<?php echo isset($user_data['email']) ? $user_data['email'] : ''; ?>" required>
                    <?php if (isset($errors['email'])): ?>
                        <div class="text-danger"><?php echo $errors['email']; ?></div>
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <textarea id="message" name="message" placeholder="Message" required><?php echo isset($user_data['message']) ? $user_data['message'] : ''; ?></textarea>
                </div>
                <button type="submit" class="btn">Send</button>
            </form>
        </div>
    </div>

    <div class="faq-container">
        <h2>Frequently Asked Questions</h2>
        <div class="accordion">Q: Do you accept G-cash payment?</div>
        <div class="panel">A: Yes. You can send payment through our QR.</div>
        
        <div class="accordion">Q: Can you personalize sugar level?</div>
        <div class="panel">A: Yes.</div>
        
        <div class="accordion">Q: Do you have parking?</div>
        <div class="panel">A: Unfortunately as of the moment no, but we are working on that for our dear customers.</div>
        
        <div class="accordion">Q: Are you pet-friendly?</div>
        <div class="panel">A: Yes. We value all our furry customers too!</div>
        
        <div class="accordion">Q: Can your store be a learning space?</div>
        <div class="panel">A: Yes. You can even plug in your laptops while we play relaxing music for you to enjoy studying.</div>
    </div>

    <script>
        var acc = document.getElementsByClassName("accordion");
        for (var i = 0; i < acc.length; i++) {
            acc[i].addEventListener("click", function() {
                this.classList.toggle("active");
                var panel = this.nextElementSibling;
                if (panel.style.display === "block") {
                    panel.style.display = "none";
                } else {
                    panel.style.display = "block";
                }
            });
        }
    </script>

    <div class="star-rating">
        <h1><center>Rate Us</center></h1>
        <input type="radio" id="star5" name="rating" value="5"><label for="star5" title="5 stars"></label>
        <input type="radio" id="star4" name="rating" value="4"><label for="star4" title="4 stars"></label>
        <input type="radio" id="star3" name="rating" value="3"><label for="star3" title="3 stars"></label>
        <input type="radio" id="star2" name="rating" value="2"><label for="star2" title="2 stars"></label>
        <input type="radio" id="star1" name="rating" value="1"><label for="star1" title="1 star"></label>
    </div>

    <div class="comments-section">
        <h2>Comments</h2>
        <?php if (isset($_SESSION['username'])): ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <div class="form-group">
                    <textarea id="comment" name="comment" placeholder="Leave a comment" required></textarea>
                </div>
                <?php if (isset($errors['comment'])): ?>
                    <div class="text-danger"><?php echo $errors['comment']; ?></div>
                <?php endif; ?>
                <button type="submit" class="btn">Post Comment</button>
            </form>
        <?php endif; ?>
        <?php if (!empty($comments)): ?>
            <ul class="comment-list">
                <?php foreach ($comments as $comment): ?>
                    <li class="comment-box">
                        <strong><?php echo htmlspecialchars($comment['username']); ?></strong>
                        <em><?php echo $comment['date_of_comment']; ?></em>
                        <p><?php echo htmlspecialchars($comment['message']); ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>