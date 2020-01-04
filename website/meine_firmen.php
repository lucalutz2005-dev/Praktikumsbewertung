<?php 
include("includes/login/login_check_inhaber.php");
?>

<html>
    <head>
        <title>Firmenverwaltung</title>
        <?php $head = file_get_contents("includes/html/head.html"); 
        echo $head; ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
        <link rel="stylesheet" href="/assets/css/meine_firmen.css"
    </head>
    <body>
        <section id="container">
            <?php
            include("includes/php/sidebar.php");
            ?>
            <div class="se-pre-con"></div>
            <!--main content start-->
            <section id="main-content">
            
            <script>
                document.documentElement.style.overflow = 'hidden';  // firefox, chrome
                document.body.scroll = "no"; // ie only
            </script>

                <section class="wrapper">
                    <div class="row">
  <div class="row">
  <div class="column" style="background-color:#4ECDC4;">
    <h2><a href="firma_registrieren.php" style="color: white;">Firma anlegen</a></h2>
    <p></p>
  </div>
  <div class="column" style="background-color:#FFF;">
    <h2><a href="praktikum_zustimmen.php" style="color: #404040;">Praktikas zustimmen</a></h2>
    <p></p>
  </div>
  <div class="column" style="background-color:#4ECDC4;">
    <h2><a href="praktikum_anlegen.php" style="color: white;">Praktika erstellen</a></h2>
    <p></p>
  </div>
</div>

<!--<div id="mmenu_screen" class="container-fluid main_container bg-primary text-white d-flex">
    <div class="row flex-fill">
        <div class="col-sm-6 h-100">
            <div class="row h-100 bg-warning">
                <div class="col-sm-12" id="mmenu_screen--book">
                    Booking
                </div>
            </div>
        <div class="col-sm-6 mmenu_screen--direktaction bg-faded flex-fill">
            Action
        </div>
    </div>
</div>-->

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