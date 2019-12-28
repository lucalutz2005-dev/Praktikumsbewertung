<?php 
session_start();
#echo $_SESSION["ID"];
if(!isset($_SESSION["Eingeloggt"]) || $_SESSION["Rechte"] < 5) {
    header("Location: /includes/login/login.php");
}
?> 