<?php
require_once("../../basic_doc_fns.php");
require_once("../../config.php");

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
}
if($_SESSION['userType'] == 'admin') {
    header("Location: ../../index.php");
}

$words = getDictionary('../../');
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);
$file = 'userPage1';

doHTMLHeader($lang, $words['homePage']['userPage1Title'][$lang], "userPage1", '../../');
require_once("../navBar.php");

//---------------------------------------pripojenie k databaze-------------------------------------------------------
$mysqli = new mysqli($hostname, $username, $password, $dbname);
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
$mysqli->query("SET NAMES 'utf8'");

//--------------prechadzanie tabuliek z predmetmi-----------------------------------------------

$userId = $_SESSION['userID']; //ID prihlaseneho pouzivatela

$flag=false;
$sql = "SELECT PredmetId, Nazov, Rok FROM Predmet";
$result = $mysqli->query($sql);
while($obj = $result->fetch_object()) {
    $id = $obj->PredmetId;
    $predmet = $obj->Nazov;
    $rok = $obj->Rok;
    $tableName = 't' . $id;

    $sql2 = "SELECT * FROM `$tableName`";
    $result2 = $mysqli->query($sql2);
    while($row = $result2->fetch_row()) {
        if ($row[0] == $userId){ //ak sa najde zhoda
            $flag=true;
            $sql3 = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '$tableName'";
            $result3 = $mysqli->query($sql3);
            $resultArrayHead = $result3->fetch_all(MYSQLI_NUM);

            //-----vypis tabulky---------------------
            $finalTable= '<div class="container mt-5">' . "\n"
                .'<h2>'.$predmet.' - '.$rok.'</h2>' . "\n"
                .'<table class="table">' . "\n"
                .'<thead class="thead-dark">' . "\n"
                .'<tr>' . "\n";

            for ($i = 0; $i < count($resultArrayHead); $i++) {
                $finalTable=$finalTable.'<th>' . $resultArrayHead[$i][0] . '</th>' . "\n";
            }

            $finalTable=$finalTable . '</tr>' . "\n"
                .'</thead>' . "\n"
                .'<tbody>' . "\n"
                .'<tr>' . "\n";

            for ($i = 0; $i < count($row); $i++) {
                $finalTable = $finalTable . '<td>' . $row[$i] . '</td>' . "\n";
            }

            $finalTable=$finalTable.'</tr>' . "\n"
                .'</tbody>' . "\n"
                .'</table>' . "\n"
                .'</div>' . "\n";

            echo $finalTable;
        }
    }
}

if ($flag==false){
    echo '<div class=container>'."\n"
        .'<div class="alert alert-info" role="alert">'."\n"
        .'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'."\n"
        .$words['userPage1']['emptyResult'][$lang]." ! \n"
        .'</div>'."\n"
        .'</div>';
}

doHTMLFooter();