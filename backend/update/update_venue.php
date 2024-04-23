<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $venue = $_POST['venue'];
    $v_id = $_POST['v_id'];

    $query = "UPDATE venue SET venue = ? WHERE v_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $venue, $v_id);
    $stmt->execute();


    if ($stmt->affected_rows > 0) {
        echo '1';
    } else {
        echo '0';
    }

}

