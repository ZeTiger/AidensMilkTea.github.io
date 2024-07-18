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
   
    $id="";
    $fname="";
    $lname="";
    $email="";
    $phone="";
    $gender="";
    $birthdate="";
    $address="";
    $postalcode="";
    $country="";
    $username="";
    $password="";

    $id = $_GET["id"];

    if ( $_SERVER["REQUEST_METHOD"] == "GET"){

        $sql = "SELECT * FROM clients WHERE id=$id";
        $result = $con->query($sql);
        $row = $result->fetch_assoc();

        $fname=$row["first_name"];
        $lname=$row["last_name"];
        $email=$row["email"];
        $phone=$row["phone"];
        $gender=$row["gender"];
        $birthdate=$row["birthdate"];
        $address1=$row["address1"];
        $address2=$row["address2"];
        $postalcode=$row["postal_code"];
        $country=$row["country"];
        $username=$row["username"];
        $password=$row["password"];

    }

    else{
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
            $password=password_hash($password,PASSWORD_DEFAULT);
            $sql = "UPDATE clients SET first_name='$fname', last_name='$lname', email='$email', phone='$phone', gender='$gender', birthdate='$birthdate', username='$username', password='$password', address1='$address1', address2='$address2', postal_code='$postalcode', country='$country' WHERE id=$id";
            echo $sql;
            $result = $con->query($sql);

    
            mysqli_close($con);
            header("location:AdminModule.php");
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
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</head>
<body style="background-image: url('img/log in bg.png');";>
    <div style="display:flex;justify-content:flex-end;align-items:center;">
        <a class="btn btn-danger m-3 float-right" href="logout.php" role="button" style="font-size: 1rem; ">Logout</a>
    </div>
    <div class="container my-4 p-4" style="background-color:white; box-shadow: 2px 2px 15px 0px rgba(0, 0, 0, 0.3); border-radius: 19px;">
    <h2><span style="color:#551e8a;"><b>| </b></span>User information</h2>

        <form method="POST">
            <input type="hidden" name="id" value="<?php echo $id; ?>">
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
                    <input type="text" class="form-control" name="address2" value="<?php echo $address2; ?>" required>
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
                    <button type="submit" class="btn btn-secondary">Overwrite</button>
                </div>
                <div class="col-sm-3 d-grid">
                <a class='btn btn-outline-secondary btm-sm' href='AdminModule.php'>Back</a>
                </div>
            </div>

        </form>
</body>
</html>