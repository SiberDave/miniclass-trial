<?php

require_once('ses_start.php');
require_once('conn.php');

$data = $_POST;
$datauser = mysqli_query($conn, "SELECT * FROM user")->fetch_all(MYSQLI_ASSOC);


if (isset($_SESSION['tempuser'])){
    unset($_SESSION['tempuser']);
}

if (isset($_GET['err'])){
    if ($_GET['err'] == 'c'){
        echo "<script>alert('akun tidak ditemukan')</script>";
    }
}

if (isset($_SESSION['sudahlogin'])){
    unset($_SESSION['sudahlogin']);
}


if(isset($data['register'])){
    if (trim($data['firstname']) != ""){
        if (trim($data['username']) != ""){
            $double = false;
            foreach ($datauser as $key => $val)
            {
                if ($val['username'] == $data['username'])
                {
                    $double = true;
                }
            }

            if ($double == false)
            {
                if (trim($data['password']) != ""){
                    if (trim($data['confirmpass']) != ""){
                        if($data['password'] == $data['confirmpass']){
                            $query = "INSERT INTO user VALUES ('$data[firstname]', '$data[lastname]', '$data[username]', '$data[password]' , '$data[role]')";
                            $res = mysqli_query($conn, $query);
                            echo "<script type='text/javascript'>alert('registrasi berhasil');</script>";
                        }
                        else{
                            echo '<script>alert("your password isnt match")</script>';
                            header('Location : register.php');
                        }
                    }
                    else{
                        echo '<script>alert("field confirm password tidak boleh kosong")</script>';
                        header('Location : register.php');
                    }
                }
                else{
                    echo '<script>alert("Username sudah terdaftar")</script>';
                    header('Location : register.php');
                }
            }
            else
            {
                echo '<script>alert("field password tidak boleh kosong")</script>';
                header('Location : register.php');
            }
        }
        else{
            echo '<script>alert("field username tidak boleh kosong")</script>';
            header('Location : register.php');
        }
    }
    else{
        echo '<script>alert("field nama tidak boleh kosong")</script>';
        header('Location : register.php');
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        *{
            margin: 0;
            padding: 0;
        }
        header{
            height: 100 vh;
            width: 100 vw;
        }
        #navbar{
            float: left;
            height: auto;
            width: 100%;
            background-color: gray;
            display: block;
        }
        #logo{
            margin-left: 5%;
            width: 25%;
            float: left;
            padding: 1%;
        }
        #navbar ul{
            width: 25%;
            float: right;
            padding: 1%;
        }
        #navbar ul li{
            float: right;
            padding-right: 5%;
            width: 25%;
            overflow: hidden;
        }
    </style>
</head>
<body> 
    <header>
        <nav id="navbar">
            <h1 id="logo">GugelClassroom</h1>
            <ul>
                <li><form action="" method="post">
                    <button type="submit" formaction="register.php" name="registerreg" style="float: right; background-color: gray; border: none; color: white;">Register</button>
                </form></li>
                <li><form action="" method="post">
                    <button type="submit" formaction="index.php" name="loginreg" style="float: right; background-color: gray; border: none; color: white;">Login</button></li>
                </form>
            </ul>
        </nav>
        <div style="margin-top: 10%; position: absolute; left: 40%;">
            <form method="post" style="height: auto; padding-top: 2%;">
                <h1 style="text-align: center;">Login</h1>
                <br>
                <p style="float: none;">Username</p>
                <input type="text" name="username" id="" size="30">
                <p>Password</p>
                <input type="password" name="password" id="" size="30">
                <br> <br>
                <button type="submit" name="login" formaction="dashboard.php" style="width: 243px; background-color: green; padding: 1%; color: white;">Login</button>
            </form>
        </div>
    </header>
</body>
</html>