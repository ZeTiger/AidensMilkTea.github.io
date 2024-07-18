<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

function is_valid_phone($phone) {
    return preg_match("/^09\d{9}$/", $phone);
}

function calculate_age($birthdate) {
    $birthDate = new DateTime($birthdate);
    $today = new DateTime();
    $age = $today->diff($birthDate)->y;
    return $age;
}

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch user data
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM clients WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $city = $user['city']; 
} else {
    echo "No user found";
    exit();
}

$validationMessage = "";
$updateSuccess = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['save_changes'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $gender = $_POST['gender'];
    $birthdate = $_POST['birthdate'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address1 = $_POST['address1'];
    $address2 = $_POST['address2'];
    $city = $_POST['city']; 
    $postal_code = $_POST['postal_code'];

    $errors = [];
    $Age = calculate_age($birthdate);
    
    if ($Age < 18) {
        $errors['Age'] = "You must be at least 18 years old to update your account.";
    }

    if (!is_valid_email($email)) {
        $errors['email'] = "Invalid email format. Please enter a valid email address.";
    } else {
        $email_check_query = "SELECT * FROM clients WHERE email=? AND id != ? LIMIT 1";
        $stmt = $conn->prepare($email_check_query);
        $stmt->bind_param("si", $email, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['email'] = "This email is already registered. Please use a different email.";
        }
    }

    if (strpos($phone, '+63') === 0) {
        $phone = '0' . substr($phone, 3);
    }

    if (!is_valid_phone($phone)) {
        $errors['phone'] = 'Please enter a valid phone number starting with 09 and containing 10 digits.';
    } else {
        $phone_check_query = "SELECT * FROM clients WHERE phone=? AND id != ? LIMIT 1";
        $stmt = $conn->prepare($phone_check_query);
        $stmt->bind_param("si", $phone, $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $errors['phone'] = "This phone number is already registered. Please use a different phone number.";
        }
    }

    if (empty($errors)) {
        $sql = "UPDATE clients SET first_name = ?, last_name = ?, gender = ?, birthdate = ?, email = ?, phone = ?, address1 = ?, address2 = ?, city = ?, postal_code = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssi", $first_name, $last_name, $gender, $birthdate, $email, $phone, $address1, $address2, $city, $postal_code, $user_id);

        if ($stmt->execute()) {
            $validationMessage = "Changes saved successfully";
            $updateSuccess = true;
        } else {
            $validationMessage = "Error updating user information: " . $conn->error;
        }
    } else {
        $validationMessage = "Changes not saved. Please correct the following errors:";
        foreach ($errors as $error) {
            $validationMessage .= "<br>- " . $error;
        }
    }
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Account Settings</title>
    <link rel="stylesheet" href="user-setting.css">
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
</head>
<body>
    <div class="navbarr" style="background-color:white; position: relative;">
        <ul>
            <?php
            if (isset($_SESSION['username'])) {
                $firstLetter = strtoupper(substr($user['first_name'], 0, 1)); 
                echo "<a href='user-setting.php'><li style='position:absolute; top: 10px; left: 20px; align-items: center;'><div class='user-logo'>$firstLetter</div></li></a>";
                echo "<span style='animation: none; position:absolute; top: 18px; left: 50px; margin-left:30px; font-size:18px; font-family:\"Poppins\";'>Welcome, <b>" . $user['first_name'] . "!</b></span>";  
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

    <div class="container light-style flex-grow-1 container-p-y" style="border-radius: 20px; margin-top: 20px; margin-bottom: 20px;">
        <div class="card overflow-hidden">
            <div class="row no-gutters row-bordered row-border-light" style="width: 95%;">
                <div class="col-md-3 pt-0">
                    <div class="list-group list-group-flush account-settings-nav">
                        <a class="list-group-item list-group-item-action active" data-toggle="list"
                            href="#overview">Account Details</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#change-password">Change password</a>
                        <a class="list-group-item list-group-item-action" data-toggle="list"
                            href="#Accinfo">Account Information</a>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="tab-content">
                        <div class="tab-pane fade active show" id="overview">
                            <div class="card-body media align-items-center">
                                <div class="media-body ml-4">
                                    <div class="profile-image-top">
                                        <div class="user-logo"><?php echo strtoupper(substr($user['username'], 0, 1)); ?></div>
                                    </div>
                                    <div class="profile-info">
                                        <h2 class="username"><?php echo htmlspecialchars($user['username']); ?></h2>
                                        <div class="account-info">
                                            <div class="form-group">
                                                <label class="info-label"><b>Email</b></label>
                                                <div class="info-value"><?php echo htmlspecialchars($user['email']); ?></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="info-label"><b>Contact Number</b></label>
                                                <div class="info-value"><?php echo htmlspecialchars($user['phone']); ?></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="info-label"><b>Gender</b></label>
                                                <div class="info-value"><?php echo ($user['gender'] == 'M') ? 'Male' : 'Female'; ?></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="info-label"><b>Birthdate</b></label>
                                                <div class="info-value"><?php echo htmlspecialchars($user['birthdate']); ?></div>
                                            </div>
                                            <div class="form-group">
                                                <label class="info-label"><b>Address</b></label>
                                                <div class="info-value"><?php echo htmlspecialchars($user['address1']); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="change-password">
                            <div class="card-body pb-2">
                                <?php if (!empty($message)): ?>
                                    <div class="alert alert-info"><?php echo $message; ?></div>
                                <?php endif; ?>
                                <form action="" method="POST" onsubmit="return validatePassword();">
                                    <div class="form-group">
                                        <label class="form-label" require>Current password<span style="color: red;">*</span></label>
                                        <input type="password" name="current_password" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" require>New password<span style="color: red;">*</span></label>
                                        <input type="password" name="new_password" class="form-control" id="new_password" required>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-label" require>Repeat new password<span style="color: red;">*</span></label>
                                        <input type="password" name="repeat_new_password" class="form-control" id="repeat_new_password" required>
                                    </div>
                                    <div id="password_message" style="color: red;"></div>
                                    <button type="submit" class="btn btn-primary" style=" background-color:#8009c5e5;">Change Password</button>
                                </form>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="Accinfo">
                            <div class="card-body pb-2">
                                <div class="M6Container">
                                    <h2>Edit Your Account!</h2>
                                    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                                        <fieldset>
                                            <legend>Personal Information</legend>
                                            <label for="first_name">First Name:</label>
                                            <input type="text" id="first_name" name="first_name" placeholder="Juan" value="<?php echo htmlspecialchars($user['first_name']); ?>" required>
                                            <br><br>
                                            <label for="last_name">Last Name:</label>
                                            <input type="text" id="last_name" name="last_name" placeholder="Dela Cruz" value="<?php echo htmlspecialchars($user['last_name']); ?>" required>
                                            <br><br>
                                            <label for="gender">Gender:</label>
                                            <select id="gender" name="gender" required>
                                                <option value="M" <?php if ($user['gender'] == 'M') echo 'selected'; ?>>Male</option>
                                                <option value="F" <?php if ($user['gender'] == 'F') echo 'selected'; ?>>Female</option>
                                            </select>
                                            <br><br>
                                            <label for="birthdate">Birthdate:</label>
                                            <input type="date" id="birthdate" name="birthdate" value="<?php echo htmlspecialchars($user['birthdate']); ?>" required>
                                            <br><br>
                                        </fieldset>
                                        <fieldset>
                                            <legend>Contact Information</legend>
                                            <label for="email">Email:</label>
                                            <input type="email" id="email" name="email" placeholder="juan.delacruz@email.com" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                            <br><br>
                                            <label for="phone">Contact Number:</label>
                                            <input type="text" id="phone" name="phone" placeholder="09xxxxxxxxx" value="<?php echo htmlspecialchars($user['phone']); ?>" required>
                                        </fieldset>
                                        <br><br>
                                        <fieldset>
                                            <legend>Address Information</legend>
                                            <label for="address1">Address:</label>
                                            <input type="text" id="address1" name="address1" placeholder="House Number, Building and Street" value="<?php echo htmlspecialchars($user['address1']); ?>" required>
                                            <br>
                                            <label for="address2">Address 2 (Optional):</label>
                                            <input type="text" id="address2" name="address2" placeholder="Apartment, Suite, Unit, Building, Floor, etc. (Optional)" value="<?php echo htmlspecialchars($user['address2']); ?>">
                                            <br>
                                            <label for="city">City:</label>
                                            <input type="text" class="form-control" name="city" value="<?php echo htmlspecialchars($city); ?>" required>
                                            <br>
                                            <label for="postal_code">Postal Code:</label>
                                            <input type="text" id="postal_code" name="postal_code" placeholder="Postal Code" value="<?php echo htmlspecialchars($user['postal_code']); ?>" required>
                                        </fieldset>
                                        <br>
                                        <div class="form-group">
                                            <button type="submit" class="form-button" name="save_changes">Save Changes</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <script>
                            function validatePassword() {
                                var newPassword = document.getElementById('new_password').value;
                                var repeatPassword = document.getElementById('repeat_new_password').value;
                                var passwordMessage = document.getElementById('password_message');

                                if (newPassword !== repeatPassword) {
                                    passwordMessage.textContent = 'New passwords do not match';
                                    return false;
                                }

                                if (newPassword.length < 8) {
                                    passwordMessage.textContent = 'Password must be at least 8 characters long';
                                    return false;
                                }

                                return true;
                            }
                        </script>
                        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
                        <div class="modal fade" id="validationModal" tabindex="-1" role="dialog" aria-labelledby="validationModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="validationModalLabel">Validation Message</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <?php if (!empty($validationMessage)): ?>
                                            <div class="validation-message <?php echo strpos($validationMessage, 'successfully') !== false ? 'success' : 'error'; ?>">
                                                <?php echo $validationMessage; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        <script>
                            $(document).ready(function() {
                                <?php if (!empty($validationMessage)): ?>
                                    $('#validationModal').modal('show');
                                <?php endif; ?>

                                // Refresh the page only if update was successful
                                <?php if ($updateSuccess): ?>
                                    // Use sessionStorage to ensure refresh only happens once per session
                                    if (!sessionStorage.getItem('updateSuccess')) {
                                        sessionStorage.setItem('updateSuccess', 'true');
                                        location.reload();
                                    }
                                <?php endif; ?>
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

