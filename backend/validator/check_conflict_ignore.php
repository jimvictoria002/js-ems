<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $start_datetime = str_replace('T', ' ', $_POST['start_datetime']);
    $end_datetime = str_replace('T', ' ', $_POST['end_datetime']);
    $v_id = $_POST['v_id'];
    $event_id = $_POST['event_id'];

    // Trim extra whitespace
    $start_datetime = trim($start_datetime);
    $end_datetime = trim($end_datetime);

    // Format datetime properly
    $start_datetime = date('Y-m-d H:i:s', strtotime($start_datetime));
    $end_datetime = date('Y-m-d H:i:s', strtotime($end_datetime));

    $query = "CALL checkConflictIgnore($event_id, '$start_datetime ', '$end_datetime', $v_id);";

    $result = $conn->query($query);

    $row = $result->fetch_array()[0];

    

    if($row == 0){
        echo 'false';


    } else {
        echo 'true';

    }
}
