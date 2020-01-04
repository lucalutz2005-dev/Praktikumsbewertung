<?php 
include("includes/login/login_check.php");
?>

<html>
    <head>
        <title>Praktikum bewerten</title>
        <?php $head = file_get_contents("includes/html/head.html"); 
        echo $head; ?>
        <link rel="stylesheet" href="/lib/leaflet/leaflet.css" />
        <script src="/lib/leaflet/leaflet.js"></script>
        <link rel="stylesheet" href="/lib/leaflet-locator/L.Control.Locate.min.css" />
        <link rel="stylesheet" href="/assets/css/praktikum_bewerten.css" />

    </head>
    <body>
        <div class="se-pre-con"></div>
        <section id="container">
            <?php
                include("includes/php/sidebar.php");
            ?>
            <section id="main-content">
                <section class="wrapper">
                    <div class="row" style="margin-left: 15px; margin-right: 15px">
                        <?php
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
                            }
                        }
                        ?>
                        <div class="row mt">
                            <div class="col-md-8 mb">
                                <div class="message-p pn">
                                    <div class="message-header">
                                        <h5>Firmen Karte</h5>
                                    </div>
                                    <div id='meineKarte' style="padding-top: 90px;padding-bottom: 90px;"></div>
                                    <!-- OSM-Basiskarte einfügen und zentrieren -->
                                    <script src="/lib/leaflet-locator/L.Control.Locate.min.js"></script>
                                    <script type='text/javascript'>
                                        var Karte = L.map('meineKarte').setView([48.8845159, 10.1878466], 15);
                                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                            'attribution':  'Kartendaten &copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> Mitwirkende',
                                            'useCache': true
                                        }).addTo(Karte);
                                        L.control.locate(
                                        {
                                            strings: {
                                                title: "Orte mich!"
                                            },
                                            setView: "always",
                                            iconElementTag: "i",
                                            showPopup: false,
                                            icon: "fas fa-map-marker-alt"
                                        }
                                        ).addTo(Karte);
                                    </script>
                                    <!-- Marker einfügen -->
                                    <script>
                                        var points = [
                                            <?php
                                                require_once "includes/db/config_praktikumsbewertung.php";
                                                $sql = "SELECT * FROM Firmen";
                                                $result = $verbindung->query($sql);
                                                $Counter = 1;
                                                if($result){
                                                    if($result->num_rows > 0){
                                                        while($row = $result->fetch_assoc()){
                                                          echo '["P' . $Counter . '", ' . $row['Laengengrad'] . ', ' . $row['Breitengrad'] . ', "' . $row['ID'] . '", "' . $row["Name"] . '"],';
                                                          #"var marker = L.marker([" . $row['Laengengrad'] . "," . $row['Breitengrad'] . "]).addTo(Karte);";
                                                          $Counter = $Counter + 1;
                                                        }
                                                        echo '["P'.$Counter.'", 48.883598, 10.178100, "1234", "Dummy"]';
                                                        mysqli_free_result($result);
                                                    } 
                                                }
                                                mysqli_close($link);
                                            ?>
                                        ];
                                        var marker = [];
                                        var i;
                                        for (i = 0; i < points.length-1; i++) {
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
                                                    <th class="numeric">Aktionen</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php 
                                                    require_once "includes/db/config_praktikumsbewertung.php";
                                                    $sql = "SELECT ID,NameBeruf,Berufsgruppe FROM Angebote WHERE Firmen_ID = '".$ID."'";
                                                    $result = mysqli_query($db1, $sql);
                                                    if(mysqli_num_rows($result) > 0) {
                                                        while($row = mysqli_fetch_array($result)) {
                                                            echo "<tr>"; 
                                                            echo "<td>" . $row["ID"] . "</td>"; 
                                                            echo "<td>" . $row["NameBeruf"] . "</td>"; 
                                                            echo "<td>" . $row["Berufsgruppe"] . "</td>"; 
                                                            $sql = "SELECT COUNT(ID) FROM Bewertungen WHERE Firmen_ID = '".$ID."';";
                                                            $result = mysqli_query($db1, $sql);
                                                            $row1 = mysqli_fetch_array($result);
                                                            echo "<td>" . $row1["COUNT(ID)"] . "</td>"; 
                                                            echo "<td>";
                                                            echo "<a href='javascript:OeffnePopup(".$row["ID"].")'> Bewertung &ouml;ffnen</a>";
                                                            echo "</td>"; 
                                                            echo "</tr>";
                                                        }
                                                    }
                                                ?>
                                            </tbody>
                                        </table>
                                        <script>
                                            var test1234 = 0;
                                            function OeffnePopup(test) {
                                                var Angebot_ID = test;
                                                test1234 = Angebot_ID;
                                                var span = document.getElementsByClassName("schliessen")[0];
                                                var modal = document.getElementById("myModal");
                                                var map = document.getElementById("meineKarte");
                                                modal.style.display = "block";
                                                map.style.display = "none";
                                                span.onclick = function() {
                                                    modal.style.display = "none";
                                                    map.style.display = "block";
                                                }
                                                window.onclick = function(event) {
                                                    if (event.target == modal) {
                                                        modal.style.display = "none";
                                                        map.style.display = "block";
                                                    }
                                                }
                                            }
                                        </script>
                                    </section>
                                    <!-- Trigger/Open The Modal -->
<!-- The Modal --><!--
<div id="myModal" class="modal">

--><!-- Modal content --><!--
  <div class="modal-content">
    <span class="schliessen">&times;</span>

                                <h4><i class="fa fa-angle-right"></i> Praktikumsangebote</h4>
                                <section id="unseen">
                                    <table class="table table-bordered table-striped table-condensed">
                                        <thead>
                                            <tr>
                                                <th>Gebiet</th>
                                                <th class="numeric">1</th>
                                                <th class="numeric">2</th>
                                                <th class="numeric">3</th>
                                                <th class="numeric">4</th>
                                                <th class="numeric">5</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                /*require_once "includes/db/config_praktikumsbewertung.php";
                                                $sql = "SELECT ID,NameBeruf,Berufsgruppe FROM Angebote WHERE Firmen_ID = '".$ID."'";
                                                $result = mysqli_query($db1, $sql);
                                                if(mysqli_num_rows($result) > 0) {
                                                    while($row = mysqli_fetch_array($result)) {*/
                                                        $id_button = 1;
                                                        echo "<tr>"; 
                                                        echo "<td> Personalabteilung </td>"; 
                                                        echo "<td>";
                                                        echo "<button id='stern01' name='stern01' class='btn stern' onclick='ModifiziereURL_personal(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern02' name='stern02' class='btn stern' onclick='ModifiziereURL_personal(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern03' name='stern03' class='btn stern' onclick='ModifiziereURL_personal(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern04' name='stern04' class='btn stern' onclick='ModifiziereURL_personal(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern05' name='stern05' class='btn stern' onclick='ModifiziereURL_personal(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                        echo "<tr>"; 
                                                        echo "<td> Arbeitsumfeld </td>"; 
                                                        echo "<td>";
                                                        echo "<button id='stern11' name='stern01' class='btn stern' onclick='ModifiziereURL_arbeitsumfeld(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern12' name='stern02' class='btn stern' onclick='ModifiziereURL_arbeitsumfeld(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern13' name='stern03' class='btn stern' onclick='ModifiziereURL_arbeitsumfeld(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern14' name='stern04' class='btn stern' onclick='ModifiziereURL_arbeitsumfeld(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern15' name='stern05' class='btn stern' onclick='ModifiziereURL_arbeitsumfeld(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                        echo "<tr>"; 
                                                        echo "<td> Individualität </td>"; 
                                                        echo "<td>";
                                                        echo "<button id='stern21' name='stern01' class='btn stern' onclick='ModifiziereURL_individualitaet(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern22' name='stern02' class='btn stern' onclick='ModifiziereURL_individualitaet(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern23' name='stern03' class='btn stern' onclick='ModifiziereURL_individualitaet(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern24' name='stern04' class='btn stern' onclick='ModifiziereURL_individualitaet(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern25' name='stern05' class='btn stern' onclick='ModifiziereURL_individualitaet(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                        echo "<tr>"; 
                                                        echo "<td> Inhalt </td>"; 
                                                        echo "<td>";
                                                        echo "<button id='stern31' name='stern01' class='btn stern' onclick='ModifiziereURL_inhalt(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern32' name='stern02' class='btn stern' onclick='ModifiziereURL_inhalt(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern33' name='stern03' class='btn stern' onclick='ModifiziereURL_inhalt(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern34' name='stern04' class='btn stern' onclick='ModifiziereURL_inhalt(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern35' name='stern05' class='btn stern' onclick='ModifiziereURL_inhalt(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                        echo "<tr>"; 
                                                        echo "<td> Soziale Leistungen	 </td>"; 
                                                        echo "<td>";
                                                        echo "<button id='stern41' name='stern01' class='btn stern' onclick='ModifiziereURL_SozialeLeistungen(1, ".$id_button.");' value='".$id_button."'><i class='far fa-heart'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern42' name='stern02' class='btn stern' onclick='ModifiziereURL_SozialeLeistungen(2, ".$id_button.");' value='".$id_button."'><i class='far fa-grin'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern43' name='stern03' class='btn stern' onclick='ModifiziereURL_SozialeLeistungen(3, ".$id_button.");' value='".$id_button."'><i class='far fa-meh'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern44' name='stern04' class='btn stern' onclick='ModifiziereURL_SozialeLeistungen(4, ".$id_button.");' value='".$id_button."'><i class='far fa-angry'></i></button>";
                                                        echo "</td>";
                                                        echo "<td>";
                                                        echo "<button id='stern45' name='stern05' class='btn stern' onclick='ModifiziereURL_SozialeLeistungen(5, ".$id_button.");' value='".$id_button."'><i class='fas fa-bomb'></i></button>";
                                                        echo "</td>";
                                                        echo "</tr>";
                                                    /*}
                                                }*/
                                            ?>
                                            </tbody>
                                    </table>
                                </section>
                                <button type="button" onclick="Absenden();" class="btn" id="berechnen">Absenden</button>

  </div>
</div>

<script>
$('#myModal').appendTo('body');
// Get the modal
var modal = document.getElementById("myModal");
var map = document.getElementById("meineKarte");
// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
    modal.style.display = "block";
    map.style.display = "none";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
  map.style.display = "block";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
    map.style.display = "block";
  }
}
</script>
                                <script>
                                    var personal = 0;
                                    var arbeitsumfeld = 0;
                                    var individualitaet = 0;
                                    var inhalt = 0;
                                    var SozialeLeistungen = 0;
                                    function ModifiziereURL_personal(Sterne, Angebot_ID) {
                                        if(Sterne == 1)
                                        {
                                            document.getElementById("stern01").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern01").style.color = "#797979";
                                        }
                                        if(Sterne == 2)
                                        {
                                            document.getElementById("stern02").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern02").style.color = "#797979";
                                        }
                                        if(Sterne == 3)
                                        {
                                            document.getElementById("stern03").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern03").style.color = "#797979";
                                        }
                                        if(Sterne == 4)
                                        {
                                            document.getElementById("stern04").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern04").style.color = "#797979";
                                        }
                                        if(Sterne == 5)
                                        {
                                            document.getElementById("stern05").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern05").style.color = "#797979";
                                        }
                                        personal = Sterne;
                                    }
                                    function ModifiziereURL_arbeitsumfeld(Sterne, Angebot_ID) {
                                        if(Sterne == 1)
                                        {
                                            document.getElementById("stern11").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern11").style.color = "#797979";
                                        }
                                        if(Sterne == 2)
                                        {
                                            document.getElementById("stern12").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern12").style.color = "#797979";
                                        }
                                        if(Sterne == 3)
                                        {
                                            document.getElementById("stern13").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern13").style.color = "#797979";
                                        }
                                        if(Sterne == 4)
                                        {
                                            document.getElementById("stern14").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern14").style.color = "#797979";
                                        }
                                        if(Sterne == 5)
                                        {
                                            document.getElementById("stern15").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern15").style.color = "#797979";
                                        }
                                        arbeitsumfeld = Sterne;
                                    }
                                    function ModifiziereURL_individualitaet(Sterne, Angebot_ID) {
                                        if(Sterne == 1)
                                        {
                                            document.getElementById("stern21").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern21").style.color = "#797979";
                                        }
                                        if(Sterne == 2)
                                        {
                                            document.getElementById("stern22").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern22").style.color = "#797979";
                                        }
                                        if(Sterne == 3)
                                        {
                                            document.getElementById("stern23").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern23").style.color = "#797979";
                                        }
                                        if(Sterne == 4)
                                        {
                                            document.getElementById("stern24").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern24").style.color = "#797979";
                                        }
                                        if(Sterne == 5)
                                        {
                                            document.getElementById("stern25").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern25").style.color = "#797979";
                                        }
                                        individualitaet = Sterne;
                                    }
                                    function ModifiziereURL_inhalt(Sterne, Angebot_ID) {
                                        if(Sterne == 1)
                                        {
                                            document.getElementById("stern31").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern31").style.color = "#797979";
                                        }
                                        if(Sterne == 2)
                                        {
                                            document.getElementById("stern32").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern32").style.color = "#797979";
                                        }
                                        if(Sterne == 3)
                                        {
                                            document.getElementById("stern33").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern33").style.color = "#797979";
                                        }
                                        if(Sterne == 4)
                                        {
                                            document.getElementById("stern34").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern34").style.color = "#797979";
                                        }
                                        if(Sterne == 5)
                                        {
                                            document.getElementById("stern35").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern35").style.color = "#797979";
                                        }
                                        inhalt = Sterne;
                                    }
                                    function ModifiziereURL_SozialeLeistungen(Sterne, Angebot_ID) {
                                        if(Sterne == 1)
                                        {
                                            document.getElementById("stern41").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern41").style.color = "#797979";
                                        }
                                        if(Sterne == 2)
                                        {
                                            document.getElementById("stern42").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern42").style.color = "#797979";
                                        }
                                        if(Sterne == 3)
                                        {
                                            document.getElementById("stern43").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern43").style.color = "#797979";
                                        }
                                        if(Sterne == 4)
                                        {
                                            document.getElementById("stern44").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern44").style.color = "#797979";
                                        }
                                        if(Sterne == 5)
                                        {
                                            document.getElementById("stern45").style.color = "red";
                                        }
                                        else {
                                            document.getElementById("stern45").style.color = "#797979";
                                        }
                                        SozialeLeistungen = Sterne;
                                    }
                                    function Absenden() {
                                        if(personal > 0 && personal < 6 && arbeitsumfeld > 0 && arbeitsumfeld < 6 && individualitaet > 0 && individualitaet < 6 && inhalt > 0 && inhalt < 6 && SozialeLeistungen > 0 && SozialeLeistungen < 6)
                                        {
                                        var path = location.protocol + '//' + location.host + location.pathname;
                                        var id = getURLParameter('id');
                                        var final_url = path+"?id="+id+"&bewertung="+personal+"&bewertung1="+arbeitsumfeld+"&bewertung2="+individualitaet+"&bewertung3="+inhalt+"&bewertung4="+SozialeLeistungen+"&angebot="+window.test1234;
                                        window.location.href = final_url;
                                        }
                                        //window.location.href = final_url;
                                    }
                                    function ModifiziereURL4(Sterne4, Angebot_ID) {
                                        /*var path = location.protocol + '//' + location.host + location.pathname;
                                        var id = getURLParameter('id');
                                        var Sterne = getURLParameter('bewertung');
                                        var Sterne1 = getURLParameter('bewertung1');
                                        var Sterne2 = getURLParameter('bewertung2');
                                        var Sterne3 = getURLParameter('bewertung3');
                                        var final_url = path+"?id="+id+"&bewertung="+Sterne+"&bewertung1="+Sterne1+"&bewertung2="+Sterne2+"&bewertung3="+Sterne3+"&bewertung4="+Sterne4+"&angebot="+Angebot_ID;
                                        window.location.href = final_url;*/
                                    }

                                    function getURLParameter(name) {
                                        var value = decodeURIComponent((RegExp(name + '=' + '(.+?)(&|$)').exec(location.search) || [, ""])[1]);
                                        return (value !== 'null') ? value : false;
                                    }
                                </script>
                            </div>
                        </div>
                    </div>
                </div>-->
            </section>
        </section>    
        <?php $footer = file_get_contents("includes/html/footer.html"); 
        echo $footer; ?>
        </div>
        <?php $scripts = file_get_contents("includes/html/javascript.html"); 
        echo $scripts; ?>-
    </body>
</html>