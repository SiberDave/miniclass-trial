<?php

require_once('ses_start.php');
require_once('conn.php');
require('pental.php');

$uname = $_SESSION['tempuser'][0]['username'];
$role = $_SESSION['tempuser'][0]['role'];
$datamember = mysqli_query($conn, "SELECT * FROM userkelas WHERE username = '$uname'")->fetch_all(MYSQLI_ASSOC);

$name = $_SESSION['tempuser'][0]['firstname'].' '. $_SESSION['tempuser'][0]['lastname'];
var_dump($name);
$datakelas = mysqli_query($conn, "SELECT * FROM kelas")->fetch_all(MYSQLI_ASSOC);
$datainvite = mysqli_query($conn, "SELECT * FROM invitation WHERE receiver = '$name' ")->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['accept']))
{
    $kodekelas = $_GET['kk'];
    $dupe = false;
    $lock = false;
    foreach($datakelas as $key => $value)
    {
        if ($value['status'] == "close")
        {
            $lock = true;
        }
    }

    foreach($datamember as $k => $v)
    {
        if ($v['username'] == $uname && $v['kodekelas'] == $kodekelas)
        {
            $dupe = true;
        }
    }

    if ($lock == false)
    {
        if ($dupe == false){
            $query = "INSERT INTO userkelas VALUES ('$uname', '$kodekelas', '$role', '$name')";
            $res = mysqli_query($conn, $query);
            echo "<script>alert('Sukses bergabung dengan kelas ini')</script>";
            $query = "DELETE FROM invitation WHERE kodekelas = '$kodekelas' AND receiver = '$name'";
            $res = mysqli_query($conn, $query);
        }
        else if ($dupe == true){
            echo "<script>alert('sudah bergabung dengan kelas ini')</script>";
        }
    }
    else
    {
        echo "<script>alert('kelas dikunci dan tidak bisa join')</script>";
    }

}

if (isset($_POST['decline']))
{
    $query = "DELETE FROM invitation WHERE kodekelas = '$kk', receiver = '$name'";
    $res = mysqli_query($conn, $query);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>
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
        <h1 style="padding-top: 3%; padding-left: 10%;">INBOX</h1>
    </div>
    <section style="width: 100%; display: block; position: absolute; top: 40%;">
    <table id="tabel" style="width: 80%; margin-left: 10%; ">
        <tr>
            <th>Invitation</th>
            <th>Sent At</th>
            <th>Action</th>
        </tr>
        <?php 
        foreach($datainvite as $key => $val)
        {
            foreach($datakelas as $k => $v){
                if ($val['kodekelas'] == $v['kodekelas'])
                {
                    $namakelas = $v['namakelas'];
                }
            }
            $msg = $val['sender']. " invited you to " . $namakelas;
        ?>
        <tr style="text-align: center;">
            <td><?php echo $msg ?></td>
            <td><?php echo $val['sent'] ?></td>
            <td>
                <div style="width: 70%; margin-right: 30%;">
                <form action="inbox.php?kk=<?= $val['kodekelas'] ?>" method="post">
                <button id="buttonsend" name="decline" type="submit" style="float: right; margin-left: 10px; padding: 2px; background-color: red; border-color: red; padding: 5px;"> X </button>
                <button id="buttonclose" name="accept" type="submit" style="float: right; padding: 2px; background-color: green; border-color: green; padding: 5px;"> V </button>
                </form>
                </div>
            </td>
        </tr>
        <?php }?>
    </table>
    </section>
</body>
</html>