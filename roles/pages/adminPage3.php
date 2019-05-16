<?php
require_once("../../basic_doc_fns.php");

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
}

$words = getDictionary('../../');
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);
$file = 'adminPage3';

doHTMLHeader($lang, $words['homePage']['adminPage3Title'][$lang], "adminPage3", '../../');
require_once("../navBar.php");
doHTMLFooter();