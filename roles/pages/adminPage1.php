<?php
ob_start();
require_once("../../basic_doc_fns.php");
require_once("../../config.php");

session_start();
if(!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
}

$words = getDictionary('../../');
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);
$file = 'adminPage1';

doHTMLHeader($lang, $words['homePage']['adminPage1Title'][$lang], "adminPage1", '../../');
require_once("../navBar.php");

//---------------------------------ziskanie aktualneho roku pre formular---------------------------------------------
$date = date('Y-m-d');
$timeArray = explode("-", $date);
$year = "";
if ($timeArray[1] > 8){
    $year = $timeArray[0] . '/' . ($timeArray[0]+1);
} else{
    $year = ($timeArray[0]-1) . '/' . $timeArray[0];
}

//---------------------------------------pripojenie k databaze-------------------------------------------------------
$mysqli = new mysqli($hostname, $username, $password, $dbname);
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
$mysqli->query("SET NAMES 'utf8'");

//--------------------------zistovanie dostupnych predmetov v databaze-----------------------------------------------

$predmety = array();
$predmety2 = array();


$ZSyear = 'ZS '.$year;
$counter=0;
$query = "SELECT Nazov"
    . " FROM Predmet WHERE Rok='$ZSyear'";
$result = $mysqli->query($query);
while($obj = $result->fetch_object()) {
    $predmety[$counter] = $obj->Nazov;
    $counter++;
}

$LSyear = 'LS '.$year;
$counter=0;
$query = "SELECT Nazov"
    . " FROM Predmet WHERE Rok='$LSyear'";
$result = $mysqli->query($query);
while($obj = $result->fetch_object()) {
    $predmety2[$counter]=$obj->Nazov;
    $counter++;
}


if(isset($_GET['duplicateError'])){
    echo '<div class=container>'."\n"
        .'<div class="alert alert-danger" role="alert">'."\n"
        .'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'."\n"
        .'<span class="bold">Error: </span>'.$words['adminPage1']['duplicateError'][$lang]." ! \n"
        .'</div>'."\n"
        .'</div>';
}

?>

<div class="container">
    <label for="sel1"><?php echo $words['adminPage1']['selectTitle'][$lang];?>:</label>
    <div class="d-flex flex-row">
    <select class="form-control mr-2" id="sel1">
        <option value="1" selected><?php echo $words['adminPage1']['option1'][$lang];?></option>
        <option value="2"><?php echo $words['adminPage1']['option2'][$lang];?></option>
        <option value="3"><?php echo $words['adminPage1']['option3'][$lang];?></option>
    </select>
    <button type="button" class="btn btn-primary" id="sel1Button" onclick="selectOption()"><?php echo $words['adminPage1']['selButton'][$lang];?></button>
    </div>


    <form action="adminPage1.php?form=1" method="post" enctype="multipart/form-data" id="selForm1">
        <div class="form-group">
            <label for="form1rok"><?php echo $words['adminPage1']['formYear'][$lang];?>:</label>
            <select class="form-control" id="form1rok" name="form1year">
                <option value="<?php echo 'ZS '.$year;?>" selected><?php echo 'ZS '.$year;?></option>
                <option value="<?php echo 'LS '.$year;?>"><?php echo 'LS '.$year;?></option>
            </select>
        </div>
        <div class="form-group">
            <label for="form1predmet"><?php echo $words['adminPage1']['formCourse'][$lang];?> (<?php echo $words['adminPage1']['formCourseChoose'][$lang];?>):</label>
            <div class="d-flex flex-row">
                <select class="form-control mr-2" id="form1predmet" name="form1course">
                    <?php
                    foreach ($predmety as $item){
                        echo '<option value="'.$item.'">'.$item.'</option>'."\n";
                    }
                    ?>
                </select>
                <select class="form-control mr-2" id="form1predmet2" name="form1course2">
                    <?php
                    foreach ($predmety2 as $item){
                        echo '<option value="'.$item.'">'.$item.'</option>'."\n";
                    }
                    ?>
                </select>
                <button type="button" class="btn btn-primary" id="selPredmetButton" onclick="showInputForNewCourse()"><?php echo $words['adminPage1']['selCourseButton'][$lang];?></button>
            </div>
            <div class="form-group" id="selForm1Hidden">
                <label for="newCourse"><?php echo $words['adminPage1']['formNewCourse'][$lang];?>:</label>
                <input type="text" class="form-control" id="newCourse" name="form1newCourse">
            </div>
        </div>
        <div class="form-group">
            <label for="fileToUpload"><?php echo $words['adminPage1']['formFile'][$lang];?>:</label>
            <input type="file" name="fileToUpload" id="fileToUpload" required>
        </div>
        <div class="form-group">
            <label for="oddelovac"><?php echo $words['adminPage1']['formFileDelimiter'][$lang];?>:</label>
            <input type="text" name="form1delimiter" class="form-control" id="oddelovac" required>
        </div>
        <input type="hidden" id="form1hidden" name="form1hidden" value="form1course">
        <button type="submit" name="submit" class="btn btn-primary"><?php echo $words['adminPage1']['formSubmit'][$lang];?></button>
    </form>



</div>

<?php

if(isset($_GET['form'])){
    switch ($_GET['form']){
        case "1":{
            if(isset($_POST['form1year']) && isset($_POST['submit'])){

                if(isset($_POST['form1newCourse'])){ //------------------ ak je to novy predmet---------

                    //-----------------test duplicitneho nazvu-------------------------------------------
                    $nazov=trim($_POST['form1newCourse']);
                    $year=$_POST['form1year'];

                    $sql = "SELECT COUNT(*) AS pocet FROM Predmet WHERE `Nazov` = '$nazov' AND `Rok` = '$year'";
                    $result = $mysqli->query($sql);
                    $obj = $result->fetch_object();
                    if($obj->pocet > 0) {
                        header('Location: adminPage1.php?duplicateError=1');
                        die();
                    }
                    //-------------------------------------------------------------------------------------

                    $sql = "INSERT INTO Predmet (`Nazov`, `Rok`)"
                        ." VALUES ('$nazov', '$year')";
                    if ($mysqli->query($sql) === FALSE) {
                        echo "Error: " . $sql . "<br>" . $mysqli->error;
                    }
                    $sql2 = "SELECT PredmetId FROM Predmet WHERE `Nazov` = '$nazov' AND `Rok` = '$year'";
                    $result = $mysqli->query($sql2);
                    $obj = $result->fetch_object();
                    $id = $obj->PredmetId;
                   // echo $id;
                    if ($_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
                        $string =  file_get_contents($_FILES['fileToUpload']['tmp_name']);
                        // $encodedOutput = mb_convert_encoding(file_get_contents($_FILES['fileToUpload']['tmp_name']), "utf-8", "windows-1250");

                        $lines = explode("\n", $string);
                        $head = explode(trim($_POST['form1delimiter']), $lines[0]);
                        $newTableName = 't'.$id;

                        //-------------------------vytvorenie novej tabulky-------------------------------------------

                        $table = "CREATE TABLE `$newTableName` ( ";

                        $i = 0;
                        foreach ($head as $value){ //---------pridavanie stlpcov do tabulky---------------
                            if ($i == 0) {
                                $table=$table."`$value` INT NOT NULL PRIMARY KEY ";
                            } else if ($i == 1)  {
                                $table=$table.", `$value` VARCHAR(255) NOT NULL ";
                            } else{
                                $table=$table.", `$value` VARCHAR(255) NULL ";
                            }
                            $i++;
                        }
                        $table=$table." )";

                        if ($mysqli->query($table) === FALSE) {
                              echo "Error: " . $table . "<br>" . $mysqli->error;
                        }

                        //---------------------------naplnenie tabulky------------------------------------------------

                        for($i=1; $i<count($lines);$i++){
                            $values = explode(trim($_POST['form1delimiter']), $lines[$i]);

                            $fillTable = "INSERT INTO `$newTableName` (";
                            foreach ($head as $value){
                                $fillTable=$fillTable."`$value`, ";
                            }
                            $fillTable=substr($fillTable, 0, -2);
                            $fillTable=$fillTable.") VALUES (";
                            foreach ($values as $value){
                                $fillTable=$fillTable."'$value', ";
                            }
                            $fillTable=substr($fillTable, 0, -2);
                            $fillTable=$fillTable.")";

                            if ($mysqli->query($fillTable) === FALSE) {
                              echo "Error: " . $fillTable . "<br>" . $mysqli->error;
                            }
                        }
                    }else{
                        echo 'ERROR FILE !';
                    }

                }else{ //------------------ak uz predmet existuje (bol vybrany zo zoznamu)------------
                    echo 'existuje predmet';
                }
            }
            break;
        }


        case "2":{
            break;
        }



        case "3":{
            break;
        }
    }
}

doHTMLFooter();
?>