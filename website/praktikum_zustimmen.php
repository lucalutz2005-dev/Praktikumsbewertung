<?php 
include("includes/login/login_check_inhaber.php");
?>



<html>
    <head>
        <title>Praktikum best&auml;tigen</title>
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
                <section class="wrapper">
                    <div class="row">
                    <?php
    $erfolgreich = 0;
    $name_err = 0;
    require_once "includes/db/config_beantragt.php";
    require_once "includes/db/config_praktikumsbewertung.php";
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $action = $_GET["action"];
        $id = $_GET["id"];
        if($action == "remove") {
            $sql = "DELETE FROM Bewertungen WHERE ID = '".$id."';";
            $result = mysqli_query($db2, $sql);
            $erfolgreich = 1;
        }
        if($action == "uebernehmen") {
            $sql = "SELECT * FROM Bewertungen WHERE ID = '".$id."'";
            $result = mysqli_query($db2, $sql);
            $reihe1 = mysqli_fetch_array($result);
            #echo $reihe1["EMail"];
            $sql = "SELECT ID FROM Bewertungen WHERE Bewerter_ID = '".$reihe1['Bewerter_ID']."' && Firmen_ID = '".$reihe1["Firmen_ID"]."' && Praktika_ID = '".$reihe1["Praktika_ID"]."'";
            #echo $sql;
            $result = mysqli_query($db1, $sql);
            if(mysqli_num_rows($result) > 0) { 
                $name_err = 1;
                echo "<div style='margin-top: 20px; margin-left: 16px; margin-right: 20px;' class='alert alert-danger alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Dieser Praktikant hat dieses Praktikum bereits bewertet</div>";
            } 
            if ($name_err == 0) {
                $sql = "SELECT ID,Praktika_ID,Bewertung_1,Bewertung_2,Bewertung_3,Bewertung_4,Bewertung_5,Bewerter_ID,Firmen_ID,Erstellt FROM Bewertungen WHERE ID ='".$id."'";
                $result_uebernehmen = mysqli_query($db2, $sql);
                if(mysqli_num_rows($result_uebernehmen) > 0) {
                    $reihe = mysqli_fetch_array($result_uebernehmen);
                    $sql = "INSERT INTO Bewertungen (Praktika_ID, Bewertung_1, Bewertung_2, Bewertung_3, Bewertung_4, Bewertung_5, Bewerter_ID, Firmen_ID, Erstellt, Verfizierer_ID, Verifiziert) VALUES ('".$reihe['Praktika_ID']."', '".$reihe['Bewertung_1']."', '".$reihe['Bewertung_2']."', '".$reihe['Bewertung_3']."', '".$reihe['Bewertung_4']."', '".$reihe['Bewertung_5']."', '".$reihe['Bewerter_ID']."', '".$reihe['Firmen_ID']."', '".$reihe['Erstellt']."', '".$_SESSION['ID']."', '". time() ."');";
                    #echo $sql;
                    $result_uebernehmen = mysqli_query($db1, $sql);
                    echo "<div style='margin-top: 20px; margin-left: 16px; margin-right: 20px;' class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Deine Bewertung wurde eingereicht</div>";
                    #echo "Es hat geklappt";     
                    $sql = "DELETE FROM Bewertungen WHERE ID = '".$id."';";
                    #echo $sql;
                    $result = mysqli_query($db2, $sql);
                    if (mysqli_num_rows($result)  > 0) {
                    #    echo "Erfolg";
                    }
                    $erfolgreich = 1;   
                }
            }
        }
    }
?>
                    <div class="row" style="margin-left: 15px; margin-right: 15px; margin-bottom: 10px;">
                    <div class="row mt">
                        <div class="col-lg-12">
                            <div class="content-panel">
                                <h4><i class="fa fa-angle-right"></i> Praktikum best&auml;tigen f&uuml;r</h4>
                                <section id="unseen">
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Operationen</th>
                                                <th>Erstelldatum</th>
                                                <th>Name Praktikant</th>
                                                <th>Name Beruf</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                                        require_once "includes/db/config_beantragt.php";
                                                                        require_once "includes/db/config_praktikumsbewertung.php";
                                                                        $sql = "SELECT * FROM Firmen WHERE Ersteller_ID = '".$_SESSION["ID"]."';";
                                                                        //echo $sql;
                                                                        $result = mysqli_query($db1, $sql);
                                                                        if(mysqli_num_rows($result) > 0) {
                                                                        while($row = mysqli_fetch_array($result)) {
                                                                            $Firmen_ID = $row["ID"];
                                                                            echo '<h4><i class="fa fa-angle-right"></i> '.$row["Name"].":</h4><br />";
                                                                            #echo $Firmen_ID;
                                                                            $sql = "SELECT * FROM Bewertungen WHERE Firmen_ID = '".$Firmen_ID."';";
                                                                            //echo $sql;
                                                                            $result1 = mysqli_query($db2, $sql);
                                                                            if(mysqli_num_rows($result1) > 0) {
                                                                                while($row = mysqli_fetch_array($result1)) {
                                                                                    #echo "Hallo";
                                                                                    $ID = $row["ID"];
                                                                                    $Bewerter_ID = $row["Bewerter_ID"];
                                                                                    $Angebot_ID = $row["Praktika_ID"];
                                                                                    $Erstelltimestamp = $row["Erstellt"];
                                                                                    $sql = "SELECT Benutzername FROM Benutzer WHERE ID = '".$Bewerter_ID."'";
                                                                                    $result = mysqli_query($db1, $sql);
                                                                                    if(mysqli_num_rows($result) > 0) {
                                                                                        $row = mysqli_fetch_array($result);
                                                                                        $Bewerter_Name = $row["Benutzername"];
                                                                                    }
                                                                                    $sql = "SELECT Benutzername FROM Benutzer WHERE ID = '".$Bewerter_ID."'";
                                                                                    $result = mysqli_query($db1, $sql);
                                                                                    if(mysqli_num_rows($result) > 0) {
                                                                                        $row = mysqli_fetch_array($result);
                                                                                        $Bewerter_Name = $row["Benutzername"];
                                                                                    }
                                                                                    $sql = "SELECT NameBeruf FROM Angebote WHERE ID = '".$Angebot_ID."'";
                                                                                    $result = mysqli_query($db1, $sql);
                                                                                    if(mysqli_num_rows($result) > 0) {
                                                                                        $row = mysqli_fetch_array($result);
                                                                                        $Praktikums_Name = $row["NameBeruf"];
                                                                                    }

                                                                                    echo "<tr>"; 
                                                                                    echo "<td style='text-align:center;'> <a href='"."?action=remove&id=".$ID."'><i class='fa fa-ban'></i></a>&#160&#160&#160&#160<a href='"."?action=uebernehmen&id=".$ID."'><i class='fa fa-check'></i></a></td>"; 
                                                                                    echo "<td>" . gmdate("Y-m-d\ H:i:s", $Erstelltimestamp) . "</td>"; 
                                                                                    echo "<td>" . $Bewerter_Name . "</td>"; 
                                                                                    echo "<td>" . $Praktikums_Name . "</td>"; 
                                                                                    echo "</tr>";

                                                                                }
                                                                            }
                                                                        }
                                                                    }
                                            ?>
                                                        </tbody>
                                                        </table>
                                                        </section>
                                                        </div>
                                                        </div>
                                                        </div>
                                                        </div>

                                                        <?php
                                                        if($erfolgreich == 1) {
                                                            echo "<script>";
                                                            echo "var url = window.location.href;";
                                                            echo "if(url.indexOf('?') != -1) {";
                                                            echo '  window.location = window.location.href.split("?")[0];';
                                                            echo "}";
                                                            echo "</script>";
                                                        }
                                                        ?>

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