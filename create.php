<?php
    session_start();

    $hostname = "localhost";  
    $uname = "root";      
    $dbpassword = "";          
    $dbname = "client_registration";  

    $con = new mysqli($hostname, $uname, $dbpassword, $dbname);

    if ($con->connect_error) {
        die("Connection failed: " . $con->connect_error);
    }

    if (isset($_SESSION['username'])) {
        $loggedInUsername = $_SESSION['username'];
        
        $stmt = $con->prepare("SELECT type FROM clients WHERE username = ?");
        $stmt->bind_param("s", $loggedInUsername);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $userType = $row['type'];
        
            if ($userType == 0) {
                header("Location: index.php");
                exit(); 
            }
        } else {
            session_destroy();
            header("Location: login.php");
            exit();
        }
        
        $stmt->close();
    } else {

        header("Location: login.php");
        exit();
    }

    $fname="";
    $lname="";
    $email="";
    $phone="";
    $gender="";
    $birthdate="";
    $address1="";
    $address2="";
    $postalcode="";
    $country="";
    $username="";
    $password="";
    $alertMessage="";

    if ( $_SERVER["REQUEST_METHOD"] == "POST"){
        
        $fname=$_POST["fname"];
        $lname=$_POST["lname"];
        $email=$_POST["email"];
        $phone=$_POST["phone"];
        $gender=$_POST["gender"];
        $birthdate=$_POST["birthdate"];
        $address1=$_POST["address1"];
        $address2=$_POST["address2"];
        $postalcode=$_POST["postalcode"];
        $country=$_POST["country"];
        $username=$_POST["username"];
        $password=$_POST["password"];

    do{
        $sql = "INSERT INTO clients (first_name, last_name, email, phone, gender, birthdate, username, password, address1, address2, postal_code, country) VALUES ('$fname', '$lname', '$email', '$phone', '$gender', '$birthdate', '$username', '$password', '$address1', '$address2', '$postalcode', '$country')";
        $result = $con->query($sql);

        mysqli_close($con);
        header("location: AdminModule.php");
        exit;
    }
    while(false);

    }   
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Module</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</head>
<body style="background-image: url('img/log in bg.png');";> 
    <div style="display:flex;justify-content:flex-end;align-items:center;">
        <a class="btn btn-danger m-3 float-right" href="logout.php" role="button" style="font-size: 1rem; ">Logout</a>
    </div>
    <div class="container my-4 p-4" style="background-color:white; box-shadow: 2px 2px 15px 0px rgba(0, 0, 0, 0.3); border-radius: 19px;">
    <h2><span style="color:#551e8a;"><b>| </b></span>Create new user</h2>
        <?php
            if (!empty($alertMessage)){
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>
                    <strong>$alertMessage</strong>
                    <button type='button' class='close' data-dismiss='alert' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                    </button>
                </div>";
            }
        ?>

        <form method="POST">
            <div class="row mb-3 my-3">
                <label class="col-sm-3 col-form-label">First Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="fname" value="<?php echo $fname; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Last Name</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="lname" value="<?php echo $lname; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="email" value="<?php echo $email; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Phone</label>
                <div class="col-sm-6">
                    <input type="tel" class="form-control" name="phone" value="<?php echo $phone; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Gender</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="gender" value="<?php echo $gender; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Birthdate</label>
                <div class="col-sm-6">
                    <input type="date" class="form-control" name="birthdate" value="<?php echo $birthdate; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Address 1</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address1" value="<?php echo $address1; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Address 2</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="address2" value="<?php echo $address2; ?>">
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Postal Code</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="postalcode" value="<?php echo $postalcode; ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Country</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="country" value="<?php echo $country ?>" required>
                </div>
            </div>

            <div class="row mb-3 my-5">
                <label class="col-sm-3 col-form-label">Username</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="username" value="<?php echo $username ?>" required>
                </div>
            </div>
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Password</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="password" value="<?php echo $password ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-secondary">Submit</button>
                </div>
                <div class="col-sm-3 d-grid">
                <a class='btn btn-outline-secondary btm-sm' href='AdminModule.php'>Cancel</a>
                </div>
            </div>

        </form>
</body>
</html>