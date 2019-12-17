<?php
define("DB_Server", "localhost");
define("DB_Benutzer", "luca");
define("DB_Passwort", "LiviT2005");
define("DB_Name", "praktikumsbewertung");
define("DB_Port", "3306");

$verbindung = new mysqli(DB_Server, DB_Benutzer, DB_Passwort, DB_Name, DB_Port);
// Ueberpuefe Verbindung
if($verbindung === false){
    die("FEHLER: Kann nicht verbinden. " . mysqli_connect_error());
}
?>