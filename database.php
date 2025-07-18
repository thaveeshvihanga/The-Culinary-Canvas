<?php

$servername ="localhost";
$username ="root";
$password ="";
$dbname="culinary_canvas";


$conn = new mysqli($servername,$username,$password,$dbname);

if($conn->connect_error){
    die("Connection failed :".$conn->connect_error);
}else{
    echo"Successfully connected";
}

?>