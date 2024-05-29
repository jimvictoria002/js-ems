<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    session_start();

    $event_id = $_POST['event_id'];

    // $query = "DELETE FROM response_form WHERE event_id = $event_id";
    // $result = $conn->query($query);

    $query = "UPDATE events SET f_id = NULL WHERE event_id = $event_id";
    $result = $conn->query($query);

    if ($result) {
        $_SESSION['success'] = 'Form detached successfuly';

        echo "unlinked";
    } else {
        echo "Error: " . $conn->error;
    }
}
