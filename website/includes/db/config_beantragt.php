<?php
define("DB_Server1", "localhost");
define("DB_Benutzer1", "luca");
define("DB_Passwort1", "LiviT2005");
define("DB_Name1", "beantragt");
define("DB_Port1", "3306");

$db2 = mysqli_connect(DB_Server1, DB_Benutzer1, DB_Passwort1) or die("Unable to connect to MySQL2");
mysqli_select_db($db2 , DB_Name1);

#$verbindung1 = new mysqli(DB_Server, DB_Benutzer, DB_Passwort, DB_Name, DB_Port);
// Ueberpuefe Verbindung
#if($verbindung1 === false){
#    die("FEHLER: Kann nicht verbinden. " . mysqli_connect_error());
#}
?>