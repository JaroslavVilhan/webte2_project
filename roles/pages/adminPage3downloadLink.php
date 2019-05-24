<?php
require_once("../../basic_doc_fns.php");
require_once("../../config.php");

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
$file = 'adminPage3downloadLink';

doHTMLHeader($lang, $words['homePage']['adminPage3Title'][$lang], "adminPage3", '../../');
require_once("../navBar.php");

echo '<div class="container mt-5"><p><a class="btn btn-success mr-3" href="adminPage3.php">' . $words['adminPage3']['Back'][$lang] . '</a><a class="btn btn-primary" href="adminPage3generateCSV.php" target="_blank">' . $words['adminPage3']['Download'][$lang] . '</a></p></div>' . "\n";


doHTMLFooter();