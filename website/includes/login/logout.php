<?php
session_start();
session_destroy();
session_unset();
if ( isset( $_COOKIE[session_name()] ) )
setcookie( session_name(), "", time()-3600, "/" );
//clear session from globals
$_SESSION = array();
//clear session from disk
session_destroy();
header("Location: /firmenliste.php");
?>