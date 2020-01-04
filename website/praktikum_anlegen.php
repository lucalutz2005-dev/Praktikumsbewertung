<?php 
include("includes/login/login_check_inhaber.php");
?>

<html>
    <head>
        <title>Praktikum registrieren</title>
        <?php $head = file_get_contents("includes/html/head.html"); 
        echo $head; ?>    
    </head>
    <body>
        <section id="container">
            <?php $navbar = file_get_contents("includes/html/navbar.html"); 
            echo $navbar; ?>
            <?php $sidebar = file_get_contents("includes/html/sidebar.html"); 
            if ($_SESSION["Rechte"] >= 5) {
                $test4 = str_replace('id="Admin" style="display: none;"', "", $sidebar); 
                echo  str_replace('%name%', $_SESSION["Benutzername"], $test4);
            } 
            if ($_SESSION["Beruf"] == 6 || $_SESSION["Beruf"] == 4) {
                $test3 = str_replace('id="Beruf" style="display: none;"', "", $sidebar); 
                echo str_replace('%name%', $_SESSION["Benutzername"], $test3);
            } 
            if ($_SESSION["Beruf"] == 6 || $_SESSION["Beruf"] == 4 && $_SESSION["Rechte"] >= 5) {
                $test1 = str_replace('id="Beruf" style="display: none;"', "", $sidebar); 
                $test2 = str_replace('id="Admin" style="display: none;"', "", $test1);
                echo str_replace('%name%', $_SESSION["Benutzername"], $test2);
            }
            if ($_SESSION["Beruf"] != 6 || 4 && $_SESSION["Rechte"] < 5) {
                echo str_replace('%name%', $_SESSION["Benutzername"], $sidebar);
            }
            ?>
            <div class="se-pre-con"></div>
            <!--main content start-->
            <section id="main-content">
                <section class="wrapper">
                    <div class="row" style="margin-left: 15px; margin-right: 15px">
                        <?php
                            require_once "includes/db/config_beantragt.php";
                            require_once "includes/db/config_praktikumsbewertung.php";
                            session_start();
                            if($_SERVER["REQUEST_METHOD"] == "POST"){
                                $sql = "SELECT * FROM Firmen WHERE Ersteller_ID = '".$_SESSION["ID"]."';";
                                $result = mysqli_query($db1, $sql);
                                if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_array($result)) {
                                    $Firmen_ID = $row["ID"];
                                }
                                }
                                $name = $_POST["name"];
                                $Berufsgruppe = $_POST["Berufsgruppe"];
                                #echo $name;
                                $name_err = "";
                                if(empty($name)){
                                    $name_err = "Bitte trage einen Namen ein.";
                                } else{
                                    $sql = "SELECT * FROM Angebote WHERE NameBeruf = '".$name."'";
                                    #echo $sql;
                                    $result = mysqli_query($db1, $sql);
                                        if(mysqli_num_rows($result) > 0) {
                                            $name_err = 1;
                                            echo "<div style='margin-top: 20px; margin-left: px; margin-right: 0px;' class='alert alert-danger alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Dieser Name wird bereits verwendet</div>";
                                        }
                                }
                                $sql = "SELECT * FROM Berufsgruppen WHERE (Name REGEXP '".$Berufsgruppe.".*');";
                                #echo $sql;
                                $result = mysqli_query($db1, $sql);
                                if(mysqli_num_rows($result) > 0) {
                                while($row = mysqli_fetch_array($result)) {
                                    $Berufsgruppen_ID = $row["ID"];
                                    #echo $Berufsgruppen_ID;
                                }
                                }
                                if($name_err == ""){
                                    $timestamp = time(); 
                                    $sql = "INSERT INTO Angebote (NameBeruf, Berufsgruppe, Firmen_ID, Erstellt) VALUES ('" . $name . "','". $Berufsgruppen_ID . "','" . $Firmen_ID . "','" . time() ."')";
                                    $result = mysqli_query($db1, $sql); 
                                    if(mysqli_num_rows($result) > 0){
                                        #header("location: login.php");
                                    } else{
                                        echo "<div style='margin-top: 20px; margin-left: 16px; margin-right: 20px;' class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Das Praktikum wurde angelegt</div>";
                                    }
                                }
                            }
                        ?>
                        </script>
                        <div class="row" style="margin-left: -10px; margin-right: -10px; margin-bottom: 10px;">
                            <div class="row mt">
                                <div class="col-lg-12">
                                    <div class="form-panel">
                                        <h4 class="mb"><i class="fa fa-angle-right"></i> Praktikumsdaten eingeben</h4>
                                        <form class="form-horizontal style-form" method="post">
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Name des Berufs</label>
                                                <div class="col-sm-10">
                                                    <input type="text" class="form-control" id="name" name="name"  required>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Berufsgruppe</label>
                                                <div class="col-sm-10">
                                                    <div id="searchbox">
                                                        <input id="Berufsgruppe" class="form-control" type="text" value="" autofocus="true" name="Berufsgruppe" placeholder=""></input>
                                                        <div id="results">
                                                            <ul id="autocomplete-results" style="padding-inline-start: 0px;">
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <script>
                                                function httpGet(theUrl)
                                                {
                                                  var xmlHttp = null;
                                                
                                                  xmlHttp = new XMLHttpRequest();
                                                  xmlHttp.open( "GET", theUrl, false );
                                                  xmlHttp.send( null );
                                                  return xmlHttp.responseText;
                                                }
                                                $( function() {
                                                    var availableTags = [
                                                        <?php 
                                                            /*require_once "includes/db/config_beantragt.php";
                                                            require_once "includes/db/config_praktikumsbewertung.php";
                                                            $sql = "SELECT * FROM Berufsgruppen";
                                                            $result = mysqli_query($db1, $sql);
                                                            if(mysqli_num_rows($result) > 0) { 
                                                                while($row1234 = mysqli_fetch_array($result)) {
                                                                    echo '"'.$row1234["Name"].'",';
                                                                }
                                                            }*/
                                                        ?>
                                                        "Metallerzeugung und -bearbeitung, Metallbauberufe"
                                                    ];
                                                    var content = httpGet("search.php");
                                                    $( "#Berufsgruppe" ).autocomplete({
                                                        source: "search.php"
                                                    });
                                                } );
                                            </script>
                                            <div class="form-group">
                                                <label class="col-sm-2 col-sm-2 control-label">Beschreibung</label>
                                                <div class="col-sm-10">
                                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                                </div>
                                            </div>
                                            <div class="p-t-20">
                                                <button class="btn btn--radius btn--green" type="submit">Absenden</button>
                                            </div>
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