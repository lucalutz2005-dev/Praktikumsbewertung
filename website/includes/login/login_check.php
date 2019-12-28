<?php 
session_start();
#echo $_SESSION["ID"];
if(!isset($_SESSION["Eingeloggt"])) {
    header("Location: /includes/login/login.php");
}
?> 