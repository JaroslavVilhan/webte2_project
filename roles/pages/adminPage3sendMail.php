<?php
//ob_start();
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
$file = 'adminPage3';

doHTMLHeader($lang, $words['homePage']['adminPage3Title'][$lang], "adminPage3", '../../');
require_once("../navBar.php");

//---------------------------------------pripojenie k databaze-------------------------------------------------------
$mysqli = new mysqli($hostname, $username, $password, $dbname4);
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
$mysqli->query("SET NAMES 'utf8'");

$emailBody = array();
$cells = array();
$opt='';

if(isset($_POST['submit']) && isset($_POST['selectedTemplates'])){

    $templateId =  $_POST['selectedTemplates'];
    $option = $_POST['options'];

    if ($_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
        $string = file_get_contents($_FILES['fileToUpload']['tmp_name']);

        $string = trim($string);
        $lines = explode("\n", $string);
        $head = explode(trim($_POST['form1delimiter']), $lines[0]);

        $template='';


        $sql ="SELECT `data` FROM `Templates` WHERE `id` = '$templateId'";
        $result = $mysqli->query($sql);
        if ($result === FALSE) {
            echo "Error: " . $sql . "<br>" . $mysqli->error;
            die();
        }
        $obj = $result->fetch_object();

        if($option=='1'){
            echo'<h2 class="text-center mb-2">'.$words['adminPage3']['UsedTemplate'][$lang].':</h2><div class="container textTemplate mt-3">'.$obj->data.'</div><div class="container" id="htmleditor" style="display: none"><div id="editor">'.$obj->data.'</div></div>';
           // echo "\n" . '<script type="text/javascript">var string = $(".textTemplate").html(); console.log(string); document.cookie = "var1="+string;</script>' . "\n";
            //setcookie('var1',$obj->data,0);
            //$opt='plain';

        }else{
            echo '<h2 class="text-center mb-2">'.$words['adminPage3']['EditTemplate'][$lang].':</h2><div class="container mt-3" id="htmleditor"><div id="editor">'.$obj->data.'</div> </div> ';
           // $opt='html';
        }

        echo "\n" . '<script type="text/javascript"> var quill = new Quill(\'#editor\', {theme: \'snow\'});</script>' ."\n";
        echo '<script type="text/javascript">var string = $(".ql-editor").html(); document.cookie = "var1="+string;</script>' . "\n";


        $_SESSION['delimit']= trim($_POST['form1delimiter']);
        $_SESSION['lines'] = $lines;
        $_SESSION['head'] = $head;
        $_SESSION['selectedTemplates'] = $templateId;

    }else{
        echo "ERROR FILE !\n";
    }

}

?>

<div class="container pb-4">
    <h2 class="text-center mb-2 mt-5"><?php echo$words['adminPage3']['fillWindow'][$lang];?>:</h2>
    <form action="adminPage3processMail.php" method="post" enctype="multipart/form-data" id="sendForm">
        <div class="form-group">
            <label for="InputEmail1"><?php echo$words['adminPage3']['sendEmail'][$lang];?>:</label>
            <input type="email" class="form-control" id="InputEmail1" aria-describedby="emailHelp" name="sentEmail" required>
        </div>
        <div class="form-group">
            <label for="mailer"><?php echo$words['adminPage3']['sendName'][$lang];?>:</label>
            <input type="text" class="form-control" id="mailer" name="sentName" required>
        </div>
        <div class="form-group">
            <label for="smtpLog"><?php echo$words['adminPage3']['sendSMTPlog'][$lang];?>:</label>
            <input type="text" class="form-control" id="smtpLog" name="sentSMTPlog" required>
        </div>
        <div class="form-group">
            <label for="smtPass"><?php echo$words['adminPage3']['sendSMTPpass'][$lang];?>:</label>
            <input type="password" class="form-control" id="smtPass" name="sentSMTPpass" required>
        </div>
        <div class="form-group">
            <label for="subject"><?php echo$words['adminPage3']['sendSubject'][$lang];?>:</label>
            <input type="text" class="form-control" id="subject" name="sentSubject" required>
        </div>
        <div class="form-group">
            <label for="fileToUpload"><?php echo $words['adminPage3']['sendFile'][$lang];?>:</label>
            <input type="file" name="fileToUpload" id="fileToUpload">
        </div>
        <input id="prodId" name="hidden" type="hidden" value="">
        <div class="row">
            <div class="col text-center">
                <button type="submit" class="btn btn-primary"><?php echo$words['adminPage1']['formSubmit'][$lang];?></button>
            </div>
        </div>
    </form>
</div>

