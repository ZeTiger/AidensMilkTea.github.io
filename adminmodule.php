<?php
session_start();
$_SESSION['lastpage'] = "Location: login.php";

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Admin Module</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
</head>
<body style="background-image: url('img/log in bg.png');";>
<div style="display:flex;justify-content:flex-end;align-items:center;">
    <a class='btn btn-secondary btm-sm' href='ordersummary.php' role="button" style='font-size: 1rem; width:70px;'>Back</a>
    <a class="btn btn-danger m-3 float-right" href="logout.php" role="button" style="font-size: 1rem; ">Logout</a>
</div>
    <div class="container my-4 p-4" style="background-color:white; box-shadow: 2px 2px 15px 0px rgba(0, 0, 0, 0.3); box-shadow: 2px 2px 15px 0px rgba(0, 0, 0, 0.3); border-radius: 19px;">
    <h2><span style="color:#551e8a;"><b>| </b></span>User Database</h2>
    <a class = "btn btn-secondary my-3" href="create.php" role="button" style="font-size: 0.8rem;">Create User</a>
        <form action="" method="GET">
            <div class="input-group mb-0">
                <input type="text" style="font-size: 0.8rem" name="search" value="<?php if(isset($_GET['search'])){echo $_GET['search']; } ?>" class="form-control" placeholder="Search data">
                <button type="submit" class="btn btn-secondary" style="font-size: 0.8rem;">Search</button>
            </div>
        </form>
        <br>
        <table class="table my-2" style="font-size: 0.8rem;" valign="middle" >
            <thead>
                <tr>
                    <th>#</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Birthdate</th> 
                    <th>Address 1</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php

            $con = new mysqli('localhost','root','','client_registration');

            if(isset($_GET['search']))
                {
                    $filtervalues = $_GET['search'];
                    $sql = "SELECT * FROM clients WHERE CONCAT(id,first_name,last_name,email,phone,username) LIKE '%$filtervalues%' ";
                    $result = mysqli_query($con, $sql);

                    if(mysqli_num_rows($result) > 0)
                    {
                        while ($row = $result->fetch_assoc()){
                            echo "
                            <tr>
                                <td>$row[id]</td>
                                <td>$row[first_name]</td>
                                <td>$row[last_name]</td>
                                <td>$row[email]</td>
                                <td>$row[phone]</td>
                                <td>$row[gender]</td>
                                <td>$row[birthdate]</td>
                                <td>$row[address1]</td>
                                <td>
                                    <a class='btn btn-secondary btm-sm' href='edit.php?id=$row[id]' style='font-size: 0.8rem;'>More</a>
                                    <button class='btn btn-danger btn-sm delete-btn' data-id='$row[id]';>Delete</button>
                                </td>
                            </tr>
                            ";
                        }
                    }
                    else
                    {
                        ?>
                            <tr>
                                <td colspan="8" align="middle"><br>No Record Found<br><br></td>
                            </tr>
                        <?php
                    }
                }
                else{
                    if(mysqli_connect_errno())
                    {
                        die("Connection failed");
                    }

                    $sql = "SELECT * FROM clients";
                    $result = $con->query($sql);

                    while ($row = $result->fetch_assoc()){
                        echo "
                        <tr>
                            <td>$row[id]</td>
                            <td>$row[first_name]</td>
                            <td>$row[last_name]</td>
                            <td>$row[email]</td>
                            <td>$row[phone]</td>
                            <td>$row[gender]</td>
                            <td>$row[birthdate]</td>
                            <td>$row[address1]</td>
                            <td>
                                <a class='btn btn-secondary btm-sm' href='edit.php?id=$row[id]' style='font-size: 0.8rem;'>More</a>
                                <button class='btn btn-danger btn-sm delete-btn' data-id='$row[id]';>Delete</button>
                            </td>
                        </tr>
                        ";
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
    <script>
        const deleteButton = document.querySelectorAll('.delete-btn');

            deleteButton.forEach(btn => {
            btn.addEventListener('click', (e) => {
            const id = e.target.dataset.id;
            
            if (confirm(`Are you sure you want to delete this user?`)) {
                window.location.href = `delete.php?id=${id}`;
            }
            });
        });
    </script>
</body>
</html>

<?php
