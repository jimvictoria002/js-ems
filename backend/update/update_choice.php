<?php

require "../../connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $choice_name = $_POST['choice_name'];
    $c_id = $_POST['c_id'];

    $query = "UPDATE choices SET choice_name = ? WHERE c_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $choice_name, $c_id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo '1';
    } else {
        echo '0';
    }

}

