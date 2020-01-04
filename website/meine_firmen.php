<?php 
include("includes/login/login_check_inhaber.php");
?>

<html>
    <head>
        <title>Firmenverwaltung</title>
        <?php $head = file_get_contents("includes/html/head.html"); 
            echo $head; ?>
        <link rel="stylesheet" href="/assets/css/meine_firmen.css" />
    </head>
    <body>
        <section id="container">
            <?php
                include("includes/php/sidebar.php");
            ?>
            <div class="se-pre-con"></div>
            <section id="main-content">
                <script>
                    document.documentElement.style.overflow = 'hidden'; // firefox, chrome
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
                </section>
            </section>
            <?php $footer = file_get_contents("includes/html/footer.html"); 
                echo $footer; 
            ?>
            </div>
            <?php $scripts = file_get_contents("includes/html/javascript.html"); 
                echo $scripts; 
            ?>
        </section>
    </body>
</html>