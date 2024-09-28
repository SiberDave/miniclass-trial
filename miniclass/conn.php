<?php

$host = $_ENV["MINICLASS_HOST"];
$password = $_ENV["MINICLASS_PASS"];
$username = $_ENV["MINICLASS_USER"];
$database = $_ENV["MINICLASS_DB"]; // dirubah

$conn = mysqli_connect($host, $username, $password, $database);

if (!$conn){
    die("Connection failed: " . mysqli_connect_error());
    echo "<script>console.log('Error')</script>";

}
else{
    echo "<script>console.log('Sukses')</script>";
}

?>