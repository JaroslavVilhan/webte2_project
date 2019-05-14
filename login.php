<?php
require_once("basic_doc_fns.php");

$words = getDictionary();
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);

session_start();
if(isset($_SESSION['user'])) {
    header('Location: index.php');
}

doHTMLHeader($lang, $words['loginPage']['headTitle'][$lang], "loginPage");
?>

<div class="pos-f-t mainBar">
    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-dark p-4">
            <a class="navbar-brand py-0" href="index.php?lang=<?php echo $flag;?>"><img src="img/<?php echo $flag;?>-shiny.png" alt="flag" height="50" onmouseover="this.src='img/<?php echo $flag;?>.png';" onmouseout="this.src='img/<?php echo $flag;?>-shiny.png';"></a>
        </div>
    </div>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <a class="navbar-brand py-0 ml-auto" href="index.php?lang=<?php echo $flag;?>"><img src="img/<?php echo $flag;?>-shiny.png" alt="flag" height="50" onmouseover="this.src='img/<?php echo $flag;?>.png';" onmouseout="this.src='img/<?php echo $flag;?>-shiny.png';"></a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
</div>

<?php
if(isset($_POST['login']) && isset($_POST['password'])){
    if($_GET['method']=='ldap'){
        $ldapuid = $_POST['login'];
        $ldappass = $_POST['password'];

        $dn  = 'ou=People, DC=stuba, DC=sk';
        $ldaprdn  = "uid=$ldapuid, $dn";

        $ldapconn = ldap_connect("ldap.stuba.sk")
        or die("Could not connect to LDAP server.");

        $set = ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

        if ($ldapbind) {
            $sr = ldap_search($ldapconn, $ldaprdn, "uid=". $ldapuid);
            $entry = ldap_first_entry($ldapconn, $sr);

            $usrId = ldap_get_values($ldapconn, $entry, "uisid")[0];
            $usrName = ldap_get_values($ldapconn, $entry, "givenname")[0];
            $usrSurname = ldap_get_values($ldapconn, $entry, "sn")[0];

            $_SESSION['user'] = $usrName ." ". $usrSurname;
            $_SESSION['userType'] = "user";
            header('Location: index.php');
        } else {
            echo '<div class=container>'."\n"
                .'<div class="alert alert-danger" role="alert">'."\n"
                .'<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>'."\n"
                .'<span class="bold">Error: </span>'.$words['loginPage']['loginError'][$lang]."\n"
                .'</div>'."\n"
                .'</div>';
        }
    } else if($_GET['method']=='local'){
        $_SESSION['userType'] = "user";
        header('Location: index.php');

    } else if($_GET['method']=='admin'){
        $_SESSION['userType'] = "admin";
        header('Location: index.php');
    }
}
?>

<div class="container-fluid h-100">
    <div class="row justify-content-center h-100">
        <div class="col-sm-2">
        </div>
        <div class="col-sm-4">
            <div class="page-header my-4">
                <h1><?php echo $words['loginPage']['title'][$lang]?></h1>
            </div>
            <form id="loginForm" action="" method="post">
                <div class="form-group">
                    <div class="form-group">
                        <input type="text" class="form-control" id="login" name="login" placeholder="<?php echo $words['loginPage']['name'][$lang]?>" required>
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="password" name="password" placeholder="<?php echo $words['loginPage']['password'][$lang]?>" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="btn-group btn-group-toggle" data-toggle="buttons">
                        <label class="btn btn-secondary active">
                            <input type="radio" name="options" id="option1" autocomplete="off" checked><?php echo $words['loginPage']['loginAs1'][$lang]."\n"?>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="options" id="option2" autocomplete="off"><?php echo $words['loginPage']['loginAs2'][$lang]."\n"?>
                        </label>
                        <label class="btn btn-secondary">
                            <input type="radio" name="options" id="option3" autocomplete="off"><?php echo $words['loginPage']['loginAs3'][$lang]."\n"?>
                        </label>
                    </div>
                </div>
                <div class="submitBut">
                    <button type="submit" onclick="changeLoginType()" class="btn btn-primary"><?php echo $words['loginPage']['login'][$lang]?></button>
                </div>
            </form>
        </div>
        <div class="col-sm-2">
        </div>
    </div>
</div>


<?php
doHTMLFooter();

