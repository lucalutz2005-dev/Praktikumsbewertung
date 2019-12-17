<?php

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

#echo("<br />");
#echo("<br />");

require_once "includes/db/config_beantragt.php";
 
// Define variables and initialize with empty values
$name_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    if(empty($name)){
        echo "error2";
        $name_err = "Bitte trage einen Namen ein.";
    } else{
        $sql = "SELECT ID FROM Firmen WHERE Name = '".$name."'";
        $result = $verbindung->query($sql);
            if($result->num_rows > 0) {
                $name_err = 1;
                echo "Dieser Name wird bereits verwendet";
            }
    }
    
    
    if($name_err == ""){
        $timestamp = time(); 
        $sql = "INSERT INTO Firmen (Erstellt, Name, Geschaeftsfuehrer, EMail, tel, Laengengrad, Breitengrad, Land, PLZ, Ort, Strasse, Hausnummer) VALUES ('" . $timestamp . "','". $name . "','" . $geschaeftsfuehrer . "','" .  $email . "','". $tel . "','" . $laengengrad . "','" . $breitegrad  . "','" . $land  . "','" . $PLZ . "','" . $ort . "','" . $strasse  . "','" . $hausnummer . "')";
        #if($stmt = mysqli_prepare($link, $sql)){
            if($verbindung->query($sql) === TRUE){
                #header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
        #}
        #mysqli_stmt_close($stmt);
    }
    #mysqli_close($link);
}

?>

<html>
    <head>
        <title></title>
          <!-- Tempusdominus Bbootstrap 4 -->
          <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
        <!-- Required meta tags-->
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Title Page-->
        <title>Firma registrieren</title>
        <!-- Icons font CSS-->
        <link href="plugins/mdi-font/css/material-design-iconic-font.min.css" rel="stylesheet" media="all">
        <link href="plugins/font-awesome-4.7/css/font-awesome.min.css" rel="stylesheet" media="all">
        <!-- Font special for pages-->
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
        <!-- plugins CSS-->
        <link href="plugins/select2/select2.min.css" rel="stylesheet" media="all">
        <link href="plugins/datepicker/daterangepicker.css" rel="stylesheet" media="all">
        <!-- Main CSS-->
        <link href="assets/css/firma_registrieren.css" rel="stylesheet" media="all">
    </head>
    <body>
    <div class="page-wrapper bg-blue p-t-100 p-b-100 font-robo">
        <div class="wrapper wrapper--w680">
            <div class="card card-1">
                <div class="card-heading"></div>
                <div class="card-body">
                    <h2 class="title">Firmenregistrierung</h2>
                    <form action="/firma_registrieren.php" method="post">
                        <div class="input-group">
                            <input class="input--style-1" type="text" placeholder="Name" id="name" name="name"              />
                        </div>
                        <div class="input-group">
                            <input class="input--style-1" type="text" placeholder="Gesch&uuml;ftsf&uuml;hrer" id="geschaeftsfuehrer" name="geschaeftsfuehrer" /><br />
                        </div>
                        <div class="input-group">
                            <input class="input--style-1" type="text" placeholder="E-Mail" id="email" name="email" /><br />
                        </div>
                        <div class="input-group">
                            <input class="input--style-1" type="text" placeholder="Telefonnummer" id="tel" name="tel" /><br />
                        </div>
                        <div class="input-group">
                            <select id="land" name="land" class="form-control">
                                <?php
                                    $laener_html = file_get_contents('./static/laender.txt', true);
                                    echo $laener_html;
                                ?>
                            </select><br />
                        </div>
                        <div class="input-group">
                            <input type="number" id="PLZ"        name="PLZ"        placeholder="PLZ"          />    
                        </div>
                        <div class="input-group">
                            <input type="text"   id="Ort"        name="ort"        placeholder="Ort"          /> <br />
                        </div>
                        <div class="input-group">
                            <input type="text"   id="Straße"     name="strasse"    placeholder="Stra&szlig;e" />
                        </div>
                        <div class="input-group">
                            <input type="text"   id="Hausnummer" name="hausnummer" placeholder="Hausnummer"   /> <br />
                        </div>
                        <!--
                        <div class="input-group">
                            <input type="submit" />
                        </div>
                        -->
                        <div class="p-t-20">
                            <button class="btn btn--radius btn--green" type="submit">Absenden</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>            
    </form>

        <!-- Jquery JS-->
        <script src="plugins/jquery/jquery.min.js"></script>
        <!-- plugins JS-->
        <script src="plugins/select2/select2.min.js"></script>
        <script src="plugins/datepicker/moment.min.js"></script>
        <script src="plugins/datepicker/daterangepicker.js"></script>
        <!-- Main JS-->
        <script src="assets/js/firma_registrieren.js"></script>
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