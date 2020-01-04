<?php
    require_once "includes/db/config_praktikumsbewertung.php";
    $return_arr = array();

    $sql = 'SELECT * FROM Berufsgruppen';
    
    $result = mysqli_query($db1, $sql);
    while($row = mysqli_fetch_array($result)) {
        $return_arr[] =  $str = str_replace("\n", '', $row['Name']);
    }


    /* Toss back results as json encoded array. */
    echo json_encode($return_arr);

?>