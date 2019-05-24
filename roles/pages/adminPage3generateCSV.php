<?php
session_start();
if(isset($_SESSION['headValues']) && isset($_SESSION['array'])) {
    $head = $_SESSION['headValues'];
    $array = $_SESSION['array'];

    $new_csv = fopen('/tmp/report.csv', 'wb');

    fwrite($new_csv, $head[0] . ';' . $head[1] . ';' . $head[2] . ';' . $head[3] . ';' . $head[4] . "\r\n\r");

    for ($i = 1; $i < count($array); $i++) {
        fwrite($new_csv, $array[$i][0] . ';' . $array[$i][1] . ';' . $array[$i][2] . ';' . $array[$i][3] . ';' . $array[$i][4] . "\r\n\r");
    }

    fclose($new_csv);

    header("Content-type: text/csv");
    //header('Content-Type: application/octet-stream');
    header("Content-disposition: attachment; filename = report.csv");
    readfile("/tmp/report.csv");
}