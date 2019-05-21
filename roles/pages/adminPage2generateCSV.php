<?php
session_start();
if (isset($_GET['team'])) {
    $values = $_SESSION['tableValues'.$_GET['team']];

    $new_csv = fopen('/tmp/report.csv', 'wb');

    for ($i = 0; $i < count($values); $i++) {
        if ($values[$i][3] == -1) {
            $body = '';
        } else {
            $body = $values[$i][3];
        }
        fwrite($new_csv, $values[$i][0] . ';' . $values[$i][1] . ';' . $body . "\r\n\r");
    }

    fclose($new_csv);

    header("Content-type: text/csv");
    //header('Content-Type: application/octet-stream');
    header("Content-disposition: attachment; filename = report.csv");
    readfile("/tmp/report.csv");
}