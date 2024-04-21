<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $field = $_POST['field'];
    $value = $_POST['value'];
    $f_id = $_POST['f_id'];

    $query = "UPDATE forms SET $field = ? WHERE f_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $value, $f_id);
    $stmt->execute();


    if ($stmt->affected_rows > 0) {
        echo '1';
    } else {
        echo '0';
    }

}

