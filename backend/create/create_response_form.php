<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    session_start();

    $user_id = $_SESSION['user_id'];
    $event_id = $_POST['event_id'];
    $access = $_SESSION['access'];

    $stmt = $conn->prepare("CALL insert_response(?, ?, ?, @r_f_id)");
    $stmt->bind_param("iss", $user_id, $event_id, $access);
    $stmt->execute();
    $stmt->close();

    $result = $conn->query("SELECT @r_f_id as r_f_id");
    $row = $result->fetch_assoc();
    $r_f_id = $row['r_f_id'];

    echo $r_f_id;
}
