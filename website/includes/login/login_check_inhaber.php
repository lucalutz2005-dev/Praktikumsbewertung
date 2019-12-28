<?php 
session_start();
#echo $_SESSION["Beruf"];
if(!isset($_SESSION["Eingeloggt"])) {
        header("Location: /includes/login/login.php");
} 
else {
    if ($_SESSION["Beruf"] == 6 || $_SESSION["Beruf"] == 4) {
    } 
    else {
        header("Location: /includes/login/login.php");
    }
}
?> 