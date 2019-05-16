<?php
require_once("../../basic_doc_fns.php");

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
}

$words = getDictionary('../../');
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);
$file = 'userPage2';

doHTMLHeader($lang, $words['homePage']['userPage2Title'][$lang], "userPage2", '../../');
require_once("../navBar.php");
doHTMLFooter();