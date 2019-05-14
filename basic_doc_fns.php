<?php

function getDictionary($prefix=''){
    $string = file_get_contents($prefix."languages.json");
    $content = json_decode($string, true);
    return $content;
}

function findLang($lang, $langHandler){
    foreach ($langHandler['defLang'] as $value){
        if($value==$lang){
            return true;
        }
    }
    return false;
}

function getCurrentLanguage($getParam, $langHandler){
    session_start();
    if(isset($getParam) && findLang($getParam, $langHandler)){
        $lang=$_SESSION['lang']=$getParam;
    }
    else {
        if (isset($_SESSION['lang'])) {
            $lang = $_SESSION['lang'];
        } else {
            $lang = $_SESSION['lang'] = 'sk';
        }
    }
    return $lang;
}

function getFlag($lang){
    if($lang == 'en'){
        return 'sk';
    }
    else return 'en';
}

function doHTMLHeader($lang, $title, $cssName, $cssPrefix=''){
    echo '<!DOCTYPE html>'."\n"
    .'<html lang="'.$lang.'">'."\n"
    .'<head>'."\n"
        ."\t".'<meta charset="UTF-8">'."\n"
        ."\t".'<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n"
        ."\t".'<title>'.$title.'</title>'."\n"
        ."\t".'<link rel="stylesheet" href="'.$cssPrefix.'css/'.$cssName.'.css">'."\n"
        ."\t".'<link rel="stylesheet" href="'.$cssPrefix.'css/all.css">'."\n"
        ."\t".'<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous">'."\n"
        ."\t".'<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">'."\n"
        ."\t".'<link rel="shortcut icon" href="'.$cssPrefix.'img/st.png">'."\n"
        ."\t".'<script src="'.$cssPrefix.'js/script.js"></script>'."\n"
        ."\t".'<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha256-pasqAKBDmFT4eHoN2ndd6lN370kFiGUFyTiUHWhU7k8=" crossorigin="anonymous"></script>'."\n"
        ."\t".'<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>'."\n"
        ."\t".'<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>'."\n"
    .'</head>'."\n"
    .'<body>'."\n";

}

function doHTMLFooter(){
    echo "\n".'</body>'."\n"
        .'</html>';
}