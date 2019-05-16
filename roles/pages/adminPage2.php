<?php
require_once("../../basic_doc_fns.php");

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
}

$words = getDictionary('../../');
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);
$file = 'adminPage2';

doHTMLHeader($lang, $words['homePage']['adminPage2Title'][$lang], "adminPage2", '../../');
require_once("../navBar.php");
doHTMLFooter();