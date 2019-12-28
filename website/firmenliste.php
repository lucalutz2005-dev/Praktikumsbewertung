<?php 
include("includes/login/login_check.php");
?>

<html>
    <head>
        <title>Firmenliste</title>
        <?php $head = file_get_contents("includes/html/head.html"); 
        echo $head; ?>
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.4.0/dist/leaflet.css" />
            <script src="https://unpkg.com/leaflet@1.4.0/dist/leaflet.js"></script>
    </head>
    <body>
    <div class="se-pre-con"></div>
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
        <!--main content start-->
        <section id="main-content">
            <section class="wrapper">
                <div class="row" style="margin-left: 15px; margin-right: 15px">
                    <!--<div class="col-lg-12 main-chart"> -->
                        <!--CUSTOM CHART START --> <!--
                        <div class="border-head">
                            <h3>USER VISITS</h3>
                        </div>
                        <div class="custom-bar-chart">
                            <ul class="y-axis">
                                <li><span>10</span></li>
                                <li><span>8</span></li>
                                <li><span>6</span></li>
                                <li><span>4</span></li>
                                <li><span>2</span></li>
                                <li><span>0</span></li>
                            </ul>
                            <div class="bar">
                                <div class="title">JAN</div>
                                <div class="value tooltips" data-original-title="8.500" data-toggle="tooltip" data-placement="top">85%</div>
                            </div>
                            <div class="bar ">
                                <div class="title">FEB</div>
                                <div class="value tooltips" data-original-title="5.000" data-toggle="tooltip" data-placement="top">50%</div>
                            </div>
                            <div class="bar ">
                                <div class="title">MAR</div>
                                <div class="value tooltips" data-original-title="6.000" data-toggle="tooltip" data-placement="top">60%</div>
                            </div>
                            <div class="bar ">
                                <div class="title">APR</div>
                                <div class="value tooltips" data-original-title="4.500" data-toggle="tooltip" data-placement="top">45%</div>
                            </div>
                            <div class="bar">
                                <div class="title">MAY</div>
                                <div class="value tooltips" data-original-title="3.200" data-toggle="tooltip" data-placement="top">32%</div>
                            </div>
                            <div class="bar ">
                                <div class="title">JUN</div>
                                <div class="value tooltips" data-original-title="6.200" data-toggle="tooltip" data-placement="top">62%</div>
                            </div>
                            <div class="bar">
                                <div class="title">JUL</div>
                                <div class="value tooltips" data-original-title="7.500" data-toggle="tooltip" data-placement="top">75%</div>
                            </div>
                        </div>
                    </div>-->
                    <div class="row mt">
                        <div class="col-md-8 mb">
                            <div class="message-p pn">
                            <div class="message-header">
                                <h5>Firmen Karte</h5>
                            </div>

                            <div id='meineKarte' style="padding-top: 90px;padding-bottom: 90px;"></div>
                            <!-- OSM-Basiskarte einfügen und zentrieren -->
                            <script src="/librarys/jquery/jquery.min.js"></script>
                            <script type='text/javascript'>
                               var Karte = L.map('meineKarte').setView([48.8845159, 10.1878466], 15);
                               L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                               'attribution':  'Kartendaten &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> Mitwirkende',
                               'useCache': true
                               }).addTo(Karte);
                            </script>
                            <!-- Marker einfügen -->
                            <script>
                            var points = [
                              <?php
                                require_once "includes/db/config_praktikumsbewertung.php";
                                $sql = "SELECT * FROM Firmen";
                                $result = $verbindung->query($sql);
                                if($result){
                                    if($result->num_rows > 0){
                                        $Counter = 1;
                                        while($row = $result->fetch_assoc()){
                                          echo '["P' . $Counter . '", ' . $row['Laengengrad'] . ', ' . $row['Breitengrad'] . ', "' . $row['ID'] . '", "' . $row["Name"] . '"],';
                                          #"var marker = L.marker([" . $row['Laengengrad'] . "," . $row['Breitengrad'] . "]).addTo(Karte);";
                                          $Counter = $Counter + 1;
                                        }
                                        echo '["P'.$Counter.'", 48.883598, 10.178100, "1234", "Dummy"]';
                                        mysqli_free_result($result);
                                    } else{
                                    }
                                } else{
                                }
                                mysqli_close($link);
                              ?>
                            ];
                            var marker = [];
                            var i;
                            for (i = 0; i < 10; i++) {
                                marker[i] = new L.Marker([points[i][1], points[i][2]], {
                                    win_url: points[i][3],
                                    name: points[i][4],
                                });
                                marker[i].addTo(Karte);
                                marker[i].on('click', onClick);
                                test = marker[i];
                                marker[i].on('mouseover', popup);
                                marker[i].on('mouseover', function(e) {
                                    L.closePopup();
                                }
                                );
                            };
                            function popup(e) {
                                    //open popup;
                                    var popup = L.popup({offset: [0, -30]})
                                    .setLatLng(e.latlng) 
                                    .setContent(this.options.name)
                                    .openOn(Karte);
                            }
                            function onClick(e) {
                                //console.log(this.options.win_url);
                                //window.open(this.options.win_url);
                                /*$.ajax({
                                    type: "POST",
                                    url: "sfirmenliste.php",
                                    data: infoPO,
                                    success: function() {   
                                        location.reload();  
                                    }
                                });*/
                                var url = window.location.href;    
                                if (url.indexOf('?') > -1){
                                   url = url.split("?")[0];
                                   url += '?id='+this.options.win_url;
                                }else{
                                   url += '?id='+this.options.win_url;
                                }
                                window.location.href = url;
                            }
                            </script>
                      </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-left: 15px; margin-right: 15px; margin-bottom: 10px;">
                    <div class="row mt">
                        <div class="col-lg-12">
                            <div class="content-panel">
                                <h4><i class="fa fa-angle-right"></i> Praktikumsangebote</h4>
                                <h6>Um die verf&uuml;gbaren Praktikumsstellen zu sehen w&auml;hlen sie bitte eine Firma aus.</h6>
                                <section id="unseen">
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name des Berufs</th>
                                                <th>Berufsgruppe</th>
                                                <th class="numeric">Bewertungen</th>
                                                <th class="numeric">&empty; Bewertung</th>
                                                
                                                <!--<th class="numeric">Personalabteilung</th>
                                                <th class="numeric">Arbeitsumfeld</th>
                                                <th class="numeric">Individualit&auml;t</th>
                                                <th class="numeric">Inhalt</th>
                                                <th class="numeric">Soziale Leistungen</th>-->

                                                <th>Tags</th>
                                                <th>Bewerbungsdaten</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                require_once "includes/db/config_praktikumsbewertung.php";
                                                $ID = $_GET["id"];
                                                #echo $ID;
                                                $sql = "SELECT ID,NameBeruf,Berufsgruppe,Bewertungen,DurchschnittlicheBewertung,Tags,Firmen_ID FROM Angebote WHERE Firmen_ID = '".$ID."'";
                                                $result = $verbindung->query($sql);
                                                if($result->num_rows > 0) {
                                                    while($row = $result->fetch_assoc()) {
                                                        echo "<tr>"; 
                                                        echo "<td>" . $row["ID"] . "</td>"; 
                                                        echo "<td>" . $row["NameBeruf"] . "</td>"; 
                                                        echo "<td>" . $row["Berufsgruppe"] . "</td>"; 
                                                        $sql = "SELECT COUNT(ID) FROM Bewertungen WHERE Firmen_ID = '".$row["Firmen_ID"]."';";
                                                        #echo $sql;
                                                        $result = mysqli_query($db1, $sql);
                                                        $row1 = mysqli_fetch_array($result);
                                                        echo "<td>" . $row1["COUNT(ID)"] . "</td>"; 
                                                        #echo "<td>" . $row["Bewertungen"] . "</td>"; 
                                                        $sql = "SELECT * FROM Bewertungen WHERE Firmen_ID = '".$ID."'";
                                                        $result1 = mysqli_query($db1, $sql);
                                                        if(mysqli_num_rows($result1) > 0) {
                                                            $durchlauf = 0;
                                                            while($row5 = mysqli_fetch_array($result1)) {
                                                                $Bewertung_1 += $row5["Bewertung_1"];
                                                                $Bewertung_2 += $row5["Bewertung_2"];
                                                                $Bewertung_3 += $row5["Bewertung_3"];
                                                                $Bewertung_4 += $row5["Bewertung_4"];
                                                                $Bewertung_5 += $row5["Bewertung_5"];
                                                                $durchlauf = $durchlauf + 1;
                                                            }
                                                            /*echo "<td>" . $Bewertung_1/$durchlauf . "</td>";
                                                            echo "<td>" . $Bewertung_2/$durchlauf . "</td>";
                                                            echo "<td>" . $Bewertung_3/$durchlauf . "</td>";
                                                            echo "<td>" . $Bewertung_4/$durchlauf . "</td>";
                                                            echo "<td>" . $Bewertung_5/$durchlauf . "</td>";*/
                                                            $summe = $Bewertung_1/$durchlauf + $Bewertung_2/$durchlauf + $Bewertung_3/$durchlauf + $Bewertung_4/$durchlauf + $Bewertung_5/$durchlauf;
                                                            $durchschnitt = ($summe / 5);
                                                            echo "<td><center>" . $durchschnitt . "</center>1 = <i class='far fa-heart'></i>, 6 = <i class='fas fa-bomb'></i>" . "</td>";
                                                        }
                                                        
                                                        #echo "<td>" . $row["DurchschnittlicheBewertung"] . "</td>"; 
                                                        echo "<td>" . $row["Tags"] . "</td>"; 
                                                        $sql = "SELECT EMail FROM Firmen WHERE ID = '".$row["Firmen_ID"]."';";
                                                        $result = mysqli_query($db1, $sql);
                                                        $row1 = mysqli_fetch_array($result);
                                                        echo "<td>" . '<a href="mailto:'.$row1["EMail"].'?subject='.format_text_for_mailto_param("Bewerbung um einen Praktikumsplatz als ".$row["NameBeruf"]).'&amp;body="">Per Email Kontaktieren</a>' . "</td>";
                                                        echo "</tr>";
                                                    }
                                                }
                                                function format_text_for_mailto_param($text) {
                                                    return rawurlencode(htmlspecialchars_decode($text));
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </section>
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