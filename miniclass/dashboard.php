<?php
require_once('ses_start.php');
require_once('conn.php');


$data = $_POST;
$datauser = mysqli_query($conn, "SELECT * FROM user")->fetch_all(MYSQLI_ASSOC);
$datakelas = mysqli_query($conn, "SELECT * FROM kelas")->fetch_all(MYSQLI_ASSOC); 

if (isset($data['login'])){
    $isbenar = false;

    foreach ($datauser as $key => $value)
    {
        if ($value['username'] == $data['username'] && $value['password'] == $data['password'])
        {
            $isbenar = true;
            $_SESSION["tempuser"] = mysqli_query($conn, "SELECT * FROM user WHERE username LIKE '$value[username]' ")->fetch_all(MYSQLI_ASSOC);
        }
    }


    if ($isbenar){
        echo "<script>alert('sukses login')</script>";
        $_SESSION['sudahlogin'] = 1;
    }
    else{
        header('Location: index.php?err=c');
    }

}

else{
    require('pental.php');
}

$uname = $_SESSION['tempuser'][0]['username'];
$role = $_SESSION['tempuser'][0]['role'];
$name = $_SESSION['tempuser'][0]['firstname'].' '. $_SESSION['tempuser'][0]['lastname'];
$datamember = mysqli_query($conn, "SELECT * FROM userkelas WHERE username = '$uname'")->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['join'])){
    $kodekelas = $_POST['kodekelas'];
    $ada = false;
    $dupe = false;
    $lock = false;
    $penuh = false;
    $jumlah = mysqli_query($conn, "SELECT COUNT(*) FROM userkelas WHERE kodekelas = '$kodekelas'")->fetch_all(MYSQLI_ASSOC);
    var_dump($jumlah);
    foreach($datakelas as $key => $value)
    {
        if ($value['kodekelas'] == $_POST['kodekelas'])
        {
            $ada = true;
        }

        if ($value['status'] == "close")
        {
            $lock = true;
        }
        if ($jumlah >= $value['capacity']){
            $penuh = true;
        }
    }

    foreach($datamember as $k => $v)
    {
        if ($v['username'] == $_SESSION["tempuser"][0]["username"])
        {
            $dupe = true;
        }
    }

    if ($penuh == false)
    {
        if ($lock == false)
        {
            if ($ada == true && $dupe == false){
                $query = "INSERT INTO userkelas VALUES ('$uname', '$kodekelas', '$role', '$name')";
                $res = mysqli_query($conn, $query);
                echo "<script>alert('Sukses bergabung dengan kelas ini')</script>";
            }
            else if ($ada == true && $dupe == true){
                echo "<script>alert('sudah bergabung dengan kelas ini')</script>";
            }
            else if ($ada == false){
                echo "<script>alert('tidak ada kelas yang dimaksud')</script>";
            }
        }
        else
        {
            echo "<script>alert('kelas dikunci dan tidak bisa join')</script>";
        }   
    }
    else{
        echo "<script>alert('kelas penuh')</script>";
    }
}

if (isset($_POST['deleteclass'])){

    $query = "DELETE FROM userkelas WHERE kodekelas = '$_GET[id]'";
    $res = mysqli_query($conn, $query);

    $query = "DELETE FROM kelas WHERE kodekelas = '$_GET[id]'";
    $res = mysqli_query($conn, $query);

    unset($_SESSION['datakelas'][$_GET['id']]);
    echo "<script>alert('Data terhapus')</script>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            <h1 style="padding-top: 1%; padding-left: 10%;">Selamat Datang, <?php echo $_SESSION["tempuser"][0]['firstname'].' '. $_SESSION["tempuser"][0]['lastname']; ?></h1>
        </div>
        <?php if ($_SESSION["tempuser"][0]['role'] == "Student"){ ?>
            <div style="margin-top: 12%; position: absolute; width: 100%; height: 50%; left: 10%;">
                    <form method="post" style="padding-top: 5%;">
                        <h2>Join a Class</h2>
                        <input type="text" name="kodekelas" id="">
                        <br> <br>
                        <button type="submit" name="join" formaction="dashboard.php" style="background-color: blue; padding: 5px; border-radius:10%; padding-left: 20px; padding-right: 20px; color: white; ">Join</button>
                    </form>
            </div>
        <?php } ?>
    </header>
</body>
</html>