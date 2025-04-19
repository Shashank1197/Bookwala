<?php
include_once("db.php");

$password = $_POST["password"];
$username = $_POST["username"];

$password = md5($password);

$sql = "SELECT * FROM `login` WHERE Username = '$username'";
//echo $sql;
$result = mysqli_query($conn,$sql);

if($result){
    $row = $result->fetch_assoc();
    if($row["Password"]==$password){
        header("Location: homepage1.php");
    }else{
        echo "Wrong Password";
    }
    
}else{
    echo "Wrong credentials";
}



?>