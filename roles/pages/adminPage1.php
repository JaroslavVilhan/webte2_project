<?php
require_once("../../basic_doc_fns.php");

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

$words = getDictionary('../../');
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);
$file = 'adminPage1';

doHTMLHeader($lang, $words['homePage']['adminPage1Title'][$lang], "adminPage1", '../../');
require_once("../navBar.php");
doHTMLFooter();