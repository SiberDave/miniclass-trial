<?php

require_once('ses_start.php');

if (isset($_SESSION['sudahlogin'])){
    unset($_SESSION['sudahlogin']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
        #navbar a{
            text-decoration: none;
            color: seashell;
        }
        #form_reg{
            left: 40%;
            top: 20%;
        }
    </style>
</head>
<body>
    <header>
        <div id="navbar">
            <h1 id="logo">GugelClassroom</h1>
            <ul>
                <li><form method="post">
                    <button type="submit" formaction="register.php" name="registerreg" style="float: right; background-color: gray; border: none; color: white;">Register</button>
                </form></li>
                <li><form method="post">
                    <button type="submit" formaction="index.php" name="loginreg" style="float: right; background-color: gray; border: none; color: white;">Login</button></li>
                </form>
            </ul>
        </div>
        <div style="margin-top: 10%; position: absolute; left: 40%;">
            <form method="post" style="height: auto; padding-top: 2%;">
                <h1 style="text-align: center;">Register</h1>
                <br>
                <div style="float: left;">
                    <p>First Name</p>
                    <input type="text" name="firstname" id="" size="12">
                </div>
                <div style="float: left; margin-left: 9px;">
                    <p>Last Name</p>
                    <input type="text" name="lastname" id="" size="12">
                </div>
                <br> <br>
                <p style="float: none;">Username</p>
                <input type="text" name="username" id="" size="30">
                <p>Password</p>
                <input type="password" name="password" id="" size="30">
                <p>Confirm Password</p>
                <input type="password" name="confirmpass" id="" size="30">
                <p>Role</p>
                <select name="role" style="width: 243px;">
                    <option value="Teacher">Teacher</option>
                    <option value="Student">Student</option>
                </select>
                <br> <br>
                <button type="submit" name="register" formaction="index.php" style="width: 243px; background-color: green; padding: 1%; color: white;">Register</button>
            </form>
        </div>
    </header>
</body>
</html>