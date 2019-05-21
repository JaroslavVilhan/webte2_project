<?php
require_once('config.php');

if(isset($_GET['f'])){

    $mysqli = new mysqli($hostname, $username, $password, $dbname2);
    if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
    $mysqli->query("SET NAMES 'utf8'");

    switch ($_GET['f']){
        case 'processPointsFromAdmin':{
            if(isset($_POST['team']) && isset($_POST['tableName']) && isset($_POST['value'])) {
                $tableName = $_POST['tableName'];
                $team = $_POST['team'];
                $teamPoints = $_POST['value'];

                $sql3="UPDATE `$tableName` SET `TeamPoints` = '$teamPoints' WHERE `Team` = '$team'";
                if ($mysqli->query($sql3) === FALSE) {
                    die();
                }
                echo "Body boli vložené.\nThe points have been added.";
            }
            break;
        }

        case 'processPointsFromStudent':{
            if(isset($_POST['id']) && isset($_POST['tableName']) && isset($_POST['value'])) {
                $tableName = $_POST['tableName'];
                $id = $_POST['id'];
                $studentPoints = $_POST['value'];

                $sql3="UPDATE `$tableName` SET `StudentPoints` = '$studentPoints' WHERE `ID` = '$id'";
                if ($mysqli->query($sql3) === FALSE) {
                    die();
                }
                echo "";
            }
            break;
        }

        case 'setAgreeFromAdmin':{
            if(isset($_POST['team']) && isset($_POST['tableName']) && isset($_POST['state'])) {
                $tableName = $_POST['tableName'];
                $team = $_POST['team'];
                $state = $_POST['state'];

                $sql3="UPDATE `$tableName` SET `AdminAgree` = '$state' WHERE `Team` = '$team'";
                if ($mysqli->query($sql3) === FALSE) {
                    die();
                }
                echo "";
            }
            break;
        }

        case 'setAgreeFromStudent':{
            if(isset($_POST['id']) && isset($_POST['tableName']) && isset($_POST['state'])) {
                $tableName = $_POST['tableName'];
                $id = $_POST['id'];
                $state = $_POST['state'];

                $sql3="UPDATE `$tableName` SET `StudentAgree` = '$state' WHERE `ID` = '$id'";
                if ($mysqli->query($sql3) === FALSE) {
                    die();
                }
                echo "";
            }
            break;
        }

        case 'getAgreeFromAdmin':{
            if(isset($_GET['team']) && isset($_GET['table']) && isset($_GET['lang'])) {
                $tableName = $_GET['table'];
                $team = $_GET['team'];
                $lang = $_GET['lang'];


                $sql = "SELECT `AdminAgree` FROM `$tableName` WHERE `Team` = '$team'";
                $result = $mysqli->query($sql);
                if ($result === FALSE) {
                    die();
                }
                $obj = $result->fetch_object();
                $data = $obj->AdminAgree;
                if ($lang == 'en' && $data == 'Y') {
                    echo '<i class="far fa-thumbs-up"></i> Admin agree with the students divided points';
                } else if ($lang == 'en' && $data == 'N') {
                    echo '<i class="far fa-thumbs-down"></i> Admin don\'t agree with the students divided points';
                } else if ($lang == 'sk' && $data == 'Y') {
                    echo '<i class="far fa-thumbs-up"></i> Admin súhlasí z rozdelením bodov medzi študentami';
                } else if ($lang == 'sk' && $data == 'N') {
                    echo '<i class="far fa-thumbs-down"></i> Admin nesúhlasí z rozdelením bodov medzi študentami';
                } else{
                    echo ' ';
                }
            }
            break;
        }

        case 'adminCheckFilledCells':{
            if(isset($_POST['team']) && isset($_POST['tableName'])) {
                $tableName = $_POST['tableName'];
                $team = $_POST['team'];
                $filled = 'yes';

                $sql = "SELECT `StudentPoints`, `StudentAgree` FROM `$tableName` WHERE `Team` = '$team'";
                $result = $mysqli->query($sql);
                if ($result === FALSE) {
                    die();
                }
                while($obj = $result->fetch_object()){
                    if($obj->StudentPoints == -1 || $obj->StudentAgree == '-'){
                        $filled = 'no';
                    }
                }
                echo $filled;
            }
            break;
        }

        case 'studentCheckFilledCells':{
            if(isset($_POST['team']) && isset($_POST['tableName'])) {
                $tableName = $_POST['tableName'];
                $team = $_POST['team'];
                $filled = 'yes';

                $sql = "SELECT `StudentPoints`, `TeamPoints` FROM `$tableName` WHERE `Team` = '$team'";
                $result = $mysqli->query($sql);
                if ($result === FALSE) {
                    die();
                }
                $teamPoints ="-1";
                $sum=0;
                while($obj = $result->fetch_object()){
                    if($teamPoints == '-1'){
                        $teamPoints = $obj->TeamPoints;
                    }
                    if($obj->StudentPoints == -1 ){
                        $filled = 'no';
                        break;
                    }
                    $sum += $obj->StudentPoints;
                }
                if($sum != $teamPoints){
                    $filled = 'no';
                }
                echo $filled;
            }
            break;
        }

        case 'getStudentAgree':{
            if(isset($_POST['id']) && isset($_POST['tableName'])) {
                $tableName = $_POST['tableName'];
                $id = $_POST['id'];
                $filled = 'yes';

                $sql = "SELECT `StudentAgree` FROM `$tableName` WHERE `ID` = '$id'";
                $result = $mysqli->query($sql);
                if ($result === FALSE) {
                    die();
                }
                $obj = $result->fetch_object();
                if($obj->StudentAgree == '-'){
                    $filled = 'no';
                }
                echo $filled;
            }
            break;
        }

        case 'getStudentAgreeToAdmin':{
            if(isset($_GET['id']) && isset($_GET['table'])) {
                $tableName = $_GET['table'];
                $id = $_GET['id'];

                $sql = "SELECT `StudentAgree` FROM `$tableName` WHERE `ID` = '$id'";
                $result = $mysqli->query($sql);
                if ($result === FALSE) {
                    die();
                }
                $obj = $result->fetch_object();
                $data = $obj->StudentAgree;
                if($data == 'Y'){
                    echo '<i class="far fa-thumbs-up"></i>';
                }else if($data == 'N'){
                    echo '<i class="far fa-thumbs-down"></i>';
                }
                else echo ' ';
            }
            break;
        }

        case 'getStudentPointsToAdmin':{
            if(isset($_GET['id']) && isset($_GET['table'])) {
                $tableName = $_GET['table'];
                $id = $_GET['id'];

                $sql = "SELECT `StudentPoints` FROM `$tableName` WHERE `ID` = '$id'";
                $result = $mysqli->query($sql);
                if ($result === FALSE) {
                    die();
                }
                $obj = $result->fetch_object();
                $data = $obj->StudentPoints;
                if($data == -1){
                    $data = ' ';
                }
                echo $data;
            }
            break;
        }

        case 'getStudentPointsToAdmin2':{
            if(isset($_POST['id']) && isset($_POST['table'])) {
                $tableName = $_POST['table'];
                $id = $_POST['id'];

                $sql = "SELECT `StudentPoints` FROM `$tableName` WHERE `ID` = '$id'";
                $result = $mysqli->query($sql);
                if ($result === FALSE) {
                    die();
                }
                $obj = $result->fetch_object();
                $data = $obj->StudentPoints;
                if($data == -1){
                    $data = '';
                }
                echo $data;
            }
            break;
        }
    }

}