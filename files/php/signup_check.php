<?php
include_once("db.php");
$username = $_POST["username"];
$email = $_POST["email"];
$password = $_POST["password"];
$cpassword = $_POST["cpassword"];
$password = md5($password);


    $sql = "INSERT INTO `login`(`sno`, `Username`, `Email`, `Password`) VALUES (NULL,'$username','$email','$password')";
    $result= mysqli_query($conn,$sql);
    if($result==1){
        echo "Signup successfull";
    }


?>