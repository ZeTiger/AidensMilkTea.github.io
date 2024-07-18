<?php
session_start();

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_valid_password($password) {
    return preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*_]).{8,}$/", $password);
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$stage = 1;
$otp_sent = false;
$otp_resend_message = '';
$otp_code = ''; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submit'])) {
        $email = sanitize_input($_POST["email"]);

        if (!is_valid_email($email)) {
            $errors['email'] = "Invalid email format. Please enter a valid email address.";
        } else {
            $email_check_query = "SELECT * FROM clients WHERE email='$email' LIMIT 1";
            $result = $conn->query($email_check_query);
            if ($result->num_rows == 0) {
                $errors['email'] = "This email is not registered.";
            } else {
                $otp_code = rand(100000, 999999);
                $_SESSION['otp'] = $otp_code;
                $_SESSION['email'] = $email;

                mail($_SESSION['email'],'OTP for New Password', $_SESSION['otp']);
                $otp_sent = true;
                $stage = 2;
            }
        }
    } elseif (isset($_POST['verify_otp'])) {
        $entered_otp = sanitize_input($_POST['otp']);
        if ($entered_otp == $_SESSION['otp']) {
            $stage = 3;
        } else {
            $errors['otp'] = "Invalid OTP. Please try again.";
        }
    } elseif (isset($_POST['reset_password'])) {
        $new_password = sanitize_input($_POST['new_password']);
        $confirm_password = sanitize_input($_POST['confirm_password']);

        if (!is_valid_password($new_password)) {
            $errors['new_password'] = "Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
        } elseif ($new_password !== $confirm_password) {
            $errors['confirm_password'] = "Passwords do not match.";
        } else {
            $email = $_SESSION['email'];
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_query = "UPDATE clients SET password='$hashed_password' WHERE email='$email'";
            if ($conn->query($update_query) === TRUE) {
                // Password updated successfully
                session_destroy();
                header("Location: login.php?password_reset=success");
                exit();
            } else {
                $errors['db_error'] = "Error updating password: " . $conn->error;
            }
        }
    }
}


?>

<!DOCTYPE html>
<html>
<head>
    <title>Forgot/Reset Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
        font-family: Poppins, Verdana;
        background-size: 400% 400%;
        background-image:
          linear-gradient(
          123deg,
          rgba(21, 0, 41, 1) 0%,
          rgba(21, 0, 41, 0) 18%
          ),
          radial-gradient(
          30% 71% at 44% 87%,
          rgba(100, 40, 140, 1) 0%,
          rgba(100, 40, 140, 0) 100%
          ),
          radial-gradient(
          34% 54% at 36% 100%,
          rgba(90, 35, 130, 1) 0%,
          rgba(90, 35, 130, 0) 100%
          ),
          radial-gradient(
          43% 50% at 46% 45%,
          rgba(47, 9, 73, 1) 0%,
          rgba(60, 20, 100, 0) 100%
          ),
          linear-gradient(
          135deg,
          rgba(86, 25, 128, 1) 21%,
          rgba(90, 35, 130, 0) 48%
          ),
          linear-gradient(
          216deg,
          rgba(171, 0, 255, 0) 0%,
          rgba(171, 0, 255, 1) 1%,
          rgba(171, 0, 255, 0) 17%
          ),
          linear-gradient(
          129deg,
          rgba(33, 0, 50, 0) 29%,
          rgba(83, 0, 188, 1) 38%,
          rgba(81, 0, 188, 0) 50%
          ),
          radial-gradient(
          41% 97% at 91% 40%,
          rgba(120, 50, 160, 1) 0%,
          rgba(110, 45, 150, 1) 34%,
          rgba(100, 40, 140, 1) 70%,
          rgba(90, 35, 130, 0) 100%
          ),
          linear-gradient(
          360deg,
          rgba(70, 25, 110, 1) 0%,
          rgba(70, 25, 110, 1) 100%
          );
      margin: 0;
      padding: 0;
      position: relative;
      min-height: 100vh;
      color: white;
      animation: gradient-animation 15s ease infinite;
      }

      @keyframes gradient-animation {
          0% {
              background-position: 0% 50%;
          }
          50% {
              background-position: 100% 50%;
          }
          100% {
              background-position: 0% 50%;
          }
      }

      .M6Container {
        width: 50%;
        margin: 50px auto;
        padding: 40px;
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

        label {
            margin: 10px 0 5px;
        }

        input[type="email"],
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 5px 0 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: rgba(62, 5, 62, 0.868);
            font-size: 15px;
            font-weight: bold;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        input[type="submit"]:hover {
            background-color: rgba(96, 7, 96, 0.868);
        }

        .error-message {
            color: red;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .success-message {
            color: #90EE90;
            font-size: 15px;
            margin-bottom: 10px;
        }

        .btn-cancel {
            background-color: #dc3545;
            color: white;
        }

        .btn-cancel:hover {
            background-color: #c82333;
        }

        .modal-body {
            color: black;
        }

        .ques {
            font-weight: bold;
            color: white;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="M6Container">
        <div class="logo">
            <img src="img/logo_2-removebg-preview (1).png">
        </div>
        <h2><?php echo ($stage == 1) ? 'Forgot Password' : 'Reset Password'; ?></h2>
        <?php if ($stage == 1): ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <center><p>Enter your email address to receive a one-time password (OTP).</p></center>
                <label for="email">Email: <span style="color: red;">*</span></label>
                <input type="email" id="email" name="email" placeholder="juandelacruz@gmail.com" value="<?php echo isset($user_data['email']) ? $user_data['email'] : ''; ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <p class="error-message"><?php echo $errors['email']; ?></p>
                <?php endif; ?>
                <input type="submit" name="submit" value="Send OTP">
                <hr width="100%" color="white" style="opacity:40%;">
                <p style="text-align: center;">Remembered your password? <a href="login.php" style="color: #fff; text-decoration: underline;">Log In</a></p>
            </form>
        <?php elseif ($stage == 2): ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="otp">OTP: <span style="color: red;">*</span></label>
                <input type="text" id="otp" name="otp" required>
                <?php if (isset($errors['otp'])): ?>
                    <p class="error-message"><?php echo $errors['otp']; ?></p>
                <?php endif; ?>
                <?php if ($otp_resend_message): ?>
                    <p class="success-message"><?php echo $otp_resend_message; ?></p>
                <?php endif; ?>
                <p><a class="ques" data-toggle="modal" data-target="#resendModal">Didn't receive code? Resend code.</a></p>
                <input type="submit" name="verify_otp" value="Verify OTP">
                <button type="button" class="btn btn-cancel" data-toggle="modal" data-target="#cancelModal">Cancel</button>
            </form>
        <?php elseif ($stage == 3): ?>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                <label for="new_password">New Password: <span style="color: red;">*</span></label>
                <input type="password" id="new_password" name="new_password" required>
                <?php if (isset($errors['new_password'])): ?>
                    <p class="error-message"><?php echo $errors['new_password']; ?></p>
                <?php endif; ?>
                <label for="confirm_password">Confirm Password: <span style="color: red;">*</span></label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <?php if (isset($errors['confirm_password'])): ?>
                    <p class="error-message"><?php echo $errors['confirm_password']; ?></p>
                <?php endif; ?>
                <input type="submit" name="reset_password" value="Reset Password">
            </form>
        <?php endif; ?>
    </div>

    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelModalLabel">Cancel OTP Verification</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel OTP verification?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="window.location.href='forgot_password.php'">Yes, Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="resendModal" tabindex="-1" role="dialog" aria-labelledby="resendModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resendModalLabel">Resend OTP</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to resend the OTP?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <input type="submit" name="resend_otp" value="Yes, Resend OTP" class="btn btn-primary">
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>

<?php
$conn->close();
?>
