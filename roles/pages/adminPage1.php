<?php
ob_start();
require_once("../../basic_doc_fns.php");
require_once("../../config.php");

session_start();
if(!isset($_SESSION['user'])) {
    header("Location: ../../login.php");
}
if($_SESSION['userType'] == 'user') {
    header("Location: ../../index.php");
}

$words = getDictionary('../../');
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);
$file = 'adminPage1';

doHTMLHeader($lang, $words['homePage']['adminPage1Title'][$lang], "adminPage1", '../../');
require_once("../navBar.php");

//---------------------------------ziskanie aktualneho akademickeho roku pre formular---------------------------------------------
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

//--------------------------zistovanie dostupnych predmetov v databaze pre aktualny akad. rok---------------------------------

$predmety = array();
$predmety2 = array();


$ZSyear = 'ZS '.$year;
$counter=0;
$query = "SELECT Nazov"
    . " FROM Predmet WHERE Rok='$ZSyear'";
$result = $mysqli->query($query);
if ($result === FALSE) {
    echo "Error: " . $query . "<br>" . $mysqli->error;
    die();
}
while($obj = $result->fetch_object()) {
    $predmety[$counter] = $obj->Nazov;
    $counter++;
}

$LSyear = 'LS '.$year;
$counter=0;
$query = "SELECT Nazov"
    . " FROM Predmet WHERE Rok='$LSyear'";
$result = $mysqli->query($query);
if ($result === FALSE) {
    echo "Error: " . $query . "<br>" . $mysqli->error;
    die();
}
while($obj = $result->fetch_object()) {
    $predmety2[$counter]=$obj->Nazov;
    $counter++;
}

//-----------------------------------spracovanie sprav--------------------------------------

if(isset($_GET['duplicateError'])){
    echo '<div class=container>'."\n"
        .'<div class="alert alert-danger" role="alert">'."\n"
        .'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'."\n"
        .'<strong>Error: </strong>'.$words['adminPage1']['duplicateError'][$lang]." ! \n"
        .'</div>'."\n"
        .'</div>';
}

if(isset($_GET['fillError'])){
    echo '<div class=container>'."\n"
        .'<div class="alert alert-danger" role="alert">'."\n"
        .'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'."\n"
        .'<strong>Error: </strong>'.$words['adminPage1']['fillError'][$lang]." ! \n"
        .'</div>'."\n"
        .'</div>';
}

if(isset($_GET['success'])){
    echo '<div class=container>'."\n"
        .'<div class="alert alert-success" role="alert">'."\n"
        .'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'."\n"
        .'<strong>Success! </strong>'.$words['adminPage1']['successMessage'][$lang].".\n"
        .'</div>'."\n"
        .'</div>';
}
if(isset($_GET['success2'])){
    echo '<div class=container>'."\n"
        .'<div class="alert alert-success" role="alert">'."\n"
        .'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'."\n"
        .'<strong>Success! </strong>'.$words['adminPage1']['successMessage2'][$lang].".\n"
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
        <h3 class="text-center"><?php echo $words['adminPage1']['form1Head'][$lang];?></h3>
        <div class="form-group">
            <label for="form1rok"><?php echo $words['adminPage1']['formYear'][$lang];?> (<?php echo $words['adminPage1']['formYearInfo'][$lang];?>):</label>
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


    <form action="adminPage1.php?form=2" method="post" id="selForm2">
        <h3 class="text-center"><?php echo $words['adminPage1']['form2Head'][$lang];?></h3>
        <div class="form-group">
            <label for="form2rok"><?php echo $words['adminPage1']['formYear'][$lang];?>:</label>
            <select class="form-control" id="form2rok" name="form2year">
                <option value="1" selected>--- <?php echo $words['adminPage1']['formChoose'][$lang];?> ---</option>
                <?php
                $sql = "SELECT DISTINCT Rok FROM Predmet";
                $result = $mysqli->query($sql);
                while ($obj = $result->fetch_object()){
                    echo '<option value="'.$obj->Rok.'">'.$obj->Rok.'</option>'."\n";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="form2predmet"><?php echo $words['adminPage1']['formCourse'][$lang];?>:</label>
                <select class="form-control mr-2" id="form2predmet" name="form2course">
                    <option value="1" selected>--- <?php echo $words['adminPage1']['formChoose'][$lang];?> ---</option>
                    <?php
                    $sql = "SELECT Nazov, Rok FROM Predmet";
                    $result = $mysqli->query($sql);
                    while ($obj = $result->fetch_object()){
                        echo '<option value="'.$obj->Nazov.'" year="'.$obj->Rok.'" hideopt="1">'.$obj->Nazov.'</option>'."\n";
                    }
                    ?>
                </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary mt-3"><?php echo $words['adminPage1']['formView'][$lang];?></button>
    </form>


    <form action="adminPage1.php?form=3" method="post" id="selForm3">
        <h3 class="text-center"><?php echo $words['adminPage1']['form3Head'][$lang];?></h3>
        <div class="form-group">
            <label for="form3rok"><?php echo $words['adminPage1']['formYear'][$lang];?>:</label>
            <select class="form-control" id="form3rok" name="form3year">
                <option value="1" selected>--- <?php echo $words['adminPage1']['formChoose'][$lang];?> ---</option>
                <?php
                $sql = "SELECT DISTINCT Rok FROM Predmet";
                $result = $mysqli->query($sql);
                while ($obj = $result->fetch_object()){
                    echo '<option value="'.$obj->Rok.'">'.$obj->Rok.'</option>'."\n";
                }
                ?>
            </select>
        </div>
        <div class="form-group">
            <label for="form3predmet"><?php echo $words['adminPage1']['formCourse'][$lang];?>:</label>
            <select class="form-control mr-2" id="form3predmet" name="form3course">
                <option value="1" selected>--- <?php echo $words['adminPage1']['formChoose'][$lang];?> ---</option>
                <?php
                $sql = "SELECT Nazov, Rok FROM Predmet";
                $result = $mysqli->query($sql);
                while ($obj = $result->fetch_object()){
                    echo '<option value="'.$obj->Nazov.'" year="'.$obj->Rok.'" hideopt="1">'.$obj->Nazov.'</option>'."\n";
                }
                ?>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary mt-3"><?php echo $words['adminPage1']['formDelete'][$lang];?></button>
    </form>



</div>

<?php

if(isset($_GET['form'])){
    switch ($_GET['form']){
        case "1":{
            $insertResults = false;
            if(isset($_POST['form1year']) && isset($_POST['submit'])){

                $nazov=trim($_POST['form1newCourse']);

                if($nazov != ""){ //------------------ ak je to novy predmet---------

                    //-----------------test duplicitneho nazvu-------------------------------------------

                    $year=$_POST['form1year'];

                    $sql = "SELECT COUNT(*) AS pocet FROM Predmet WHERE `Nazov` = '$nazov' AND `Rok` = '$year'";
                    $result = $mysqli->query($sql);
                    $obj = $result->fetch_object();
                    if($obj->pocet > 0 || $nazov=="") {
                        header('Location: adminPage1.php?duplicateError=1');
                        die();
                    }
                    //-------------------------------------------------------------------------------------

                    $sql = "INSERT INTO Predmet (`Nazov`, `Rok`)"
                        ." VALUES ('$nazov', '$year')";
                    if ($mysqli->query($sql) === FALSE) {
                        echo "Error: " . $sql . "<br>" . $mysqli->error;
                        die();
                    }
                    $sql2 = "SELECT PredmetId FROM Predmet WHERE `Nazov` = '$nazov' AND `Rok` = '$year'";
                    $result = $mysqli->query($sql2);
                    $obj = $result->fetch_object();
                    $id = $obj->PredmetId;

                    if ($_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
                        $string =  file_get_contents($_FILES['fileToUpload']['tmp_name']);
                        // $encodedOutput = mb_convert_encoding(file_get_contents($_FILES['fileToUpload']['tmp_name']), "utf-8", "windows-1250");

                        $string=trim($string);
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
                              die();
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
                              die();
                            }
                        }
                        $insertResults=true;
                    }else{
                        echo 'ERROR FILE !';
                    }

                }else{ //------------------ak uz predmet existuje (bol vybrany zo zoznamu)------------

                    $predmet = $_POST[$_POST['form1hidden']];
                    $year = $_POST['form1year'];

                    if($predmet=="") {
                        header('Location: adminPage1.php?fillError=1');
                        die();
                    }

                    //---------zistenie id predmetu-----------------------------
                    $sql2 = "SELECT PredmetId FROM Predmet WHERE `Nazov` = '$predmet' AND `Rok` = '$year'";
                    $result = $mysqli->query($sql2);
                    $obj = $result->fetch_object();
                    $id = $obj->PredmetId;
                    $tableName = 't'.$id;

                    if ($_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
                        $string = file_get_contents($_FILES['fileToUpload']['tmp_name']);

                        $string = trim($string);
                        $lines = explode("\n", $string);
                        $head = explode(trim($_POST['form1delimiter']), $lines[0]);

                        $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = '$tableName'";
                        $result = $mysqli->query($sql);
                        $resultArray = $result->fetch_all(MYSQLI_NUM);
                        $IdName = $resultArray[0][0];

                        //--------------------------------zistovanie ci sa dany stlpec nachadza v tabluke---------------
                        foreach ($head as $filehead) {
                            $flag=false;
                            for ($i = 0; $i < count($resultArray); $i++) {
                                if($resultArray[$i][0] == $filehead){
                                    $flag=true;
                                }
                            }
                            if($flag==false){ //-------ak sa dany stlpec nenajde prida sa do tabulky
                                $sql2 ="ALTER TABLE `$tableName` ADD COLUMN `$filehead` VARCHAR(255) NULL";
                                if ($mysqli->query($sql2) === FALSE) {
                                    echo "Error: " . $sql2 . "<br>" . $mysqli->error;
                                    die();
                                }
                            }
                        }

                        //---------------------------------vkladanie hodnot---------------------------------------
                        for($i=1; $i<count($lines);$i++){
                            $values = explode(trim($_POST['form1delimiter']), $lines[$i]);

                            //----------------------zistovanie ci sa dany student uz nachadza v predmete-------------
                            $fileId = $values[0];
                            $sql3="SELECT COUNT(*) AS pocet FROM `$tableName` WHERE `$IdName` = '$fileId'";
                            $result = $mysqli->query($sql3);
                            $obj = $result->fetch_object();

                            if($obj->pocet > 0) { //---ak sa nachadza updatuju sa hodnoty---

                                $sql3="UPDATE `$tableName` SET ";

                                for ($k=1; $k<count($head); $k++){
                                    $headValue=$head[$k];
                                    $updateValue=$values[$k];
                                    $sql3=$sql3."`$headValue` = '$updateValue', ";
                                }

                                $sql3 = substr($sql3, 0, -2);
                                $sql3=$sql3." WHERE `$IdName` = '$fileId'";
                                if ($mysqli->query($sql3) === FALSE) {
                                    echo "Error: " . $sql3 . "<br>" . $mysqli->error;
                                    die();
                                }

                            }else { //---inak sa vlozi novy riadok---
                                $fillTable = "INSERT INTO `$tableName` (";
                                foreach ($head as $value) {
                                    $fillTable = $fillTable . "`$value`, ";
                                }
                                $fillTable = substr($fillTable, 0, -2);
                                $fillTable = $fillTable . ") VALUES (";
                                foreach ($values as $value) {
                                    $fillTable = $fillTable . "'$value', ";
                                }
                                $fillTable = substr($fillTable, 0, -2);
                                $fillTable = $fillTable . ")";

                                if ($mysqli->query($fillTable) === FALSE) {
                                    echo "Error: " . $fillTable . "<br>" . $mysqli->error;
                                    die();
                                }
                            }
                        }
                        $insertResults=true;

                    }else{
                        echo 'ERROR FILE !';
                    }
                }
            } else{
                $mysqli->close();
                header('Location: adminPage1.php?fillError=1');
                die();
            }
            $mysqli->close();
            if($insertResults == true){
                header('Location: adminPage1.php?success2=1');
                die();
            }
            header('Location: adminPage1.php');
            die();
            break;
        }


        case "2":{
            if(isset($_POST['form2year']) && isset($_POST['submit']) && $_POST['form2year'] != '1' && $_POST['form2course'] != '1'){
                $rok = $_POST['form2year'];
                $predmet = $_POST['form2course'];

                //------------------zistenie nazvu tabulky-------------------------------------------
                $sql = "SELECT PredmetId FROM Predmet WHERE `Nazov` = '$predmet' AND `Rok` = '$rok'";
                $result = $mysqli->query($sql);
                $obj = $result->fetch_object();
                $id = $obj->PredmetId;
                $tableName = 't'.$id;

                //-------------------vypis tabulky-------------------------------------------------
                //----ziskanie nazvov stlpcov----------
                $sql = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$dbname' AND TABLE_NAME = '$tableName'";
                $result = $mysqli->query($sql);
                $resultArrayHead = $result->fetch_all(MYSQLI_NUM);

                //-------ziskanie jednotlivych hodnot---
                $sql = "SELECT * FROM `$tableName`";
                $result = $mysqli->query($sql);
                $resultArrayValues = $result->fetch_all(MYSQLI_NUM);

                //------vypis-----
                $finalTable= '<div class="container mt-5">' . "\n"
                    .'<h2>'.$predmet.' - '.$rok.'</h2>' . "\n"
                    .'<table class="table table-striped table-responsive-sm">' . "\n"
                    .'<thead class="thead-dark">' . "\n"
                    .'<tr>' . "\n";

                for ($i = 0; $i < count($resultArrayHead); $i++) {
                    $finalTable=$finalTable.'<th>' . $resultArrayHead[$i][0] . '</th>' . "\n";
                }

                $finalTable=$finalTable . '</tr>' . "\n"
                    .'</thead>' . "\n"
                    .'<tbody>' . "\n";

                for ($i = 0; $i < count($resultArrayValues); $i++) {
                    $finalTable=$finalTable.'<tr>' . "\n";
                    for ($k = 0; $k < count($resultArrayValues[$i]); $k++) {
                        $finalTable = $finalTable . '<td>' . $resultArrayValues[$i][$k] . '</td>' . "\n";
                    }
                    $finalTable=$finalTable.'</tr>' . "\n";
                }

                $finalTable=$finalTable.'</tbody>' . "\n"
                    .'</table>' . "\n";

                echo $finalTable;

                //-----------------------------------GET TABLE AS PDF-------------------------------------------------

                $_SESSION['tableHead']=$resultArrayHead;
                $_SESSION['tableValues']=$resultArrayValues;

                echo '<p><a class="btn btn-primary" href="adminPage1printTable.php" target="_blank">' .$words['adminPage1']['viewPDF'][$lang].'</a></p>'  . "\n"
                    .'</div>' . "\n";

            }
            else{
                $mysqli->close();
                header('Location: adminPage1.php?fillError=1');
                die();
            }
            break;
        }



        case "3":{
            if(isset($_POST['form3year']) && isset($_POST['submit']) && $_POST['form3year'] != '1' && $_POST['form3course'] != '1') {
                $rok = $_POST['form3year'];
                $predmet = $_POST['form3course'];

                //------------------zistenie nazvu tabulky-------------------------------------------
                $sql = "SELECT PredmetId FROM Predmet WHERE `Nazov` = '$predmet' AND `Rok` = '$rok'";
                $result = $mysqli->query($sql);
                $obj = $result->fetch_object();
                $id = $obj->PredmetId;
                $tableName = 't'.$id;

                //-----vymazanie zaznamu v tabulke Predmet--------------
                $sql = "DELETE FROM Predmet WHERE `Nazov` = '$predmet' AND `Rok` = '$rok'";
                $result = $mysqli->query($sql);

                //----------vymazanie tabluky predmetu-------------
                $sql = "DROP TABLE `$tableName`";
                $result = $mysqli->query($sql);

                $mysqli->close();
                header('Location: adminPage1.php?success=1');
                die();
            }else{
                $mysqli->close();
                header('Location: adminPage1.php?fillError=1');
                die();
            }
            break;
        }
    }
}

doHTMLFooter();
?>