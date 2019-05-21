<?php
require_once("../../basic_doc_fns.php");

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
}
if($_SESSION['userType'] == 'user') {
    header("Location: ../../index.php");
}

$words = getDictionary('../../');
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);
$file = 'adminPage3';

doHTMLHeader($lang, $words['homePage']['adminPage3Title'][$lang], "adminPage3", '../../');
require_once("../navBar.php");


//****************************************************************************************************************
//require_once("../../config.php");
//$mysqli = new mysqli($hostname, $username, $password, $dbname3);
//if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
//$mysqli->query("SET NAMES 'utf8'");
//
//$heslo='ycBLPnCXq3xBpjN';
//$meno = 'admin1';
//
//$hash = password_hash($heslo, PASSWORD_DEFAULT);
//$queryAdd = "INSERT INTO adminAccount (`Login`, `Password`) VALUES ('$meno', '$hash')";
//$mysqli->query($queryAdd);

//echo mb_strtolower('Tím');
//****************************************************************************************************************

doHTMLFooter();