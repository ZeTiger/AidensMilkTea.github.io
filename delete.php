<?php
if (isset($_GET["id"])){
        $id = $_GET["id"];

        $servername = "localhost";
        $username = "root";
        $password = "";
        $database = "client_registration";

        $con = new mysqli('localhost','root','','client_registration');

        $sql = "DELETE FROM clients WHERE id=$id";
        $con -> query($sql);
    }
    mysqli_close($con);
    header("location:AdminModule.php");
    exit;
?>