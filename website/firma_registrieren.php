<?php 
include("includes/login/login_check_inhaber.php");
?>

<html>
    <head>
        <title>Firma registrieren</title>
        <?php $head = file_get_contents("includes/html/head.html"); 
        echo $head; ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
    </head>
    <body>
        <section id="container">
            <?php
                include("includes/php/sidebar.php");
            ?>
            <div class="se-pre-con"></div>
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">
                    <div class="row" style="margin-left: 15px; margin-right: 15px">
                        <?php
                            session_start();
                            if($_SERVER["REQUEST_METHOD"] == "POST"){
                                $name               = $_POST["name"];
                                $geschaeftsfuehrer  = $_POST["geschaeftsfuehrer"];
                                $land               = $_POST["land"];
                                $PLZ                = $_POST["PLZ"];
                                $ort                = $_POST["ort"];
                                $strasse            = $_POST["strasse"];
                                $hausnummer         = $_POST["hausnummer"];
                                $email              = $_POST["email"];
                                $tel                = $_POST["tel"];

                                $url = "https://nominatim.openstreetmap.org/search?q=".urlencode($strasse  . " " . $hausnummer . " " . $PLZ . " " . $ort . " " . $land) . "&format=json";
                                $ergebniss = get_web_page($url);
                                $test = json_decode($ergebniss);
                                $laengengrad = $test[0]->{"lat"};
                                $breitegrad  = $test[0]->{"lon"};
                                require_once "includes/db/config_beantragt.php";
                                $name_err = "";
                                if(empty($name)){
                                    echo "error2";
                                    $name_err = "Bitte trage einen Namen ein.";
                                } else{
                                    $sql = "SELECT ID FROM Firmen WHERE Name = '".$name."'";
                                    $result = mysqli_query($db2, $sql);
                                        if(mysqli_num_rows($result) > 0) {
                                            $name_err = 1;
                                            echo "<div style='margin-top: 20px; margin-left: 16px; margin-right: 20px;' class='alert alert-danger alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Dieser Name wird bereits verwendet</div>";
                                        }
                                }
                                if($name_err == ""){
                                    $timestamp = time(); 
                                    $sql = "INSERT INTO Firmen (Erstellt, Name, Geschaeftsfuehrer, EMail, tel, Laengengrad, Breitengrad, Land, PLZ, Ort, Strasse, Hausnummer, Ersteller_ID) VALUES ('" . $timestamp . "','". $name . "','" . $geschaeftsfuehrer . "','" .  $email . "','". $tel . "','" . $laengengrad . "','" . $breitegrad  . "','" . $land  . "','" . $PLZ . "','" . $ort . "','" . $strasse  . "','" . $hausnummer . "','" . $_SESSION["ID"] ."')";
                                    $result = mysqli_query($db2, $sql); 
                                    if(mysqli_num_rows($result) > 0){
                                        #header("location: login.php");
                                    } else{
                                        echo "<div style='margin-top: 20px; margin-left: 16px; margin-right: 20px;' class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Die Firma wurde beantragt</div>";
                                    }
                                }
                            }
                        ?>
                        <div class="row" style="margin-left: -10px; margin-right: -10px; margin-bottom: 10px;">
                            <div class="row mt">
                                <div class="col-lg-12">
                                    <div class="form-panel">
                                        <h4 class="mb"><i class="fa fa-angle-right"></i> Firmendaten eingeben</h4>
                                        <form class="form-horizontal style-form" method="post">
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Name</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="name" name="name"  required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Gesch&auml;ftsf&uuml;hrer</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="geschaeftsfuehrer" name="geschaeftsfuehrer"  required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">E-Mail</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="email" name="email"  required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Telefonnummer</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="tel" name="tel"  required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Land</label>
                                                <div class="col-sm-10">
                                                    <select class="form-control" id="land" name="land">
                                                        <?php
                                                            $laener_html = file_get_contents('./static/laender.txt', true);
                                                            echo $laener_html;
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">PLZ</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="PLZ" name="PLZ"  required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Ort</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="Ort" name="ort" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Stra&szlig;e</label>
                                                <div class="col-sm-10">
                                                    <input required type="text" class="form-control" id="strasse" name="strasse" required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Hausnummer</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="hausnummer" name="hausnummer" required>
                                                </div>
                                            </div>
                                            <div class="p-t-20">
                                                <button class="btn btn--radius btn--green" type="submit">Absenden</button>
                                            </div>
                                            <!--<div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Help text</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control">
                                                    <span class="help-block">A block of help text that breaks onto a new line and may extend beyond one line.</span>
                                                </div>
                                            </div>
                                        <div class="form-group">
                                          <label class="col-sm-2 col-sm-2 control-label">Password</label>
                                          <div class="col-sm-10">
                                            <input type="password" class="form-control" placeholder="">
                                          </div>
                                        </div>-->

                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
            </section>
        </section>    
        <?php $footer = file_get_contents("includes/html/footer.html"); 
        echo $footer; ?>
        </div>
        <?php $scripts = file_get_contents("includes/html/javascript.html"); 
        echo $scripts; ?>
    </body>
</html>


<?php
/**
 * Get a web file (HTML, XHTML, XML, image, etc.) from a URL.  Return an
 * array containing the HTTP server response header fields and content.
 */
function get_web_page( $url )
{
    $options = array(
        CURLOPT_RETURNTRANSFER => true,     // return web page
        CURLOPT_HEADER         => false,    // don't return headers
        CURLOPT_FOLLOWLOCATION => true,     // follow redirects
        CURLOPT_ENCODING       => "",       // handle all encodings
        CURLOPT_USERAGENT      => "LordVoldemord", // who am i
        CURLOPT_AUTOREFERER    => true,     // set referer on redirect
        CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
        CURLOPT_TIMEOUT        => 120,      // timeout on response
        CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        CURLOPT_SSL_VERIFYPEER => false     // Disabled SSL Cert checks
    );

    $ch      = curl_init( $url );
    curl_setopt_array( $ch, $options );
    $content = curl_exec( $ch );
    $err     = curl_errno( $ch );
    $errmsg  = curl_error( $ch );
    $header  = curl_getinfo( $ch );
    curl_close( $ch );

    $header['errno']   = $err;
    $header['errmsg']  = $errmsg;
    $header['content'] = $content;
    #return $header;
    return $content;
}
?>