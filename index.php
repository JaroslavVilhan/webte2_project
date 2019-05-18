<?php
require_once("basic_doc_fns.php");

session_start();
if(!isset($_SESSION['user'])) {
    header('Location: login.php');
}

//$_SESSION['userType'] = 'admin';
//echo $_SESSION['user'];

$words = getDictionary();
$lang = getCurrentLanguage($_GET['lang'], $words);
$flag = getFlag($lang);

doHTMLHeader($lang, $words['homePage']['headTitle'][$lang], "homePage");

?>

<div class="pos-f-t mainBar">
    <div class="collapse" id="navbarToggleExternalContent">
        <div class="bg-dark p-4">
            <a class="navbar-brand py-0" href="index.php?lang=<?php echo $flag;?>"><img src="img/<?php echo $flag;?>-shiny.png" alt="flag" height="50" onmouseover="this.src='img/<?php echo $flag;?>.png';" onmouseout="this.src='img/<?php echo $flag;?>-shiny.png';"></a>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="namebold"><?php echo $_SESSION['user'];?></span>
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> <?php echo $words['multiple'][$lang];?></a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
    <nav class="navbar navbar-expand-sm navbar-dark bg-dark">
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <div class="ml-auto">
                <a class="navbar-brand py-0 flag" href="index.php?lang=<?php echo $flag;?>"><img src="img/<?php echo $flag;?>-shiny.png" alt="flag" height="50" onmouseover="this.src='img/<?php echo $flag;?>.png';" onmouseout="this.src='img/<?php echo $flag;?>-shiny.png';"></a>
                <ul class="navbar-nav user">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <span class="namebold"><?php echo $_SESSION['user'];?></span>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="logout.php"><i class="fas fa-sign-out-alt"></i> <?php echo $words['multiple'][$lang];?></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </nav>
</div>


<?php
if($_SESSION['userType'] == 'user'){
    require_once("roles/user.php");
} else if ($_SESSION['userType'] == 'admin'){
    require_once("roles/admin.php");
}

doHTMLFooter();