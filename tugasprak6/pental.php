<?php
if (!isset($_SESSION['sudahlogin'])){
    header("location: index.php");
}
?>