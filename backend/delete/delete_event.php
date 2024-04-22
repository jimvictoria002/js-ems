<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $event_id = $_POST['event_id'];

    $query = "DELETE FROM events WHERE event_id = $event_id;";
    $result = $conn->query($query);

    header('Location:../../frontend/event-calendar.php');

    $referrer = $_SERVER['HTTP_REFERER'];
    $_SESSION['success'] = 'Deleted successfuly';

    header("Location: $referrer");

}



