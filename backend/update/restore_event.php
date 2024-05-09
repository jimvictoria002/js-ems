<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $event_id = $_POST['event_id'];

    $query = "UPDATE events SET status = 'pending' WHERE event_id = $event_id";
    $result = $conn->query($query);

    $query = "SELECT title FROM events WHERE event_id = $event_id ";
    $result = $conn->query($query);
    $title = $result->fetch_assoc()['title'];
    $_SESSION['success'] = "".$title." has been restored";
}
