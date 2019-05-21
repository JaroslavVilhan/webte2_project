<?php

session_start();
unset($_SESSION['user']);
unset($_SESSION['userType']);
unset($_SESSION['userID']);

header('Location: login.php');
die();