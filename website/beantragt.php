<?php 
include("includes/login/login_check_admin.php");
?>

<html>
    <head>
        <title>Bentragte Firmen</title>
        <?php $head = file_get_contents("includes/html/head.html"); 
        echo $head; ?>
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
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
    $erfolgreich = 0;
    $name_err = 0;
    require_once "includes/db/config_beantragt.php";
    require_once "includes/db/config_praktikumsbewertung.php";
    if($_SERVER["REQUEST_METHOD"] == "GET")
    {
        $action = $_GET["action"];
        $id = $_GET["id"];
        if($action == "remove") {
            $sql = "DELETE FROM Firmen WHERE ID = '".$id."';";
            $result = mysqli_query($db2, $sql);
            $erfolgreich = 1;
        }
        if($action == "uebernehmen") {
            $sql = "SELECT Name,EMail FROM Firmen WHERE ID = '".$id."'";
            $result = mysqli_query($db2, $sql);
            $reihe1 = mysqli_fetch_array($result);
            #echo $reihe1["EMail"];
            $sql = "SELECT ID FROM Firmen WHERE LOWER(`Name`) = '".strtolower($reihe1['Name'])."'";
            #echo $sql;
            $result = mysqli_query($db1, $sql);
            if(mysqli_num_rows($result) > 0) {
                $name_err = 1;
                echo "<div style='margin-top: 20px; margin-left: 16px; margin-right: 20px;' class='alert alert-danger alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Dieser Name wird bereits verwendet</div>";
            } 
            $sql = "SELECT ID FROM Firmen WHERE LOWER(`EMail`) = '".strtolower($reihe1['EMail'])."'";
            $result = mysqli_query($db1, $sql);
            if(mysqli_num_rows($result) > 0) {
                $name_err = 1;
                echo "<div  style='margin-top: 20px; margin-left: 16px; margin-right: 20px;' class='alert alert-danger alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Diese Email wird bereits verwendet</div>";
            }
            if ($name_err == 0) {
                $sql = "SELECT ID,Erstellt,Name,Geschaeftsfuehrer,EMail,tel,Laengengrad,Breitengrad,Land,PLZ,Ort,Strasse,Hausnummer,Ersteller_ID FROM Firmen WHERE id='".$id."'";
                $result_uebernehmen = mysqli_query($db2, $sql);
                if(mysqli_num_rows($result_uebernehmen) > 0) {
                    $reihe = mysqli_fetch_array($result_uebernehmen);
                    $sql = "INSERT INTO Firmen (Erstellt, Name, Geschaeftsfuehrer, EMail, tel, Laengengrad, Breitengrad, Land, PLZ, Ort, Strasse, Hausnummer, Ersteller_ID, Kontrolleur_ID, Genehmigt) VALUES ('".$reihe['Erstellt']."', '".$reihe['Name']."', '".$reihe['Geschaeftsfuehrer']."', '".$reihe['EMail']."', '".$reihe['tel']."', '".$reihe['Laengengrad']."', '".$reihe['Breitengrad']."', '".$reihe['Land']."', '".$reihe['PLZ']."', '".$reihe['Ort']."', '".$reihe['Strasse']."', '".$reihe['Hausnummer']."', '".$reihe['Ersteller_ID']."', '".$_SESSION['ID']."', '". time() ."');";
                    $result_uebernehmen = mysqli_query($db1, $sql);
                    echo "Es hat geklappt";     
                    $sql = "DELETE FROM Firmen WHERE ID = '".$id."';";
                    $result = mysqli_query($db2, $sql);
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
                                <h4><i class="fa fa-angle-right"></i> Beantragte Firmen</h4>
                                <section id="unseen">
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Operationen</th>
                                                <th>Erstelldatum</th>
                                                <th>Name</th>
                                                <th>Geschaeftsfuehrer</th>
                                                <th>Adresse</th>
                                                <th>Email</th>
                                                <th>Tel. Nummer</th>
                                                <th>Ersteller</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $sql = "SELECT ID,Erstellt,Name,Geschaeftsfuehrer,Email,tel,Laengengrad,Breitengrad,Land,PLZ,Ort,Strasse,Hausnummer,Ersteller_ID FROM Firmen";
                                                #$result1 = $verbindung1->query($sql);
                                                $result = mysqli_query($db2, $sql);
                                                if(mysqli_num_rows($result) > 0) {
                                                    while($row = mysqli_fetch_array($result)) {
                                                        echo "<tr>"; 
                                                        echo "<td style='text-align:center;'> <a href='"."?action=remove&id=".$row["ID"]."'><i class='fa fa-ban'></i></a>&#160&#160&#160&#160<a href='"."?action=uebernehmen&id=".$row["ID"]."'><i class='fa fa-check'></i></a></td>"; 
                                                        echo "<td>" . gmdate("Y-m-d\ H:i:s", $row["Erstellt"]) . "</td>"; 
                                                        echo "<td>" . $row["Name"] . "</td>"; 
                                                        echo "<td>" . $row["Geschaeftsfuehrer"] . "</td>"; 
                                                        $url = "https://nominatim.openstreetmap.org/reverse?lat=".urlencode($row["Laengengrad"])  . "&lon=" . urlencode($row["Breitengrad"]) . "&format=json";
                                                        #echo $url . "<br />";
                                                        $ergebniss = get_web_page($url);
                                                        $test = json_decode($ergebniss);
                                                        $adress_array = $test->{"address"};
                                                        $adresse = $adress_array->{'country'} . ";" . $adress_array->{'postcode'} . " " . $adress_array->{'village'} . ";" . $adress_array->{'road'} . " " . $adress_array->{'house_number'};
                                                        echo "<td>" . $adresse . "</td>"; 
                                                        echo "<td>" . $row["Email"] . "</td>"; 
                                                        echo "<td>" . $row["tel"] . "</td>";                                                        
                                                        $id = $row["Ersteller_ID"];
                                                        $sql = "SELECT Benutzername FROM Benutzer";
                                                        $result_praktikumsbewertung = mysqli_query($db1, $sql) or die(mysqli_error($db1));
                                                        $row = mysqli_fetch_array($result_praktikumsbewertung);
                                                        echo "<td>" . $row["Benutzername"] . "</td>";
                                                        echo "</tr>";
                                                        

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