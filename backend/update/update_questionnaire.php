<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $field = $_POST['field'];
    $value = $_POST['value'];
    $q_id = $_POST['q_id'];

    $query = "UPDATE questionnaire SET $field = ? WHERE q_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $value, $q_id);
    $stmt->execute();


    if ($stmt->affected_rows > 0) {
        echo '1';
    } else {
        echo '0';
    }

}

