<?php
require_once('ses_start.php');
require_once('conn.php');
require('pental.php');

$datakelas = mysqli_query($conn, "SELECT * FROM kelas")->fetch_all(MYSQLI_ASSOC);

if (isset($_GET['attr'])){
    foreach ($datakelas as $key => $val){
        if ($val['kodekelas'] == $_GET['attr']){
            $_SESSION['kelasini'] = $datakelas[$key];
        }
    }
}
$ksk = $_SESSION['kelasini']['kodekelas'];
$datamember = mysqli_query($conn, "SELECT * FROM userkelas WHERE kodekelas = '$ksk'")->fetch_all(MYSQLI_ASSOC);
$uname = $_SESSION['tempuser'][0]['username'];
$nama = $_SESSION['tempuser'][0]['firstname'].' '. $_SESSION['tempuser'][0]['lastname'];

    // echo "<pre>";
    // var_dump($datamember);
    // echo "</pre>";

if(isset($_POST['lockclass'])){
    if ($_SESSION['kelasini']['status'] == "open"){
        $_SESSION['kelasini']['status'] = "close";
        $query = "UPDATE kelas 
        SET status = 'close'
        WHERE kodekelas = '$ksk'";
        $res = mysqli_query($conn, $query);
        echo "<script>alert('Class Locked')</script>";
    }
    else if ($_SESSION['kelasini']['status'] == "close"){
        $_SESSION['kelasini']['status'] = "open";
        $query = "UPDATE kelas 
        SET status = 'open'
        WHERE kodekelas = '$ksk'";
        $res = mysqli_query($conn, $query);
        echo "<script>alert('Class Unlocked')</script>";
    }
}


if (isset($_POST['savechanges'])){
    $nk = $_POST['namakelaschange'];
    $lc = $_POST['limitchange'];

    $query = "UPDATE kelas 
    SET namakelas = '$nk',
        capacity = $lc
    WHERE kodekelas = '$ksk'";
    $res = mysqli_query($conn, $query);

    $_SESSION['kelasini']['namakelas'] = $nk;
    $_SESSION['kelasini']['capacity'] = $lc;
    echo "<script>alert('perubahan sukses')</script>";
}

if (isset($_POST['deleteuser'])){
    $isada = false;

    foreach($datamember as $k => $v)
    {
        if ($v['username'] == $_POST['deleteuser'])
        {
            $query = "DELETE FROM userkelas WHERE username = '$v[username]'";
            $res = mysqli_query($conn, $query);
            echo "<script>alert('User sudah dihapus')</script>";
            $datamember = mysqli_query($conn, "SELECT * FROM userkelas WHERE kodekelas = '$ksk'")->fetch_all(MYSQLI_ASSOC);
            $isada = true;
        }
    }

    if (!$isada){
        echo "<script>alert('User tidak ada')</script>";
    }
}

if (isset($_POST['sendinvite']))
{
    $invitexist = false;
    $alreadyinvite = false;
    $isteacher = false;
    $datauser = mysqli_query($conn, "SELECT * FROM user")->fetch_all(MYSQLI_ASSOC);
    $inviteuser = mysqli_query($conn, "SELECT * FROM invitation")->fetch_all(MYSQLI_ASSOC);
    $nama = $_SESSION['tempuser'][0]['firstname'].' '. $_SESSION['tempuser'][0]['lastname'];
    foreach ($datauser as $key => $value)
    {
        if ($value['username'] == $_POST['userinvite'])
        {
            if ($value['role'] == "Teacher"){
                $isteacher = true;
            }
            $invitexist = true;
            foreach ($inviteuser as $k => $v)
            {
                if($v['receiver'] == $_POST['userinvite']){
                    $alreadyinvite = true;
                }
            }
            if($alreadyinvite == false && $isteacher == true){
                $namarec = $value['firstname']. " " . $value['lastname'];
                $query = "INSERT INTO invitation (kodekelas, sender, receiver) VALUES ('$ksk', '$nama', '$namarec')";
                $res = mysqli_query($conn, $query);
                echo "<script>alert('Invitation sent')</script>";
            }
            else if ($alreadyinvite == true)
            {
                echo "<script>alert('already invited')</script>";
            }
        }
    }

    if ($invitexist == false){
        echo "<script>alert('User tidak ada')</script>";
    }

    if ($isteacher == false){
        echo "<script>alert('User yang diinvite harus teacher')</script>";
    }
}

if (isset($_POST['posting']))
{
    $query = "INSERT INTO post (kodekelas, usernamec, isi, fullname) VALUES ('$ksk', '$uname', '$_POST[textposting]', '$nama')";
    $res = mysqli_query($conn, $query);
    echo "<script>alert('Post sudah ditambahkan')</script>";
}

if (isset($_POST['commenting']))
{
    $codekom = $_GET['ckom'];
    $query = "INSERT INTO comment (idkomentar, kodekelas, usernamecm, isi, fullname) VALUES ('$codekom' ,'$ksk', '$uname', '$_POST[textcomment]', '$nama')";
    $res = mysqli_query($conn, $query);
    echo "<script>alert('Komentar sudah ditambahkan')</script>";
}

if (isset($_POST['delpos']))
{
    $codekom = $_GET['ckom'];
    $query = "DELETE FROM post WHERE kodekomentar = '$codekom' ";
    $res = mysqli_query($conn, $query);
    $datapost = mysqli_query($conn, "SELECT * FROM post WHERE kodekelas = '$ksk'")->fetch_all(MYSQLI_ASSOC);
    $query = "DELETE FROM comment WHERE idkomentar = '$codekom' ";
    $res = mysqli_query($conn, $query);
    $datacomment = mysqli_query($conn, "SELECT * FROM comment WHERE kodekelas = '$ksk'")->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>class detail</title>
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

        /* Style the tab */
        .tab {
        overflow: hidden;
        background-color: #f1f1f1;
        top: 29%;
        position: absolute;
        width: 80%;
        margin-left: 10%;
        margin-right: 10%;
        }

        /* Style the buttons that are used to open the tab content */
        .tab button {
        background-color: inherit;
        float: left;
        border: none;
        outline: none;
        cursor: pointer;
        padding: 14px 16px;
        transition: 0.3s;
        }

        /* Change background color of buttons on hover */
        .tab button:hover {
        background-color: #ddd;
        }

        /* Create an active/current tablink class */
        .tab button.active {
        border: 1px solid #ccc;
        border-bottom: none;
        }

        /* Style the tab content */
        .tabcontent {
        display: none;
        position: absolute;
        top: 36.5%;
        width: 80%;
        margin-left: 10%;
        margin-right: 10%;
        }

        /* modal */

        .modal{
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.4);
        }

        .content{
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 30%;
            height: auto;
            background-color: white;
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
        <h1 style="padding-top: 2%; padding-left: 10%;"><?php echo $_SESSION['kelasini']['namakelas'] ?></h1>
    </div>
    <!-- Tab links -->
    <div class="tab">
    <button class="tablinks" onclick="openCity(event, 'Home')">Home</button>
    <button class="tablinks" onclick="openCity(event, 'Participants')">Participants</button>
    <?php if ($_SESSION['tempuser'][0]['role'] == "Teacher") { ?>
    <button class="tablinks" onclick="openCity(event, 'Settings')">Settings</button>
    <?php } ?>
    </div>

    <!-- Tab content -->
    <div id="Home" class="tabcontent">
        <h1 style="margin-left: 10%; margin-top: 5%;">Selamat Datang, <?php echo $_SESSION['tempuser'][0]['firstname'].' '.$_SESSION['tempuser'][0]['lastname'] ?></h1>
        <br><br>
        <?php if ($_SESSION['tempuser'][0]['role'] == "Teacher") 
        { ?>
        <div style="width: 100%; height: auto; position: absolute; border: 1px solid grey; padding: 15px;">
            <h2>Post News about your class</h2>
            <br>
            <form action="" method="post" style="width: 100%; height: auto;">
                <textarea style="float: left;" name="textposting" id="" cols="120" rows="4" placeholder="Type Something..."></textarea>
                <button name="posting" type="submit" style="float: left; margin-left: 10%; margin-top: 1.5%; padding: 5px; background-color: aqua; color: white;">SENT</button>
            </form>
        </div>
        <?php } ?>
        <section style="width: 100%; height: auto; position: absolute; top: 300px;">
            <?php
            $datapost = mysqli_query($conn, "SELECT * FROM post WHERE kodekelas = '$ksk'")->fetch_all(MYSQLI_ASSOC);
            foreach ($datapost as $k => $v)
            {
                if ($v['kodekelas'] == $ksk)
                {
            ?>
            <div style="width: 100%; height: auto; position: relative; display: inline-block; border: 1px solid grey; padding: 15px;" >
                <div style="width: 100%; height: auto; position: absolute;">
                    <h2 style="float: left;"><?php echo $v['fullname']  ?></h2>
                    <h3 style="float: left; margin-left: 10px; margin-top: 5px; color: grey;"><?php echo $v['timestamp']  ?></h3>
                </div>
                <br>
                <div style="width: 100%; height: auto; position: relative; word-wrap: break-word; display: inline-block;">
                    <div style="width: 60%; height: auto; position: relative; float: left;">
                        <br>
                        <?php echo $v['isi']  ?>
                    </div>
                    <?php if ($nama == $v['fullname']){ ?>
                        <form action="classdetail.php?ckom=<?= $v['kodekomentar'] ?>&attr=<?= $_GET['attr'] ?>" method="post">
                        <button name="delpos" type="submit" style="float: right; margin-left: 10%; padding: 5px; background-color: red; color: white;">DEL</button>
                        </form>
                    <?php } ?>
                </div>
                <br> <br>
                <div style="width: 100%; height: auto; position: relative ">
                    <form action="classdetail.php?ckom=<?= $v['kodekomentar'] ?>&attr=<?= $_GET['attr'] ?>" method="post" style="width: 100%; height: auto;">
                        <textarea style="float: left;" name="textcomment" id="" cols="120" rows="4" placeholder="Type Something..."></textarea>
                        <button name="commenting"  type="submit" style="float: right; margin-left: 10%; margin-top: 1.5%; padding: 5px; background-color: aqua; color: white;">SENT</button>
                    </form>
                </div>
                <?php
                $datacomment = mysqli_query($conn, "SELECT * FROM comment WHERE kodekelas = '$ksk'")->fetch_all(MYSQLI_ASSOC);
                foreach ($datacomment as $key => $val)
                {
                    if ($val['idkomentar'] == $v['kodekomentar']){
                ?>
                    <div style="width: 95%; height: auto; position: relative; display: inline-block; border: 1px solid grey; padding: 15px; margin-top: 10px; background-color: grey;" >
                        <div style="width: 100%; height: auto; position: absolute;">
                            <h3 style="float: left;"><?php echo $val['fullname']  ?></h3>
                            <h4 style="float: left; margin-left: 10px; margin-top: 5px; color: grey;"><?php echo $v['timestamp']  ?></h4>
                        </div>
                        <br>
                        <div style="width: 60%; height: auto; position: relative; float: left;">
                            <br>
                            <?php echo $val['isi']  ?>
                        </div>
                    </div>
                <?php
                    }
                } ?>
            </div>
            <br><br>
            <?php
                }
            } 
            ?>
        </section>
    </div>

    <div id="Participants" class="tabcontent">
        <div style="width: 100%; padding: 5px;">
            <?php if ($_SESSION['tempuser'][0]['username'] == $_SESSION['kelasini']['UMN'] && $_SESSION['tempuser'][0]['role'] == "Teacher"){ ?>
                <button type="submit" id="invite" style="margin-top: 2%; margin-bottom: 2%; padding: 10px; background-color: green; color: white;" >Add Teacher</button>
                <div id="classmodal" class="modal">
                    <div class="content">
                    <h1 style="padding-bottom: 10px; float: left;" >Add New Teacher</h1>
                    <button type="submit" style="border: none; float: right; padding: 5px;" id="close">x</button>
                    <br><br><br>
                    <form action="" method="post">
                        <h2 style="float: none;">Username</h1>
                        <input type="text" name="userinvite" id="">
                        <div style="width: 100%; height: 10%; padding: 10px;">
                            <button id="buttonsend" name="sendinvite" type="submit" style="float: right; margin-left: 10px; padding: 2px; background-color: aqua; border-color: aqua;">send invitations</button>
                            <button id="buttonclose" name="close" type="submit" style="float: right; padding: 2px; background-color: grey; border-color: grey;">close</button>
                        </div>
                    </form>
                    </div>
                </div>
            <?php } ?>
            <p style="border-bottom: 1px solid #ccc;">Teacher</p>
            <?php foreach($datamember as $key => $value){
                if ($value['role'] == "Teacher"){ ?>
                    <p><?= $value['nama'] ?></p>
            <?php    }
            } ?>
            <p style="border-bottom: 1px solid #ccc;">Member</p>
            <?php foreach($datamember as $key => $value){
                if ($value['role'] == "Student"){ ?>
                    <div style="margin-bottom: 25px; width: 100%;">
                        <p style="float: left; width: auto;"><?= $value['nama'] ?></p><br>
                        <?php if ($_SESSION['tempuser'][0]['role'] == "Teacher") { ?>
                            <form method="post" style="float: right;" >
                            <button type="submit" onclick="return confirm('Apa anda yakin untuk menghapus murid')" value="<?= $value['username'] ?>" name="deleteuser" style="background-color: red;">Delete</button>
                            </form>
                        <?php } ?>
                    </div>
            <?php    }
            } ?>
        </div>
    </div>
    <div id="Settings" class="tabcontent">
        <div style="width: 100%; padding: 5px;" >
            <form method="post">
                <p>Kode Kelas : </p>
                <input type="text" size="148" readonly value="<?= $_SESSION['kelasini']['kodekelas'] ?>">
                <p>Nama Kelas: </p>
                <input type="text" size="148" name="namakelaschange" value="<?= $_SESSION['kelasini']['namakelas'] ?>">
                <p>Limit Peserta</p>
                <input type="text" size="148" name="limitchange" value="<?= $_SESSION['kelasini']['capacity'] ?>">
                <br> <br>
                <button type="submit" name="savechanges" style="background-color: green; color: white; padding: 5px; border-radius:10%; border-color: green;">Save Changes</button>
                <?php if ($_SESSION['kelasini']['status'] == "close"){ ?>
                    <button type="submit" name="lockclass" style="background-color: yellow; color: white; padding: 5px; border-radius:10%; border-color: yellow;">Unlock Class</button>
                <?php   } else{ ?>
                    <button type="submit" name="lockclass" style="background-color: yellow; color: white; padding: 5px; border-radius:10%; border-color: yellow;">Lock Class</button>
                <?php } ?>
                <button type="submit" name="deleteclass" onclick="return confirm('Apa anda yakin untuk menghapus kelas')" formaction="dashboard.php?id=<?= $_GET['attr'] ?>" style="background-color: red; color: white; padding: 5px; border-radius:10%; border-color: red;">Delete Class</button>
            </form>
            
        </div>
    </div>
    <script>
        function openCity(evt, cityName) {
        // Declare all variables
        var i, tabcontent, tablinks;

        // Get all elements with class="tabcontent" and hide them
        tabcontent = document.getElementsByClassName("tabcontent");
        for (i = 0; i < tabcontent.length; i++) {
            tabcontent[i].style.display = "none";
        }

        // Get all elements with class="tablinks" and remove the class "active"
        tablinks = document.getElementsByClassName("tablinks");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }

        // Show the current tab, and add an "active" class to the button that opened the tab
        document.getElementById(cityName).style.display = "block";
        evt.currentTarget.className += " active"
        }

        var modal = document.getElementById('classmodal');
        var button = document.getElementById('invite');
        var cancel = document.getElementById('buttonclose');
        var send = document.getElementById('buttonsend');
        var closebutton = document.getElementById('close');

        button.onclick = function(){
            modal.style.display = "block";
        }

        closebutton.onclick = function(){
            modal.style.display = "none";
        }

        cancel.onclick = function(){
            modal.style.display = "none";
        }

        send.onclick = function(){
            modal.style.display = "none";
        }

        window.onclick = function(event){
            if (event.target == modal){
                modal.style.display = "none";
            }
        }
    </script>
    </body>
    </html>