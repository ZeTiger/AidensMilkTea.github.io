<?php
session_start();

function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function is_valid_username($username) {
    return strlen($username) >= 6;
}

function is_valid_first_name($first_name) {
    return strlen($first_name) > 1;
}

function is_valid_last_name($last_name) {
    return strlen($last_name) > 1;
}

function is_valid_address($address1) {
    return strlen($address1) > 1;
}

function is_valid_phone($phone) {
    return preg_match("/^09\d{9}$/", $phone);
}


function is_valid_password($password) {
    return preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*_]).{7,}$/", $password);
    return strlen($password) > 1;
}

function is_valid_confirm_password($confirm_password) {
    return preg_match("/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*_]).{7,}$/", $confirm_password);
    return strlen($confirm_password) > 1;
}

function is_valid_firstname($first_name, $allow_numbers = false) {
    $pattern = "/^[a-zA-Z";
    if ($allow_numbers) {
        $pattern .= "0-9";
    }
    $pattern .= " .]*$/"; 
    return preg_match($pattern, $first_name);
}

function is_valid_lastname($last_name, $allow_numbers = false) {
    $pattern = "/^[a-zA-Z";
    if ($allow_numbers) {
        $pattern .= "0-9";
    }
    $pattern .= " .]*$/"; 
    return preg_match($pattern, $last_name);
}

function is_valid_gender($gender) {
    return in_array(strtolower($gender), ['m', 'f']);
}

function is_valid_zip_code($zipCode) {
    return preg_match("/^\d{4}$/", $zipCode);
}

function is_valid_city($city) {
    $valid_cities = ['Caloocan', 'Malabon', 'Navotas', 'Valenzuela'];
    return in_array($city, $valid_cities);
}

function is_valid_birthdate($birthdate) {
    // Check if birthdate is a valid date string
    if (!strtotime($birthdate)) {
      return false;
    }
  
    // Extract year from the birthdate string
    $birthYear = (int)date('Y', strtotime($birthdate));
  
    // Define the minimum and maximum allowed years based on your requirement
    $minYear = 2007;
    $maxYear = 2024;
  
    // Check if birth year is within the valid range
    return ($birthYear >= $minYear && $birthYear <= $maxYear);
  }
  

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "client_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errors = [];
$user_data = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = sanitize_input($_POST["first_name"]);
    $last_name = sanitize_input($_POST["last_name"]);
    $email = sanitize_input($_POST["email"]);
    $phone = sanitize_input($_POST["phone"]);
    $username = sanitize_input($_POST["username"]);
    $password = sanitize_input($_POST["password"]);
    $confirm_password = sanitize_input($_POST["confirm_password"]);
    $address1 = sanitize_input($_POST["address1"]);
    $address2 = sanitize_input($_POST["address2"]);
    $gender = isset($_POST["gender"]) ? sanitize_input($_POST["gender"]) : null; 
    $birthdate = sanitize_input($_POST["birthdate"]);
    $city = isset($_POST["city"]) ? sanitize_input($_POST["city"]) : '';    
    $postal_code = sanitize_input($_POST["postal_code"]);

    if (empty($birthdate)){
        $errors['birthdate'] = "Please input your birthdate";
    }elseif(is_valid_birthdate($birthdate)){
        $errors['birthdate'] = "You must be of legal age to sign up.";
    }
   
    // Validate gender
    if (!is_valid_gender($gender)) {
        $errors['gender'] = "Please select a gender .";
    }

   // Validate email format
    if (!is_valid_email($email)) {
        $errors['email'] = "Invalid email format. Please enter a valid email address.";
    } else {
        // Check if email already exists
        $email_check_query = "SELECT * FROM clients WHERE email='$email' LIMIT 1";
        $result = $conn->query($email_check_query);
        if ($result->num_rows > 0) {
            $errors['email'] = "This email is already registered. Please use a different email.";
        }
    }

    // Remove +63 and add 0 at the beginning for phone number validation
    if (strpos($phone, '+63') === 0) {
        $phone = '0' . substr($phone, 3);
    }

    // Validate phone number format
    if (!is_valid_phone($phone)) {
        $errors['phone'] = 'Please enter a valid phone number starting with 9 and containing 10 digits.';
    } else {
        // Check if phone number already exists
        $phone_check_query = "SELECT * FROM clients WHERE phone='$phone' LIMIT 1";
        $result = $conn->query($phone_check_query);
        if ($result->num_rows > 0) {
            $errors['phone'] = "This phone number is already registered. Please use a different phone number.";
        }
    }

    if (empty($address1)){
        $errors['address1'] = "Please input your address";
    }

    // Validate password format
    if (empty($password)){
        $errors['password'] = "Please input a password";
    } elseif (!is_valid_password($password)) {
        $errors['password'] = "Invalid password format. Password must be at least 8 characters long and include at least one uppercase and lowercase letter, number, and special character.";
    }

    // Check if passwords match
    if (empty($confirm_password)){
        $errors['confirm_password'] = "Please confirm your password";
    } elseif ($password !== $confirm_password) {
        $errors['confirm_password'] = "Passwords do not match. Please enter matching passwords.";
    }

    // Validate postal code
    if (!is_valid_zip_code($postal_code)) {
        $errors['postal_code'] = "Please input a valid ZIP code (4 digits only).";
    }

    if (empty($username)) {
        $errors['username'] = "Please input a username";
    } elseif (!is_valid_username($username)) {
        $errors['username'] = "Username does not meet the required criteria";
    }

    if (empty($first_name)) {
        $errors['first_name'] = "Please input your first name";
    } elseif (!is_valid_firstname($first_name)) {
        $errors['first_name'] = "Please input a valid first and last name!";
    }

    if (empty($last_name)) {
        $errors['last_name'] = "Please input your surname";
    } elseif (!is_valid_lastname($last_name)) {
        $errors['last_name'] = "Please input a valid first and last name!";
    }

    if (!is_valid_city($city)) {
        $errors['city'] = "Please select a city.";
    }
    
    if (empty($errors)) {
        $_SESSION['user_data'] = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
            'address1' => $address1,
            'address2' => $address2,
            'gender' => $gender,
            'birthdate' => $birthdate,
            'city' => $city,
            'postal_code' => $postal_code,
            'confirm_password' => $confirm_password
        ];
    
        header('Location: UserConfirmation.php');
        exit();
    } else {
        $user_data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'username' => $username,
            'address1' => $address1,
            'address2' => $address2,
            'gender' => $gender,
            'birthdate' => $birthdate,
            'city' => $city,
            'postal_code' => $postal_code,
            'confirm_password' => $confirm_password
        ];
    }
}
?>



<!DOCTYPE html>
<html>
<head>

  <title>Sign Up</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

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
    }

    .logo img {
        width: 150px;
        height: auto;
        z-index: 0;
    }

    p {
        font-size: clamp(13px,0.9vw,1vw);
        margin-top: 10px;
        margin-bottom: 30px;
    }

    h2 {
        text-align: center;
        font-size: clamp(20px,4vw,4vw);
        margin:0;
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

    .flex-container {
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

    /* Error message styles */
    .error-message {
        color: red;
        font-size: 12px;
    }

    /*City*/
    .city label,
        .city select {
            display: block;
            border-radius : 5px;
            
        }


        .flex-container {
            display: flex;
            gap: 10px; /* Space between columns */
        }

        .flex-container div {
            display: flex;
            flex-direction: column;
        }

        .flex-container label,
        .flex-container select,
        .flex-container input {
            width: 200px; 
            height: 40px; 
            font-size: 16px; 
        }


  </style>
  <script>

    document.addEventListener('DOMContentLoaded', function () {
        var phoneInput = document.getElementById('phone');
        phoneInput.addEventListener('input', function (event) {
        if (!phoneInput.value.startsWith('+63')) {
            phoneInput.value = '+63' + phoneInput.value.replace(/^\+63/, '');
        }
        });
        phoneInput.addEventListener('blur', function () {
        if (!phoneInput.value.startsWith('+63')) {
            phoneInput.value = '+63' + phoneInput.value;
        }
        });
    });

    document.querySelector('form').addEventListener('submit', function (event) {
        var phoneInput = document.getElementById('phone');
        var phone = phoneInput.value.replace('+63', '');
        var phonePattern = /^[9]\d{9}$/;

    });
  </script>

<script>
    function calculateAge() {
        var birthdate = document.getElementById('birthdate').value;
        if (birthdate) {
            var today = new Date();
            var birthDate = new Date(birthdate);
            var age = today.getFullYear() - birthDate.getFullYear();
            var monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            document.getElementById('Age').innerHTML = 'Age: ' + age;
        }
    }

    document.getElementById('birthdate').addEventListener('input', calculateAge);
</script>
</head>
<body>
  <div class="M6Container">
    <div class="logo">
      <img src="img/logo_2-removebg-preview (1).png">
    </div>
    <h2>SIGN UP</h2>
    <center>
        <p style="margin-bottom:20px;">Create your <b>account.</b></p> 
        <p style="font-size:12px; margin-top:0;">Fields marked with <span style="color: red;">*</span> are required.</p>
    </center>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
      <fieldset style="padding:60px;">
        <legend>Personal Information</legend>

        <label for="first_name">First Name: <span style="color: red;">*</span></label>
        <input type="text" maxlength="25" id="first_name" name="first_name" placeholder="Juan" value="<?php echo isset($user_data['first_name']) ? $user_data['first_name'] : ''; ?>" >
        <?php if (isset($errors['first_name'])): ?>
            <p class="error-message"><?php echo $errors['first_name']; ?></p>
        <?php endif; ?>

        <label for="last_name">Last Name: <span style="color: red;">*</span></label>
        <input type="text" maxlength="25" id="last_name" name="last_name" placeholder="Dela Cruz" value="<?php echo isset($user_data['last_name']) ? $user_data['last_name'] : ''; ?>">
        <?php if (isset($errors['last_name'])): ?>
            <p class="error-message"><?php echo $errors['last_name']; ?></p>
        <?php endif; ?>

        <label for="email">Email: <span style="color: red;">*</span></label>
        <input type="email" maxlength="30" id="email" name="email" placeholder="juandelacruz@gmail.com" value="<?php echo isset($user_data['email']) ? $user_data['email'] : ''; ?>">
        <?php if (isset($errors['email'])): ?>
            <p class="error-message"><?php echo $errors['email']; ?></p>
        <?php endif; ?>


        <label for="phone">Phone: <span style="color: red;">*</span></label>
        <div class="phone-container">
            <input maxlength="13" type="tel" id="phone" class="phone-input" name="phone" placeholder="9865423456" value="<?php echo isset($user_data['phone']) ? $user_data['phone'] : '+63'; ?>"  required>
        </div>
        <?php if (isset($errors['phone'])): ?>
            <p class="error-message"><?php echo $errors['phone']; ?></p>
        <?php endif; ?>

        <div class="radio-container">
            <label>Gender: <span style="color: red;">*</span></label>
            <div class="radio-buttons" style="margin-top:10px;">
                <label>
                    <input type="radio" name="gender" value="m" <?php echo (isset($user_data['gender']) && $user_data['gender'] == 'm') ? 'checked' : ''; ?> > Male
                </label>
                <label>
                    <input type="radio" name="gender" value="f" <?php echo (isset($user_data['gender']) && $user_data['gender'] == 'f') ? 'checked' : ''; ?> > Female
                </label>
            </div>
        </div>
        <?php if (isset($errors['gender'])): ?>
            <p class="error-message"><?php echo $errors['gender']; ?></p>
        <?php endif; ?>
        <br>

        <div class="form-group">
        <label for="birthdate">Birthdate: <span style="color: red;">*</span></label>
            <input type="date" id="birthdate" name="birthdate" value="<?php echo $user_data['birthdate'] ?? ''; ?>" oninput="validateBirthdate()">
            <div id="birthdate-error" class="error-message"><?php echo $errors['birthdate'] ?? ''; ?></div>
            
      </div>

        <label for="address">Address 1: <span style="color: red;">*</span></label>
        <textarea id="address1" maxlength="50" name="address1" placeholder="Address 1" ><?php echo isset($user_data['address1']) ? $user_data['address1'] : ''; ?></textarea>
        <?php if (isset($errors['address1'])): ?>
            <p class="error-message"><?php echo $errors['address1']; ?></p>
        <?php endif; ?>
        <label for="address">Address 2:</label>
        <textarea id="address2" maxlength="50" name="address2" placeholder="Address 2" ><?php echo isset($user_data['address2']) ? $user_data['address2'] : ''; ?></textarea>
        
        <div class="flex-container">
            <div class="city">
            <label for="city">City:<span style="color: red;">*</span></label>
            <select name="city" id="city"  >
                <option value="" disabled selected>Select a City</option>
                <option value="Caloocan" <?php echo isset($user_data['city']) ? $user_data['city']: ''; ?>>Caloocan</option>
                <option value="Malabon" <?php echo isset($user_data['city']) ? $user_data['city']: ''; ?>>Malabon</option>
                <option value="Navotas" <?php echo isset($user_data['city']) ? $user_data['city']: ''; ?>>Navotas</option>
                <option value="Valenzuela" <?php echo isset($user_data['city']) ? $user_data['city']: ''; ?>>Valenzuela</option>
            </select>
            <?php if (isset($errors['city'])): ?>
                <p class="error-message"><?php echo $errors['city']; ?></p>
            <?php endif; ?>
        </div>

            <div class="postal_code-container">
            <label for="postal_code">Postal Code: <span style="color: red;">*</span></label>
            <input type="text" maxlength="4" id="postal_code" name="postal_code" placeholder="1012" pattern="[0-9]{4}" title="Please enter a 4-digit postal code" value="<?php echo isset($user_data['postal_code']) ? $user_data['postal_code'] : ''; ?>" >
            <?php if (isset($errors['postal_code'])): ?>
            <p class="error-message"><?php echo $errors['postal_code']; ?></p>
            <?php endif; ?>
            </div>

        </div>
        
        <label for="username">Username: <span style="color: red;">*</span></label>
        <input type="text" maxlength="20" id="username" name="username" placeholder="juandelacruz" value="<?php echo isset($user_data['username']) ? $user_data['username'] : ''; ?>" >
        <?php if (isset($errors['username'])): ?>
            <p class="error-message"><?php echo $errors['username']; ?></p>
        <?php endif; ?>

        <label for="password">Password: <span style="color: red;">*</span></label>
        <input type="password" id="password" name="password" placeholder="Enter password" >
        <?php if (isset($errors['password'])): ?>
            <p class="error-message"><?php echo $errors['password']; ?></p>
        <?php endif; ?>

        <label for="confirm_password">Confirm Password: <span style="color: red;">*</span></label>
        <input type="password" id="confirm_password" name="confirm_password" placeholder="Re-enter password" >
        <?php if (isset($errors['confirm_password'])): ?>
            <p class="error-message"><?php echo $errors['confirm_password']; ?></p>
        <?php endif; ?>

      </fieldset>
      <input style="margin-top:30px; font-size:16px;" type="submit" name="submit" value="Sign Up">
      <hr width=100% style="opacity:30%; margin-top:30px; margin-bottom:20px;">
      <p style="text-align: center; margin-bottom:0px;">Already have an account? <a href="login.php" style="color: #fff; text-decoration: underline;">Log In</a></p>
    </form>
  </div>
</body>
</html>
<?php
$conn->close();
?>