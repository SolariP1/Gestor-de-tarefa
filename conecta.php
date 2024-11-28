<?php
$host = "localhost";
$username = "root";
$password = "";
$database = "saep";

$conn = mysqli_connect($host, $username, $password, $database);

if(!$conn){
    die("falhou". mysqli_connect_error());
}

?>