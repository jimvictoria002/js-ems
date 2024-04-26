<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $event_id = $_POST['event_id'];

    $query = "DELETE FROM response_form WHERE event_id = $event_id";
    $result = $conn->query($query);

    $query = "UPDATE events SET f_id = NULL WHERE event_id = $event_id";
    $result = $conn->query($query);

    if ($result) {
        echo "unlinked";
    } else {
        echo "Error: " . $conn->error; 
    }

}
