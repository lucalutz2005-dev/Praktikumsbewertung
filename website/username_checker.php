<?php
require_once "./config_praktikumsbewertung.php";

$sql = "SELECT Benutzername FROM Benutzer WHERE Benutzername = '".$_POST["Benutzername"]."'";
$result = $verbindung->query($sql);
if($result->num_rows > 0) {
    echo '<div style="margin:0px;padding:0px 15px;line-height:30px;height: 30px;" class="alert alert-danger"  role="alert"> Benutzername nicht verf&uuml;gbar.</div>';

} else {
    echo '<div style="margin:0px;padding:0px 15px;line-height:30px;height: 30px;" class="alert alert-success" role="alert"> Benutzername verf&uuml;gbar.</div>';
}
?>