<?php 
require_once "../db/config_praktikumsbewertung.php";
session_start();

#header("Location: https://www.google.de");

if($_SERVER["REQUEST_METHOD"] == "POST"){

//login
if(isset($_POST["email"])) {
$email    = $_POST["email"];
$passwort = $_POST["passwort"];
$passwort_hash = md5($passwort);
if(empty($email)){
    $name_err = "Bitte trage deinen Namen ein.";
} else{
    $sql = "SELECT ID,Passwort,EMail,Benutzername FROM Benutzer WHERE EMail = '".$email."'";
    $result = $verbindung->query($sql);
        if($result->num_rows > 0) {
            $name_err = 1;
            $row = $result->fetch_assoc();
            $mysql_passwort = $row["Passwort"];
            #echo($mysql_passwort);
            #echo("<br />");
            #echo($passwort_hash);
            #echo("<br />");
            if($mysql_passwort === $passwort_hash) 
            {
                session_start();
                #echo("Du bist eingeloggt");
                $_SESSION["ID"]             = $row["ID"];
                #echo($_SESSION["ID"]);
                $_SESSION["Email"]          = $row["EMail"];
                $_SESSION["Benutzername"]   = $row["Benutzername"];
                $_SESSION["Eingeloggt"]     = "1";
                $_SESSION["tier"]           = "Hund";
                #echo("<br />");
                #echo "<script type='text/javascript'>window.top.location='http://website.com/';</script>";
                header("Location: /secret.php");

            }
            else {
                echo("Dein Passwort ist leider falsch");
                //session_destroy();
            }
        }
        else {
            echo "Du bist leider nicht registriert";
            //session_destroy();
        }
    }
}
}
if (isset($_POST["reg_email"])) {
    $benutzername_reg   = $_POST["reg_benutzername"];
    $email_reg          = $_POST["reg_email"];
    $passwort_reg       = $_POST["reg_passwort"];
    $beruf_reg          = $_POST["beruf"];

    $meldungen = "<br />" . $benutzername_reg . "<br />" . $email_reg . "<br />" . $passwort_reg . "<br />" . $beruf_reg;

    if(empty($email_reg)){
        $meldungen = "Bitte trage deinen Namen ein.";
    } 
    else{
        $sql = "SELECT ID,Passwort,EMail,Benutzername FROM Benutzer WHERE Benutzername = '".$benutzername_reg."'";
        $result = $verbindung->query($sql);
        if($result->num_rows > 0) {
            echo "Dein Benutzername ist schon registriert";
        }
        else {
            $sql = "SELECT ID,Passwort,EMail,Benutzername FROM Benutzer WHERE EMail = '".$email_reg."'";
            $result = $verbindung->query($sql);
            if($result->num_rows > 0) {
                echo "Deine E-Mail ist bereits registriert";
            } 
            else {
                echo "alles gut";
                $sql = "INSERT INTO Benutzer (Benutzername, Passwort, EMail, Beruf, Rechte) VALUES ('".$benutzername_reg."', '".md5($passwort_reg)."', '".$email_reg."', '".$beruf_reg."', '"."0"."')";
                $result = $verbindung->query($sql);
                if($result->num_rows > 0) {
                    echo "Es ist ein Fehler aufgetreten";
                } 
                else {
                    
                    echo "Erfolgreich registriert";
                    $sql = "SELECT ID,Passwort,EMail,Benutzername FROM Benutzer WHERE Benutzername = '".$benutzername_reg."'";
                    $result = $verbindung->query($sql);
                    if($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        $_SESSION["ID"]             = $row["ID"];
                        $_SESSION["Email"]          = $row["EMail"];
                        $_SESSION["Benutzername"]   = $row["Benutzername"];
                        $_SESSION["Eingeloggt"]     = "1";
                        $_SESSION["tier"]           = "Hund";
                        header("Location: /secret.php");
                    } 
                }
            }
        }
    }
}
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Login</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="/assets/css/login.css"  type="text/css">
        <link rel="stylesheet" href="/librarys/bootstrap/css/bootstrap.css"  type="text/css">
        <link rel="stylesheet" href="/librarys/bootstrap/css/bootstrap.min.css"  type="text/css">
        <link rel="stylesheet" href="/assets/css/login_navbar.css" type="text/css">
	</head>
	<body style= "padding-top: 65px;">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top" role="navigation">
            <div class="container">
                <a class="navbar-brand" href="#">Brand</a>
                <button class="navbar-toggler border-0" type="button" data-toggle="collapse" data-target="#exCollapsingNavbar">
                    &#9776;
                </button>
                <div class="collapse navbar-collapse" id="exCollapsingNavbar">
                    <ul class="nav navbar-nav">
                        <li class="nav-item"><a href="#" class="nav-link">Firmen</a></li>
                        <!--<li class="nav-item"><a href="#" class="nav-link">Registrieren</a></li>
                        <li class="nav-item"><a href="#" class="nav-link">Service</a></li>-->
                        <li class="nav-item"><a href="#" class="nav-link">&Uuml;ber</a></li>
                    </ul>
                    <ul class="nav navbar-nav flex-row justify-content-between ml-auto">
                        <!--<li class="nav-item order-1 order-md-1"><a href="#" class="nav-link" title="settings"><i class="fa fa-cog fa-fw fa-lg"></i></a></li>-->
                        <li class="nav-item order-1 order-md-1"><a href="#" data-toggle="modal" data-target="#modalRegistrieren" class="nav-link">Registrieren</a></li>
                        <li class="dropdown order-2">
                            <button type="button" id="dropdownMenu1" data-toggle="dropdown" class="btn btn-outline-secondary dropdown-toggle">Einloggen <span class="caret"></span></button>
                            <ul class="dropdown-menu dropdown-menu-right mt-2">
                               <li class="px-3 py-2">
                                   <form class="form" role="form" action="login.php" method="POST">
                                        <div class="form-group">
                                            <input id="email" name="email" placeholder="E-Mail" class="form-control form-control-sm" type="text" required="">
                                        </div>
                                        <div class="form-group">
                                            <input id="passwort" name="passwort" placeholder="Passwort" class="form-control form-control-sm" type="password" required="">
                                        </div>
                                        <div class="form-group">
                                            <button type="submit" class="btn btn-primary btn-block">Einloggen</button>
                                        </div>
                                        <div class="form-group text-center">
                                            <small><a href="#" data-toggle="modal" data-target="#modalRegistrieren">Noch kein Account?</a></small>
                                        </div>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <script src="/librarys/jquery/jquery.min.js"></script>
        <script src="/librarys/bootstrap/js/bootstrap.bundle.min.js"></script>
        <div id="modalRegistrieren" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <form action="login.php" method="POST">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3>Registrieren</h3>
                        <button type="button" class="close font-weight-light" data-dismiss="modal" aria-hidden="true">×</button>
                    </div>
                    <div class="modal-body">
                        <p>Lege dir einen Account an.</p>
                            <div class="form-group">
                            <div class="input-group">
                                <input id="reg_benutzername" onBlur="checkAvailability()" name="reg_benutzername" placeholder="Benutzername" class="form-control form-control-sm" type="text" required="">
                                <div id="user-availability-status"></div> 
                                <!--<div id="laedt">l&auml;dt</div> -->
                            </div>

                            </div>
                            <div class="form-group">
                                <input id="reg_email" name="reg_email" placeholder="E-Mail" class="form-control form-control-sm" type="text" required="">
                            </div>
                            <div class="form-group">
                                <input id="reg_passwort" name="reg_passwort" placeholder="Passwort" class="form-control form-control-sm" type="password" required="">
                            </div>
                            <select class="form-control" id="beruf" name="beruf" >
                                <!--<option value="audi">Beruf</option>-->
                                <option value="1">Sch&uuml;ler/in</option>
                                <option value="2">Auszubildender/in</option>
                                <option value="3">Angestellte/r</option>
                                <option value="4">Sekret&auml;r/in</option>
                                <option value="5">Berufsberater/in</option>
                                <option value="6">Firmen-Inhaber/in</option>
                            </select>
                            <br />
                            <script>
                            function checkAvailability() {
                            //$("#laedt").show();
                            jQuery.ajax({
                            url: "username_checker.php",
                            data:'Benutzername='+$("#reg_benutzername").val(),
                            type: "POST",
                            success:function(data){
                            $("#user-availability-status").html(data);
                            //$("#laedt").hide();
                            },
                            error:function (){}
                            });
                            }
                            </script>
                            <!--
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block">Registrieren</button>
                            </div>
                            <div class="form-group text-center">
                                <small><a href="#" data-toggle="modal" data-target="#modalRegistrieren">Noch kein Account?</a></small>
                            </div> 
                            -->


                    </div>
                    <div class="modal-footer">
                            <button class="btn" data-dismiss="modal" aria-hidden="true">Schlie&szlig;en</button>
                            <button type="submit" class="btn btn-primary">Registrieren</button>
                        </div>
                        </form>
                </div>
            </div>
        </div>      
        <?php echo $meldungen;?>

	</body>
</html>