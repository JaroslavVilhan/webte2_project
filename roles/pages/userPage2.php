<?php
require_once("../../basic_doc_fns.php");
require_once("../../config.php");

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: ../../login.php');
}
if($_SESSION['userType'] == 'admin') {
    header("Location: ../../index.php");
}

$words = getDictionary('../../');
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);
$file = 'userPage2';

doHTMLHeader($lang, $words['homePage']['userPage2Title'][$lang], "userPage2", '../../');
require_once("../navBar.php");

//---------------------------------------pripojenie k databaze-------------------------------------------------------
$mysqli = new mysqli($hostname, $username, $password, $dbname2);
if ($mysqli->connect_error) die("Connection failed: " . $mysqli->connect_error);
$mysqli->query("SET NAMES 'utf8'");

$userId = $_SESSION['userID']; //ID prihlaseneho pouzivatela

//-----------------------------------spracovanie sprav--------------------------------------

if(isset($_GET['fillError'])){
    echo '<div class=container>'."\n"
        .'<div class="alert alert-danger" role="alert">'."\n"
        .'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'."\n"
        .'<strong>Error: </strong>'.$words['adminPage1']['fillError'][$lang]." ! \n"
        .'</div>'."\n"
        .'</div>';
}

if (isset($_GET['emptyResult'])){
    echo '<div class=container>'."\n"
        .'<div class="alert alert-info" role="alert">'."\n"
        .'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'."\n"
        .$words['userPage2']['emptyResult'][$lang]." ! \n"
        .'</div>'."\n"
        .'</div>';
}
?>

<div class="container">
    <form action="userPage2.php" method="post" id="selForm">
        <h3 class="text-center"><?php echo $words['adminPage2']['form2Head'][$lang];?></h3>
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
</div>

<?php

if(isset($_POST['form2year']) && isset($_POST['submit']) && $_POST['form2year'] != '1' && $_POST['form2course'] != '1'){
    $rok = $_POST['form2year'];
    $predmet = $_POST['form2course'];

    echo '<p> <a class="btn btn-primary" href="userPage2.php">' . $words['userPage2']['BackButton'][$lang] . '</a></p>' . "\n";

    echo '<div class="container my-4">' . "\n"
        .'<h1>'.$predmet.' - '.$rok.'</h1>' . "\n"
        .'</div>';

    //------------------zistenie nazvu tabulky-------------------------------------------
    $sql = "SELECT PredmetId FROM Predmet WHERE `Nazov` = '$predmet' AND `Rok` = '$rok'";
    $result = $mysqli->query($sql);
    $obj = $result->fetch_object();
    $id = $obj->PredmetId;
    $tableName = 't'.$id;

    //-------------------vypis tabulky-------------------------------------------------

    $resultArrayHead = array('ID', 'Name', 'Email', 'Points', 'Agree');

    //-------ziskanie jednotlivych hodnot---
    $sql = "SELECT Team FROM `$tableName` WHERE `ID` = '$userId'";
    $result = $mysqli->query($sql);

    if($result->num_rows == 0) {
        header('Location: userPage2.php?emptyResult=1');
        die();
    }
    $obj = $result->fetch_object();
    $team = $obj->Team;

    echo '<script type="text/javascript">',
        'hideSelectCell();',
    '</script>';

    //-------------------ziskanie bodov pre tim---------------------------------
    $sql = "SELECT DISTINCT `TeamPoints` FROM `$tableName` WHERE `Team` = '$team'";
    $result = $mysqli->query($sql);
    if ($result === FALSE) {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
        die();
    }
    $obj = $result->fetch_object();
    $body=$obj->TeamPoints;
    if($body == -1){
        $body="";
    }

    //------------------------zistenie suhlasu admina pre tim-------------------------

    $sql = "SELECT DISTINCT `AdminAgree` FROM `$tableName` WHERE `Team` = '$team'";
    $result = $mysqli->query($sql);
    if ($result === FALSE) {
        echo "Error: " . $sql . "<br>" . $mysqli->error;
        die();
    }
    $obj = $result->fetch_object();
    $state=$obj->AdminAgree;

    $messId = 'stateText'.$team;
    $stateButtons='';
    if($state == '-'){
        $ButtId = 'stateButtons'.$team;
        $stateMess = '<br><p id="'.$messId.'" class="stateTextAdmin"></p>';
        $stateButtons='<p id="'.$ButtId.'"><input type="button" class="stateButt" value="'.$words['adminPage2']['StateButtonY'][$lang].'" onclick="studentSendAgree(\'Y\', '.$userId.', '.$team.', \''.$tableName.'\', \''.$messId.'\', \''.$ButtId.'\', \''.$lang.'\')"> <input type="button" class="stateButt" value="'.$words['adminPage2']['StateButtonN'][$lang].'" onclick="studentSendAgree(\'N\', '.$userId.', '.$team.', \''.$tableName.'\', \''.$messId.'\', \''.$ButtId.'\', \''.$lang.'\')"></p>';
    }else if($state == 'Y'){
        $stateMess = '<br><p id="stateText'.$team.'" class="stateTextAdmin"><i class="far fa-thumbs-up"></i> '.$words['userPage2']['StateMessageY'][$lang].'</p>';
    }else{
        $stateMess = '<br><p id="stateText'.$team.'" class="stateTextAdmin"><i class="far fa-thumbs-down"></i> '.$words['userPage2']['StateMessageN'][$lang].'</p>';
    }

    $finalTable = '<div class="container my-5">' . "\n"
        . '<h2>' . $words['adminPage2']['Team'][$lang]  . ' ' . $team . '</h2>' . "\n"
        . '<span class="totalPointsTitle">'.$words['userPage2']['Points'][$lang] .' :</span><span id="'.$team.'" class="totalPoints"> '.$body.'</span>'
        .$stateMess
        . '<table class="table table-striped table-responsive-sm">' . "\n"
        . '<thead class="thead-dark">' . "\n"
        . '<tr>' . "\n";

    for ($i = 0; $i < count($resultArrayHead); $i++) {
        $finalTable = $finalTable . '<th>' . $resultArrayHead[$i] . '</th>' . "\n";
    }

    $finalTable = $finalTable . '</tr>' . "\n"
        . '</thead>' . "\n"
        . '<tbody>' . "\n";

    $sql = "SELECT `ID`, `FullName`, `Email`, `StudentPoints`, `StudentAgree` FROM `$tableName` WHERE `Team` = '$team'";
    $result = $mysqli->query($sql);
    $resultArrayValues = $result->fetch_all(MYSQLI_NUM);

    for ($i = 0; $i < count($resultArrayValues); $i++) {
        $finalTable = $finalTable . '<tr>' . "\n";
        for ($k = 0; $k < count($resultArrayValues[$i]); $k++) {
            $data = $resultArrayValues[$i][$k];
            if($data == '-1' || $data == '-'){
                $data='';
            }
            if($k == 4){
                if($data == 'Y'){
                    $finalTable = $finalTable . '<td data-table="\'' . $tableName . '\'" data-team="' . $team . '" data-ord="' . $k . '" class="'.$resultArrayValues[$i][0].'"><i class="far fa-thumbs-up"></i></td>' . "\n";
                }else if($data == 'N'){
                    $finalTable = $finalTable . '<td data-table="\'' . $tableName . '\'" data-team="' . $team . '" data-ord="' . $k . '" class="'.$resultArrayValues[$i][0].'"><i class="far fa-thumbs-down"></i></td>' . "\n";
                }else{
                    $finalTable = $finalTable . '<td data-table="\'' . $tableName . '\'" data-team="' . $team . '" data-ord="' . $k . '" class="'.$resultArrayValues[$i][0].'">' . $data . '</td>' . "\n";
                }
            }else if ($k == 3){
                $id = $resultArrayValues[$i][0];

                $sql = "SELECT `StudentAgree` FROM `$tableName` WHERE `ID` = '$id'";
                $result = $mysqli->query($sql);
                if ($result === FALSE) {
                    die();
                }
                $obj = $result->fetch_object();
                if($obj->StudentAgree == '-') {
                    $finalTable = $finalTable . '<td data-table="\'' . $tableName . '\'" data-team="' . $team . '" data-ord="' . $k . '" class="' . $resultArrayValues[$i][0] . '"><input class="inputStudentValue" type="text" value="' . $data . '"><input type="button" class="pointsButt" value="' . $words['adminPage2']['AddPointsButton'][$lang] . '" onclick="studentSendPoints(' . $resultArrayValues[$i][0] . ', ' . $team . ', \'' . $tableName . '\', \'' . $lang . '\')"></td>' . "\n";
                }else{
                    $finalTable = $finalTable . '<td data-table="\'' . $tableName . '\'" data-team="' . $team . '" data-ord="' . $k . '" class="' . $resultArrayValues[$i][0] . '"><input class="inputStudentValue" type="text" value="' . $data . '" disabled="disabled"><input type="button" class="pointsButt" value="' . $words['adminPage2']['AddPointsButton'][$lang] . '" onclick="studentSendPoints(' . $resultArrayValues[$i][0] . ', ' . $team . ', \'' . $tableName . '\', \'' . $lang . '\')" style="display: none;"></td>' . "\n";
                }
            }else{
                $finalTable = $finalTable . '<td data-table="\'' . $tableName . '\'" data-team="' . $team . '" data-ord="' . $k . '">' . $data . '</td>' . "\n";
            }
        }
        $finalTable = $finalTable . '</tr>' . "\n";
    }

    $finalTable = $finalTable . '</tbody>' . "\n"
        . '</table>' . "\n"
        .$stateButtons;

    echo $finalTable;

    echo '</div>' . "\n";

    echo '<script type="text/javascript">',
        'manageStudentsAjaxCalls('.$team.', \''.$tableName.'\', \''.$messId.'\', \''.$lang.'\', '.$userId.');',
    '</script>';

}
else{
    header('Location: userPage2.php?fillError=1');
    die();
}

doHTMLFooter();