<?php
if (isset($argv[1]) && isset($argv[2])) {

    $meno = $argv[1];
    $heslo = $argv[2];

    $mysqli = new mysqli('localhost', 'xvilhanj', 'CcXQY2InLIfD', 'webteAccounts');
    if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
    $mysqli->query("SET NAMES 'utf8'");

    $hash = password_hash($heslo, PASSWORD_DEFAULT);
    $queryAdd = "INSERT INTO adminAccount (`Login`, `Password`) VALUES ('$meno', '$hash')";
    $result = $mysqli->query($queryAdd);
    if ($result === FALSE) {
        echo "Error: " . $query . "\n" . $mysqli->error;
        die();
    }

} else {
    die("Not enough parameters !\n");
}