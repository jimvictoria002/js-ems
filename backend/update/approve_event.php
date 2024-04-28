<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $event_id = $_POST['event_id'];
    $status = $_POST['status'];

    $query = "UPDATE events SET status = '$status' WHERE event_id = $event_id";
    $result = $conn->query($query);


    if (!$result) {
        echo 'error';
    } else {
        if ($status == 'pending') {
            $status = 'unapprove';
            $referrer = $_SERVER['HTTP_REFERER'];
            $_SESSION['success'] = "Event $status successfuly";

            header("Location: ../../frontend/pending-events.php");
        } else {
            $status = 'approve';

            if (isset($_POST['from'])) {
                header("Location: ../email-format/approve-message.html");
            } else {
                $_SESSION['success'] = "Event $status successfuly";
                header("Location: ../../frontend/event-calendar.php");
            }
        }
    }
}
