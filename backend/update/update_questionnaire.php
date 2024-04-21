<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $field = $_POST['field'];
    $value = $_POST['value'];
    $q_id = $_POST['q_id'];

    $query = "UPDATE questionnaire SET $field = ? WHERE q_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $value, $q_id);

    // echo "UPDATE questionnaire SET $field = '$value' WHERE q_id = $q_id ";

    if ($stmt->execute()) {
        echo '1';
    } else {
        echo '0';
    }

}

