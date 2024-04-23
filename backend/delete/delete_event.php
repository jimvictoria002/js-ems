<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $event_id = $_POST['event_id'];

    $query = "SELECT event_img FROM events WHERE event_id = $event_id";
    $result = $conn->query($query);

    $uploadDir = '../../uploads/event_img/';

    $fileNameToDelete = $result->fetch_assoc()['event_img'];

    if (file_exists($uploadDir . $fileNameToDelete)) {
        // Attempt to delete the file
        if (unlink($uploadDir . $fileNameToDelete)) {
            echo "File deleted successfully.";
        } else {
            echo "Error deleting file.";
        }
    }


    $query = "DELETE FROM events WHERE event_id = $event_id;";
    $result = $conn->query($query);

    header('Location:../../frontend/event-calendar.php');

    $referrer = $_SERVER['HTTP_REFERER'];
    $_SESSION['success'] = 'Deleted successfuly';

    header("Location: ../../frontend/event-calendar.php");

}



