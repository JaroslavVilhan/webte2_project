<?php
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
        .'<strong>Success! </strong>'.$words['adminPage3']['successSend'][$lang]."\n"
        .'</div>'."\n"
        .'</div>';
}


//$sql = "SELECT  `mime`, `id` FROM `Templates` WHERE `mime` = 'text/html'";
//$result = $mysqli->query($sql);
//if ($result === FALSE) {
//    echo "Error: " . $sql . "<br>" . $mysqli->error;
//    die();
//}
//$obj = $result->fetch_object();
//echo $obj->data;

?>

<div class="container">
    <label for="sel1"><?php echo $words['adminPage1']['selectTitle'][$lang];?>:</label>
    <div class="d-flex flex-row">
    <select class="form-control mr-2" id="sel1">
        <option value="1" selected><?php echo $words['adminPage3']['form1Head'][$lang];?></option>
        <option value="2"><?php echo $words['adminPage3']['form2Head'][$lang];?></option>
        <option value="3"><?php echo $words['adminPage3']['form3Head'][$lang];?></option>
    </select>
    <button type="button" class="btn btn-primary" id="sel1Button" onclick="selectOption()"><?php echo $words['adminPage1']['selButton'][$lang];?></button>
    </div>

    <form action="adminPage3.php?form=1" method="post" enctype="multipart/form-data" id="selForm1">
        <h3 class="text-center"><?php echo $words['adminPage3']['form1Head'][$lang];?></h3>
        <div class="form-group">
            <label for="fileToUpload"><?php echo $words['adminPage1']['formFile'][$lang];?>:</label>
            <input type="file" name="fileToUpload" id="fileToUpload" required>
        </div>
        <div class="form-group">
            <label for="oddelovac"><?php echo $words['adminPage1']['formFileDelimiter'][$lang];?>:</label>
            <input type="text" name="form1delimiter" class="form-control" id="oddelovac" required>
        </div>
        <input type="hidden" id="form1hidden" name="form1hidden" value="form1course">
        <button type="submit" name="submit" class="btn btn-primary"><?php echo $words['adminPage2']['AddPointsButton'][$lang];?></button>
    </form>


    <form action="adminPage3sendMail.php" method="post" enctype="multipart/form-data" id="selForm2">
        <h3 class="text-center"><?php echo $words['adminPage3']['form2Head'][$lang];?> - <?php echo $words['adminPage3']['step'][$lang];?> 1/2</h3>
        <div class="form-group mt-3">
            <label for="fileToUpload"><?php echo $words['adminPage1']['formFile'][$lang];?>:</label>
            <input type="file" name="fileToUpload" id="fileToUpload" required>
        </div>
        <div class="form-group">
            <label for="oddelovac"><?php echo $words['adminPage1']['formFileDelimiter'][$lang];?>:</label>
            <input type="text" name="form1delimiter" class="form-control" id="oddelovac" required>
        </div>
        <div class="form-group">
            <label for="sendOptions"><?php echo $words['adminPage3']['sendOptions'][$lang];?>:</label>
            <div class="btn-group btn-group-toggle" data-toggle="buttons" id="sendOptions">
                <label class="btn btn-secondary active">
                    <input type="radio" name="options" id="option1" value="1" autocomplete="off" checked> text/plain
                </label>
                <label class="btn btn-secondary">
                    <input type="radio" name="options" id="option2" value="2" autocomplete="off"> text/html
                </label>
            </div>
        </div>
        <label for="template"><?php echo $words['adminPage3']['ChooseTemp'][$lang];?>:</label>
            <select class="form-control mr-2" id="template" name="selectedTemplates">
                <?php
                $sql = "SELECT `id` FROM `Templates`";
                $result = $mysqli->query($sql);
                if ($result === FALSE) {
                    echo "Error: " . $sql . "<br>" . $mysqli->error;
                    die();
                }
                while($obj = $result->fetch_object()){
                    echo '<option value="'.$obj->id.'">id: '.$obj->id.'</option>'."\n";
                }
                ?>
            </select>
        <p><button type="submit" name="submit" class="btn btn-primary mt-4">2. <?php echo $words['adminPage3']['step'][$lang];?></button></p>
    </form>
</div>




<?php
//------------------------------vypis tabulky o odoslanych mailoch---------------------------
$sql = "SELECT `Datum`, `Meno`, `Predmet`, `SablonaID` FROM `Historia`";
$result = $mysqli->query($sql);
if ($result === FALSE) {
    echo "Error: " . $sql . "<br>" . $mysqli->error;
    die();
}
$resultArray = $result->fetch_all(MYSQLI_NUM);


echo '<div class="container" id="selForm3">' ."\n"
    .'<table id="historyTable" class="table table-striped table-responsive-sm" style="width:100%">' ."\n"
    .'<thead class="thead-dark">'."\n"
    .'<tr>'."\n"
    .'<th class="hisTabHead">'.$words['adminPage3']['tableDate'][$lang].'</th>'."\n"
    .'<th class="hisTabHead">'.$words['adminPage3']['tableName'][$lang].'</th>'."\n"
    .'<th class="hisTabHead">'.$words['adminPage3']['tableSubject'][$lang].'</th>'."\n"
    .'<th class="hisTabHead">'.$words['adminPage3']['tableTempID'][$lang].'</th>'."\n"
    .'</tr>'."\n"
    .'</thead>'."\n"
    .'<tbody>'."\n";
for($n=0; $n<count($resultArray); $n++){
    echo '<tr>'."\n";
    foreach ($resultArray[$n] as $value){
        echo '<td>'.$value.'</td>'."\n";
    }
    echo '</tr>'."\n";
}
echo '</tbody>'."\n"
    .'</table>'."\n"
    .'</div>'."\n";



if(isset($_GET['form'])){
    if(isset($_POST['submit']) && $_GET['form'] == '1'){
        echo '<script type="text/javascript">',
            'hideSelectCell();',
        '</script>';
        if ($_FILES['fileToUpload']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
            $string = file_get_contents($_FILES['fileToUpload']['tmp_name']);

            $string = trim($string);
            $lines = explode("\n", $string);
            $head = explode(trim($_POST['form1delimiter']), $lines[0]);

            $head[4]='heslo';


            $array = array();

            for($i=1; $i<count($lines);$i++) {
                $values = explode(trim($_POST['form1delimiter']), $lines[$i]);

                $array[$i] = array();

                $array[$i][0]= $values[0];
                $array[$i][1] = $values[1];
                $array[$i][2] = $values[2];
                $array[$i][3] = $values[3];
                $array[$i][4] = generatePassword(15);
            }
            $_SESSION['headValues'] = $head;
            $_SESSION['array'] = $array;


            echo '<div class="container mt-5"><p><a class="btn btn-success mr-3" href="adminPage3downloadLink.php">Link</a></p></div>' . "\n";
            //echo '<div class="container mt-5"><p><a class="btn btn-success mr-3" href="adminPage3.php">' . $words['adminPage3']['Back'][$lang] . '</a><a class="btn btn-primary" href="adminPage3generateCSV.php" target="_blank">' . $words['adminPage3']['Download'][$lang] . '</a></p></div>' . "\n";

        }else{
            echo "ERROR FILE !\n";
        }


    }
//    else if(isset($_POST['submit']) && $_GET['form'] == '2') {
//        echo '2';
//    }
//    }else if($_GET['form'] == '3'){
//        echo'3';
//    }


}else{
    header('Location: adminPage3.php?fillError=1');
    die();
}

?>

<?php
doHTMLFooter();