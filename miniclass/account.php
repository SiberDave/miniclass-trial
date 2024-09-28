<?php
    require_once('ses_start.php');
    require_once('conn.php');
    require('pental.php');

    if (isset($_POST['changepassword'])){
        $mhs = $_SESSION['tempuser'][0]['username'];
        $pass = $_POST['passwordacc'];
        $query = "UPDATE user 
        SET 
            password = '$pass'
        WHERE
            username = '$mhs'";
        $res = mysqli_query($conn, $query);

        $_SESSION['tempuser'][0]['password'] = $_POST['passwordacc'];
        echo "<script type='text/javascript'>alert('password berhasil diganti');</script>";
    }

    if (isset($_POST['savechanges'])){
        $mhs = $_SESSION['tempuser'][0]['username'];
        $username = $_POST['usernameacc'];
        $pass = $_POST['passwordacc'];
        $fn = $_POST['firstnameacc'];
        $ln = $_POST['lastnameacc'];
        $query = "UPDATE user 
        SET 
            firstname = '$fn',
            lastname = '$ln',
            username = '$username',
            password = '$pass'
        WHERE
            username = '$mhs'";
        $res = mysqli_query($conn, $query);

        $_SESSION['tempuser'][0]['firstname'] = $_POST['firstnameacc'];
        $_SESSION['tempuser'][0]['lastname'] = $_POST['lastnameacc'];
        $_SESSION['tempuser'][0]['username'] = $_POST['usernameacc'];
        $_SESSION['tempuser'][0]['password'] = $_POST['passwordacc'];
        echo "<script type='text/javascript'>alert('info akun berhasil diganti');</script>";
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account</title>
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
        <nav id="navbar">
            <h1 id="logo">GugelClassroom</h1>
            <ul>
                <li><form action="" method="post">
                    <button type="submit" formaction="inbox.php" name="class" style="float: right; background-color: gray; border: none; color: white;">Inbox</button></li>
                </form>
                <li><form action="" method="post">
                    <button type="submit" formaction="account.php" name="Account" style="float: right; background-color: gray; border: none; color: white;">Account</button>
                </form></li>
                <li><form action="" method="post">
                    <button type="submit" formaction="class.php" name="class" style="float: right; background-color: gray; border: none; color: white;">My classes</button></li>
                </form>
            </ul>
        </nav>
        <div style="margin-top: 4%; position: absolute; width: 100%; background-color: gainsboro; height: 20%;">
            <h1 style="padding-top: 1%; padding-left: 10%;">My Account</h1>
        </div>
        <div style="margin-top: 12%; position: absolute; width: 100%; height: 50%; left: 10%;">
                <form method="post" style="height: auto; padding-top: 2%;">
                        <br>
                        <div style="float: left;">
                            <p>First Name</p>
                            <input type="text" name="firstnameacc" id="" size="12" value="<?php echo $_SESSION['tempuser'][0]['firstname'] ?>">
                        </div>
                        <div style="float: left; margin-left: 9px;">
                            <p>Last Name</p>
                            <input type="text" name="lastnameacc" id="" size="12" value="<?php echo $_SESSION['tempuser'][0]['lastname'] ?>">
                        </div>
                        <br> <br>
                        <p style="float: none;">Username</p>
                        <input type="text" name="usernameacc" id="" size="30" value="<?php echo $_SESSION['tempuser'][0]['username'] ?>">
                        <p>Password</p>
                        <input type="text" name="passwordacc" id="" size="30" value="<?php echo $_SESSION['tempuser'][0]['password'] ?>">
                        <br>
                        <button type="submit" name="changepassword" style="background-color: yellow; padding: 1px; border-radius:10%; border-color: yellow;">Change Password</button>
                        <br> <br>
                        <button type="submit" name="savechanges" style="background-color: green; color: white; padding: 5px; border-radius:10%; border-color: green;">Save Changes</button>
                        <button type="submit" name="logout" formaction="index.php" style="background-color: red; color: white; padding: 5px; border-radius:10%; border-color: red;">Log out</button>
                </form>
        </div>
</body>
</html>