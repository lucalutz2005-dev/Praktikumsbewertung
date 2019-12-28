<?php 
include("includes/login/login_check.php");
?>

<html>
    <head>
        <title>Praktikum bewerten</title>
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

<?php
#echo 1;
#echo 2;
#echo $timestamp;
#echo $sql;
            
require_once "includes/db/config_beantragt.php";
require_once "includes/db/config_praktikumsbewertung.php";
$Praktika_ID = $_GET["id"];
$ID = $Praktika_ID;
if(isset($_GET["id"]) && isset($_GET["bewertung"]) && isset($_GET["bewertung1"]) && isset($_GET["bewertung2"]) && isset($_GET["bewertung3"]) && isset($_GET["bewertung4"]) && isset($_GET["angebot"]) ) {
    $bewertung1 = $_GET["bewertung"];
    $bewertung2 = $_GET["bewertung1"];
    $bewertung3 = $_GET["bewertung2"];
    $bewertung4 = $_GET["bewertung3"];
    $bewertung5 = $_GET["bewertung4"];
    if($bewertung1 > 0 && $bewertung1 <= 5 && $bewertung2 > 0 && $bewertung2 <= 5 && $bewertung3 > 0 && $bewertung3 <= 5 && $bewertung4 > 0 && $bewertung4 <= 5 && $bewertung5 > 0 && $bewertung5 <= 5) {
        //if($bewertung == 1 || $bewertung == 2 || $bewertung == 3 || $bewertung == 4 || $bewertung == 5) {
            if(is_numeric($_GET["angebot"])) {
                $Angebot_ID = $_GET["angebot"];
                $Bewerter_ID = $_SESSION["ID"];
                $Firmen_ID = $ID;
                
                $sql = "SELECT ID FROM Bewertungen WHERE Bewerter_ID = '".$Bewerter_ID."'";
                $result = mysqli_query($db2, $sql);
                if(mysqli_num_rows($result) > 0) {
                    echo "<div style='margin-top: 20px; margin-left: 0px; margin-right: 0px;' class='alert alert-danger alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Du hast dieses Praktikum bereits bewertet</div>";
                }
                else {
                    $sql = "SELECT * FROM Bewertungen WHERE ID = '".$Angebot_ID."' && Bewerter_ID = '".$Bewerter_ID."'";
                    #echo $sqecho $sql . "<br />";
                    #$result = mysqli_query($db2, $sql);
                    #$reihe1 = mysqli_fetch_array($result);
                    #echo $reihe1["Firmen_ID"];
                    #$sql = "SELECT ID FROM Bewertungen WHERE Bewerter_ID = '".$Bewerter_ID."'";
                    $sql = "SELECT ID FROM Bewertungen WHERE Bewerter_ID = '".$Bewerter_ID."' && Firmen_ID = '".$Firmen_ID."' && Praktika_ID = '".$Angebot_ID."'";
                    #echo $sql;
                    $result = mysqli_query($db1, $sql);
                    if(mysqli_num_rows($result) > 0) {
                        echo "<div style='margin-top: 20px; margin-left: 0px; margin-right: 0px;' class='alert alert-danger alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Du hast dieses Praktikum bereits bewertet</div>";
                    }
                    else {
                        $sql = "SELECT Firmen_ID FROM Angebote WHERE Praktika_ID = '".$Praktika_ID."'";
                        $result = mysqli_query($db1, $sql);
                        if(mysqli_num_rows($result) > 0) {
                            $row = mysqli_fetch_array($result);
                            $Firmen_ID = $row["Firmen_ID"];
                        }
                        $timestamp = time();
                        $sql = "INSERT INTO Bewertungen (Praktika_ID, Bewertung_1, Bewertung_2, Bewertung_3, Bewertung_4, Bewertung_5, Bewerter_ID, Firmen_ID, Erstellt) VALUES ('".$Angebot_ID."', '".$bewertung1."', '".$bewertung2."', '".$bewertung3."', '".$bewertung4."', '".$bewertung5."', '".$Bewerter_ID."', '".$Praktika_ID."', '".$timestamp."')";
                        #echo "<br />" . $sql;
                        #$result = mysqli_query($db2, $sql);
                        if($result = mysqli_query($db2, $sql)) {
                            echo "<div style='margin-top: 20px; margin-left: 0px; margin-right: 0px;' class='alert alert-success alert-dismissable' role='alert'><button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>Deine Bewertung wurde eingereicht</div>";
                            while($row = mysqli_fetch_array($result)) {        
                                #echo "<td>" . $row["Name"] . "</td>"; 
                            }
                        }
                    }
                }
            }
        //}
    }
}
?>

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
                                <section id="unseen">
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name des Berufs</th>
                                                <th>Berufsgruppe</th>
                                                <th class="numeric">Bewertungen</th>
                                                <th class="numeric"><!--&empty;--> Personalabteilung</th>
                                                <th class="numeric">Arbeitsumfeld</th>
                                                <th class="numeric">Individualit&auml;t</th>
                                                <th class="numeric">Inhalt</th>
                                                <th class="numeric">Soziale Leistungen</th>
                                                <!--<th>Tags</th>-->
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <style>
                                                .stern {
                                                    background-color: transparent;
                                                }
                                                .table td.abbreviation {
                                                    max-width: 30px;
                                                }
                                                .table td.abbreviation p {
                                                    white-space: nowrap;
                                                    overflow: hidden;
                                                    text-overflow: ellipsis;
                                                
                                                }
                                            </style>
                                            <?php 
                                                require_once "includes/db/config_praktikumsbewertung.php";
                                                $sql = "SELECT ID,NameBeruf,Berufsgruppe,Bewertungen,DurchschnittlicheBewertung,Tags FROM Angebote WHERE Firmen_ID = '".$ID."'";
                                                $result = mysqli_query($db1, $sql);
                                                if(mysqli_num_rows($result) > 0) {
                                                    while($row = mysqli_fetch_array($result)) {
                                                        echo "<tr>"; 
                                                        echo "<td>" . $row["ID"] . "</td>"; 
                                                        echo "<td>" . $row["NameBeruf"] . "</td>"; 
                                                        echo "<td>" . $row["Berufsgruppe"] . "</td>"; 
                                                        $sql = "SELECT COUNT(ID) FROM Bewertungen;";
                                                        $result = mysqli_query($db1, $sql);
                                                        $row1 = mysqli_fetch_array($result);
                                                        echo "<td>" . $row1["COUNT(ID)"] . "</td>"; 
                                                        echo "<td>";
                                                        $id_button = $row["ID"];
                                                        echo "<button id='stern01' name='stern01' class='btn stern' onclick='ModifiziereURL(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "<button id='stern02' name='stern02' class='btn stern' onclick='ModifiziereURL(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "<button id='stern03' name='stern03' class='btn stern' onclick='ModifiziereURL(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "<button id='stern04' name='stern04' class='btn stern' onclick='ModifiziereURL(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "<button id='stern05' name='stern05' class='btn stern' onclick='ModifiziereURL(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>"; 
                                                        echo "<td>";
                                                        echo "<button id='stern06' name='stern06' class='btn stern' onclick='ModifiziereURL1(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "<button id='stern07' name='stern07' class='btn stern' onclick='ModifiziereURL1(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "<button id='stern08' name='stern08' class='btn stern' onclick='ModifiziereURL1(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "<button id='stern09' name='stern09' class='btn stern' onclick='ModifiziereURL1(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "<button id='stern10' name='stern10' class='btn stern' onclick='ModifiziereURL1(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>"; 
                                                        echo "<td>";
                                                        echo "<button id='stern11' name='stern06' class='btn stern' onclick='ModifiziereURL2(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "<button id='stern12' name='stern07' class='btn stern' onclick='ModifiziereURL2(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "<button id='stern13' name='stern08' class='btn stern' onclick='ModifiziereURL2(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "<button id='stern14' name='stern09' class='btn stern' onclick='ModifiziereURL2(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "<button id='stern15' name='stern10' class='btn stern' onclick='ModifiziereURL2(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>"; 
                                                        echo "<td>";
                                                        echo "<button id='stern16' name='stern06' class='btn stern' onclick='ModifiziereURL3(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "<button id='stern17' name='stern07' class='btn stern' onclick='ModifiziereURL3(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "<button id='stern18' name='stern08' class='btn stern' onclick='ModifiziereURL3(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "<button id='stern19' name='stern09' class='btn stern' onclick='ModifiziereURL3(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "<button id='stern20' name='stern10' class='btn stern' onclick='ModifiziereURL3(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>"; 
                                                        echo "<td>";
                                                        echo "<button id='stern21' name='stern06' class='btn stern' onclick='ModifiziereURL4(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "<button id='stern22' name='stern07' class='btn stern' onclick='ModifiziereURL4(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "<button id='stern23' name='stern08' class='btn stern' onclick='ModifiziereURL4(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "<button id='stern24' name='stern09' class='btn stern' onclick='ModifiziereURL4(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "<button id='stern25' name='stern10' class='btn stern' onclick='ModifiziereURL4(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>"; 
                                                        #echo "<td>" . $row["Tags"] . "</td>"; 
                                                        echo "</tr>";
                                                    }
                                                }
                                            ?>
                                            </tbody>
                                    </table>
                                </section>
                                <script>
                                    /*$(document).on('click', '#stern01', function(e) 
                                    {
                                        var angebot = document.getElementById("stern01").value;
                                        ModifiziereURL(5, angebot);
                                    });
                                    $(document).on('click', '#stern02', function(e) 
                                    {
                                        var angebot = document.getElementById("stern01").value;
                                        ModifiziereURL(4, angebot);
                                    });
                                    $(document).on('click', '#stern03', function(e) 
                                    {
                                        var angebot = document.getElementById("stern01").value;
                                        ModifiziereURL(3, angebot);
                                    });
                                    $(document).on('click', '#stern04', function(e) 
                                    {
                                        var angebot = document.getElementById("stern01").value;
                                        ModifiziereURL(2, angebot);
                                    });
                                    $(document).on('click', '#stern05', function(e) 
                                    {
                                        var angebot = document.getElementById("stern01").value;
                                        ModifiziereURL(1, angebot);
                                    });
                                    $(document).on('click', '#stern06', function(e) 
                                    {
                                        var angebot = document.getElementById("stern01").value;
                                        ModifiziereURL1(5, e.target.value);
                                    });
                                    $(document).on('click', '#stern07', function(e) 
                                    {
                                        ModifiziereURL1(4, e.target.value);
                                    });
                                    $(document).on('click', '#stern08', function(e) 
                                    {
                                        ModifiziereURL1(3, e.target.value);
                                    });
                                    $(document).on('click', '#stern09', function(e) 
                                    {
                                        ModifiziereURL1(2, e.target.value);
                                    });
                                    $(document).on('click', '#stern10', function(e) 
                                    {
                                        ModifiziereURL1(1, e.target.value);
                                    });*/
                                    function ModifiziereURL(Sterne, Angebot_ID) {
                                        var path = location.protocol + '//' + location.host + location.pathname;
                                        var id = getURLParameter('id');
                                        var final_url = path+"?id="+id+"&bewertung="+Sterne+"&angebot="+Angebot_ID;
                                        window.location.href = final_url;
                                    }
                                    function ModifiziereURL1(Sterne1, Angebot_ID) {
                                        var path = location.protocol + '//' + location.host + location.pathname;
                                        var id = getURLParameter('id');
                                        var Sterne = getURLParameter('bewertung');
                                        var final_url = path+"?id="+id+"&bewertung="+Sterne+"&bewertung1="+Sterne1+"&angebot="+Angebot_ID;
                                        window.location.href = final_url;
                                    }
                                    function ModifiziereURL2(Sterne2, Angebot_ID) {
                                        var path = location.protocol + '//' + location.host + location.pathname;
                                        var id = getURLParameter('id');
                                        var Sterne = getURLParameter('bewertung');
                                        var Sterne1 = getURLParameter('bewertung1');
                                        var final_url = path+"?id="+id+"&bewertung="+Sterne+"&bewertung1="+Sterne1+"&bewertung2="+Sterne2+"&angebot="+Angebot_ID;
                                        window.location.href = final_url;
                                    }
                                    function ModifiziereURL3(Sterne3, Angebot_ID) {
                                        var path = location.protocol + '//' + location.host + location.pathname;
                                        var id = getURLParameter('id');
                                        var Sterne = getURLParameter('bewertung');
                                        var Sterne1 = getURLParameter('bewertung1');
                                        var Sterne2 = getURLParameter('bewertung2');
                                        var final_url = path+"?id="+id+"&bewertung="+Sterne+"&bewertung1="+Sterne1+"&bewertung2="+Sterne2+"&bewertung3="+Sterne3+"&angebot="+Angebot_ID;
                                        window.location.href = final_url;
                                    }
                                    function ModifiziereURL4(Sterne4, Angebot_ID) {
                                        var path = location.protocol + '//' + location.host + location.pathname;
                                        var id = getURLParameter('id');
                                        var Sterne = getURLParameter('bewertung');
                                        var Sterne1 = getURLParameter('bewertung1');
                                        var Sterne2 = getURLParameter('bewertung2');
                                        var Sterne3 = getURLParameter('bewertung3');
                                        var final_url = path+"?id="+id+"&bewertung="+Sterne+"&bewertung1="+Sterne1+"&bewertung2="+Sterne2+"&bewertung3="+Sterne3+"&bewertung4="+Sterne4+"&angebot="+Angebot_ID;
                                        window.location.href = final_url;
                                    }

                                    function getURLParameter(name) {
                                        var value = decodeURIComponent((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, ""])[1]);
                                        return (value !== 'null') ? value : false;
                                    }
                                </script>
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