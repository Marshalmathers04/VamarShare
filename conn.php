<?php
    session_start();
    $host = "localhost";
    $username = "root";
    $password = "";
    $serverdb = "vamarshare_db";
    $conn = mysqli_connect($host,$username,$password,$serverdb);
    $_SESSION["conn"] = $conn;
    if (!$conn){
        die("asdasd");
    }
    else{
        echo "asd";
    }
?>