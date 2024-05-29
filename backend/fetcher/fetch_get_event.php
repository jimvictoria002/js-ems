<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $event_id = $_POST['event_id'];


    $query = "SELECT * FROM events WHERE event_id = $event_id";
    $result = $conn->query($query);
    $row = $result->fetch_assoc();

    echo json_encode($row);


}



