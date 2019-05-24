<?php
use PHPMailer\PHPMailer\PHPMailer;
//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
date_default_timezone_set('Etc/UTC');

require '../../vendor/autoload.php';
require_once("../../config.php");
require_once("../../basic_doc_fns.php");

session_start();

if(isset($_POST['sentEmail']) && isset($_POST['sentName']) && isset($_POST['sentSMTPlog']) && isset($_POST['sentSMTPpass']) && isset($_POST['sentSubject'])) {

    $email = $_POST['sentEmail'];
    $name = $_POST['sentName'];
    $smtpLog = $_POST['sentSMTPlog'];
    $smtpPass = $_POST['sentSMTPpass'];
    $subject = $_POST['sentSubject'];

    $template='';

    $templateId = $_SESSION['selectedTemplates'];
    $lines = $_SESSION['lines'];
    $delimiter = $_SESSION['delimit'];
    $head = $_SESSION['head'];

    $emailBody = array();
    $cells = array();

    file_put_contents("/tmp/template.txt",getcookie('var1'));
    $template = file_get_contents("/tmp/template.txt");

    $array = array();
    for($i=1; $i<count($lines);$i++) {
        $values = explode($delimiter, $lines[$i]);

        for($k =0; $k<count($head);$k++){
            $array[$head[$k]]=$values[$k];
            $cells[$i][$k]=$values[$k];
        }

        $emailBody[$i] = replace($array, $template);

    }
    var_dump($cells);


//---------------------------------------pripojenie k databaze-------------------------------------------------------
    $mysqli = new mysqli($hostname, $username, $password, $dbname4);
    if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
    $mysqli->query("SET NAMES 'utf8'");

//---------------------ziskanie aktualneho casu pre databazu-----------------------------------------------------

    $time = date('Y-m-d H:i:s');
    $date = date('Y-m-d H:i:s', strtotime($time . $operation  . $time_offset));


//--------------------------------configuracia PHPMailer----------------------------------------------------------
    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';

//---------------Server settings--------------------
    $mail->isSMTP();
//Enable SMTP debugging:
// 0 = off (for production use)
// 1 = client messages
// 2 = client and server messages
    $mail->SMTPDebug = 0;
    $mail->Host = 'mail.stuba.sk';
    $mail->SMTPAuth   = true;
    $mail->Username   = $smtpLog;
    $mail->Password   = $smtpPass;
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

//--------------Recipients-----------------------------
    $mail->setFrom($email, $name);
    $mail->Subject = $subject;

    for($i=1; $i<=count($emailBody);$i++) {
        $mail->addAddress($cells[$i][2]);

        $mail->isHTML(true);

        $emailBody[$i] = str_replace('{{sender}}', $name, $emailBody[$i]);
        $mail->Body = $emailBody[$i];

        if ($_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
            $mail->addAttachment($_FILES['fileToUpload']['tmp_name'], $_FILES['fileToUpload']['name']);
        }

        if (!$mail->send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
            die();
        }
        $mail->ClearAllRecipients();

        $studentName = $cells[$i][2];
        $fillTable = "INSERT INTO `Historia` (`Datum`, `Meno`, `Predmet`, `SablonaID`) VALUES ( '$date', '$studentName', '$subject', '$templateId')";
        if ($mysqli->query($fillTable) === FALSE) {
            echo "Error: " . $fillTable . "<br>" . $mysqli->error;
            die();
        }

    }
    header('Location: adminPage3.php?success=1');
    exit();

} else {
    header('Location: adminPage3.php?fillError=1');
    die();
}