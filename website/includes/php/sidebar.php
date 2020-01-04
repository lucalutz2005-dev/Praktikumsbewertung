<?php $navbar = file_get_contents("includes/html/navbar.html"); 
            echo $navbar; ?>
            <?php $sidebar1 = file_get_contents("includes/html/sidebar.html");
            $sidebar2 = str_replace('img/ui-sam.jpg', "https://api.adorable.io/avatars/285/".md5($_SESSION["Benutzername"])."", $sidebar1); 
            $sidebar = str_replace('profile.html', $_SERVER['PHP_SELF'], $sidebar2); 
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