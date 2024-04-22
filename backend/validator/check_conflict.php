<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $start_datetime = $_POST['start_datetime'];
    $end_datetime = $_POST['end_datetime'];
    $v_id = $_POST['v_id'];

    $query = "CALL checkConflict('$start_datetime ', '$end_datetime', $v_id);";

    $result = $conn->query($query);

    $row = $result->fetch_array()[0];

    

    if($row == 0){
        echo 'false';


    } else {
        echo 'true';

    }
}
