<?php
    require_once('ses_start.php');
    require_once('conn.php');
    require('pental.php');

    $uname = $_SESSION['tempuser'][0]['username'];
    $role = $_SESSION['tempuser'][0]['role'];
    $name = $_SESSION['tempuser'][0]['firstname'].' '. $_SESSION['tempuser'][0]['lastname'];

    $indexkelas = 0;
    $datakelas = mysqli_query($conn, "SELECT * FROM kelas")->fetch_all(MYSQLI_ASSOC);
    $datamember = mysqli_query($conn, "SELECT * FROM userkelas WHERE username = '$uname'")->fetch_all(MYSQLI_ASSOC);
    // echo "<pre>";
    // var_dump($datakelas);
    // echo "</pre>";

    if (isset($_POST['savechange'])){
        $kodekelas = $_POST['kodekelas'];
        $namakelas = $_POST['namakelas'];
        $time = $_POST['hari'] . ', ' . $_POST['starthour']. ' - ' . $_POST['endhour'];
        $status = "open";
        $capacity = 20;

        $query = "INSERT INTO kelas VALUES ('$kodekelas', '$namakelas', '$time', '$status' , $capacity, '$name', '$uname')";
        $res = mysqli_query($conn, $query);

        $query = "INSERT INTO userkelas VALUES ('$uname', '$kodekelas', '$role', '$name')";
        $res = mysqli_query($conn, $query);    

        echo "<script type='text/javascript'>alert('kelas berhasil dibuat');</script>";
    }

    
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Class</title>
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
            background-color: white;
        }

        .kotak{
            width: 20%;
            margin: 5%;
            height: auto;
            float: left;
            border: 2px solid lightgray;
        }

        .picpemanis{
            width: 100%;
            height: auto;
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
        <h1 style="padding-top: 2%; padding-left: 10%;">My Class</h1>
    </div>
    <br>
    <?php if($_SESSION['tempuser'][0]['role'] == "Teacher"){ ?>
        <div style="top: 26%; position: absolute; height: auto; width: 100%;">
        <button type="submit" id="addclass" style="margin-top: 5%; margin-left: 5%; padding: 10px; background-color: green; color: white;" >Add New Class</button>
        </div>
    <?php } ?>
    <div id="classmodal" class="modal">
        <div class="content">
            <div style="width: 100%; height: 10%;">
                <h2 style="padding-bottom: 10px; float: left;" >Add Class</h2>
                <button type="submit" style="border: none; float: right; padding: 5px;" id="close">X</button>
            </div>
            <br><br>
            <form method="post" style="float: none;">
                <p>kode kelas</p>
                <input type="text" name="kodekelas" id="kode" readonly >
                <p>Nama Kelas</p>
                <input type="text" name="namakelas" id="">
                <p>jam</p>
                <div style="width: 100%;">
                    <select name="hari" id="" style="width: 90px;">
                        <option value="senin">Senin</option>
                        <option value="selasa">Selasa</option>
                        <option value="rabu">Rabu</option>
                        <option value="kamis">Kamis</option>
                        <option value="jumat">Jumat</option>
                        <option value="sabtu">Sabtu</option>
                        <option value="minggu">Minggu</option>
                    </select>
                    <select name="starthour" id="" style="width: 90px;">
                        <option value="08.00">08.00</option>
                        <option value="09.00">09.00</option>
                        <option value="10.00">10.00</option>
                        <option value="11.00">11.00</option>
                        <option value="12.00">12.00</option>
                        <option value="13.00">13.00</option>
                        <option value="14.00">14.00</option>
                        <option value="15.00">15.00</option>
                        <option value="16.00">16.00</option>
                    </select>
                    <select name="endhour" id="" style="width: 90px;">
                        <option value="08.00">08.00</option>
                        <option value="09.00">09.00</option>
                        <option value="10.00">10.00</option>
                        <option value="11.00">11.00</option>
                        <option value="12.00">12.00</option>
                        <option value="13.00">13.00</option>
                        <option value="14.00">14.00</option>
                        <option value="15.00">15.00</option>
                        <option value="16.00">16.00</option>
                    </select>
                    <br> <br>
                    <div style="width: 100%; height: 10%; padding: 10px;">
                        <button id="buttonsave" name="savechange" type="submit" style="float: right; margin-left: 10px; padding: 2px; background-color: aqua; border-color: aqua;">save changes</button>
                        <button id="buttonclose" name="close" type="submit" style="float: right; padding: 2px; background-color: grey; border-color: grey;">close</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <section style="width: 100%; height: auto; position: absolute; top: 45%;">
        

        <?php
        foreach ($datakelas as $key => $value)
        {
            foreach ($datamember as $k => $val)
            {
                if ($value['kodekelas'] == $val['kodekelas'] && $val['username'] == $uname)
                {
        ?>

                <div class="kotak">
                    <div class="picpemanis">
                        <img src="headerclass.jpg" style="height: 100%; width: 100%;">
                    </div>
                    <div style="width: 100%; height: auto; padding: 10px;" >
                        <h3><?php echo $value['namakelas'] ?></h3>
                        <p><?php echo $value['Maker'] ?></p>
                        <p><?php echo $value['waktu'] ?></p>
                        <form method="post">
                            <button type="submit" formaction="classdetail.php?attr=<?= $value['kodekelas'] ?>" name="view" style="padding: 5px; color: white; background-color: skyblue; border: none; margin-top: 5px;">View</button>
                        </form>
                    </div>
                </div>

        <?php
                }
            }
        }
        ?>
    </section>
    
    <script>
        var modal = document.getElementById('classmodal');
        var cancel = document.getElementById('buttonclose');
        var save = document.getElementById('buttonsave');
        var button = document.getElementById('addclass');
        var closebutton = document.getElementById('close');

        button.onclick = function(){
            modal.style.display = "block";
            document.getElementById('kode').value = randomstring();
        }

        closebutton.onclick = function(){
            modal.style.display = "none";
        }

        save.onclick = function(){
            modal.style.display = "none";
        }

        cancel.onclick = function(){
            modal.style.display = "none";
        }

        window.onclick = function(event){
            if (event.target == modal){
                modal.style.display = "none";
            }
        }

        function randomstring() {
        classcode = "";
        var char = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        for (let i = 0; i < 5; i++) {
            classcode += char.charAt(Math.floor(Math.random() * char.length));
        }
        return classcode;
        }
        
    </script>
</body>
</html>