<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $f_id = $_POST['f_id'];
    $event_id = $_POST['event_id'];

    $query = "DELETE FROM response_form WHERE event_id = $event_id;";
    $result = $conn->query($query);

    $query = "DELETE FROM forms WHERE f_id = $f_id;";
    $result = $conn->query($query);


    echo 'deleted';
}



