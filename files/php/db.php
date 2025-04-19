<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "bookwala";

$conn = mysqli_connect($servername,$username,$password,$db);

if(!$conn){
    echo "Not connected";
}



?>