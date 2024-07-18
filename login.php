  <?php
  session_start();

  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "client_registration";
  
  $conn = new mysqli($servername, $username, $password, $dbname);

  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $error_message = "";


  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $username = $_POST['username'];
      $password = $_POST['password'];
      $remember_me = isset($_POST['remember_me']) ? true : false;

      $sql = "SELECT * FROM clients WHERE username = '$username'";
      $result = $conn->query($sql);


      if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
          $stored_password = $row['password'];
          $username = $row['username'];
          $id = $row['id'];

          if (password_verify($password, $stored_password)) {
              session_start();
              $_SESSION['username'] = $username;
              $_SESSION['user_id'] = $id;

              $type_result = $conn->query("SELECT type FROM clients WHERE username = '$username'");
              if ($type_result && $type_result->num_rows > 0) {
                  $type_row = $type_result->fetch_assoc();
                  $type = $type_row['type'];
                  $_SESSION['type'] = $type;

                  if ($type == 1) {
                      header("Location: ordersummary.php");
                      exit();
                  } else {
                    if($_SESSION['lastpage']=="Location: login.php"){
                      header("Location: index.php");
                      exit();
                      }
                      elseif (isset($_SESSION['lastpage'])) {
                          header($_SESSION['lastpage']);
                          exit();
                      }
                      else {
                          header("Location: index.php");
                          exit();
                      }
                  }

                }
          } else {
              $error_message = "Incorrect password";
          }
      } else {
          $error_message = "Username not found";
      }
    }
  ?>

  <!DOCTYPE html>
  <html>
  <head>
    <title>Client Registration System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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
      }

      form {
        display: flex;
        flex-direction: column;
        box-sizing: border-box;
        z-index: 2;
      }

      label {
        margin: 10px 0 5px;
        margin-top: -5px;
      }

      input[type="text"],
      input[type="password"] {
        width: 100%;
        padding: 10px;
        margin: 5px 0 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
      }

      .error-message {
        color: red;
        margin-top: 5px;
      }

      .form-check {
        margin-bottom: 20px;
        margin-left: 5px;
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
    </style>
  </head>
  <body>
  <div style="display:flex;justify-content:flex-end;align-items:center; padding-top:10px; padding-right:10px;">
      <a class="btn btn-outline-light m-2 float-right" href="index.php" role="button" style="font-size: 1rem; ">Home</a>
      <a class="btn btn-light m-2 float-right" href="sign_up.php" role="button" style="font-size: 1rem; ">Sign Up</a>
  </div>
    <div class="M6Container">
      <div class="logo">
        <img src="img/logo_2-removebg-preview (1).png" >
      </div>
      <center>
      <h2><b>LOGIN</b></h2>
      <p>Enter your username and password to login.</p>
      </center>
      <form method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
        <label for="username">Username:</label>
        <input type="text" maxlength="20" id="username" name="username" placeholder="juandc_123" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        
        <div class="form-check">
          <input class="form-check-input" type="checkbox" id="remember_me" name="remember_me">
          <label class="form-check-label" for="remember_me">Remember me</label>
        </div>

        <?php if (!empty($error_message)) : ?>
          <p class="error-message"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <input type="submit" value="Login">
        <p style="text-align: center;"><a href="forgot_password.php" style="color: #fff; text-decoration: none;">Forgot your password?</a></p>
        <hr>
        <p style="text-align: center; margin-bottom:0px;">Don't have an account? <a href="sign_up.php" style="color: #fff; text-decoration: underline;">Sign Up</a></p>


      </form>
    </div>
  </body>
  </html>
